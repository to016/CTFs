<?php
namespace App\Controller;

use App\Model\Comment;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function get(){
        header('location: index.php?page=shop');
    }
}
?>