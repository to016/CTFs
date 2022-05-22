<?php
    error_reporting(0);
    session_start();
    $main = true;
    if (!isset($_SESSION['db'])) {
        $db = '/db/' . session_id() . ".db";
        $_SESSION['db'] = $db;
        $init = true;
    }
    else {
        $db = $_SESSION['db'];
        $init = false;
    }
    $pdo = new PDO("sqlite:" . getcwd() . $db);
    if ($init) {
        $pdo->exec("CREATE TABLE img (user_url string);");
    }
    if (!isset($_GET["page"]))
        die(header("Location: index.php?page=view.php")); 
    $page = $_GET["page"];
    if (stripos($page, "..") !== false || substr($page, 0, 1) == "/") {
        die("Hack detected");
    }
    include($page);
?>