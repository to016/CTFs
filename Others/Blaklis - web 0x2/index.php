<?php

mkdir('/tmp/sys');
if($_SERVER['HTTP_X_FORWARDED_FOR'] && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

class User {
    private $users;
    function __construct(){
        $userFile = '/users2.txt';
        $fp = fopen($userFile,'r');
        while(($userLine = fgets($fp))!==false){
            $user = explode(':',trim($userLine),2);
            $this->users[] = $user;
        }
        session_start();
    }

    function __destruct(){ session_write_close(); }

    function login($username, $password) {
        // User is banned, tell him!
        if($this->isBanned($username)) {
            echo "Your ip is banned to use; you can't use this account anymore. Feel free to reach an administrator if you think this is an error.";
            return;
        }
    foreach($this->users as $user) {
            if($username === $user[0] && $password === $user[1]) {
                $_SESSION['username'] = $username;
                $_SESSION['authenticated'] = true;
                return;
            }
        }

        // We keep a number of failed logins for this user
        $_SESSION['failed_login_'.$username] = $_SESSION['failed_login_'.$username] + 1;

        // 3 tries = banned
        if($_SESSION['failed_login_'.$username] >= 3) {
            $this->ban($username);
        }
        echo "Seems like either your username or password weren't valid.";
    }

    function isBanned($username) {
        if($username === 'admin') {
            // Never ban administrator account, we might be in trouble otherwise
            return false;
        }
        $banfileName = '/tmp/sys/'.$_SERVER['REMOTE_ADDR'];
        if(!file_exists($banfileName)) {
            return false;
        }

        $banfile = new DOMDocument();
        $banfile->load($banfileName);
        $xpath = new DOMXpath($banfile);
        $elements = $xpath->query("//banentry[text() = '".$username."']");
        if($elements && $elements->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ban($username) {
        $banfile = new DOMDocument();
        // Validate the document
        # $banfile->validate();

        $banfileName = '/tmp/sys/'.$_SERVER['REMOTE_ADDR'];
        if(!file_exists($banfileName)) {
            $banfile->loadXML('<banlist></banlist>');
            $banfile->save($banfileName);    
        }
        $banfile->load($banfileName);
        // Avoid loading xincludes for security
        $banfile->xinclude(0);
        $banentry = $banfile->createDocumentFragment();
        $banentry->appendXML('<banentry>'.$username.'</banentry>');
        $banfile->documentElement->appendChild($banentry);
        $banfile->save($banfileName);
        return;
    }

    function getFlag() {
        if($_SESSION['username'] === 'admin' && $_SESSION['authenticated'] === true) {
            echo file_get_contents('/flag2.txt');
        } else {
            echo "No flag for you";
        }
    }
}

if(isset($_GET['page'])) {
    switch($_GET['page']) {
        case 'login':
            $user = new User();
            $user->login($_GET['username'],$_GET['password']);
            break;
        case 'flag':
            $user = new User();
            $user->getFlag();
            break;
        case 'showMeTheCode':
            highlight_file(__FILE__);
            exit;    
            
    }
}