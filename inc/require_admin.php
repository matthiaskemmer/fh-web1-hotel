<?php
// Restrict access to the site to admin users 
// Guests are redirected to login page
// Users are redirected to home page
if (!isset($_SESSION['name'])) {
  $_SESSION["msg_error"] = "Sie haben keinen Zugriff auf diese Seite!";
  header('Location: index.php?site=login');
} else if (!$_SESSION["isadmin"]){
  $_SESSION["msg_error"] = "Sie haben keinen Zugriff auf diese Seite!";
  header('Location: index.php?site=home');
}
?>