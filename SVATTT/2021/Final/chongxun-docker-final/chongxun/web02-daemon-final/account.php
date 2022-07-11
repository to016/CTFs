<?php
    require('patch/patch.php');
    require_once('dbconnect.php');
    require_once('lib.php');
    require_once('auth.php');
    ini_set("display_errors", 0);
    ini_set("session.cookie_httponly", 1);
    ob_start();
    session_start();
    
    if ($_auth === false) {
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chongxun</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/box.css">

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
        .card {
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
          max-width: 300px;
          margin: auto;
          text-align: center;
          font-family: arial;
        }

        .title {
          color: grey;
          font-size: 18px;
        }

        button {
          border: none;
          outline: 0;
          display: inline-block;
          padding: 8px;
          color: white;
          background-color: #000;
          text-align: center;
          cursor: pointer;
          width: 100%;
          font-size: 18px;
          opacity: 0.8;
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
        background-color: white;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }
    </style>

</head>

<body>
    <header class="row" style="margin: 10px 0; box-shadow: 0 4px 2px -2px #f2f2f2;">
        <div class="container">
            
            <div class="row">
                <div class="col-md-2" style="margin: 10px 0;">
                    <a href = "index.php"><img src="images/chongxun.png" width="250px" height="150px" /></a>
                </div>
                <div class="col-md-10">
                    <div class="row">
                    <div class="col-md-5 col-md-offset-6 icon-action">
                </div>
                
                <div class="col-md-1 icon-action">
                        <div id="profile">
                            <div class="icon" id="profile-icon" style="cursor: pointer">
                                <img src="images/avatar.png" width="50px" height="50px">
                            </div>

                            <div class="list-group" style="position: absolute; width: 300%; left: -250%; margin-top: 10px; z-index: 10; display: none; box-shadow: 0 1px 10px rgba(0,0,0,.1); border-width: 0;"
                                id="profile-detail">
                                <div class="list-group-item" style="background-color: #e9e9e9;">
                                    <div class="row" >
                                        <div class="col-md-9">
                                            <div style="padding: 5px 0">
                                                <center>
                                                    <b><p style='font-size:18px; color:#0099cc'>Hello Chongxun</p></b>
                                                    <b><p style='font-size:15px; color:#0099cc'>Having a nice day</p></b>
                                                </center>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <a href="account.php" class="list-group-item" style="display: block;">
                                        <div class="row" >
                                                <div class="col-md-2" style="text-align: center;">
                                                        <i class="fa fa-address-book fa-lg" aria-hidden="true"></i>
                                                </div>
                                                <div class="col-md-9">
                                                           My account
                                                </div>
                                            </div>
                                </a>
                                <a href="logout.php" class="list-group-item" style="display: block;">
                                    <div class="row" >
                                        <div class="col-md-2" style="text-align: center;">
                                            <i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-md-9">
                                            Logout
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                </div>
                </div>

                <div align="center" class="row">
                    <goprovioletbig>Chongxun's home</goprovioletbig>
                    <br>
                    <b><goproviolet>Below are some of my achievement in CyberSec industry</goproviolet></b>
                </div>
            </div>

            <script type="text/javascript">
                $(function () {
                    $("#profile-icon").click(function () {
                        $("#profile-detail").toggle();
                    });

                    $(document).mouseup(function(e) 
                    {
                        var container = $("#profile-detail");
                        if (!container.is(e.target) && container.has(e.target).length === 0){
                            container.hide();
                        }
                    });
                });
            </script>

            
            </div>
        </div>

    </header>
    <div class="container">

        <br />
        <article class="row">
            <section class="col-md-8">
                <div class="card">
                    <goprobluebig>User Information</goprobluebig>
                            <b><goprovioletbig2>Chongxun</goprovioletbig2></b>
                            <br>
                            <b><goprovioletbig2>ID: 1337</goprovioletbig2></b>
                            <br>
                            <img src="images/avatar.png" width="100px" height="100px">
                            <br>
                            <div style="margin: 24px 0;">
                            <a href="#under_construction"><i class="fa fa-dribbble"></i></a> 
                            <a href="#under_construction"><i class="fa fa-twitter"></i></a>  
                            <a href="#under_construction"><i class="fa fa-linkedin"></i></a>  
                            <a href="#under_construction"><i class="fa fa-facebook"></i></a> 
                            </div>
                            <a href="#under_construction"><p><button>RCE 0day repo</button></p></a>
                </div>
                <h3>
                    <!-- My change avatar function implementation but I'm to busy for finish it. Maybe it still not work BTW. -->
                    <gopro371337>Change avatar:</gopro371337>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <goproblue>Select image to upload:</goproblue>
                        <br>
                        <br>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <br>
                        <br>
                        <input type="submit" value="Upload Image" name="submit">
                    </form>
                </h3>
                <br />
            </section>
        </article>
        <footer class="row">
        </footer>
    </div>
</body>
</html>