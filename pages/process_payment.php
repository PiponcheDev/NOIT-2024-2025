<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
session_start();
include 'config.php';

$clientId = loadEnv(__DIR__ . '/config.env')['PAYPAL_CLIENT'] ?? '';
$secret = loadEnv(__DIR__ . '/config.env')['PAYPAL_SECRET'] ?? '';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['selected_card_type'])) {
    die(json_encode(['success' => false, 'error' => 'Session data missing.']));
}

$user_id = $_SESSION['user_id'];
$cardType = $_SESSION['selected_card_type'];

$cardTypeMap = [
    'Пенсионерска' => 'R',
    'Ученическа' => 'S',
    'Нормална' => 'N'
];

$cardTypeLetter = $cardTypeMap[$cardType] ?? '';
if (empty($cardTypeLetter)) {
    die(json_encode(['success' => false, 'error' => 'Invalid card type selected.']));
}

$data = json_decode(file_get_contents("php://input"), true);
if (json_last_error() !== JSON_ERROR_NONE || !isset($data['orderID'])) {
    die(json_encode(['success' => false, 'error' => 'Invalid input data.']));
}

$orderID = $data['orderID'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$secret");
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$response = curl_exec($ch);
if (curl_errno($ch)) {
    error_log("PayPal OAuth error: " . curl_error($ch));
    die(json_encode(['success' => false, 'error' => curl_error($ch)]));
}
curl_close($ch);

$accessToken = json_decode($response)->access_token;
if (empty($accessToken)) {
    die(json_encode(['success' => false, 'error' => 'Failed to get access token.']));
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v2/checkout/orders/$orderID");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);

$orderResponse = curl_exec($ch);
if (curl_errno($ch)) {
    error_log("PayPal order details error: " . curl_error($ch));
    die(json_encode(['success' => false, 'error' => curl_error($ch)]));
}
curl_close($ch);

$orderDetails = json_decode($orderResponse, true);
if ($orderDetails['status'] == 'COMPLETED') {
    try {
        $pdo->beginTransaction();

        $query = "SELECT * FROM card WHERE user_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $updateQuery = "UPDATE card SET cardType = ? WHERE user_id = ?";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindValue(1, $cardTypeLetter, PDO::PARAM_STR);
            $updateStmt->bindValue(2, $user_id, PDO::PARAM_INT);
            $updateStmt->execute();
        } else {
            $insertQuery = "INSERT INTO card (user_id, cardType) VALUES (?, ?)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $insertStmt->bindValue(2, $cardTypeLetter, PDO::PARAM_STR);
            $insertStmt->execute();
        }

        $pdo->commit();
        $_SESSION['has_card'] = true; // Ensure this is set
        echo json_encode(['success' => true, 'message' => 'Payment and card processing successful.']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Database error: " . $e->getMessage());
        die(json_encode(['success' => false, 'error' => 'Database operation failed.']));
    }
} else {
    die(json_encode(['success' => false, 'error' => 'Payment not completed.']));
}