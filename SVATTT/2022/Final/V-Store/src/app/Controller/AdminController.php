<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\Flag;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function isLocal()
    {
        return $_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR'];
    }

    function get()
    {

        if ($this->isLocal()) {
            echo file_get_contents("/flag");
        } else {
            echo "you are not admin";
        }
    }

}
