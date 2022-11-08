<?php
namespace App\Model;

use App\Util\Database\Database;

class Flag
{
    public $flag;

    function __construct($params = array())
    {
        // $this->id = isset($params['flag']) ? $params['flag'] : 0;
        $this->flag = isset($params['flag']) ? $params['flag'] : 0;
    }

    function __toString()
    {
        return $this->flag;
    }

    function save(){
        $con = Database::getInstance();
        $con->queryUpdate("insert into flags(flag) values(?)", [$this->flag]);
    }
}