<?php
require('patch/patch.php');
require_once('dbconnect.php');
require_once('auth.php');
require_once('lib.php');
require_once('prepare.php');
require_once('curl.php');

ini_set("session.cookie_httponly", 1);
ob_start();
session_start();

if ($_auth === false) {
    header("Location: index.php?page=login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>InStarGram</title>
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
</head>

<body>
    <header class="row" style="margin: 10px 0; box-shadow: 0 4px 2px -2px #f2f2f2;">
        <div class="container">
            
            <div class="row">
                <div class="col-md-2" style="margin: 10px 0;">
                    <a href = "index.php"><img src="images/instargram.png" width="250px" height="150px" /></a>
                </div>
                <div class="col-md-10">
                    <div class="row">
                    <div class="col-md-5 col-md-offset-6 icon-action">
                    <div class="row" style="text-align: right; margin: 15px 0;">
                        <p>
                            <a href ="#"><i class="fa fa-video-camera fa-lg" aria-hidden="true"></i></a>
                        </p>
                        <p>
                            <a href ="#"><i class="fa fa-calendar fa-lg" aria-hidden="true"></i></a>
                        </p>
                        <p>
                            <a href ="#"><i class="fa fa-comment-o  fa-lg" aria-hidden="true"></i></a>
                        </p>
                        <p>
                            <a href ="#"><i class="fa fa-bell fa-lg" aria-hidden="true"></i></a>
                        </p>
                        <p>
                        </p>
                    </div>

                </div>
                
                <div class="col-md-1 icon-action">
                        <div id="profile">
                            <div class="icon" id="profile-icon" style="cursor: pointer">
                                <?php echo $_parse_current_avatar;?>
                            </div>

                            <div class="list-group" style="position: absolute; width: 300%; left: -250%; margin-top: 10px; z-index: 10; display: none; box-shadow: 0 1px 10px rgba(0,0,0,.1); border-width: 0;"
                                id="profile-detail">
                                <div class="list-group-item" style="background-color: #e9e9e9;">
                                    <div class="row" >
                                        <div class="col-md-2" style="text-align: center;">
                                            <?php echo $_thumbnail;?>
                                        </div>
                                        <div class="col-md-9">
                                            <div style="padding: 5px 0">
                                                <center>
                                                    <b><p style='font-size:15px; color:#0099cc'>Hello <?php echo $username ;?></p></b>
                                                    <b><p style='font-size:12px; color:#0099cc'>Having a nice day</p></b>
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
                                <a href="#under_construction" class="list-group-item" style="display: block;">
                                    <div class="row" >
                                        <div class="col-md-2" style="text-align: center;">
                                                <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-md-9">
                                                    Creator Studio
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
                    <goprovioletbig>NextGen Edit Image Platform</goprovioletbig>
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
                <img src="images/image1.jpg" style="width:750px;height:550px;">
                <h3>
                    <goproblue>VietNam Wallpaper - taken by ducnt</goproblue>
                </h3>
                <div class="row">
                    <div class="col-md-5">
                        <goproblue>1,4 M Like</goproblue>
                    </div>
                    <div class="col-md-7" style="text-align: right">
                        <a href = "#"><img src = "images/like.png" height="25" width="25"><b style="font-size:20px">1,4 M</b>    
                        <a href = "#"><img src = "images/dislike.jpg" height="25" width="25"><b style="font-size:20px">400</b>
                        <a href = "#under_construction"><img src = "images/report.png" height="25" width="25"><b style="font-size:20px">Report</b></a>
                    </div>
                </div>
                <form method="post" action="comment.php">  
                <br>
                    <gopro3713377>Comment:<gopro3713377><goprovioletbig2><textarea name="comment" rows="1" cols="40" style="width: 742px; height: 66px;"></textarea></goprovioletbig2>
                <br>
                <input type="submit" name="submit" value="Submit">  
                </form>
                <?php
                    $parse = mysqli_query($conn,"SELECT * FROM comments ORDER BY timestamp DESC limit 10");
                    while($row=mysqli_fetch_array($parse)){
                        echo "<div>";
                        echo "<goprovioletbig2>".$row['username'] . ": " . $row['comment']."</goprovioletbig2>";
                        echo "</div>";
                    }
                    mysqli_close($conn);
                ?>
            </section>

            <section class="col-md-4">
                <div class="row" >
                    <a href="#">
                        <div class="col-md-7">
                            <img src="images/image2.jpg" style="width:200px;height:200px;">
                        </div>
                        <div class="col-md-5">
                            <goproblue>VIP Pro - taken by HuanRose</goproblue>
                            <br>
                            <goproblue>2,4 millions like</goproblue>
                        </div>
                    </a>
                </div>
                <hr />
                <div class="row" style="margin-bottom: 10px">
                    <a href="#">
                        <div class="col-md-7">
                            <img src="images/image3.gif" style="width:200px;height:200px;">
                        </div>
                        <div class="col-md-5">
                            <goproblue>Cat - taken by GieDuckyChoat</goproblue>
                            <br>
                            <goproblue>1,4 millions like</goproblue>
                        </div>
                    </a>
                </div>
                <div class="row"  style="margin-bottom: 10px">
                    <a href="#">
                        <div class="col-md-7">
                            <img src="images/image4.jpg" style="width:200px;height:200px;">
                        </div>
                        <div class="col-md-5">
                            <goproblue>Day Night - taken by anonymous</goproblue>
                            <br>
                            <goproblue>1,1 millions like</goproblue>
                        </div>
                    </a>
                </div>

            </section>
        </article>
        <footer class="row">
        </footer>
    </div>

</body>

</html>

