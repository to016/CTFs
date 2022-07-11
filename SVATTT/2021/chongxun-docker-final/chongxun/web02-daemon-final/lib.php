<?php
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


function superwaf($data) {
    $data = strtolower($data);
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = escapeshellcmd($data);
    return $data;
}

function uuid4()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function regexpasswd($data)
{
    $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,}$)/";
    if (preg_match_all($regex, $data) === 1)
        return True;
    else
        return False;
}

?>