# Uni of Straya - Part 1

Sau khi register, và login ta nhận thấy header `Authorization` được set, bỏ giá trị lên `jwt.io` có một trường là `iss` trỏ tới `/api/auth/pub-key` (vị trí của public key của author). Vậy có cách nào để server dùng public của ta verify jwt hay không ?, ở file `admin.js` sẽ có một api bị lỗi open redirect đó là `/api/auth/logout?redirect=`

Vậy kịch bản khai thác sẽ là
- Gen ra một cặp public - prive rsa key, sau đó host http server

`ssh-keygen -t rsa -b 4096 -m PEM -f jwtRS256.key`

`openssl rsa -in jwtRS256.key -pubout -outform PEM -out jwtRS256.key.pub`

- Bỏ jwt ban đầu lên `jwt.io`, sau đó dùng cặp key này để chỉnh `id` trong payload thành `1` và  `iss` ở header thành `api/auth/pub-key/../logout?redirect=<http_server>/jwtRS256.key.pub`

Sau khi forge được jwt của admin `id=1` access tới `/api/auth/isstaff` ta có được flag