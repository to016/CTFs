<?php
    session_start();
    error_reporting(0);
    $time = 600;
    $now = time();
    
    if (isset($_SESSION['last_activity']) && ($now - $_SESSION['last_activity']) > $time) {
        session_unset(); session_destroy();
    }
    $_SESSION['last_activity'] = $now;
    include 'config.php';
    include 'css.php';
    if($_SERVER['REQUESTS_URI'] === '/') {
        header('Location: /index.php');
        exit;
    }
    
    if(preg_match('/login2|SERVER|\%/i',$_SERVER['REQUEST_URI'])) die('[!] No hacking');

    extract($_GET);
    $secretid = "admin";
    $secretpw = rand(10000,99999);

    if (!isset($_SESSION['guestpw'])) {
        $_SESSION['guestpw'] = rand(1000, 9999);
    }
    $guestpw = $_SESSION['guestpw'];

    if(($secretid == $_GET['id']) and ($secretpw == $_GET['pw'])) $login = 1;

    if($login == 1 && $_GET['login2'] == 2){
        disallow($include);
        include $include;
    }
    else if ($_POST['id'] === 'guest' && $_POST['pw'] === strval($guestpw)) {
        echo "<div class='message'>Login Success<hr></div>";
        result_();
    }
    else {
        echo "<div class='message'>Login Fail<hr></div>";
    }
?>
<html>
    <title>Trick_or_Trick</title>
    <body>
        <div class="background-layer"></div>
        <form method="post" action="index.php">
            <h2>Login</h2>
            <hr>
            <label for="id">ID</label>
            <input name="id" type="text" id="id" placeholder="Enter your ID">
            <label for="pw">PW</label>
            <input name="pw" type="password" id="pw" placeholder="Enter your password">
            <input type="submit" value="Submit">
        </form>
        <!-- [23.xx.xx] Dev Source Code, being modified! :
        <?php echo file_get_contents(__FILE__); ?>
    </body>
</html>
    </body>
</html>
