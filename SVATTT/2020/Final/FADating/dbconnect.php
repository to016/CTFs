<?php
	define('DBHOST', 'localhost');
	define('DBUSER', 'FADating');
	define('DBPASS', 'password'); 
	define('DBNAME', 'dating');
	
	$conn = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
	
	
	if ( !$conn ) {
		die("Connection failed : " . mysql_error());
	}
	
