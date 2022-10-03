# Sekai Game Start

Về solution, ta cần phải làm cho server khi unserialize thì magic method `__wakup` sẽ không được gọi mà thay vào đó là gọi method `__destruct` để lấy được flag. Thử search "bypass __wakeup in php" sẽ được dẫn đến link sau <https://bugs.php.net/bug.php?id=81151>

Vấn đề thứ hai là ở cách pass giá trị cho param `sekai_game.run`, bởi vì trong php một vài kí tự như `<space>`, `[`, `.` ... sẽ bị chuyển thành `_`. Bay vào check source của php (ở [đây](https://github.com/php/php-src/blob/master/main/php_variables.c)), nếu kí tự đó là `[` thì sẽ bỏ qua các kí tự sau

(php_source.png)

=> truyền `?sekai[game.run=`

Final payload và flag:

(flag.png)