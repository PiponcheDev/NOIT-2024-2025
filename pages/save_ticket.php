<?php
session_start();
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ticketType']) && isset($_SESSION['user_id'])) {
    $ticketType = $data['ticketType'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO card (user_id, ticketType, purchaseDate_ticket) VALUES (?, ?, NOW())");
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $ticketType, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>