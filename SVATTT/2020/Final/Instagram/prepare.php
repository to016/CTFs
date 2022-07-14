<?php
require_once('dbconnect.php');
require_once('lib.php');
require_once('auth.php');
require_once('curl.php');

ini_set("display_errors", 0);
ini_set("session.cookie_httponly", 1);
ob_start();
session_start();

if ($_auth === false) {
        header("Location: index.php");
        exit;
    }
$username = substr($_COOKIE['user'],0,strlen($_COOKIE['user'])-32);
$hashpasswd = substr($_COOKIE['user'],-32);
$username = mysqli_real_escape_string($conn,$username);
$hashpasswd = mysqli_real_escape_string($conn,$hashpasswd);
$res = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' and password = '$hashpasswd'");
$row = mysqli_fetch_array($res);
$id = $row['id'];
$_current_avatar = $row['avatar_name'];
$count = mysqli_num_rows($res);
$_parse_current_avatar = _curl($_current_avatar);
$_parse_current_avatar = '<center><br><img src="data:image/jpg;base64,'.$_parse_current_avatar.'" height="80%" width="80%"></b></center>';
$_thumbnail = _curl($_current_avatar);
$_thumbnail = '<center><br><img src="data:image/jpg;base64,'.$_thumbnail.'" height="500%" width="500%"></b></center>';

?>
