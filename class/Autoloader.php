<?php
/**
 * Created by PhpStorm.
 * User: FredericD
 * Date: 25-03-18
 * Time: 13:02
 */

namespace OpenGPL;

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
     * @param $class string
     */
    static function autoload($class){
        if(strpos($class,__NAMESPACE__.'\\') == 0) {
            $class = str_replace(__NAMESPACE__.'\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require 'class/'.$class.'.php';
        }
    }
}