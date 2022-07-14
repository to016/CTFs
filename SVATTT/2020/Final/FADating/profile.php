<?php
@session_start();
error_reporting(0);
ini_set('display_errors', 0);
require_once 'dbconnect.php';
require_once 'patch.php';
?>
<style>
table {
	padding: 3%;
	border: dashed;
	border-color: #fc89ac;
	margin: auto;
}
td, th {
  text-align: left;
  padding: 10px;
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
  <a href="index.php" id="home">HOME</a>
  <a href="profile.php" id="profile" class="active">Profile</a>
  <a href="match.php" id="match">Match</a>
  <a href="logout.php" id="logout" class="right">Logout</a>
</div>

<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
	print("<br><br><br><center>Hello [<x class='neon'>".htmlentities($_SESSION["user"])."</x>] , below is your information: </center><br><br><br>");
}
else
{
	die("<br><center>Unauthorized</center>");
}
?>

<table>
  <tr>
    <th>Name</th>
    <th>Year-of-Birth</th>
    <th>Interested In</th>
    <th>Identity Card</th>
  </tr>

<?php
 	$username = mysqli_real_escape_string($conn, $_SESSION["user"]);
	$res = mysqli_query($conn,"SELECT * FROM fa_dating_users WHERE fa_dating_username = '$username'");
	while($row = mysqli_fetch_array($res)) 
	{
		echo '<tr>';
	 	echo '<td>'.htmlentities($row['fa_dating_name']).'</td>';
	 	echo '<td>'.htmlentities($row['fa_dating_yob']).'</td>';
	 	echo '<td>'.htmlentities($row['fa_dating_interest']).'</td>';
	 	echo '<td>********'.htmlentities($row['fa_dating_id_card']).'</td>';
	 	echo '</tr>';
	 	$pin_code = $row['fa_dating_pin_number'];
	}
?>
</table>
<br><br>
<?php

$sql = "SELECT * FROM fa_dating_comments WHERE fa_dating_username = '$username'";
$result = mysqli_query($conn, $sql);
$row2 = mysqli_fetch_assoc($result);
if($row2 === NULL) {
	$html = <<<EOF
	<div>
		<center>
			Describe Yourself (Careful! You can only add one time)<br><br>
			<form action="profile.php" method="POST">
				Description<br>
				<input type="text" name="desc" value="" class="button1"/><br>
				Pin Number<br>
				<input type="text" name="pin" value="" class="button1"/><br><br>
				<button type="submit" class="button1">Submit</button>
			</form>
		</center>
	</div>
EOF;
	print($html);
	if (isset($_POST["desc"]) && !empty($_POST["desc"]) && isset($_POST["pin"]) && !empty($_POST["pin"])  ) {
		if($_POST["pin"] === $pin_code) {
			$desc = mysqli_real_escape_string($conn, $_POST["desc"]);
			$sql = "INSERT INTO fa_dating_comments VALUES ('$username','$desc')";
			$result = mysqli_query($conn, $sql);
			if ($result) {
				print("<center>Successfully!</center>");
				header("Refresh: 0");
			} 
			else
			{
				print("Failed!");
			}
		}
		else 
		{
			print("<center>Wrong Pin!</center>");
		}
	}
}
else
{
	$html = <<<EOF
	<div>
		<center>
			<form action="profile.php" method="POST">
				Input your Pin to view your Description<br>
				<input type="text" name="pin" value="" class="button1"/><br>
				<button type="submit" class="button1" id="view_desc">View Description</button>
			</form>
		</center>
	</div>
EOF;
	print($html);
	if (isset($_POST["pin"]) && !empty($_POST["pin"]) && $_POST["pin"] === $pin_code) {
		$sql = "SELECT fa_dating_comment FROM fa_dating_comments WHERE fa_dating_username = '$username'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		print('<center>'.htmlentities($row["fa_dating_comment"]).'</center>');
	}
}
?>
</body>


