<?php
/**
* 
*/
namespace App\Util;

class Logger {
	public $file = "logs/app.log";
    
    function __construct($file = 'logs/app.log')
    {
        $this->file = $file;
    }

    function log($message, $append = true)
    {
        $time = date('Y-m-d H:i:s');
        $content = "$time   $_SERVER[REMOTE_ADDR]   $message";
        if($append)
        {
            file_put_contents($this->file, $content . PHP_EOL, FILE_APPEND);
        }
        else 
        {
            file_put_contents($this->file, $content . PHP_EOL);
        }
        
    }
}
?>