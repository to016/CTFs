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
    width: 20%;
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
  <a href="?page=forgot" class="right active">Forgot</a>
  <a href="?page=login" class="right">Login</a>
</div><br><br>
<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
	die("<center>Hi ".$_SESSION["user"].", please do task!</center>");
}
?>

<?php
if(isset($_POST["ticket"]) && !empty($_POST["ticket"]))
{
		if($_SESSION["form_token"]===$_POST["token"]) {
			unset($_SESSION['form_token']);
			$_SESSION["form_token"] = md5(uniqid(rand(), true));
			$ticket = unserialize(base64_decode($_POST["ticket"]));
			//var_dump($ticket);
			//var_dump($ticket->name);
			$username = $ticket->name;
			$secret_number = $ticket->secret_number;
			$count = check_user_exists($conn, $username);
			if($count === 1)
			{	
				if(check_length($secret_number, 9)) {
					$secret_number = strtoupper($secret_number);
					$secret_number = check_string($secret_number);
					$secret = get_secret($conn,$username);
					var_dump($secret_number);
					var_dump($secret);

					if($secret_number !== $secret) {
						print("Wrong secret!");
					}
					else
					{
					print("OK, we will send you the new password");}
					print $secret_number;
					$random_rand = rand(0,$secret_number);
					srand($random_rand);
					$new_password = "";
					while(strlen($new_password) < 30) {
						$new_password .= strval(rand());
					}
					reset_password($conn, $username, $new_password);
					//to do: send mail the new password to the user, code later
					//print($new_password);
				}
				else
				{
					print("sai length");
					print("<center>IMPOSTOR ALERT!!!!</center>");
				}
			}
			else
			{
				//print $count;
				print("sai count");
			}
		}
		else
		{
			print("sai token");
		}
}

?>
<center>
<div>
	<br>
	<form action="?page=forgot" method="POST">
		Please send us the reset ticket to reset your password!<br>
		<br><strong><font color="white">TICKET</font></strong> <br><input type="text" name="ticket" /><br>
		<input type="hidden" name="token" value="<?php $token = $_SESSION['form_token']; echo $token; ?>" />
		<button type="submit" class="button1">Send</button>
	</form>
</div>
</center>
<br>

