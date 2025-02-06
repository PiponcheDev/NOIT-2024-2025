<?php
session_start();
require 'config.php';

if (isset($_POST["login"])) {
    $email = $_POST["user"];
    $pass = $_POST["password"];

    // Retrieve the user from the database
    $query = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $query->execute([$email]);
    $row = $query->fetch(PDO::FETCH_ASSOC); // Use fetch() instead of fetchAll()

    if ($row) {
        $hashed_password = $row['password']; // Get the hashed password from the database

        // Verify the password
        if (password_verify($pass, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to the home page
            header('Location: home-login.php');
            exit();
        } else {
            echo "<script>alert('Invalid password or email'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid password or email'); window.location.href = 'login.php';</script>";
    }
}
?>

