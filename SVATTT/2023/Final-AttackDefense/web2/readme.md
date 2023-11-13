# Solution

Mục tiêu của bài này là rce thực thi `/readflag` ở `back` service, và bởi vì đây là một challenge được thiết kế với thể thức attack-defense nên sẽ có nhiều cách để giải.

Dưới đây  là cách làm cho từng service của mình.

## Bypass `front` filter

`back` và một vài path liên quan đến việc exploit backend service đã bị filter:

![image](https://github.com/to016/CTFs/assets/77546253/0e33ebd4-09ee-4743-b632-efaa8a3e5d71)

### Cách bypass thứ nhất

Để ý kĩ có thể thấy tác giả sử dụng options `-L` -> cho phép chuyển hướng, vì vậy có thể setup server trả về response header `Location: http://back/....`
=> Ưu điểm của cách này đó là các đội defense sẽ khó có thể bắt được payload lúc exploit backend (_khó_ chứ k hẵn là k được)

### Cách bypass thứ hai

Để ý version của docker image `python:3.9.2-alpine`, ở version này hàm `urlparse()` bị lỗi <https://bugs.python.org/issue36338>, đồng thời kết hợp với [url globbing](https://everything.curl.dev/cmdline/globbing) được hỗ trợ bởi curl
ta có thể dễ dàng bypass như sau `http://back:[80:80]/t{i}cket/<payload_for_exploit_backend_here>`
=> Nhược điểm của cách này là dễ bị bắt payload relay lại package

## Rce `back` server

### Bug 1

Java native deserizalition tại

![image](https://github.com/to016/CTFs/assets/77546253/86446828-0aa9-41e8-b659-4b8298657a93)

Bug này tương tự như bài waf-deser của bán kết svattt 2022, xem tại [đây](https://nguyendt.hashnode.dev/ascis-2022-qual-writeup-web-challenges)

Nhưng bởi vì payload bị giới hạn hơn so với năm ngoái, max 2048 bytes nên có thể sẽ cần custom lại chain cho ngắn hơn và bên cạnh đó service backend lại không có outbound (nằm trong network với thuộc tính internal là true)
-> native deser execute java code để thực thi `/readflag` sau đó lấy kết quả gửi ngược lại front end -> front end gửi ra webhook

![image](https://github.com/to016/CTFs/assets/77546253/8173f3ce-87a1-4a2a-81ea-b7390384007b)

### Bug 2

[SSTI thymeleaf](https://0xn3va.gitbook.io/cheat-sheets/framework/spring/view-manipulation#untrusted-thymeleaf-view-name) tại 

![image](https://github.com/to016/CTFs/assets/77546253/a3a6a751-c5ca-44a7-b93d-d7add0f94238)

Sau đó có thể thực thi java code để gửi flag ra ngoài như cách làm thứ nhất.
