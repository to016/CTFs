<?php
namespace App\Model;

use App\Util\Database\Database;

class User
{
    public $id;
    public $username;
    public $password;
    public $email;
    public $name;
    public $money;
    public $role;


    public $guarded = array('username' ,'money', 'role');
    
    function __construct($params = array())
    {
        $this->id = isset($params['id']) ? $params['id'] : 0;
        $this->username = isset($params['username']) ? $params['username'] : '';
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->password = isset($params['password']) ? $params['password'] : '';
        $this->email = isset($params['email']) ? $params['email'] : '';
        $this->money = isset($params['money']) ? $params['money'] : 0;
        $this->role = isset($params['role']) ? $params['role'] : 'user';
    }

    static function findById($id = 0)
    {
        $con = Database::newConnection();
        $sql = sprintf("select * from users where id=%d", $id);
        $user = $con->fetchOne($sql);
        $con->close();
        if(isset($user))
        {
            $_user = new User($user);
            return $_user;
        }
        return null;
    }

    static function findByUsername($username = '')
    {
        $con = Database::getInstance();
        $sql = sprintf("select * from users where username='%s'", $username);
        $user = $con->fetchOne($sql);
        if(isset($user))
        {
            $_user = new User($user);
            return $_user;
        }
        return null;
    }

    static function auth($username = '', $password = '')
    {
        $con = Database::getInstance();
        $sql = sprintf("select * from users where username='%s' and password='%s'", $username, $password);
        $user = $con->fetchOne($sql);
        if(isset($user))
        {
            $_user = new User($user);
            return $_user;
        }
        return null;
    }

    function save() {
        $con = Database::getInstance();
        $data = [$this->username, $this->password, $this->email, $this->name, 'user', $this->money];
        $con->queryUpdate("insert into users(username, password, email, name, role, money) values(?, ?, ?, ?, ?, ?)", $data);
    }
    
    function update()
    {
        $con = Database::getInstance();
        $data = [$this->password, $this->name, $this->money, $this->id];
        $con->queryUpdate("update users set password=?, name=?, money=? where id=?", $data);
    }

    function validate()
    {
        if(!preg_match("/^[a-zA-Z0-9]+$/", $this->username))
        {
            return "username only contains a-zA-Z0-9";
        }
        
        if(empty($this->email)) 
        {
            return "email empty!";
        }
        if(empty($this->password)) 
        {
            return "password empty!";
        }
        if(empty($this->name)) 
        {
            return "name empty!";
        }
        if(strlen($this->name) > 10)
        {
            return 'name < 10 character';
        }
        return true;
    }

    function __toString()
    {
        return $this->name;
    }
}