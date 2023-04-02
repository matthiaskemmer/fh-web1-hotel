<?php
// Restrict access to the site to logged-in users 
// Guests redirected to login page
if (!isset($_SESSION['name'])) {
  $_SESSION["msg_error"] = "Sie haben keinen Zugriff auf diese Seite!";
  header('Location: index.php?site=login');
} 
?>