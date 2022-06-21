# Solution

vào docs trong php của hàm `stripcslashes` ta biết được rằng:

`Returns a string with backslashes stripped off. Recognizes C-like \n, \r ..., octal and hexadecimal representation.`

Tức là nếu biểu diễn một giá trị ở dạng hex thì `stripcslashes` sẽ không strip đi `\`

```php
php > var_dump(stripcslashes('H\x65llo') == 'Hello');
bool(true)
```

Tiếp theo ta chỉ cần encode payload ở dạng này và gửi lên server thôi.