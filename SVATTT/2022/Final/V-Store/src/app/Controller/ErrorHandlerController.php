<?php

namespace App\Controller;

class ErrorHandlerController extends Controller
{
    function _404()
    {
        $page = $_GET['page'];
        header("Location: $page", true, 302);
        exit();
    }

    function _405()
    {
        return $this->view('405');
    }
}