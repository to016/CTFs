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

function miniwaf($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function superwaf($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = escapeshellcmd($data);
	return $data;
}

function watermark($string) {
    return $string."-InStarGram-HackEmAll2020";
}

function gen_secret_path($username) {
	$secret_path = "images/avatars/".md5(SALT.$username)."/";
	return $secret_path;
}

?>