<?php
	ini_set("session.cookie_httponly", 1);
	setcookie('user', '',time() - 3600);
	session_unset();
	session_destroy();
	header("Location: index.php?page=login.php");
	exit;
?>