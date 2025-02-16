<?php
session_start();
require 'config.php';

if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $pass = $_POST["pass"];
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $passcon = $_POST["confirmpass"];

    // Check for duplicate email or username
    $dup = $pdo->prepare("SELECT * FROM user WHERE email = ? OR username = ?");
    $dup->execute([$email, $username]);
    $dupRow = $dup->fetchAll(PDO::FETCH_ASSOC);
    $count = count($dupRow);

    if ($count > 0) {
        echo "<script>alert('Името или пощата са заети'); window.location.href = 'register.php';</script>";
    } else {
        if ($pass == $passcon) {
            // Insert new user into the database
            $ins = $pdo->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
            $ins->execute([$email, $username, $hash]);

            // Retrieve the newly inserted user's ID
            $check = $pdo->prepare("SELECT * FROM user WHERE email = ?");
            $check->execute([$email]);
            $row = $check->fetch(PDO::FETCH_ASSOC); // Use fetch() instead of fetchAll()

            $id = $row['id']; // Access the 'id' column correctly
            $name = $row['username']; // Access the 'username' column correctly

            // Generate a token for the card
            function token() {
                $str = "1234567890qwertyuiopasdfghjkl;zxcvbnm,./?[{]}!£$%^&*()_-=+#";
                $rnd = str_shuffle($str);
                $token = md5($rnd);
                return $token;
            }

            $token = token();

            // Insert the card into the database
            $card = $pdo->prepare("INSERT INTO card (user_id, cardToken) VALUES (?, ?)");
            $card->execute([$id, $token]);

            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $name;
            $_SESSION['has_card'] = true; // Ensure this is set

            // Redirect to the home page
            header('Location: home-login.php');
            exit();
        } else {
            echo "<script>alert('Password incorrect');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <!--metadata-->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--tab view-->
    <title>БгБус</title>
    <link rel="icon" href="../media/bus-solid.svg" />
    <!--css file-->
    <link rel="stylesheet" href="../css/login-register.css" />
    <!--fontawesome icons imbed-->
    <script src="https://kit.fontawesome.com/b56d58b9c9.js" crossorigin="anonymous" defer></script>
    <!--google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet" />
</head>
<body>
    <section>
        <div id="name-logo">
            <i class="fa-solid fa-bus" id="logo"></i>
            <h1 id="name">БгБус</h1>
        </div>
        <form action="register.php" method="post">
            <input type="text" name="username" id="username" class="input" placeholder="Създадете потребителско име" />
            <input type="text" name="email" id="email" class="input" placeholder="Въведете си имейла" />
            <input type="password" name="pass" id="pass" class="input" placeholder="Създадете си парола" />
            <input type="password" name="confirmpass" id="pass" class="input" placeholder="Повторете паролата" />
            <input type="submit" id="submit" name="register" value="Регистрирай" />
        </form>
        <h3>Имаш акаунт?<br /><br /><a href="login.php">Влез в профила си</a></h3>
    </section>
</body>
</html>