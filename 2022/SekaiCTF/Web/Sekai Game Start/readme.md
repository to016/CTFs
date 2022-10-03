# Sekai Game Start

Source:

```php
<?php
include('./flag.php');
class Sekai_Game{
    public $start = True;
    public function __destruct(){
        if($this->start === True){
            echo "Sekai Game Start Here is your flag ".getenv('FLAG');
        }
    }
    public function __wakeup(){
        $this->start=False;
    }
}
if(isset($_GET['sekai_game.run'])){
    unserialize($_GET['sekai_game.run']);
}else{
    highlight_file(__FILE__);
}

?>
```

Về solution, ta cần phải làm cho server khi unserialize thì magic method `__wakup` sẽ không được gọi mà thay vào đó là gọi method `__destruct` để lấy được flag. Thử search "bypass __wakeup in php" sẽ được dẫn đến link sau <https://bugs.php.net/bug.php?id=81151>

Vấn đề thứ hai là ở cách pass giá trị cho param `sekai_game.run`, bởi vì trong php một vài kí tự như `<space>`, `[`, `.` ... sẽ bị chuyển thành `_`. Bay vào check source của php (ở [đây](https://github.com/php/php-src/blob/master/main/php_variables.c)), nếu kí tự đó là `[` thì sẽ bỏ qua các kí tự sau

![php_source](https://user-images.githubusercontent.com/77546253/193605397-3fa326ec-8784-4bde-a2f2-9e37b6df8c8d.png)

=> truyền `?sekai[game.run=`

Final payload và flag:

![flag](https://user-images.githubusercontent.com/77546253/193605416-018b9095-d296-42fd-af6b-3baf4e44ff2a.png)
