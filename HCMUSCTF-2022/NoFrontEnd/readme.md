# Phân tích

Server hỗ trợ chức năng insert data và get data
1. insert data: 
Lưu vào database với `key` và `data` nhận từ user sau đó trả về cho user một `signature` tạo từ đoạn code

```go
byteData, err := json.Marshal(username+userData.Key)
```
`username` được lấy từ token, `userData.Key` được lấy từ POST request.


2. get data
Gửi cho server header `Signature` kèm với `key` server sẽ tạo ra HMAC tương ứng và nếu đúng sẽ trả về data cho user.

Server sẽ tạo trước 9 `admin_<i>` account với i từ 1 -> 9. Và mỗi account này đều chứa một `secretData` (flag) được lưu với key là `key`

Bên cạnh đó key đùng để tạo jwt được để sẵn ở source code: `my_secret_key`

# Solution

Tạo ra một jwt mới với `{"username": "admin_1"}` và sau đó gửi một POST request với key là `key` để tạo ra `signature` ứng với `admin_1key` - tương ứng với `signature` của `secretData` của `admin_1`

Sau đó dùng chính `signature` này để lấy flag.

Flag:
**HCMUS-CTF{Y_U_DoNt_H4ve_Fr0nt_3nddd????}**