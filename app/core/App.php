<?php
/**
 * Project : OpenGPL
 * Author: fredericd
 * Date: 28-03-18
 * Time: 15:47
 */

namespace App\Core;


class App{

    public $title;
    private static $_instance;
    private static $db;

    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    public static function getDB(){
        if(is_null(self::$db)){
            self::$db = DBConnexion::getInstance();
        }
        return self::$db;
    }
}