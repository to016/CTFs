# Solution

Author: [@Blaklis_](https://twitter.com/Blaklis_)

Challenge: <https://twitter.com/Blaklis_/status/1560859714728218625>

Link: <http://18.159.101.163/?page=showMeTheCode>

Rule:

![rule](https://user-images.githubusercontent.com/77546253/186453201-cf189699-207c-43b5-a606-2dbc54e8fb74.png)

## Phân tích

Nếu ta gửi request
```
?page=login&username=user&password=user
Cookie: PHPSESSID=1.2
```

Trước tiên server thực thi đoạn code `Session::start();`

```php
public static function start()
{
    self::$isInit = true;
    if (!self::$started) {
        if (!is_null(self::$id)) {
            session_id(self::$id);
            self::$started = session_start();
        } else {
            self::$started = session_start();
            self::$id = session_id();
        }
    }
}
```

Vì `PHPSESSID` mang giá trị `1.2` không hợp lệ (server báo `Failed to read session data: files (path: ./tmp)`) nên `session_start()` sẽ failed và trả về `false`, `session_id()` trả về `""`

Có thể tự kiểm chứng với đoạn code này:
```php
<?php

session_save_path('./tmp');

$started = session_start();
$id = session_id();

var_dump($started);
var_dump($id);

session_id($id);
session_start();
session_write_close();
session_id($id);
session_start();
session_write_close();
```

-> `self::$started = false` và `self::$id = ""`


Luồng thực thi tiếp đó sẽ là hàm `login()`

```php
function login($username, $password){
    $state = Session::get('state');
    if($state === 'connected' && Session::get('authenticated') === true) exit;
    if(method_exists($this,$state)){
        $this->$state($username, $password);
    } else {
        $this->start($username, $password);
    }
}
```

`$state=Null` và gọi đến `start()`

```php
function start($username, $password) {
    // NOT IN USE FOR NOW
    Session::set('state', 'checkCreds');
    $this->login($username, $password);
}
```

hàm này gọi đến `Session::set()`

```php
public static function set($key, $value)
{
    if (!isset($_SESSION) || self::get($key) == $value) {
        return;
    }
    if (!self::$started) {
        self::start();
        $_SESSION[$key] = $value;
        self::stop();
    } else {
        $_SESSION[$key] = $value;
    }
}
```

bởi vì `self::$started=false` nên sẽ thực thi  đoạn code trong if: `start()` -> `$_SESSION[$key] = $value;` -> `stop()`

```php
public static function start()
{
    self::$isInit = true;
    if (!self::$started) {
        if (!is_null(self::$id)) {
            session_id(self::$id);
            self::$started = session_start();
        } else {
            self::$started = session_start();
            self::$id = session_id();
        }
    }
}
```

`start()` cũng thực thi đoạn code trong if:
- vì `""` không nằm trong a-z A-Z 0-9 , (comma) and - (minus)! => `session_id(self::$id);`: failed to set the session id for the current session
- `session_start()` lúc này tạo ra một session mới (đồng thời tạo ra một session file mới tương ứng)

sau đó set giá trị `$_SESSION[$key] = $value;` và `self::stop();`: end session hiện tại

> Suy ra nếu gửi request kèm một giá trị PHPSESSID không hợp lệ thì cứ mỗi lần gọi `Session::set();` đều sẽ tạo ra một session file mới.

-> Idea sẽ là 
1. Login với tài khoản `user:user` nhưng lại dùng một `PHPSESSID` invalid để tạo một session file có giá trị như sau

![session](https://user-images.githubusercontent.com/77546253/186453261-02f0e1a2-b6cb-4c05-b165-5648aa9f0be7.png)

2. Sau đó login lại với `PHPSESSID` như trên và `username=admin`:

![admin_login_success_local](https://user-images.githubusercontent.com/77546253/186453306-a6ce1198-e1c3-4397-b005-49d21459e7d6.png)

3. Và `?page=flag` ta có được flag:

![flag_local](https://user-images.githubusercontent.com/77546253/186453346-2edc5143-1d4c-4131-aaaf-119a60d32369.png)

Tuy nhiên, Khi làm remote thì ta sẽ không thể biết được giá trị `PHPSESSID` ở bước 1 (dĩ nhiên vì nó là random) nhưng tác giả đã cấu hình để cho mỗi lần gọi `session_start()` thì sẽ xuất hiện header `Set-Cookie` trong reponse.

![mul_set-cookie_header](https://user-images.githubusercontent.com/77546253/186453375-c536f126-dd37-4656-afb5-a3a814e1dbfd.png)

Thử với từng giá trị nhận được cho tới khi được flag

![flag_remote](https://user-images.githubusercontent.com/77546253/186453398-b17911d1-2dbd-4229-8ada-c8b4e3b1b5ad.PNG)

