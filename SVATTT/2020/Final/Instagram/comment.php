<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
require_once('dbconnect.php');
require_once('lib.php');
ini_set("display_errors", 0);
ini_set("session.cookie_httponly", 1);
ob_start();
session_start();



if ($_COOKIE['user'] == "") {
  header("Location: index.php?page=login.php");
  exit;
}

else{
  $username = substr($_COOKIE['user'],0,strlen($_COOKIE['user'])-32);
  $hashpasswd = substr($_COOKIE['user'],-32);
  $username = mysqli_real_escape_string($conn,$username);
  $hashpasswd = mysqli_real_escape_string($conn,$hashpasswd);
  $res=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' and password = '$hashpasswd'");
  $row=mysqli_fetch_array($res);
  $count = mysqli_num_rows($res);
  $id = $row['id'];

  if( $count !== 1 && $row['password'] !== $hashpasswd ) 
  {
    header("Location: index.php?page=login.php");
    exit;
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["comment"])) {
      $comment = "";
    } else {
      $comment =  mysqli_real_escape_string($conn,miniwaf($_POST["comment"]));
      $time = time();
      $insert = mysqli_query($conn,"INSERT INTO comments(`timestamp`,`username`,`comment`) VALUES ($time,'$username','$comment')");
    }
  }
}
header("Location: index.php?page=instargram.php");
?>

<form method="post" action="comment.php">  
  <br><br>
  Comment: <textarea name="comment" rows="1" cols="40"></textarea>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

</body>
</html>