<?php
   session_start();
   unset($_SESSION["username"]);
   die(header('location: login.php'));
?>
