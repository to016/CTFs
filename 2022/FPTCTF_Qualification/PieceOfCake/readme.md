# Solution

Source code:

```php
<?php 
include "flag.php"; 
highlight_file(__FILE__); 
error_reporting(0); 
$str1 = $_GET['0']; 

if(isset($_GET['0'])){ 
    if($str1 == md5($str1)){ 
        echo $onion1; 
    } 
    else{ 
        die(); 
    } 
} 
else{ 
    die();    
} 

$str2 = $_GET['1']; 
$str3 = $_GET['2']; 

if(isset($_GET['1']) && isset($_GET['2'])){ 
    if($str2 !== $str3){ 
        if(hash('md5', $salt . $str2) == hash('md5', $salt . $str3)){ 
            echo $onion2; 
        } 
        else{ 
            die(); 
        } 
    } 
    else{ 
        die(); 
    } 
} 
else{ 
    die();    
} 
?> 
```

Để bypass đoạn check `$str1 == md5($str1)` mình dùng [php magic hash](https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Type%20Juggling/README.md)

=> `$str1=0e1137126905`

Flag1:

![flag1](https://user-images.githubusercontent.com/77546253/176494780-fa00ad7a-05f1-4f71-8805-ac2f5c4be44f.png)

Cuối cùng để bypass đoạn check thứ 2:

```php
    if($str2 !== $str3){ 
        if(hash('md5', $salt . $str2) == hash('md5', $salt . $str3)){ 
            echo $onion2; 
        } 
        else{ 
            die(); 
        } 
    } 
```

Chỉ cần truyền vào 2 mảng khác nhau là xong:

final payload:
`http://103.245.249.76:49155/?0=0e1137126905&1[]=bla&2[]=blabla`

Full flag:

![flag2](https://user-images.githubusercontent.com/77546253/176494809-da98c1bd-08de-41f2-ac1e-f7bf31e823ab.png)
