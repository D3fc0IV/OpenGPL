<?php
namespace App\Core;

use \PDO;

class Database{
	protected $db;
    public $tablename;
	/**
	 * Database::__construct()
	 * @param mixed $tablename
	 */
	function __construct($tablename = null){
		$this->db = DBConnexion::getInstance();
		$this->tablename = $tablename;
	}
	
	/**
	 * Database::insert()
	 * @param mixed $data
	 * @return lastInsertID
	 */
	public function insert($data){
		try {
			$dt = new DateTime();
			if(isset($this->columns)){
				if (in_array('d_insert',$this->columns)) $data['d_insert'] = $dt->format('Y-m-d H:i:s');
				if (in_array('d_update',$this->columns)) $data['d_update'] = $dt->format('Y-m-d H:i:s');	
			}
			if(isset($this->columns)){
				if (in_array('created',$this->columns)) $data['created'] = $dt->format('Y-m-d H:i:s');
                if (in_array('created_user_id',$this->columns)) $data['created_user_id'] = $_SESSION['user']['informations']['id_personnel'];
				if (in_array('modified',$this->columns)) $data['modified'] = $dt->format('Y-m-d H:i:s');
                if (in_array('modified_user_id',$this->columns)) $data['modified_user_id'] = $_SESSION['user']['informations']['id_personnel'];	
			}
			
			$fields = array();
			$values = array();
			foreach ($data as $k => $v){
				if (isset($this->columns)){
					if (in_array($k,$this->columns)){
						$fields[] = $this->protect_field($k);
						$values[] = $this->escape($v);
					}
				}else{
					$fields[] = $this->protect_field($k);
					$values[] = $this->escape($v);
				}
			}
            $this->db->exec('INSERT INTO `'.$this->tablename.'` ('.implode(', ',$fields).') VALUES ('.implode(',',$values).')');
            return $this->db->lastInsertId();

        }
        catch (PDOException $e) {
            $e->getMessage();
        }
	}

