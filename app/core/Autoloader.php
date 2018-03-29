<?php
/**
 * Created by PhpStorm.
 * User: FredericD
 * Date: 25-03-18
 * Time: 13:02
 */

namespace App\Core;

/**
 * Class Autoloader
 * @package OpenGPL
 */
class Autoloader{
    /**
     *
     */
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclusion du fichier correspondant à la classe
     * @param $class_name string
     */
    static function autoload($class_name){
        $temp = explode('\\', $class_name);
        $temp  = array_map('strtolower', $temp);
        $class_name = ucfirst(end($temp));


        if(strpos($class_name,__NAMESPACE__.'\\') == 0) {
            $class_name = str_replace(__NAMESPACE__.'\\', '', $class_name);
            $class_name = str_replace('\\', '/', $class_name);
            var_dump( __DIR__ . '\\' . $class_name . '.php');
            require __DIR__ . '\\' . $class_name . '.php';
        }
    }
}