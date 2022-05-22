<?php
    include("config.php");
    session_start();
    if (isset($_SESSION['username']))
        die(header('location: shopping.php'));
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (strlen($_POST['username']) > 20)
            $message = "Tên dài quá òi, ngắn thoy bạn ơi 😅";
        else {
            try {
                $sql = "select username from users where username=?";
                $sth = $conn->prepare($sql);
                $sth->execute(array($_POST['username']));
                if ($sth->rowCount() > 0){
                    $message = "Tên đẹp quá nên đã bị lấy rồi 😅";
                }
                else {
                    $sql = "insert into users(username, password, path) values (?, ?, ?)";
                    $sth = $conn->prepare($sql);
                    $uuid = bin2hex(random_bytes(5));
                    $sth->execute(array($_POST['username'], $_POST['password'], "/users/" .$uuid));
                    $message = "Tạo tài khoản thành công, chúc bạn vui vẻ 😉";
                }
            } catch(PDOException $e) {
                $message =  "Ôi không, có gì đó sai sai. Hãy thử lại vào lúc khác nha 😅";
            }
        }
    }

?>

<html>
<head>
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
            <ul class="navbar-nav">
              <li class="nav-item">
              </li>
            </ul>
        </div>
        <form class="form-inline my-2 my-lg-0">
            <a class="btn btn-outline-light my-2 my-sm-0" href="login.php">Đăng nhập</a>
          </form>
    </nav>
    <div class="container" style="margin-top: 10%">
        <div class="card" style="width: 18rem; margin: auto">
            <div class="card-body">
                <h5 class="card-title">Đăng ký</h5>
                <form action="/register.php" method="POST">
                    <div class="form-group">
                        <label>Tên</label>
                        <input type="text" class="form-control" name="username">
                    </div>
                    <div class="form-group">
                        <label >Mật khẩu</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng ký</button><br>
                    <?php if (isset($message)) echo $message; ?>
                </form>
              </div>
        </div>
    </div>
</body>
</html>
