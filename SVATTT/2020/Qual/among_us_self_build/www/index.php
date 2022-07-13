<?php
@session_start();
error_reporting(0);
ini_set('display_errors', 1);
require_once 'dbconnect.php';
require_once 'lib.php';
define("check_access", "check");
if(isset($_GET['page']) && !empty($_GET['page']))
{	
	echo ($_GET['page'] . ".php");
	var_dump(include($_GET['page'] . ".php"));
}
else
{
	header("Location: ?page=home");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Among Us</title>
<link rel="stylesheet" href="assets/main.css">
<link rel="stylesheet" href="assets/w3.css">
</head>

<body>

<div class="header">
  <img src="assets/among_us.png" width="20%" />
</div>



</body>
</html>
