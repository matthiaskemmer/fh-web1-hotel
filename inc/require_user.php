<?php
// Restrict access to the site to normal users 
// Guests are redirected to login page
// Admins are redirected to home page

if (!isset($_SESSION['name'])) {
  $_SESSION["msg_error"] = "Sie haben keinen Zugriff auf diese Seite!";
  header('Location: index.php?site=login');
} else if (!$_SESSION["isuser"]){
  $_SESSION["msg_error"] = "Sie haben keinen Zugriff auf diese Seite!";
  header('Location: index.php?site=home');
}
?>