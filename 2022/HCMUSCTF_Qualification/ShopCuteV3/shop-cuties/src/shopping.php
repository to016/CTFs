<?php
include("config.php");
session_start();
if (!isset($_SESSION['username']))
    die(header("location: login.php"));

$items_img = json_decode(file_get_contents(BASE_API_URL . "/items.json"))->{"msg"};
$items = json_decode(file_get_contents(BASE_API_URL . $_SESSION["api_path"] . "/items.json"))->{"msg"};

$items_img = (array)$items_img;
$items = (array)$items;

?>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">
            🥰 Cửa hàng Gấu bông siêu cute 🥰
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav navbar-right">
            </ul>
        </div>

        <form class="form-inline my-2 my-lg-0">
            <a class="btn btn-outline-light my-2 my-sm-0" href="logout.php">Đăng xuất</a>
        </form>
    </nav>
    <h2>
        <div id="message" color="red"></div>
    </h2>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Cảm ơn bạn đã mua hàng</strong> Chúng tôi sẽ liên hệ với bạn sau 😆
        <button type="button" class="close" aria-label="Close" onclick=hideBuy()>
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="container text-center">
        <h1>
           Xin chào <?= $_SESSION['username']; ?>.
        </h1>
    </div>
    <div class="container">
        <div class="shop-body row">
            <?php foreach($items as $key => $value): ?>
                <div class='col-3'>
                    <div class='card'>
                        <img src='<?= "http://$_SERVER[SERVER_NAME]:32182" .$items_img[$value] ?>' class='card-img-top' alt='...'>
                        <div class='card-body'>
                            <h5 class='card-title'> <?= $value ?></h5>
                            <p class='card-text'>Gấu bông <?= $value ?></p>
                            <div class='wrapper-btn'>
                                <button class='btn btn-primary' onclick='showBuy()'>Mua ngay 👇</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <script src="/scripts/shopping.js"></script>
</body>

</html>