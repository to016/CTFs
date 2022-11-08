<?php

require_once 'config.php';
require_once 'vendor/autoload.php';
use App\Core\FileSessionHandler;

$handler = new FileSessionHandler();
session_set_save_handler($handler, true);
session_start();

use App\Core\Application;

Application::execute();

