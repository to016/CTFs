<?php
	require_once('dbconnect.php');
	ini_set("display_errors", 0);
	ini_set("session.cookie_httponly", 1);
	ob_start();
	session_start();


	$regex="/(secret|proc|environ|access|error|\.\.|\/|,|;|[|]|\|connect)/i";
	if(isset($_GET['page']) && !empty($_GET['page']))
	{
		if(!preg_match_all($regex,$_GET['page']))
		{
			include($_GET['page']);
		}
		else
		{	
			echo "<tr><center><strong><td><font color='violet' size=50 >\nOops script kiddie detected!!\n</font></td>";
			die('<p style="text-align:center;"><img src="images/doge.jpg" width=80% height=100% /></p>');
		}
	}
	else
	{
		if ($_COOKIE['user'] == "") {
			header("Location: index.php?page=login.php");
			exit;
		}

		else{
			$username = substr($_COOKIE['user'],0,strlen($_COOKIE['user'])-32);
			$hashpasswd = substr($_COOKIE['user'],-32);
			$username = mysqli_real_escape_string($conn,$username);
			$hashpasswd = mysqli_real_escape_string($conn,$hashpasswd);
			$res=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' and password = '$hashpasswd'");
			$row=mysqli_fetch_array($res);
			$count = mysqli_num_rows($res);
			echo "dkm";
			if( $count !== 1 && $row['password'] !== $hashpasswd ) 
			{
				header("Location: index.php?page=login.php");
				exit;
			}
			header("Location: index.php?page=instargram.php");
			exit;
		}
		
		header("Location: index.php?page=login.php");
		exit;
	}

?>
