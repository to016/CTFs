<?php
@session_start();
error_reporting(0);
ini_set('display_errors', 0);
require_once 'dbconnect.php';
require_once 'patch.php';
?>
<style>
.button2 {
    background-color: pink; 
    color: white; 
    width: 15%;
    border: 2px solid white;
    padding: 5px 32px;
    margin: auto;
}

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
  <a href="register.php" class="right active">Register</a>
  <a href="login.php" class="right">Login</a>
</div><br><br>

<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
    die("<center>Already Logged in</center>");
}
?>

<center>
<?php
if(isset($_GET["user"])){
	if(!empty($_GET["user"]) && strlen($_GET["user"])<=25 && strlen($_GET["user"])>=5) {
		$sql = "SELECT * FROM fa_dating_users WHERE fa_dating_username = '".$_GET["user"]."'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if($row === NULL) {
			echo "<script>alert('Available for Registration');</script>";
		}
		else
		{
			echo "<script>alert('User already Exists');</script>";
		}
	}
	else
	{
		echo "<script>alert('Something Wrong')</script>";
	}
	echo "<script>window.close();</script>";
}
?>

<?php

// init default vals
$username = $password = $pin = $name = "";
$id_card = "1111";
$yob = "1990";
$interest = "other";
$sex = array("male","female","other");

if( isset($_POST["username"]) && !empty($_POST["username"]) && strlen($_POST["username"])<=25 && strlen($_POST["username"])>=5 && isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["pin"]) && !empty($_POST["pin"]) && strlen($_POST["pin"]) === 12) {

	$username = mysqli_real_escape_string($conn, $_POST["username"]);
	$password = md5($_POST["password"]);
	$pin = mysqli_real_escape_string($conn, $_POST["pin"]);

	if( isset($_POST["yob"]) && !empty($_POST["yob"]) && strlen($_POST["yob"]) === 4) {
		$yob = mysqli_real_escape_string($conn, $_POST["yob"]);
	}

	if( isset($_POST["interest"]) && !empty($_POST["interest"]) && in_array($_POST["interest"], $sex) ) {
		$interest = mysqli_real_escape_string($conn, $_POST["interest"]);
	}

	if( isset($_POST["id_card"]) && !empty($_POST["id_card"]) && strlen($_POST["id_card"]) === 4) {
		$id_card = mysqli_real_escape_string($conn, $_POST["id_card"]);
	}

	if( isset($_POST["name"]) && !empty($_POST["name"])) {
		$name = mysqli_real_escape_string($conn, $_POST["name"]);
	}

	$sql = "INSERT INTO fa_dating_users VALUES ('$username','$password','$pin',$yob,'$interest',$id_card,'$name')";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		print("Successfully Register!");
	} 
	else
	{
		print("Failed!");
	}
}

?>
</center>

<div class="header">
	<h1 class="neon">✨ REGISTER ✨</h1><br>
	<form action="register.php" method="POST">
		Username<font color="red">*</font><br>
		<input type="text" name="username" value="" class="button1" id="username" maxlength=25 required/><br><div onclick="check_available()" class="button2">Check</div><br>
		Full Name<font color="red">*</font>
		<input type="text" name="name" value="" class="button1" required/>
		&nbsp;&nbsp;&nbsp;Password<font color="red">*</font>
		<input type="password" name="password" value="" class="button1" required/>
		&nbsp;&nbsp;&nbsp;PIN Number<font color="red">*</font>
		<input type="password" name="pin" value="" class="button1" pattern="[0-9]{12}" title="pin contains 12 digits" required/><br><br>
		Last 4-digits ID-card
		<input type="text" name="id_card" value="" class="button1"/>
		&nbsp;&nbsp;&nbsp;Year-of-Birth
		<input type="text" name="yob" value="" class="button1"/><br><br>
		Interested [male/female/other]<br>
		<input type="text" name="interest" value="" class="button1"/><br><br>
		<button type="submit" class="button1">Register</button>
	</form>
</div>

<script>
	function check_available(){
		var username = document.getElementById("username").value;
		var left = screen.width/2 - 125
		var top = screen.height/2 - 225
		let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=250,height=150,left=${left},top=${top}`;
		window.open("?user="+username,'test', params);
	}
</script>

</body>
</html>


