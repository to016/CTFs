<?php
/**
* 
*/
namespace App\Util\Database;

abstract class Database
{
	protected $connector;
	protected static $instance = null;

	public static function getInstance(){
		if(self::$instance == null){
			global $DB;
			if(key_exists('mysql', $DB)){
				self::$instance = new MysqlDatabase();
			}
			else if(key_exists('sqlite', $DB))
			{
				self::$instance = new SqliteDatabase();
			}
		}
		return self::$instance;
	}

	public static function newConnection()
	{
		global $DB;
		if(key_exists('mysql', $DB)){
			return new MysqlDatabase();
		}
		else if(key_exists('sqlite', $DB))
		{
			return new SqliteDatabase();
		}
		return null;
	}

	public function fetchOne($sql, $params = array()) {
		$query = $this->handlerQuery($sql, $params);
		if($row = $query->fetch()) {
		    return $row;
		}
		return null;
	}

	public function fetchAll($sql, $params = array()){
		$query = $this->handlerQuery($sql, $params);
		$result = array();
		while($row = $query->fetch()) {
		    array_push($result, $row);
		}
		return $result;
	}

	public function queryUpdate($sql, $params = array()){
		$query = $this->handlerQuery($sql, $params);
		return $query;
	}

	public function close()
	{
		$this->connector = null;
	}

	private function handlerQuery($sql, $params = array()){
		$query = $this->connector->prepare($sql);
		$res = $query->execute($params);
		if($res == null){
			return null;
		}
		return $query;
	}
}
?>