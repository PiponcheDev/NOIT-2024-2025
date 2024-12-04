<?php session_start();

require 'config.php';

if(isset($_POST["login"])){

  $user_unsafe = $_POST["user"];
  $pass_unsafe = $_POST["password"];

  $user = mysqli_real_escape_string($conn , $user_unsafe);
  $pass = mysqli_real_escape_string($conn , $pass_unsafe);

  $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$user' AND password = '$pass'")or die(mysqli_error($conn));
  
  $row = mysqli_fetch_array($query);

    $name = $row['username'];
    $counter = mysqli_num_rows($query);
    $id = $row['id'];

    if($counter == 0){
      echo 
      "<script type='text/javascript'>
        alert('Invalid password or email');
        document.location = 'login.php';
      </script>";
    }else{
      $_SESSION['id'] = $id;
      $_SESSION['username'] = $name;

      echo "<script type='text/javascript'>
        document.location = 'home-login.php';
      </script>";
    }
}