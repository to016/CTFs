<?php

	// this will avoid mysql_connect() deprecation error.
	error_reporting( ~E_DEPRECATED & ~E_NOTICE );
	// but I strongly suggest you to use PDO or MySQLi.
	
	define('DBHOST', 'db');
	define('DBUSER', 'hackemall');
	define('DBPASS', 'h@ckemall');
	define('DBNAME', 'among_us');
	
	$conn = mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
	
	
	if ( !$conn ) {
		die("Connection failed : " . $conn->connect_error);
	}
