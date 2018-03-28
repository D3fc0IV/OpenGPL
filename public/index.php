<?php
session_start();
require '../app/core/Autoloader.php';
App\Core\Autoloader::register();

$app = App\Core\App::getInstance();

