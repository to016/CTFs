# Solution

Vì ta cần đọc file `flag.php` và `show_source ($file);` => `$_REQUEST['f']` sẽ mang giá trị flag.php

Ở đây nhận thấy `md5()` cùng với `==` => Có thể xảy ra php type juggling (magic hash)

```
php > echo '0e34234523' == 0;
1
```

Suy ra `$_REQUEST['hash'] = 0`

Nhưng nếu để `filename=flag.php` như vậy thì sẽ bị permisson denied ngay lập tức 🥺

Thử tạo một file `flag.php` như sau:

```php
<?php
$flag = "WEBSEC{this_is_a_flag_for_testing}";
```

và test:

```
PS D:\CTFs\WebSec\Level10> php -a
Interactive shell

php > echo show_source('./flag.php');                                                                                                   a
<code><span style="color: #000000">
<br />$flag&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #DD0000">"WEBSEC{this_is_a_flag_for_testing}"</span><span style="color: #007700">;</span>
</span>
</code>1
php > echo show_source('./////flag.php');
<code><span style="color: #000000">
<br />$flag&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #DD0000">"WEBSEC{this_is_a_flag_for_testing}"</span><span style="color: #007700">;</span>
</span>
</code>1
php >
```

=> ta sẽ brute force giá trị của file name: `./flag.php`, `.//flag.php` .... tới khi nào `md5($flag . $file . $flag)` bắt đầu với `0e`


