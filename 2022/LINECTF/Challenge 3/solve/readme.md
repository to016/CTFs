# Solution

Bài này có 2 flag
- Flag1 nằm ở service `public`
- Flag2 nằm ở service `auth`

Vì source đã cho nên mình sẽ không đi giải thích flow của chương trình nữa mà thay vào đó là thẳng vào solution

---

## Flag1

Ngay tại route `/login` nếu để ý thì ta sẽ thấy có thể khai thác nosqli từ biến `login_cred`, gửi payload như sau là có thể login thành công với account admin

![login_admin](https://user-images.githubusercontent.com/77546253/192567481-9922d1ff-8c36-4680-a631-de6679bc6bcf.png)

Nhưng sau khi login thành công với admin và access tới `/admin` thì lại yêu cầu nhập password, vì vậy mình quay lại đoạn nosqli khi nãy và bắt đầu brute password của admin

```python
import requests
import string


url = "http://127.0.0.1:5000"

# 87f36cd136b3000e5eab7a5fcf8fe190bd538d257f48e0952fac53f245f2cfb4
password = ""

while len(password) < 64:
    for c in string.digits + string.ascii_letters:
        r = requests.post(url + '/login', json={'username': 'admin', 'password': {'$regex': f'^{password + c}'}})
        if 'Login Success.' in r.text:
            password += c
            print(password)
            break
```

Sau khi có được password thì ta có thể sử dụng chức năng html to pdf

![html_to_pdf](https://user-images.githubusercontent.com/77546253/192567523-0affafe9-2dee-4ecd-bc1c-4aae1708e4ef.png)

Tới đây vì flag nằm ở `/tmp/flag.txt` nên mình nghĩ đến dạng server-side xss để include file này, chú ý một điều đó là server dùng module `HTML` của lib `weasyprint` -> include file thông qua tag link (xem ở index.html)

![pdf_generated](https://user-images.githubusercontent.com/77546253/192567574-6cd07b5b-038c-4c90-abab-14ba4cffd9cb.png)

Tải file pdf này về và mở với pdf reader (mình dùng foxit reader), sau đó xem phần attachment

![attachment_view](https://user-images.githubusercontent.com/77546253/192567629-afeb0f6c-8183-4018-b8f8-29efe1cba94c.png)

và flag:

![flag1](https://user-images.githubusercontent.com/77546253/192567652-0eb9c394-b3b6-4090-aed9-e4bd294a972e.png)

---

## Flag2

Phần này ta sẽ khai thác ssrf cũng ở chỗ `HTML(data["url"])`, liếc sơ qua service `auth` dễ thấy vuln là protoype pollution (hàm `merge`).

Idea sẽ là
- Prototype pollution ở `/logs`
- Ssrf tới `/auth` với các giá trị đã pollute để pass các đoạn check và lấy flag

Mình thực hiện prototype để "tạo" thêm một account mới ở object `creds` trong `config.js` với `uid=bla` và `upw=pw` và `is_admin=1`

![prototype](https://user-images.githubusercontent.com/77546253/192567687-869c1df9-c29b-4acc-9746-68385e71a576.png)

Sau đó ssrf tới `/auth` để lấy flag

![auth_png](https://user-images.githubusercontent.com/77546253/192567733-6ff8a04a-c800-45a3-8d54-b7dd5d963f7f.PNG)

Kết quả:

![flag2](https://user-images.githubusercontent.com/77546253/192567774-30772057-1ed0-4938-9856-9d99c715378c.png)
