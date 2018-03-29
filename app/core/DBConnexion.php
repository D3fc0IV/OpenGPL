<?php
namespace App\Core;

use \PDO;
use \PDOException;

abstract class DBConnexion{

	private static $instance;

	public static function getInstance(){
		if (!isset(self::$instance)){
		    $config = Config::getInstance();
		    try{
                self::$instance = new PDO(
                    'mysql:host='.$config->get('db_host').';dbname='.$config->get('db_name'),
                    $config->get('db_user'),
                    $config->get('db_password'),
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
                self::$instance->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
		        echo $e->getMessage();
            }
		}
		return self::$instance;
	}
}
?>