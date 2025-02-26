<?php
session_start();
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Log the received data and session variables
error_log("Received data: " . print_r($data, true));
error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));

if (isset($data['ticketType']) && isset($_SESSION['user_id'])) {
    $ticketType = $data['ticketType'];
    $user_id = $_SESSION['user_id'];

    try {
        // Check if the user already has a row in the card table
        $stmt = $pdo->prepare("SELECT id FROM card WHERE user_id = ?");
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $card = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($card) {
            // Update the existing row
            $stmt = $pdo->prepare("UPDATE card SET ticketType = ?, purchaseDate_ticket = NOW() WHERE user_id = ?");
            $stmt->bindParam(1, $ticketType, PDO::PARAM_INT);
            $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Insert a new row
            $stmt = $pdo->prepare("INSERT INTO card (user_id, ticketType, purchaseDate_ticket) VALUES (?, ?, NOW())");
            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $ticketType, PDO::PARAM_INT);
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>