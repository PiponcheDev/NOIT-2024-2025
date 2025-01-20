<?php 
if( isset($_SESSION['id'])){ 
  session_destroy();
}

if(empty($_SESSION['id'])){
  header('Location:index.html');
} 

?>
