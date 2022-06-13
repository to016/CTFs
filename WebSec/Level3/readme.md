Lỗi typo `fa1se` khiến cho return value của `sha1()` sẽ ở dạng raw byte.
 
Một lỗi liên quan đến `password_verify()` khi dính đến null byte ->
[Xem tại đây](https://blog.ircmaxell.com/2015/03/security-issue-combining-bcrypt-with.html#Detecting-Problematic-Hashes)


Hash của flag là `7c00249d409a91ab84e3f421c193520d9fb3674b`

Sau khi unhex ta được:
```
>>> import binascii
>>> binascii.unhexlify("7c00249d409a91ab84e3f421c193520d9fb3674b");
b'|\x00$\x9d@\x9a\x91\xab\x84\xe3\xf4!\xc1\x93R\r\x9f\xb3gK'
```

Ở vị trí thứ hai giá trị hash này chứa null byte `\x00`.
=> Cần tìm `c` sao cho `sha1($c)` bắt đầu với "7c00".