<?php
	ini_set("session.cookie_httponly", 1);
	define('DBHOST', 'mariadb');
	define('DBUSER', 'chongxun');
	define('DBPASS', '###CENSORED###');
	define('DBNAME', 'chongxun');
	define('SALT', '###CENSORED###');
	define('SERVER_ROOT', 'http://'.$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].'/');
	$conn = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
	if ( !$conn ) {
		die("Connection failed : " . mysql_error());
	}
?>
