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
     * @param $namespace string
     */
    static function autoload($namespace){
        $namespace = strtolower($namespace . '.php');
        $temp = explode('\\', $namespace);
        $temp  = array_map('strtolower', $temp);

        $classname = end($temp);
        $namespace = str_replace($classname, '', $namespace);
        $classname = ucfirst($classname);

        if(strpos($namespace,__NAMESPACE__.'\\') == 0) {
            $namespace = str_replace(__NAMESPACE__ . '\\', '', $namespace);
            require ROOT . '\\' . $namespace . $classname;
        }
    }
}