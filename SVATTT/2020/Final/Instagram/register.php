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

	if (isset($_POST['btn-signup'])) {
		$name = mysqli_real_escape_string($conn,$_POST['name']);
		$pass = mysqli_real_escape_string($conn,$_POST['pass']);

		if (empty($name)) 
		{
			$error = true;
			$nameError = "Please enter your full name.";
		}
		else if (strlen($name) < 3) 
		{
			$error = true;
			$nameError = "Name must have at least 3 characters.";
		}
		else if (!preg_match("/^[a-zA-Z0-9]+$/",$name))
		{
			$error = true;
			$nameError = "Name must contain alphabets and number.";
		}
		
		if (empty($pass)){
			$error = true;
			$passError = "Please enter password.";
		} else if(strlen($pass) < 6) {
			$error = true;
			$passError = "Password must have atleast 6 characters.";
		}
		
		$username = $name;
		$password = hash('md5', $pass);

		$check_user_exists = "SELECT username FROM users WHERE username='$username'";
		$check_username = mysqli_fetch_array(mysqli_query($conn, $check_user_exists));
		if($check_username>=1)
		{
			echo "<center><gopro3713377>\nUsername already exists, register again pls dude\n</gopro3713377></center>";
		}
		else
		{
			if( !$error ) {
				$default_avatar = SERVER_ROOT."images/avatars/default_image.png";
				$query = "INSERT INTO users(`username`,`password`,`avatar_name`) VALUES ('$username','$password','$default_avatar')";
				$res = mysqli_query($conn,$query);
				
				$query2 = "INSERT INTO converts(`username`) VALUES ('$username')";
				$res2 = mysqli_query($conn,$query2);
				
				if ($res) {
					$errTyp = "success";
					$errMSG = "Successfully registered, you may login now";
					unset($name);
					unset($pass);
				} else {
					$errTyp = "danger";
					$errMSG = "Something went wrong, try again later...";	
				}	
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
<link rel="stylesheet" href="css/box.css" type="text/css" />
</head>
<body>
<div class="container">
	<section id="content">
		<form method="post" action="index.php?page=register.php" autocomplete="off">
			<h1>InStarGram🌟</h1>

			<div class="form-group">
            	<h2 class="">Register</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
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
            	<input type="username" name="name" class="form-control" placeholder="Enter Name" required="" maxlength="50" value="<?php echo htmlentities($name); ?>" />
                <span class="text-danger"><?php echo $nameError; ?></span>
            	<input type="password" name="pass" class="form-control" placeholder="Enter Password" required="" maxlength="50" />
                <span class="text-danger"><?php echo $passError; ?></span>
                <input type="submit" value="Sign Up"  name="btn-signup"/>
            </div>
            
            <div class="form-group">
            	<a href="index.php?page=login.php">Login in Here...</a>
            </div>

		<div class="button">
			<center><a href = "index.php"><img src = "images/instargram.png" width="80%" height="80%"></center>
		</div>
	</section>
</div>
</body>
<?php ob_end_flush(); ?>

