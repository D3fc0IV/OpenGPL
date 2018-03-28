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

    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new App();
        }
        return self::$_instance;
    }
}