<?php
namespace App;

use App\Core\Route;
class Router
{
    static function route(){
        //default error
        Route::get('404', 'NotFoundController@get');
        Route::get('405', 'MethodNotAllowController@get');
        //add route here
        Route::get('home', 'HomeController@get');
        Route::get('logout', 'LoginController@logout');
        //login
        Route::get('login', 'LoginController@get');
        Route::post('login', 'LoginController@post');
        //register
        Route::get('register', 'RegisterController@get');
        Route::post('register', 'RegisterController@post');
         //shop
        Route::get('shop', 'ShopController@get');
        Route::post('shop', 'ShopController@post');
        //cart
        Route::get('cart', 'CartController@get');
        Route::post('cart', 'CartController@post');
        //admin
        Route::get('admin', 'AdminController@get');
        Route::post('admin', 'AdminController@post');
        //user
        Route::get('user', 'UserController@get');
        Route::post('user', 'UserController@post');
    }
}
?>