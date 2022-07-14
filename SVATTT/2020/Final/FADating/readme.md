# Setup

Có thể tham khảo file `setup.sql` của mình nhưng trước hết phải tự tạo database, mysql user ... và config cho đúng ở `dbconnect.php`

# Solution
- sqli ở `$_GET["user"]` (max là 25 kí tự), `yob` và `id_card` (4 kí tự) trong `register.php`
- Nếu login thành công với admin account thì ở `match.php` có thể ghi một webshell trên server bởi `file_put_contents()`

-> `?user=admin'into@,@a,@,@,@,@,@#`  và register với `yob=1, 1` và `id_card=@a)#`

Sau khi view profile sẽ lấy đc hash của admin password -> crack sẽ lấy đc pass -> login với admin -> upload shell -> RCE.

# Writeup: <https://github.com/hongan2419/SVATTT_final>