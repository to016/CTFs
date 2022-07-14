<?php
@session_start();
error_reporting(0);
ini_set('display_errors', 0);
require_once 'dbconnect.php';
require_once 'patch.php';
?>

<style>

.button1 {
    background-color: pink; 
    color: white; 
    border: 2px solid white;
    padding: 5px 32px;
}

input:not([type="submit"]) {
    background-color: white; 
    color: pink; 
    width: 15%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid pink;
    border-radius: 4px;
}

</style>


<!DOCTYPE html>
<html lang="en">
<head>
<title>2FA Dating</title>
<link rel="stylesheet" href="assets/main.css">
<link rel="stylesheet" href="assets/w3.css">
</head>

<body>

</style>
<div class="navbar">
  <a href="index.php">HOME</a>
  <a href="register.php" class="right">Register</a>
  <a href="login.php" class="right active">Login</a>
</div><br><br>

<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
    header("Location: profile.php");
    die("<center>Already Logged in</center><script>");
}
?>

<?php

if(isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $pass = md5($_POST["password"]);

    $res = mysqli_query($conn,"SELECT * FROM fa_dating_users WHERE fa_dating_username='$username'");
    if($res) 
    {
        $row = mysqli_fetch_array($res);
        if(md5($_POST["password"]) === $row['fa_dating_password'])
        {
            print("<center>Logged In</center>");
            $_SESSION["user"] = $_POST["username"];
            header("Refresh:0");
        }
        else 
        {
            print("<center>Something Wrong!!!</center>");
        }
    }
}

?>

<div class="header">
    <h1 class="neon">✨ LOGIN ✨</h1><br>
    <form action="login.php" method="POST">
        Username<font color="red">*</font><br>
        <input type="text" name="username" value="" class="button1" id="username" maxlength=25 required/><br>
        Password<font color="red">*</font><br>
        <input type="password" name="password" value="" class="button1" required/><br><br>
        <button type="submit" class="button1">Login</button>
    </form>
</div>>
