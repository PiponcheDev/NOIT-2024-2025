<?php
  require 'config.php';
  
  if(isset($_POST["register"])){
    $username = $_POST["username"];
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $pass = $_POST["pass"];
    $passcon = $_POST["confirmpass"];
  
    $dup = mysqli_query($conn , "SELECT * FROM user WHERE email = '$email' OR username = '$username'");
  
    if(mysqli_num_rows($dup) > 0){
      echo "<script> alert('Username or email has already been taken');</script>";
    }else{
      if($pass == $passcon){
        $query = "INSERT INTO user (email , username , password) VALUES('$email' , '$username' , '$pass')";
        mysqli_query($conn , $query);
        echo "<script> alert('Registration succses');</script>";
      }else{
        echo "<script> alert('Password incorect');</script>";
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
    <title>BgBus</title>
    <link rel="icon" href="../media/bus-solid.svg" />
    <!--css file-->
    <link rel="stylesheet" href="../css/login-register.css" />
    <!--fontawesome icons imbed-->
    <script
      src="https://kit.fontawesome.com/b56d58b9c9.js"
      crossorigin="anonymous"
      defer
    ></script>
    <!--google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <section>
      <div id="name-logo">
        <i class="fa-solid fa-bus" id="logo"></i>
        <h1 id="name">BgBus</h1>
      </div>
      <form action="register.php" method="post">
        <input
          type="text"
          name="username"
          id="username"
          class="input"
          placeholder="Създадете потребителско име"
        />
        <input
          type="text"
          name="email"
          id="email"
          class="input"
          placeholder="Въведете си имейла"
        />
        <input
          type="password"
          name="pass"
          id="pass"
          class="input"
          placeholder="Създадете си парола"
        />

        <input
          type="password"
          name="confirmpass"
          id="pass"
          class="input"
          placeholder="Повторете паролата"
        />
        <input type="submit" id="submit" name="register" value="Регистрирай" />
      </form>
      <h3>
        Имаш акаунт?<br /><br /><a href="login.php">Влез в профила си</a>
      </h3>
    </section>
  </body>
</html>
