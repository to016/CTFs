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

![reset_token](https://user-images.githubusercontent.com/77546253/172318707-b7fae873-c338-40cd-bc89-0cf6c3149489.png)


=> `234d8ccedc7bea108573a7e669dd111f`

[md5crack](https://hashes.com/en/decrypt/hash) 

![crack](https://user-images.githubusercontent.com/77546253/172318750-7ec7815d-2ffb-40b6-a225-56c35c50e76c.png)

-> `8e30b8`

Login với password là `8e30`

![payload](https://user-images.githubusercontent.com/77546253/172318662-109e34e7-8641-4e1c-a6b2-dea49464fd6d.png)


Kết quả

![flag](https://user-images.githubusercontent.com/77546253/172318599-4c0c0378-2d03-4414-bd86-1472336d742c.png)


