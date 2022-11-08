<?php
define('ROOT_PATH', __DIR__);
$DB = [
    'mysql' => [
        'host' => getenv('MYSQL_HOST') ? getenv('MYSQL_HOST') : 'localhost',
        'database' => getenv('MYSQL_DATABASE') ? getenv('MYSQL_DATABASE') : 'vstore',
        'user' =>  getenv('MYSQL_USER') ? getenv('MYSQL_USER') : 'root',
        'password' =>  getenv('MYSQL_PASSWORD') ? getenv('MYSQL_PASSWORD') : ''
    ]
];

define('SECRET', (getenv('SECRET') ? getenv('SECRET') : 'team_secret'));
define('SALT', (getenv('SALT') ? getenv('SALT') : 'salt'));
define('IV', (getenv('IV') ? getenv('IV') : '1111111111111111'));
define('ITERATIONS', 100);
