<?php

if (!defined('check_access')) 
{
  die("IMPOSTOR ALERT!!!");
}

?>
<?php

if(empty($_SESSION["form_token"]))
{
	$gen_token=md5(uniqid(rand(), true));
	$_SESSION["form_token"] = $gen_token;
}

?>
<style>
.button1 {
    background-color: black; 
    color: white; 
    border: 2px solid white;
    padding: 5px 32px;
}

input:not([type="submit"]) {
    background-color: black; 
    color: white; 
    width: 10%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid white;
    border-radius: 4px;
}


</style>
<div class="navbar">
  <a href="?page=home">Home</a>
  <a href="?page=crew">Crew</a>
  <a href="?page=map">Map</a>
  <a href="?page=cafeteria">Cafeteria</a>
  <a href="?page=medbay">MedBay</a>
  <a href="?page=electrical">Electrical</a>
  <a href="?page=o2">O2</a>
  <a href="?page=forgot" class="right">Forgot</a>
  <a href="?page=login" class="right active">Login</a>
</div><br><br>
<center>
<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
	die("Hi ".$_SESSION["user"].", please do task!");
}
?>
<?php 
if(isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"]))
{
		if($_SESSION["form_token"]===$_POST["token"]) {
			unset($_SESSION['form_token']);
			$_SESSION["form_token"] = md5(uniqid(rand(), true));

			$count = check_user_exists($conn, $_POST["username"]);
			if($count === 1)
			{	
				if(md5($_POST["password"]) === get_password($conn, $_POST["username"]))
				{
					$_SESSION["user"] = $_POST["username"];
					header("Refresh:0");
				}
				else 
				{
					print("<center>IMPOSTOR ALERT!!!</center>");
				}
			}
			else
			{
				print("<center>IMPOSTOR ALERT!!!</center>");
			}
		}
}
?>
<div>
	<br>
	<form action="?page=login" method="POST">
		<br><strong><font color="white">USERNAME</font></strong> <br><input type="text" name="username" /><br>
		<br><strong><font color="white">PASSWORD</font></strong> <br><input type="password" name="password" /><br>
		<input type="hidden" name="token" value="<?php $token = $_SESSION['form_token']; echo $token; ?>" /><br>
		<button type="submit" class="button1">Login</button>
	</form>
</div>
<br>
</center>
