<?php
    require('patch/patch.php');
    require_once('dbconnect.php');
    require_once('lib.php');
    require_once('auth.php');
    require_once('prepare.php');
    require_once('curl.php');
    ini_set("display_errors", 0);
    ini_set("session.cookie_httponly", 1);
    ob_start();
    session_start();
    
    if ($_auth === false) {
        header("Location: index.php");
        exit;
    }
    $secret_path = gen_secret_path($username);
    $list = scandir($secret_path);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>InStarGram🌟</title>
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

        /*a {
          text-decoration: none;
          font-size: 22px;
          color: black;
        }*/

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
                    <div class="card">
                        <goprobluebig>User Information</goprobluebig>
                                <?php echo $_parse_current_avatar;?>
                                <goprovioletbig2><?php echo $username;?></goprovioletbig2>
                                <br>
                                <goprovioletbig2>ID: <?php echo $id;?></goprovioletbig2>
                                <br>
                                <goproviolet>🌟Instargram@HackEmAll2020🌟</goproviolet>
                                <div style="margin: 24px 0;">
                                <a href="#under_construction"><i class="fa fa-dribbble"></i></a> 
                                <a href="#under_construction"><i class="fa fa-twitter"></i></a>  
                                <a href="#under_construction"><i class="fa fa-linkedin"></i></a>  
                                <a href="#under_construction"><i class="fa fa-facebook"></i></a> 
                                </div>
                                <a href="#under_construction"><p><button>Upgrade To Diamond</button></p></a>
                    </div>
                <h3>
                    <gopro371337>We provide some awesome feature that make your image more beautiful!!!</gopro371337>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <goproblue>Select image to upload:</goproblue>
                        <br>
                        <br>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                        <br>
                        <br>
                        <section>
                        <div class="box">
                            <h10 style="text-align:right">Filter</h10>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <div class="content">
                            <select id="action" onchange="yesnoCheck(this);">
                                <option value="" selected disabled hidden>Choose Filter</option>
                                <option value="blurimage">Blur Image</option>
                                <option value="addnoiseimage">Add Noise Image</option>
                                <option value="borderimage">Border Image</option>
                                <option value="contrastimage">Contrast Image</option>
                                <option value="colormatriximage">ColorMatrix Image</option>
                            </select>
                          </div>
                        </div>
                        </section>
                        <input type="submit" value="Upload Image" name="submit">
                    </form>
                </h3>
                <br />


                <?php
                    $hex_string = bin2hex(openssl_random_pseudo_bytes(8));
                    $_convert_image = $secret_path.$hex_string.".png";

                    $username = mysqli_real_escape_string($conn,$username);
                    $res = mysqli_query($conn,"SELECT * FROM converts WHERE username='$username'");
                    $row = mysqli_fetch_array($res);
                    $count = mysqli_num_rows($res);

                    if( $count === 1 && $row['username'] === $username) 
                    {
                        $_orignal_image = $row['for_convert'];
                        $_orignal_image = $secret_path.$_orignal_image;
                        $_orignal_image = superwaf($_orignal_image);
                        $_convert_image = superwaf($_convert_image);
                        $_beautify_image = "convert -level 0%,100%,2.0 ".$_orignal_image." ".$_convert_image;
                        system($_beautify_image);
                    } 
                    foreach ($list as $key => &$_avatar) {
                        if (strlen($_avatar) > 2){
                            // echo SERVER_ROOT.$_orignal_image;
                            // echo SERVER_ROOT.$_convert_image;
                            echo "<center><goprovioletbig2>Before Edit\n</goprovioletbig2></center>";
                            $data = _curl(SERVER_ROOT.$_orignal_image);
                            echo '<center><br><b><p style="font-size:15px; color:red"><img src="data:image/jpg;base64,'.$data.'" height="200" width="200"></b></p></br></center>';

                            echo "<center><goprovioletbig2>After Edit</goprovioletbig2></center>";
                            $data2 = _curl(SERVER_ROOT.$_convert_image);
                            echo '<center><br><b><p style="font-size:15px; color:red"><img src="data:image/jpg;base64,'.$data2.'" height="200" width="200"></b></p></br></center>';

                        }
                    }
                ?>
            </section>
        </article>
        <footer class="row">
        </footer>
    </div>

</body>

</html>

