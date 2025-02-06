<?php
session_start(); // Ensure session is started

include 'config.php';

if (isset($_POST['card_type'])) {
    $cardType = $_POST['card_type'];
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        if ($cardType == '') {
            // Card type is empty, show an error or handle the case
            echo "Please select a valid card type.";
            exit(); // Stop further execution
        } else {
            // Check if the user already has a card and update or insert accordingly
            $query = "SELECT * FROM card WHERE user_id = ?";
            $stmt = $pdo->prepare($query); // Prepare the statement
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);  // Bind user_id
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // If user already has a card, update the card type
                $query = "UPDATE card SET cardType = ? WHERE user_id = ?";
                $stmt = $pdo->prepare($query); // Prepare the statement
                $stmt->bindValue(1, $cardType, PDO::PARAM_STR);  // Bind cardType to the first parameter
                $stmt->bindValue(2, $user_id, PDO::PARAM_INT);   // Bind user_id to the second parameter
            } else {
                // If user doesn't have a card, insert a new card
                $query = "INSERT INTO card (user_id, cardType) VALUES (?, ?)";
                $stmt = $pdo->prepare($query); // Prepare the statement
                $stmt->bindValue(1, $user_id, PDO::PARAM_INT);  // Bind user_id
                $stmt->bindValue(2, $cardType, PDO::PARAM_STR); // Bind cardType
            }
        }

        // Execute the query
        if ($stmt->execute()) {
            echo "Your card has been successfully updated!";
            header("Location: home-login.php");
            exit(); // Stop further execution
        } else {
            // Get error information if the query fails
            $errorInfo = $stmt->errorInfo();
            echo "Error: Could not update your card. Please try again. " . $errorInfo[2];  // Display PDO error message
        }
    } else {
        echo "User not logged in.";
    }
} else {
    echo "Please select a card to buy.";
}
?>
