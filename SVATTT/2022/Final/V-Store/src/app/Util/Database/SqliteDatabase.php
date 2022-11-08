<?php
/**
* 
*/
namespace App\Util\Database;

use App\Util\Database\Database;
use \PDO;

class SqliteDatabase extends Database
{

	public function __construct()
	{
		global $DB;
        $url = $DB['sqlite']['url_connection'];
		$this->connector = new PDO($url);
	}

}
?>