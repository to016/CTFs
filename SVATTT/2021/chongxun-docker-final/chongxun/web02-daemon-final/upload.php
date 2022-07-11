<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chongxun</title>
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
    <link rel="shortcut icon" href="images/avatar.png">
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

    img.for_upload {
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
        background-color: white;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }
    </style>
	
</head>

<?php
    require('patch/patch.php');
    require_once('dbconnect.php');
    require_once('auth.php');
    require_once('lib.php');

    ini_set("display_errors", 0);
    ini_set("session.cookie_httponly", 1);

    ob_start();
    session_start();

    if ($_auth === false) {
        header("Location: index.php");
        exit;
    }

    $tmp  = basename($_FILES["fileToUpload"]["name"]);
    $extension = pathinfo($tmp, PATHINFO_EXTENSION);
    $filename = uuid4();
    $target_file = "images/upload/".$filename.".".$extension;
    $target_file = superwaf($target_file);
    $_check = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(isset($_POST["submit"])) {
        $_check = 1;
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $_check = 1;
        } else {
            $_check = 0;
        }
    }

    if (file_exists($target_file)) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, file already exists. The page auto refresh after 5 second</p></b></center>";
        $_check = 0;
    }

    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, your file is too large. The page auto refresh after 5 second</p></b></center>";
        $_check = 0;
    }

    if($imageFileType == "php" or $imageFileType == "phar" or $imageFileType == "pht") {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Hmmmm not like this homie.</p></b></center>";
        $_check = 0;
    }

    if ($_check == 0) {
        echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, your file was not uploaded. The page auto refresh after 5 second</p></b></center>";
        echo '<meta http-equiv="refresh" content="5;url=account.php"/>';
        die();

    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<center><b><p style='font-size:20px; color:#ff5050'>Your file has been uploaded.</p></b></center>";
            $target_file = SERVER_ROOT.$target_file;
            echo "<center><b><p style='font-size:15px; color:#cc0066'>The file locate at: <a href ='".htmlentities($target_file)."'>".htmlentities($target_file)."</a></p></b></center>";
            echo "<center><b><p style='font-size:15px; color:#cc0066'>Click <a href='account.php'>here</a> for redirect to the account page.</p></b></center>";

        } else {
            echo "<center><b><p style='font-size:15px; color:#cc0066'>Sorry, there was an error uploading your file. The page auto refresh after 5 second</p></b></center>";
            echo '<meta http-equiv="refresh" content="5;url=account.php"/>';
        }
    }
?>
