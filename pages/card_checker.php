<?php
session_start();
include 'config.php';

$card = $pdo->prepare('SELECT cardType, purchaseDate FROM card WHERE user_id = ?');
$card->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$card->execute();
$card = $card->fetch(PDO::FETCH_ASSOC);

// Debugging: Output the fetched card data
error_log(print_r($card, true));

if(isset($_GET['buy'])){
    if($card && !empty($card['cardType'])){
        echo '
        <script>
            alert("Вие вече имате карта");
            window.location.href = "home-login.php";
        </script>';
    }else{
        echo '
        <script>
            window.location.href = "subscription.html";
        </script>';
    }
}

if(isset($_GET['card'])){
    if($card && !empty($card['cardType'])){
        echo '
        <script>
            window.location.href = "card_display.php";
        </script>';
    }else{
        echo '
        <script>
            alert("Нямате закупена карта");
            window.location.href = "subscription.html";
        </script>';
    }
}
?>