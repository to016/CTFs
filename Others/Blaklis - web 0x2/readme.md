# Solution

Author: [@Blaklis_](https://twitter.com/Blaklis_)

Challenge: <https://twitter.com/Blaklis_/status/1566870787612712960>

Link: <http://18.159.101.163/chall2/index.php?page=showMeTheCode>

Rule:

(rule)

## Phân tích

Về hình thức thì bài này na ná version 0x1 trước, có `?page=login` và `?page=flag`

Tóm tắt một tí về luồng hoạt động
- Ở page login, cứ mỗi lần đăng nhập (bằng cách gửi username, password thông qua GET request) thì sever sẽ check xem username đó đã bị ban hay chưa `isBanned`, nếu chưa bị ban thì tiếp tục check username, password so với dữ liệu đã lưu ở `/users2.txt` đúng -> login thành công, set `$_SESSION['username'] = $username;$_SESSION['authenticated'] = true;`. Mặc khác đăng nhập sai quá 3 lần thì sẽ bị ban cụ thể là username sẽ được thêm vào (`<banentry>'.$username.'</banentry>`) một file xml nằm ở `/tmp/sys/'<$_SERVER['REMOTE_ADDR']>`. 
- Ở page flag, chỉ cần thỏa `$_SESSION['username'] === 'admin' && $_SESSION['authenticated'] === true` để đọc được flag

Bug nằm ở 1 dòng code trong function `ban`

```php
function ban($username) {
    $banfile = new DOMDocument();
    // Validate the document
    # $banfile->validate();

    $banfileName = '/tmp/sys/'.$_SERVER['REMOTE_ADDR'];
    if(!file_exists($banfileName)) {
        $banfile->loadXML('<banlist></banlist>');
        $banfile->save($banfileName);    
    }
    $banfile->load($banfileName);
    // Avoid loading xincludes for security
    $banfile->xinclude(0);
    $banentry = $banfile->createDocumentFragment();
    $banentry->appendXML('<banentry>'.$username.'</banentry>');
    $banfile->documentElement->appendChild($banentry);
    $banfile->save($banfileName);
    return;
}
```

`$banfile->xinclude(0);` vẫn bị dính XInclude attack, mình test online tại [đây](https://onlinephp.io/) với 1 webhook để nhận request

(xinclude_test)

Vậy kịch bản khai thác sẽ là
- Tạo một username bất kì (đề phòng thôi) và làm cho username này bị ban (login 3 lần với pass bất kì)
- Tạo payload để XInclude file `/flag2.txt` và cũng login với username là payload này (để lưu payload vào file xml đã đề cập ở trên).
- Tiếp đó là dùng XPath injection khai thác theo hướng blind để đọc từng kí tự

Script solve xem tại `sovle.py`

> FLAG{W0w_4n0th3r_fl4g_th3r3!_Congr4tz}