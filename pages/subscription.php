<?php
session_start();
include 'config.php';

if (isset($_POST['card_type'])) {
    $cardType = $_POST['card_type'];

    if (isset($_SESSION['user_id'])) {
        if ($cardType == '') {
            echo "Please select a valid card type.";
            exit();
        } else {
            // Store the selected card type in session for use after payment
            $_SESSION['selected_card_type'] = $cardType;

            // Redirect to payment process page
            header("Location: payment-procces.html");
            exit();
        }
    } else {
        echo "User not logged in.";
    }
} else {
    echo "Please select a card to buy.";
}
?>
