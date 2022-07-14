<?php
	ini_set("session.cookie_httponly", 1);
	define('DBHOST', 'localhost');
	define('DBUSER', 'instargram');
	define('DBPASS', '#CENSORED#');
	define('DBNAME', 'instargram');
	define('SALT', 'salt');
	define('SERVER_ROOT', 'file:///home/kali/LearningSpace/CTF/SVATTT/SVATTT2020_Final/Instagram/');
	$conn = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
	
	if ( !$conn ) {
		die("Connection failed : " . mysql_error());
	}
	
?>