	/**
	 * Database::set()
	 * @param mixed $string
	 * @param mixed $where
	 * @return void
	 */
	public function set($string, $where){
		if ($where == '') return false;
		if (!is_array($where)){
			$dest = array($where);
		}else{
			$dest = array();
			foreach ($where as $key => $val){
				$prefix = (count($dest) == 0) ? '' : ' AND ';
				if ($val !== ''){
					if ( ! $this->_has_operator($key)){
						$key = $this->protect_field($key).' =';
					}
					$val = ' '.$this->escape($val);
				}
				$dest[] = $prefix.$key.$val;
			}
		}
		$dt = new DateTime();
		if (in_array('d_update',$this->columns)) $data['d_update'] = $dt->format('Y-m-d H:i:s');
        if (in_array('modified_user_id',$this->columns)) $data['modified_user_id'] = $_SESSION['user']['informations']['id_personnel'];
		if (in_array('modified',$this->columns)) $data['modified'] = $dt->format('Y-m-d H:i:s');
		
		try {
			$sql = 'UPDATE `'.$this->tablename.'` ';
			$sql .= 'SET '.$string;
			$sql .= ($dest != '' && count($dest) >= 1) ? ' WHERE '.implode(' ', $dest) : '';
			//echo $sql;
			$this->db->exec($sql);
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}
	
    /**
	 * Database::update()
	 * @param mixed $data
	 * @param mixed $where
	 * @return void
	 */
    public function update($data, $where){
		if ($where == '') return false;
		if (!is_array($where)){
			$dest = array($where);
		}else{
			$dest = array();
			foreach ($where as $key => $val){
				$prefix = (count($dest) == 0) ? '' : ' AND ';
				if ($val !== ''){
					if ( ! $this->_has_operator($val)){
						$key = $this->protect_field($key).' =';
                        $val = ' '.$this->escape($val);
					}else{$key = '';}

				}
				$dest[] = $prefix.$key.$val;
			}
		}
		$dt = new DateTime();
		if (in_array('d_update',$this->columns)) $data['d_update'] = $dt->format('Y-m-d H:i:s');
        if (in_array('modified_user_id',$this->columns)) $data['modified_user_id'] = $_SESSION['user']['informations']['id_personnel'];
		if (in_array('modified',$this->columns)) $data['modified'] = $dt->format('Y-m-d H:i:s');
		$fields = array();
		foreach ($data as $k => $v){
			if (in_array($k,$this->columns)){
				if( !$this->_has_operator($k)){
					$fields[] = $this->protect_field($k).' = '.$this->escape($v);
				}else
					$fields[] = $k.' '.$v;	
			}
		}
		try {
			$sql = 'UPDATE `'.$this->tablename.'` ';
			$sql .= 'SET '.implode(', ', $fields);
			$sql .= ($dest != '' && count($dest) >= 1) ? ' WHERE '.implode(' ', $dest) : '';
			//echo $sql;
			return $this->db->exec($sql);
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}

	/**
	 * Database::delete()
	 * @param mixed $where
	 * @return number of row treated
	 */
	public function delete($where){
		if (!is_array($where)) $where = array($where);
		$conditions = '';
		if(count($where) > 0){
			$conditions = 'WHERE 1 = 1';
			foreach ($where as $w){
				$conditions .= ' AND '.$w;
			}
		}
		try {
			return $this->db->exec('DELETE FROM `'.$this->tablename.'` '.$conditions);
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}
	
	/**
	 * Database::get()
	 * @param mixed $where
	 * @return object
	 */
	public function get($where){
		if (!is_array($where)) $where = array($where);
		$conditions = '';
		if(count($where) > 0){ // || count($like)
			$conditions = 'WHERE 1 = 1';
			foreach ($where as $w){
				$conditions .= ' AND '.$w;
			}
		}
		try {
            $stmt = $this->db->query('SELECT * FROM `'.$this->tablename.'` '.$conditions);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
            $stmt = null;
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}
	
	/**
	 * Database::getList()
	 * @param mixed $where
	 * @param mixed $order
	 * @return Array
	 */
	public function getList($where = null, $order = null, $limit = null){
		if (!is_array($where) && !is_null($where)) $where = array($where);
		$conditions = '';
		if(count($where) > 0){ // || count($like)
			$conditions = 'WHERE 1 = 1';
			foreach ($where as $w){
				$conditions .= ' AND '.$w;
			}
		}
		$order = !is_null($order) ? 'ORDER BY '.$order : '';
		try {
            $stmt = $this->db->query('SELECT * FROM `'.$this->tablename.'` 
				'.$conditions.' 
				'.$order.'
				'.$limit);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt = null;
            return $result;
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}
	
	/**
	 * Database::protect_field()
	 * @param mixed $field
	 * @return String
	 */
	private function protect_field($field){
		return "`".$field."`";
	}
	
	/**
	 * Database::escape()
	 * @param mixed $str
	 * @return String
	 */
	public function escape($str){
		if (is_string($str)){
			$str = $this->db->quote($str);
		}
		elseif (is_bool($str)){
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str)){
			$str = 'NULL';
		}
		return $str;
	}
	
	/**
	 * Database::_has_operator()
	 * @param mixed $str
	 * @return BOOLEAN
	 */
	public function _has_operator($str){
		$str = trim($str);
		if ( ! preg_match("/(\s|<|>|!|=|IS NULL|IS NOT NULL|BETWEEN)/i", $str)){
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Database::getArray()
	 * @return Array
	 */
	public function getArray(){
		try {
            $stmt = $this->db->query('SELECT * FROM `'.$this->tablename.'`');
            $result = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
            $stmt = null;
            return $result;
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}

	/**
	 * Database::count()
	 * @return Int
	 */
	public function count($target, $where = null){
		if (!is_array($where) && !is_null($where)) $where = array($where);
		$conditions = '';
		if(count($where) > 0){ 
			$conditions = 'WHERE 1 = 1';
			foreach ($where as $w){
				$conditions .= ' AND '.$w;
			}
		}
		try {
            $stmt = $this->db->query('SELECT COUNT('.$target.') AS "number" 
				FROM `'.$this->tablename.'` 
				'.$conditions);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $stmt = null;
            return $result->number;
        }catch (PDOException $e) {
            $e->getMessage();
        }
	}
}