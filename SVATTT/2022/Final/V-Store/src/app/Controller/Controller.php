<?php
namespace App\Controller;

use App\Core\Template;

class Controller
{
    protected $logger;
    protected $data = array();

    public function __construct()
    {
        $this->data['user'] = $this->auth();
    }

    public function view($view, $data = null, $preview = false)
    {
        if($preview){
            return Template::preview_render($data);
        }
        if(isset($data)){
            $data['user'] = $this->auth();
            return Template::render($view, $data);
        }
        return Template::render($view, $this->data);
    }
    //define check authentication here
    public function authentication()
    {
        $user = $this->auth();
        if (!isset($user))
        {
            header("location: index.php?page=login");
            die();
        }
    }
    
    public function auth($user=null) {
        if(isset($user)){
            $_SESSION['user'] = $user;
        } else {
            $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        }
        return $user;
    }

    public function sessionInvalidate() {
        unset($_COOKIE['auth']);
        \setcookie('auth', '', time() - 86400);
        unset($_SESSION['user']);
    }
}
?>