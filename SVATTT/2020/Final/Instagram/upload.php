<!DOCTYPE html>
<html lang="en">

<head>
    <title>InStarGram🌟</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css">
        .icon-action p {
            display: inline;
            margin: 0 20px;
        }

        .hidden-overflow {
            white-space: nowrap;
            overflow: hidden !important;
            text-overflow: ellipsis;
        }
    </style>
    <link rel="shortcut icon" href="images/instargram.png">
    <html lang="en">

    <style>
    form {
        border: 3px solid #f1f1f1;
    }

    input[type=text], input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .cancelbtn {
        width: auto;
        padding: 10px 18px;
        background-color: #f44336;
    }

    .imgcontainer {
        text-align: center;
        margin: 24px 0 12px 0;
    }

    img.avatar {
        width: 40%;
        border-radius: 50%;
    }

    .container {
        padding: 16px;
    }

    span.keypass {
        float: right;
        padding-top: 16px;
    }
    @media screen and (max-width: 300px) {
        span.keypass {
           display: block;
           float: none;
        }
        .cancelbtn {
           width: 100%;
        }
    }
    </style>

    <style>
        body {
        /*background: url("images/background.jpg") no-repeat center center fixed;*/
        background-color: white;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }
    </style>
	<meta http-equiv="refresh" content="5;url=account.php" />
	
</head>

<?php
    require('patch/patch.php');
    require_once('dbconnect.php');
    require_once('auth.php');
    require_once('lib.php');
    require_once('prepare.php');

    ini_set("display_errors", 0);
    ini_set("session.cookie_httponly", 1);

    ob_start();
    session_start();
    
    if ($_auth === false) {
        header("Location: index.php");
        exit;
    }

    $secret_path = gen_secret_path($username);
    mkdir($secret_path,0777,true);
    $filename  = basename($_FILES["fileToUpload"]["name"]);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $avatar = trim($filename,".".$extension);
    $avatar = watermark($avatar).'.'.$extension;
    $avatar = miniwaf($avatar);
    $target_file = $secret_path . $avatar;

    $_for_convert = trim($filename,".".$extension);
    $_for_convert =  $_for_convert.".".$extension.watermark("").".png";

    $_check = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(isset($_POST["submit"])) {
        $_check = 1;
    }

    if (file_exists($target_file)) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, file already exists. The page auto refresh after 5 second</p></b></center>";
        $_check = 0;
    }
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, your file is too large. The page auto refresh after 5 second</p></b></center>";
        $_check = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "svg" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, only JPG, JPEG, PNG, SVG && GIF files are allowed.</p></b></center>";
        $_check = 0;
    }
    if ($_check == 0) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, your file was not uploaded. The page auto refresh after 5 second</p></b></center>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<center><b><p style='font-size:20px; color:#ff5050'>Your file has been uploaded.</p></b></center>";
            $avatar = SERVER_ROOT.$secret_path.$avatar;
            $avatar = mysqli_real_escape_string($conn,$avatar);
            $store_avatar_url = mysqli_query($conn,"UPDATE users SET avatar_name = '$avatar' WHERE username='$username'");
            $_for_convert = superwaf($_for_convert);
            $_for_convert = mysqli_real_escape_string($conn,$_for_convert);
            $store_for_convert = mysqli_query($conn,"UPDATE converts SET for_convert = '$_for_convert' WHERE username='$username'");
            $res2 = mysqli_query($conn,"SELECT for_convert FROM converts WHERE username='$username'");
            $row2 = mysqli_fetch_array($res2);
            $count2 = mysqli_num_rows($res2);

            if( $count2 === 1 && $row['username'] === $username) 
            {

                $_prepare_for_convert = $secret_path.$row2['for_convert'];
                copy($target_file, $_prepare_for_convert);

            } 
            echo "<center><b><p style='font-size:15px; color:#cc0066'>The page auto refresh after 5 second</p></b></center>";

        } else {
            echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, there was an error uploading your file. The page auto refresh after 5 second</p></b></center>";
        }
    }

?>

