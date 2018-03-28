<?php
session_start();
require '../app/core/Autoloader.php';
App\Core\Autoloader::register();

$config = new App\Core\Config();
var_dump($config);