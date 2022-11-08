<?php
/**
* 
*/
namespace App\Util\Database;

use App\Util\Database\Database;
use \PDO;
class MysqlDatabase extends Database
{

	public function __construct()
	{
		global $DB;
		$options = $DB['mysql'];
		$host = $options['host'];
		$dbname = $options['database'];
		$this->connector = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $options['user'], $options['password'], array(
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
		));
	}

}
?>