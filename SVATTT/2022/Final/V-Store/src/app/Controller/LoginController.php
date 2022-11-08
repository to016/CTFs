<?php
namespace App\Controller;

use App\Model\User;
use App\Util\Logger;

class LoginController extends Controller
{
    function get()
    {
        // $SECRET = getenv('TEAM_SECRET') ? getenv('TEAM_SECRET') : 'team_secret';
        $this->data['TEAM_SECRET'] = SECRET;
        $this->data['SALT'] = SALT;
        $this->data['IV'] = IV;
        
        return $this->view('login');
    }

    function post()
    {
        $encrypted_username = isset($_POST['username']) ? $_POST['username'] : '';
        $encrypted_password = isset($_POST['password']) ? $_POST['password'] : '';

        $username = $this->decrypt($encrypted_username);
        // echo 'username:' . $username;
        $password = sha1($this->decrypt($encrypted_password));

        $user = User::auth($username, $password);
        if(isset($user)) 
        {
            $this->auth($user);
            echo json_encode(array('success'=> true));
            return true;
        }
        
        echo json_encode(array('success'=> false, 'error' => 'username or password not match'));
        return false;
    }

    function decrypt($data)
    {
        // $SECRET = getenv('TEAM_SECRET') ? getenv('TEAM_SECRET') : 'team_secret';
        // decrypt with secret
        $encryptedText = \base64_decode($data);
        $key = \hash_pbkdf2("sha256", SECRET, SALT, ITERATIONS, 64);
        $decryptedText = \openssl_decrypt($encryptedText, 'AES-256-CBC', \hex2bin($key), OPENSSL_RAW_DATA, IV);
        return $decryptedText;
        // return $data;
    }

    function logout()
    {
        $this->sessionInvalidate();
        header('location: index.php?page=login');
    }

}