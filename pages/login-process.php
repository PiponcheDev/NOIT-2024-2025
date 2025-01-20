<?php session_start();

require 'config.php';

if(isset($_POST["login"])){

  $email = $_POST["user"];
  $pass = $_POST["password"];

  $hash = password_hash($pass, PASSWORD_BCRYPT);

  
    $query= $pdo -> prepare("SELECT * FROM user WHERE email = ? AND password = ? ");
    $query-> execute([$email , $pass]);

    $row = $query->fetchAll(PDO::FETCH_ASSOC);
      $name = $row[1];
      $counter = count($row);
      $id = $row[0];

    
      if(password_verify($pass , $hash)){
         $_SESSION['id'] = $id;
         $_SESSION['username'] = $name;
        header('Location:home-login.php');
        echo "Test";
      }else{
        echo 
       "<script type='text/javascript'>
         alert('Invalid password or email');
        document.location = 'login.php';
        </script>";
      }
}

