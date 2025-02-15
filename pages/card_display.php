<?php
include 'config.php';
require '../vendor/autoload.php'; // Include Composer's autoloader for Endroid\QrCode

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

session_start();

// Check if session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['has_card'])) {
    die("Session variables not set.");
}

if ($_SESSION['has_card'] == true) {
    // Fetch cardToken from the database
    $query = $pdo->prepare("SELECT cardToken FROM card WHERE user_id = ?");
    $query->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $cardToken = $result['cardToken'];

        // Custom encryption key (fixed)
        $encryption_key = hash('sha256', "MySecretKey123!", true); // 256-bit key

        // Encrypt function
        function encryptData($data, $key) {
            $iv = openssl_random_pseudo_bytes(16); // 16-byte IV
            $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
            return base64_encode($iv . $encrypted);
        }

        // Add validation message and encrypt
        $dataToEncrypt = "✅ Valid card!" . $cardToken;
        $encryptedToken = encryptData($dataToEncrypt, $encryption_key);

        // Create a QR code using the Builder
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $encryptedToken, // Use the encrypted cardToken
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            margin: 0,
            size: 280,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $result = $builder->build();
        $qrCodeImage = $result->getDataUri(); // Get the QR code as a data URI
    }
} else {
    echo "<script>alert('Този профил няма карта')</script>";
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
  </div>
</body>
</html>
