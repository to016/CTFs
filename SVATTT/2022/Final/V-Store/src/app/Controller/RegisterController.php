<?php
namespace App\Controller;

use App\Model\User;

class RegisterController extends Controller
{
    function get()
    {
        return $this->view('register');
    }

    function post()
    {
        $data = array();
        $data['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $data['email']= isset($_POST['email']) ? $_POST['email'] : '';
        $data['password'] = isset($_POST['password']) ? sha1($_POST['password']) : '';
        $data['name'] = isset($_POST['name']) ? $_POST['name'] : '';
        $data['money'] = 60000000;
        $user = new User($data);
        if($user->validate() === true)
        {
            if(User::findByUsername($data['username']) != null)
            {
                $this->data['error'] = 'username exists';
            }
            else 
            {
                $user->save();
                $this->data['success'] = 'register success';
            }
        } 
        else 
        {
            $this->data['error'] = $user->validate();
        }
        return $this->view('register');
    }

}