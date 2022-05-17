<?php
    include("config.php");
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {            
            if (preg_match("/'|\"/", $_POST['username']) || preg_match("/'|\"/", $_POST['password']))
                die("Làm ơn đừng hack 😵😵😵");
            $sql = "select username, path from users where username='" .$_POST['username'] ."' and password='" .$_POST['password'] ."'";
            echo "$sql<br>";
            $sth = $conn->query($sql);
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            if ($sth->rowCount() > 0){
                $row = $sth->fetch();
                {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['api_path'] = $row['path'];
                    die(header("location: shopping.php"));
                }
            }
            else {
                $message = "Sai tên và mật khẩu rồi 😅";
            }
            
        } catch(PDOException $e) {
            $message =  "Ôi không, có gì đó sai sai. Hãy thử lại vào lúc khác nha 😅";
        }
    }
    if (isset($_SESSION['username']))
        die(header("location: shopping.php"));
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
            <a class="btn btn-outline-light my-2 my-sm-0" href="register.php">Đăng ký</a>
          </form>
    </nav>
    <div class="container" style="margin-top: 10%">
        <div class="card" style="width: 18rem; margin: auto">
            <div class="card-body">
                <h5 class="card-title">Đăng nhập</h5>
                <form action="/login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Tên</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    <?php if (isset($message)) echo "<br>" .$message; ?>
                </form>
                <div class="form-group">
                    <h6>Không có tài khoản?</h6>
                    <a href="/register.php">Đăng ký</a>
                </div>
              </div>
        </div>
    </div>
</body>
</html>
