# Solution

Source code:

```php
<?php
require "flag.php";
error_reporting(0);
 $md51 = md5( '0gdVIdSQL8Cm' ); 
 $a = @$_GET[ '0ni0n' ]; 
 $md52 = @md5($a); 
 if ( isset ($a)){ 
    if ($a != '0gdVIdSQL8Cm' && $md51 == $md52) { 
        echo "<script>alert('$flag')</script>"; 
    } else { 
        echo "0ni0n{F3k4_Fl4G}"; 
    } 
} 
show_source(__FILE__);
?>
```
Đầu tiên thử tính `md5( '0gdVIdSQL8Cm' );`
```
php > echo md5( '0gdVIdSQL8Cm' );
0e366928091944678781059722345471 
```


Tới đây lợi dụng php type juggling để bypass:



