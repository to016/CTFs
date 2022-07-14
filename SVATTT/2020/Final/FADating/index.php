<?php
@session_start();
error_reporting(0);
ini_set('display_errors', 0);
require_once 'dbconnect.php';
require_once 'patch.php';
?>

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
  <a href="index.php" id="home" class="active">HOME</a>
  <a href="register.php" id="register" class="right">Register</a>
  <a href="login.php" id="login" class="right">Login</a>
<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
  print("<script>document.getElementById('login').style.display = 'none'</script>");
  print("<script>document.getElementById('register').innerHTML = 'Logout'</script>");
  print("<script>document.getElementById('register').href = 'logout.php'</script>");
  print("<a href=\"profile.php\" id=\"profile\">Profile</a>");
  print("<a href=\"match.php\" id=\"match\">Match</a>");
}
?>
</div><br><br>

<div class="header">
	<h1 class="neon">💖___ BEST DATING SITE EVER ___💋</h1>Safest System, 2 Authentication Stages, Your Identity is protected 100%!<br><br><br><br><br><font color="purple">Here how we help 😘</font><br>
	<img src="assets/alone.jpg" width="10%" />  ======>
	<img src="assets/nowkiss.jpeg" width="13%" /> ======>
	<img src="assets/ugh.jpg" width="8%" /> <br><br><br><br>	
	Join now 👇
	<a href="register.php"><h1 class="neon">💌</h1></a>
</div>

</body>
</html>
