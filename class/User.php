<?php
/**
 * Created by PhpStorm.
 * User: FredericD
 * Date: 25-03-18
 * Time: 16:33
 */

namespace OpenGPL;


class User extends Agent{
    public $username;

    public function login(){

    }
    public function logout(){
        unset($_SESSION);
    }
}