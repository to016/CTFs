<?php

namespace App\Controller;

use App\Model\Item;
use App\Model\User;
use App\Util\Database\Database;
use Exception;
use \DOMDocument;

class ShopController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function get()
    {
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            try {
                $this->data['items'] = Item::findByType($type);
            } catch (Exception  $e) {
                die("Internal Server Error");
            }
        } else {
            $this->data['items'] = Item::findAll();
        }
        return $this->view('shop');
    }

    function post()
    {
        $method = $_POST['method'];
        $redirect = true;
        if ($method === "check_stock") {
            $url = $_POST['apiUrl'];
            if (strpos($url, getenv('HOST_URL')) === 0) {
                $content = @file_get_contents($url);
            } else {
                $content = "url invalid";
            }
        } else if ($method === "add_to_cart") {
            $id = $_POST['item_id'];
            list($redirect, $content) = $this->buyItem($id);
        }

        echo json_encode(array(
            'redirect' => $redirect,
            'content' => $content
        ));
    }

    function buyItem($id)
    {
        $user = User::findById($this->auth()->id);
        if (!isset($user)) {
            return array(true, "/index.php?page=login");
        }
        $item = Item::findById($id);
        if (!isset($item)) {
            // return $this->view('404');
            return array(false, "Item not found");
        }
        if ($user->money >= $item->price) {
            //add item to cart
            $con = Database::newConnection();
            $con->queryUpdate("insert into user_items(user_id, item_id) values(?, ?)", [$user->id, $item->id]);
            $con->close();
            $user->money -= $item->price;
            $user->update();
            $this->auth()->money -= $item->price;
            $mess = 'Buy item success. Check your cart';
            return array(true, '/index.php?page=cart');
        } else {
            $mess = "Not enough money";
        }
        return array(false, $mess);
    }
}
