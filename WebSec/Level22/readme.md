# Solution


Gọi hàm với `$blacklist{index}` ([variable functions](https://www.php.net/manual/en/functions.variable-functions.php) trong php)

ta tiến hành đi tìm các hàm có thể in kết quả ra được như `var_dump`, `seralize`, `print_r` ...

```
var_dump: 582
seralize: 580
print_r: 585
```

Có thể index sẽ khác nên tự fuzz lại nhá 😙