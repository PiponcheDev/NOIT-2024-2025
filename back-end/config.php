<?php
  $conn = mysqli_connect('localhost' , 'root' , '' , 'travelpass');

  if($conn){
  }else{
    echo "Error in databse connection";
  }
?>