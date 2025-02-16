<?php
session_start();
require 'config.php';

// Enable logging manually
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

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

            // Log session data
            error_log("Session Data After Login: " . print_r($_SESSION, true));

            header('Location: home-login.php');
            exit();
        } else {
            error_log("Login Failed: Incorrect password");
            echo "<script>alert('Invalid password or email');</script>";
        }
    } else {
        error_log("Login Failed: User not found");
        echo "<script>alert('Invalid password or email');</script>";
    }
}
?>
