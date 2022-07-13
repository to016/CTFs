<?php

if (!defined('check_access')) 
{
  die("IMPOSTOR ALERT!!!");
}

?>
<div class="navbar">
  <a href="?page=home">Home</a>
  <a href="?page=crew">Crew</a>
  <a href="?page=map">Map</a>
  <a href="?page=cafeteria">Cafeteria</a>
  <a href="?page=medbay" class="active">MedBay</a>
  <a href="?page=electrical">Electrical</a>
  <a href="?page=o2">O2</a>
  <a href="?page=forgot" class="right">Forgot</a>
  <a href="?page=login" class="right">Login</a>
</div>

<style>
input[type="submit"] {
    background-color: black; 
    color: white; 
    border: 2px solid white;
    padding: 5px 32px;
}


</style>

<?php
if(!isset($_SESSION["user"]) || empty($_SESSION["user"])) {
	die("<br><center>Unauthorized!</center>");
}
?>
<center>
<br><br><br>
<?php
if(isset($_POST["scan"]) && !empty($_POST["scan"]))
{
	scan();
}

?>
<br><img src="assets/medbay_scan.png" width=15% />
<br><br>
	<form action="?page=medbay" method="POST">
		<input type="submit" name="scan" value="Scan-mo-tron-2000" />
	</form>
</center>