<?php
session_start();
date_default_timezone_set('UTC');
include 'config.php';
$pdo->exec("SET time_zone = '+00:00'");
require '../vendor/autoload.php'; // Include Composer's autoloader for Endroid\QrCode




ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/php_errors.log');

error_log("Session Data on home-login.php: " . print_r($_SESSION, true));

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


// Debugging: Log session data
error_log("Session data: " . print_r($_SESSION, true));

// Check if session variables are set
if (!isset($_SESSION['user_id'])) {
    error_log("User ID is not set in the session.");
    die("User ID is not set in the session.");
}

if (!isset($_SESSION['has_card'])) {
    error_log("Has card is not set in the session.");
    die("Has card is not set in the session.");
}

// Validity duration in seconds (60 seconds for testing)
$validityDuration = 60; // Validity in seconds
$currentTime = new DateTime('now', new DateTimeZone('UTC')); // Set to UTC
error_log("Current time: " . $currentTime->format('Y-m-d H:i:s'));

// Fetch the user's card from the database
$query = $pdo->prepare("SELECT * FROM card WHERE user_id = ?");
$query->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$query->execute();
$card = $query->fetch(PDO::FETCH_ASSOC);

if ($card) {
    $purchaseDate = DateTime::createFromFormat('Y-m-d H:i:s', $card['purchaseDate'], new DateTimeZone('UTC'));

    if (!$purchaseDate) {
        error_log("❌ Error: Invalid purchaseDate format from DB: " . $card['purchaseDate']);
        echo '<script>alert("Този профил няма карта")</script>';
        header('Location: home-login.php');
    }
    
    $purchaseDateTimestamp = $purchaseDate->getTimestamp();

    $timeDifference = $currentTime->getTimestamp() - $purchaseDateTimestamp;

    // Debugging: Log timestamps
    error_log("Fetched card data: " . print_r($card, true));
    error_log("Purchase date from DB: " . $purchaseDate->format('Y-m-d H:i:s'));
    error_log("Time difference: " . $timeDifference);

    // Prevent future-dated purchaseDates from causing errors
    if ($timeDifference < 0) {
        error_log("⚠ Warning: purchaseDate is in the future! Possible timezone mismatch.");
        $timeDifference = 0; // Avoid incorrect expiration
    }

    // Check if the card has expired
    if ($timeDifference > $validityDuration) {
        error_log("Card has expired. Invalidating card.");
        $invalidateQuery = "UPDATE card SET cardType = NULL, purchaseDate = NULL WHERE user_id = ?";
        $invalidateStmt = $pdo->prepare($invalidateQuery);
        $invalidateStmt->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);

        if ($invalidateStmt->execute()) {
            error_log("Card invalidated successfully.");
            $_SESSION['has_card'] = false;
            echo "script>alert('Картата ви е невалидна. Моля закупете нова карта.')</script>";
            header("Location: home-login.php");

        } else {
            error_log("❌ Database error: " . print_r($invalidateStmt->errorInfo(), true));
            die("Failed to invalidate card.");
        }
    } else {
        error_log("✅ Card is still valid.");
    }
} else {
    error_log("❌ No card found for user ID: " . $_SESSION['user_id']);
}


// If the card is still valid, proceed to display it
if ($_SESSION['has_card'] == true) {
    // Fetch cardToken from the database
    $query = $pdo->prepare("SELECT cardToken FROM card WHERE user_id = ?");
    $query->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $cardToken = $result['cardToken'];
        error_log("Card token: " . $cardToken);

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
    } else {
        error_log("No card token found for user ID: " . $_SESSION['user_id']);
    }
} else {
    error_log("User does not have a card.");
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