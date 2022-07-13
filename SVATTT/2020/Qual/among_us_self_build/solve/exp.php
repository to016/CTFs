<?php

// class CrewMate {
// 	public $name;
// 	public $secret_number;
// }


// $cm = new CrewMate();
// $cm -> name = "yellow";

// $cm -> secret_number = range(1,9);
// echo base64_encode(serialize($cm));

$secret_number = NULL;
$random_rand = rand(0, $secret_number);
srand($random_rand);
$new_password = "";
while(strlen($new_password) < 30) {
    $new_password .= strval(rand());
}
print $new_password;