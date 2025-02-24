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
            $_SESSION['selected_card_type'] = $cardType;

            header("Location: payment-procces.html");
            exit();
        }
    }
}
