<?php
namespace App\Core;

use Twig\Environment;
use \Twig\Loader\FilesystemLoader;
class Template
{
    static $config = [
        'template_dir' => ROOT_PATH.'/public/templates',
        'template_ext' => '.html'
    ];

    static $loader = null;
    static $twig = null;

    public static function render($view, $data)
    {
        if(self::$loader === null)
        {
            self::$loader = new FilesystemLoader(self::$config['template_dir']);
            self::$twig = new Environment(self::$loader);
        }
        echo self::$twig->render($view.self::$config['template_ext'], $data);
    }

    public static function preview_render($name){
        $loader  =  new  \Twig\Loader\ArrayLoader();
        $twig  =  new  \Twig\Environment($loader);
        $template = $twig->createTemplate("Hello { $name } !");

        echo $template -> render();
    }
}