<?php
    $db_host = 'localhost';
    $db_user = 'bif1user';
    $db_password = 'password';
    $db_db = 'bif1webtechdb';
   
    $mysqli = @new mysqli(
      $db_host,
      $db_user,
      $db_password,
      $db_db
    );
      
    if ($mysqli->connect_error) {
      echo 'Errno: '.$mysqli->connect_errno;
      echo '<br>';
      echo 'Error: '.$mysqli->connect_error;
      exit();
    }

?>