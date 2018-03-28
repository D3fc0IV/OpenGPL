<?php
/**
 * Project : OpenGPL
 * Author: fredericd
 * Date: 28-03-18
 * Time: 15:17
 */

namespace App\Core;

class Config{
    private $settings = [];
    private static $_instance;

    public function __construct(){
        $this->settings = require dirname(__DIR__) . '/config/config.php';
    }

    public static function getInstance(){
        if(self::$_instance === null){
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

    public function get($key){
        if(!isset($this->settings[$key])){
            return null;
        }
        return $this->settings[$key];
    }

}