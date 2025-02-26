<?php
session_start();

if (isset($_POST['ticket_price'])) {
    $_SESSION['ticket_type'] = $_POST['ticket_price'];
    header('Location: payment_ticket.php');
    exit();
} else {
    header('Location: ticket.html');
    exit();
}
?>