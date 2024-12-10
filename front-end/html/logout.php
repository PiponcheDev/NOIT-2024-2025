<?php session_destroy();
if(empty($_SESSION['id'])){
  header('Location:index.html');
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../css/loading.css">
  <!--google fonts-->
  <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
      rel="stylesheet"
    />
</head>
<body>
  <div>
    <div id="wrapper">
      <div class="profile-main-loader">
        <div class="loader">
          <svg class="circular-loader" viewBox="0 0 100 100">
            <circle class="loader-path" cx="50" cy="50" r="25" fill="none" stroke="#70c542" stroke-width="5" />
          </svg>
        </div>
      </div>
    </div>
    <?php
      include('config.php');
      session_destroy();

      echo '<meta http-equiv="refresh" content="2;url=index.html">';
      echo '<span class="itext">Logging out please wait!...</span>';
    ?>
  </div>
</body>
</html>