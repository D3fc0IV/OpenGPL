<?php
/**
 * Project : OpenGPL
 * Author: fredericd
 * Date: 29-03-18
 * Time: 10:20
 */

namespace App\Core;
use App\Core\Database;

class DBAuth{
    private $db;

    public function __construct(Database $users){
        $this->db = $users;
    }

    public function login($username, $password){
        $user = $this->db->get(array('username' => $username));
        var_dump($user);
    }

    public function logout(){
        unset($_SESSION);
    }
}