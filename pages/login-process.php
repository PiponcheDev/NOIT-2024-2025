<?php
session_start();
require 'config.php';

if (isset($_POST["login"])) {
    $email = $_POST["user"];
    $pass = $_POST["password"];

    $query = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $query->execute([$email]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header('Location: home-login.php');
            exit();
        } else {
            echo "<script>alert('Invalid password or email');</script>";
        }
    } else {
        echo "<script>alert('Invalid password or email');</script>";
    }
}
?>