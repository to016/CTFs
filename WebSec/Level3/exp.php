<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

while(True){
    $find = "7c00";
    $trying = generateRandomString(5);
    if (str_starts_with(sha1($trying),$find)){
        echo "FOUND: $trying";
        break;
    }

}



