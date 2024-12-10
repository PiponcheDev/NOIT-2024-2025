<?php session_start();

require 'config.php';

if(isset($_POST["login"])){

  $email_unsafe = $_POST["user"];
  $pass_unsafe = $_POST["password"];

  $email = mysqli_real_escape_string($conn , $email_unsafe);
  $pass = mysqli_real_escape_string($conn , $pass_unsafe);
  $hash = password_hash($pass, PASSWORD_BCRYPT);

  
   $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$hash' ")or die(mysqli_error($conn));
  
    $row = mysqli_fetch_array($query);

      $name = $row[1];
      $counter = mysqli_num_rows($query);
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
