<?php

require_once('dbconnect.php');
require_once('lib.php');

ini_set("session.cookie_httponly", 1);
ob_start();
session_start();

function auth($conn){
	if ($_COOKIE['user'] == "") {
		return false;
	}

	else{
		$username = substr($_COOKIE['user'],0,strlen($_COOKIE['user'])-32);
		$hashpasswd = substr($_COOKIE['user'],-32);
		$username = mysqli_real_escape_string($conn,$username);
		$hashpasswd = mysqli_real_escape_string($conn,$hashpasswd);
		$res=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' and password = '$hashpasswd'");
		$row=mysqli_fetch_array($res);
		$count = mysqli_num_rows($res);
		if( $count !== 1 && $row['password'] !== $hashpasswd ) 
		{
			return false;
		}
		else{
			return true;
		}
	}
}
$_auth = auth($conn);

?>