<?php

session_save_path('./tmp');         #added for local testing

define('DEV_MODE', false);

class Session
{
    public static $id = null;
    protected static $isInit = false;
    protected static $started = false;

    public static function start()
    {
        self::$isInit = true;
        if (!self::$started) {
            if (!is_null(self::$id)) {
                session_id(self::$id);
                self::$started = session_start();
            } else {
                self::$started = session_start();
                self::$id = session_id();
            }
        }
    }

    public static function stop()
    {
        if (self::$started) {
            session_write_close();
            self::$started = false;
        }
    }

    public static function destroy() {
        session_destroy();
    }

    public static function set($key, $value)
    {
        if (!isset($_SESSION) || self::get($key) == $value) {
            return;
        }
        if (!self::$started) {
            self::start();
            $_SESSION[$key] = $value;
            self::stop();
        } else {
            $_SESSION[$key] = $value;
        }
    }

    public static function get($key)
    {
        if (isset($_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function isInit()
    {
        return self::$isInit;
    }
}

class User {
    private $users;
    private $states = ['start', 'checkCreds', 'credsValid', 'userState', 'connected', 'error'];
    private $banlist = ['blackhat', 'notkindguy'];
    function __construct(){
        $userFile = 'users.txt';
        $fp = fopen($userFile,'r');
        while(($userLine = fgets($fp))!==false){
            $user = explode(':',trim($userLine),2);
            $this->users[] = $user;
        }
    }

    function login($username, $password){
        $state = Session::get('state');
        if($state === 'connected' && Session::get('authenticated') === true) exit;
        if(method_exists($this,$state)){
            $this->$state($username, $password);
        } else {
            $this->start($username, $password);
        }
    }

    function start($username, $password) {
        // NOT IN USE FOR NOW
        Session::set('state', 'checkCreds');
        $this->login($username, $password);
    }

    function checkCreds($username, $password) {
        foreach($this->users as $user) {
            if($username === $user[0] && $password === $user[1]) {
                Session::set('state', 'credsValid');
                $this->login($username, $password);
                return;
            }
        }
        Session::set('state', 'error');
        $this->login($username, $password);
    }

    function credsValid($username, $password) {
        Session::set('user', $username);
        Session::set('state', 'userState');
        $this->login($username, $password);
    }

    function userState($username, $password) {
        if(in_array($username, $this->banlist)) {
            Session::set('user',null);
            Session::set('state','error');
            $this->login($username, $password);
            return;
        } else {
            Session::set('state', 'connected');
            $this->login($username, $password);
        }
    }

    function connected($username, $password) {
        Session::set('authenticated',true);
        echo "Welcome $username, you're connected! Have a great day.";
    }

    function error($username, $password) {
        echo "Your login or password is incorrect, or you're banned :(";
        Session::destroy();
        return;
    }

    function getFlag() {
        if(Session::get('user') === 'admin' && Session::get('authenticated') && Session::get('state') === 'connected') {
            echo file_get_contents('flag.txt');
        } else {
            echo "No flag for you";
        }
    }
}

Session::start();
$users = file_get_contents('users.txt');
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