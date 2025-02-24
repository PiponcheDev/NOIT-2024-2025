<?php
session_start();
include 'config.php';

$card = $pdo->prepare('SELECT cardType, purchaseDate FROM card WHERE user_id = ?');
$card->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
$card->execute();
$card = $card->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['view_tickets'])) {
        // Fetch the latest ticket for the user from the database
        $query = $pdo->prepare("SELECT ticketType, purchaseDate_ticket FROM card WHERE user_id = ? ORDER BY purchaseDate_ticket DESC LIMIT 1");
        $query->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
        $query->execute();
        $ticket = $query->fetch(PDO::FETCH_ASSOC);

        if ($ticket && !empty($ticket['ticketType']) && !empty($ticket['purchaseDate_ticket'])) {
            header('Location: ticket_view.php');
            exit();
        } else {
            echo '
            <script>
                alert("Нямате закупен билет");
                window.location.href = "view_cards.php";
            </script>';
            exit();
        }
    } elseif (isset($_POST['view_card'])) {
        if ($card && !empty($card['cardType'])) {
            header('Location: card_display.php');
            exit();
        } else {
            echo '
            <script>
                alert("Нямате закупена карта");
                window.location.href = "view_cards.php";
            </script>';
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Бг бус</title>
    <style>
      :root {
        --primary-color: #333333;
        --secondary-color: #DDA853;
        --text-color: #FBF5DD;
      }

      h1{
        color: var(--text-color);
      }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--primary-color);
            font-family: Arial, sans-serif;
            flex-direction: column;
        }
        .container {
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            color: #fff;
            background-color: var(--secondary-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.5s;
        }
          .button:hover {
            box-shadow: -1px 10px 66px 16px rgba(0, 0, 0, 1);
            -webkit-box-shadow: -1px 10px 66px 16px rgba(0, 0, 0, 1);
            -moz-box-shadow: -1px 10px 66px 16px rgba(0, 0, 0, 1);
            background-color:rgb(255, 157, 0);
            color: var(--text-color);
            cursor: pointer;
          }
    </style>
</head>
<body>    
  <h1>View Cards or Tickets</h1>
    <div class="container">
        <form method="post">
            <button type="submit" name="view_tickets" class="button">View Tickets</button>
            <button type="submit" name="view_card" class="button">View Card</button>
        </form>
    </div>
</body>
</html>