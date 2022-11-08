<?php
namespace App\Core;

class Route
{
    static $routes = array();

    public static function hasRoute($page, $method){
        $method = strtoupper($method);
        if(!array_key_exists($page, self::$routes)){
            return 'ErrorHandlerController@_404';
        }
        if(!array_key_exists($method, self::$routes[$page])){
            return 'ErrorHandlerController@_405';
        }
        return self::$routes[$page][$method];
    }
    public static function get($page, $function)
    {
        self::add($page, $function, 'GET');
    }

    public static function post($page, $function)
    {
        self::add($page, $function, 'POST');
    }

    public static function put($page, $function)
    {
        self::add($page, $function, 'PUT');
    }

    public static function head($page, $function)
    {
        self::add($page, $function, 'HEAD');
    }

    public static function delete($page, $function)
    {
        self::add($page, $function, 'DELETE');
    }

    private static function add($page, $function, $method)
    {
        if(!array_key_exists($page, self::$routes)){
            self::$routes[$page] = array();
        }
        if(!array_key_exists($method, self::$routes)){
            self::$routes[$page][$method] = $function;
        }
    }
}
