<?php session_start();
if(empty($_SESSION['id'])):
  header('Location:login.php');
endif;

?>

<!DOCTYPE html>
<html lang="bg">
  <head>
    <!--meta data-->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--tab view-->
    <title>BgBus</title>
    <link rel="icon" href="../media/bus-solid.svg" />
    <!--css files-->
    <link rel="stylesheet" href="../css/index.css" />
    <!--js files-->
    <script src="../js/index.js"></script>
    <!--fontawesome icons imbed-->
    <script
      src="https://kit.fontawesome.com/b56d58b9c9.js"
      crossorigin="anonymous"
      defer
    ></script>
    <!--google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <nav>
      <div>
        <i class="fa-solid fa-bus" id="logo"></i>
        <h1 id="name">BgBus</h1>
      </div>
      <ul>
        <a href="#" class="nav-button">My tickets</a>
        <a href="logout.php"><div style="float:right"><button>logout</button></div></a>
      </ul>
    </nav>
    <section>
      <div class="container">
        <div class="deco"></div>
        <div class="info">
          <h2>Най-екологичния начин да патувате</h2>
          <p>
            Пътувайте екологично с автобус! Един автобус заменя десетки коли,
            намалявайки емисиите и задръстванията. Удобно, икономично и зелено –
            автобусите са най-добрият избор за чисто бъдеще!
          </p>
          <a href="#">Закупете карта</a>
        </div>
      </div>
    </section>
  </body>
</html>