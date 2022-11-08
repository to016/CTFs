<?php
namespace App\Controller;

use App\Model\Item;
use App\Model\User;
use App\Util\Database\Database;

class CartController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authentication();
    }

    function get()
    {
        $items = Item::findByUserId($this->auth()->id);
        $this->data['items'] = $items;
        return $this->view('cart');
    }

    function post()
    {
        if(isset($_POST['item_id']))
        {
            return $this->sellItem($_POST['item_id']);
        }
        $this->view('cart');
    }

    function sellItem($itemId)
    {
        $con = Database::newConnection();
        $userItem = $con->fetchOne("select * from user_items where item_id=? and user_id=?", [$itemId, $this->auth()->id]);
        if(isset($userItem))
        {
            $item = Item::findById($itemId);
            $user = User::findByUsername($this->auth()->username);
            $user->money += $item->price;
            $this->auth()->money += $item->price;
            $user->update();
            $con->queryUpdate("delete from user_items where id=?", [$userItem['id']]);
            $con->close();
            $this->data['success'] = 'Sell item success. Check your money';
        }
        else
        {
            $this->data['error'] = 'Your item not found';
        }
        $items = Item::findByUserId($this->auth()->id);
        $this->data['items'] = $items;
        $this->view('cart');
    }
}