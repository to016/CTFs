<?php

function _curl($url){
		$ch = curl_init();
		$timeout = 5;
		$SSL = substr($url, 0, 8) == "https://" ? true : false;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		if ( $SSL ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        }
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$dom = new DOMDocument();
		@$dom->loadHTML($data);

		foreach($dom->getElementsByTagName('a') as $link) {
			$check = $link->getAttribute('href');

			//check if files are images
			if ( (strpos($check, '.png') == true) || (strpos($check, '.jpg') == true) || (strpos($check, '.jpeg') == true) || (strpos($check, '.gif') == true) ){
				echo $check;
				echo "<br />";
			}
   		}
        return base64_encode($data);
    }

// print _curl($url);
?>