<?php
namespace App\Core;

use App\Router;
class Application
{
    static function execute()
    {
        Router::route();
        $page = 'home';
        if(!empty($_REQUEST['page']))
        {
            $page = $_REQUEST['page'];
        }
        $method =  $_SERVER['REQUEST_METHOD'];

        $route = Route::hasRoute($page, $method);
        $tmp = explode('@', $route);
        $class = "\App\Controller\\".$tmp[0];
        $function = $tmp[1];
        call_user_func(array(new $class(), $function));
    }
}