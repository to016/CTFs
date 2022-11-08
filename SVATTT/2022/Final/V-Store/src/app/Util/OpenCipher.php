<?php
/**
* 
*/
namespace App\Util;

class OpenCipher {
    private static $key = "tH1\$iSS3CrEtk3Y_s0_H4rd_VKLLLLLLLL";
	private static $iv = "sEcR3t1VsEcR3t1V";
	private static $mode = "AES-128-CBC";
	//output base64
	public static function encript($data){
		return base64_encode(openssl_encrypt($data, self::$mode, self::$key, $options=OPENSSL_RAW_DATA, self::$iv));
	}

	//input base64
	public static function decript($data){
		return openssl_decrypt(base64_decode($data), self::$mode, self::$key, $options=OPENSSL_RAW_DATA, self::$iv);
	}
}

?>