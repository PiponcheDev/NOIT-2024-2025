<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$hascard = $pdo->prepare('SELECT purchaseDate FROM card WHERE user_id = ?');
$hascard->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$hascard->execute();
$card = $hascard->fetch(PDO::FETCH_ASSOC);

if ($card && !empty($card['purchaseDate'])) {
    echo '<script>
    
        alert("Вие вече имате карта");
        window.location.href = "home-login.php";
    </script>';
    exit();
} else {
    header("Location: subscription.html");
    exit();
}
?>