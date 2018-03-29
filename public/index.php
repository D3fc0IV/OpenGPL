<?php
define('ROOT', dirname(__DIR__));
session_start();
require '../app/core/Autoloader.php';
App\Core\Autoloader::register();

use App\Core\App;
use App\Core\Database;
use \App\Table\User;
$app = App::getInstance();

/*
$user = new Database('user');
var_dump($user->get('`id` = 1'));
*/

$user = new User();
$t = new \App\Core\DBAuth($user);
var_dump($t->login('frederic','test'));

ob_start();

$content = ob_get_clean();
require ('../pages/template/default.php');