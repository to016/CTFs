<?php
namespace App\Model;

use App\Util\Database\Database;

class Item
{
    public $id;
    public $name;
    public $price;
    public $image;
    public $description;

    function __construct($params = array())
    {
        $this->id = isset($params['id']) ? $params['id'] : 0;
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->price = isset($params['price']) ? $params['price'] : 0;
        $this->description = isset($params['description']) ? $params['description'] : '';
        $this->image = isset($params['image']) ? $params['image'] : '';
    }

    static function findById($id)
    {
        $con = Database::getInstance();
        $sql = sprintf("select * from items where id=%d", $id);
        $res = $con->fetchOne($sql, [$id]);
        if(isset($res))
        {
            return new Item($res);
        }
        return null;
    }

    static function findAll()
    {
        $items = array();
        $con = Database::getInstance();
        $ress = $con->fetchAll("select * from items");
        foreach($ress as $res)
        {
            $items[] = new Item($res);
        }
        return $items;
    }

    static function findByType($type)
    {
        $items = array();
        $con = Database::getInstance();
        $sql = sprintf("select * from items where type='%s'", $type);
        $ress = $con->fetchAll($sql);
        foreach($ress as $res)
        {
            $items[] = new Item($res);
        }
        return $items;
    }

    static function findByUserId($userId)
    {
        $items = array();
        $con = Database::getInstance();
        $sql = sprintf("select * from user_items where user_id=%d", $userId);
        $ress = $con->fetchAll($sql, [$userId]);
        foreach($ress as $res)
        {
            $id = $res['item_id'];
            $items[] = Item::findById($id);
        }
        return $items;
    }
}