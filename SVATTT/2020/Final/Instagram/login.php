<?php
	require_once('dbconnect.php');
	require_once('auth.php');
	ini_set("display_errors", 0);
	ini_set("session.cookie_httponly", 1);
	ob_start();
	session_start();

	if ($_auth === true) {
		header("Location: instargram.php");
		exit;
	}
	$error = false;
	if (isset($_POST['btn-login'])) {
		$name = mysqli_real_escape_string($conn,$_POST['name']);
		$pass = mysqli_real_escape_string($conn,$_POST['pass']);
		
		if(empty($name))
		{
			$error = true;
			$nameError = "Please enter valid username.";
		}

		if(empty($pass))
		{
			$error = true;
			$passError = "Please enter your password.";
		}

		$username = $name;
		if(!$error) 
		{
			$password = hash('md5', $pass);
			$session = $username.$password;
			$res=mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
			$row=mysqli_fetch_array($res);
			$count = mysqli_num_rows($res);
			
			if( $count === 1 && $row['username'] === $username && $row['password'] === $password ) 
			{
				setcookie('user',$session);
				header("Location: index.php?page=instargram.php");
			} 
			else 
			{
				$errMSG = "Incorrect Credentials, Try again...";
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="images/instargram.png" type="image/gif" sizes="16x16">
<title>InStarGram🌟</title>
<link rel="stylesheet" href="css/ducnt.css" type="text/css" />
</head>
<body>
<div class="container">
	<section id="content">
		<form method="post" action="index.php?page=login.php" autocomplete="off" id="login_form">
			<h1>InStarGram🌟 - Login Portal</h1>

			<?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
			
			<div class="form-group">
				<input type="username" name="name" placeholder="Username" required="" id="username" />
				<input type="password" name="pass" placeholder="Password" required="" id="password" />
				<input type="submit" value="Log in"  name="btn-login"/>
				<a href="?page=register.php">Register</a>
			</div>
		</form>
		<div class="button">
			<center><a href = "index.php"><img src = "images/instargram.png" width="80%" height="80%"></center>
		</div>
	</section>
</div>
</body>
<?php ob_end_flush(); ?>
