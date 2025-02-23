<?php 
session_start();
include 'config.php';

if (isset($_GET['buy_card'])) {
    $card = $pdo->prepare('SELECT cardType, purchaseDate FROM card WHERE user_id = ?');
    $card->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $card->execute();
    $card = $card->fetch(PDO::FETCH_ASSOC);

    if ($card && !empty($card['cardType'])) {
        echo '
        <script>
            alert("Вие вече имате карта");
            window.location.href = "home-login.php";
        </script>';
    } else {
        echo '
        <script>
            window.location.href = "subscription.html";
        </script>';
    }
}

if (isset($_GET['buy_ticket'])) {
    $ticket = $pdo->prepare('SELECT ticketType, purchaseDate_ticket FROM card WHERE user_id = ?');
    $ticket->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $ticket->execute();
    $ticket = $ticket->fetch(PDO::FETCH_ASSOC);

    if ($ticket && !empty($ticket['ticketType'])) {
        echo '
        <script>
            alert("Вие вече имате билет");
            window.location.href = "home-login.php";
        </script>';
    } else {
        echo '
        <script>
            window.location.href = "ticket.html";
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/choice.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
  <title>БгБус</title>
</head>
<body>
  <div class="container"><a href="purchase_choice.php?buy_card=true">Купете карта</a></div>
  <div class="container"><a href="purchase_choice.php?buy_ticket=true">Купете билет</a></div>
</body>
</html>