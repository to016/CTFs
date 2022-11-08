<?php

namespace App\Controller;

use App\Model\Item;
use App\Model\User;
use App\Util\Database\Database;
use Exception;
use \DOMDocument;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authentication();
    }

    function get()
    {
        return $this->view('profile');
    }

    function post()
    {
        // $name = $_POST['name'];
        $user = $this->auth();


        if (isset($_POST['preview'])) {
            if ($user->role === 'admin') {
                return $this->preview($_POST['name']);
            } else {
                echo "Forbidden. Only admin can preview.";
                return;
            }
        } else {
            $inputJSON = file_get_contents('php://input');
            $arr = json_decode($inputJSON, TRUE);

            foreach ($arr as $i) {
                $key = $i['name'];
                $value = $i['value'];

                if (!in_array($key, $user->guarded))
                    $user->$key = $value;
            }
            $user->update();
            return $this->view('profile');
        }
    }

    function preview($name)
    {
        return $this->view(null, $name, true);
    }
}
