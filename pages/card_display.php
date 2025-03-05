<?php
session_start();
date_default_timezone_set('UTC');
include 'config.php';
$pdo->exec("SET time_zone = '+00:00'");
require '../vendor/autoload.php'; // Include Composer's autoloader for Endroid\QrCode

ini_set('log_errors', 1);

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
error_log("Session User ID: " . ($_SESSION['user_id'] ?? 'Not set'));
error_log("Session has_card: " . ($_SESSION['has_card'] ?? 'Not set'));
error_log("Session ID: " . session_id());
// Check if session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['has_card'])) {
    header("Location: home-login.php");
    exit();
}

// Validity duration in seconds (4 months)
$validityDuration = 4 * 30 * 24 * 60 * 600000000000;
$currentTime = new DateTime('now', new DateTimeZone('UTC'));

// Fetch the user's card from the database
$query = $pdo->prepare("SELECT * FROM card WHERE user_id = ?");
$query->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$query->execute();
$card = $query->fetch(PDO::FETCH_ASSOC);

if ($card) {
    $purchaseDate = DateTime::createFromFormat('Y-m-d H:i:s', $card['purchaseDate'], new DateTimeZone('UTC'));
    if (!$purchaseDate) {
        header('Location: home-login.php');
        exit();
    }
    
    $purchaseDateTimestamp = $purchaseDate->getTimestamp();
    $timeDifference = $currentTime->getTimestamp() - $purchaseDateTimestamp;

    if ($timeDifference < 0) {
        $timeDifference = 0;
    }

    if ($timeDifference > $validityDuration) {
        $invalidateQuery = "UPDATE card SET cardType = NULL, purchaseDate = NULL WHERE user_id = ?";
        $invalidateStmt = $pdo->prepare($invalidateQuery);
        $invalidateStmt->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $invalidateStmt->execute();
        $_SESSION['has_card'] = false;
        header("Location: home-login.php");
        exit();
    }

    // Calculate the expiration date
    $expirationDate = $purchaseDate->add(new DateInterval('P4M'));
} else {
    header("Location: home-login.php");
    exit();
}

if ($_SESSION['has_card'] == true) {
    $query = $pdo->prepare("SELECT cardToken FROM card WHERE user_id = ?");
    $query->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $cardToken = $result['cardToken'];
        $encryption_key = hash('sha256', "NOIT", true);

        function encryptData($data, $key) {
            $iv = openssl_random_pseudo_bytes(16); // 16-byte IV
            $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
            return base64_encode($iv . $encrypted);
        }

        $dataToEncrypt = "Valid card!" . $cardToken;
        $encryptedToken = encryptData($dataToEncrypt, $encryption_key);

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $encryptedToken,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            margin: 0,
            size: 280,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $result = $builder->build();
        $qrCodeImage = $result->getDataUri();
    }
} else {
    header("Location: home-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/card_display.css">
  <title>QR Code</title>
  <link rel="preconnect" href="https://fonts.googleapis.com/" />
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet"/>
</head>
<body>
  <div class="container">
    <h2 class="cardType">
      <?php 
          $query1 = $pdo->prepare('SELECT cardType FROM card WHERE user_id = ?');
          $query1->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
          $query1->execute();
          $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
          if($result1[0]['cardType'] == 'R'){
              echo 'Пенсионерска карта';
          }else if($result1[0]['cardType'] == 'S'){
              echo 'Ученическа карта';
          }else if($result1[0]['cardType'] == 'N'){
              echo 'Нормална карта';
          }
      ?>
    </h2>
    <div class="qr_container">
      <?php if (isset($qrCodeImage)): ?>
          <img src="<?php echo $qrCodeImage; ?>" alt="QR Code">
      <?php endif; ?>
    </div>
    <h4> Картата е валидна до: </br> <?php echo $expirationDate->format('Y-m-d'); ?></h4>
  </div>
</body>
</html>
