<?php
/**
 * Created by PhpStorm.
 * User: FredericD
 * Date: 25-03-18
 * Time: 16:33
 */

namespace App\Table;

use App\Core\Database;

class User extends Database {

    public $id;
    public $username;
    public $created;
    public $created_user_id;
    public $modified;
    public $modified_user_id;
    public $deleted;
    public $tablename = 'user';


}