# Solution

Luồng hoạt động: sau khi ta nhập customer details và ấn Checkout, server sẽ gọi đến hàm `renderPdf()` trong `pdf.js` để render ra invoice ở dạng file pdf chứa thông tin vừa nhập vào. 

Tuy nhiên ở route `GET /renderInvoice` đã được cấu hình CSP:

```js
app.get('/renderInvoice', async (req, res) => {
  if (!invoice) {
    invoice = await readFile('templates/invoice.html', 'utf8')
  }

  let html = invoice
  .replaceAll("{{ name }}", req.query.name)
  .replaceAll("{{ address }}", req.query.address)
  .replaceAll("{{ phone }}", req.query.phone)
  .replaceAll("{{ email }}", req.query.email)
  .replaceAll("{{ discount }}", req.query.discount)
  res.setHeader("Content-Type", "text/html")
  res.setHeader("Content-Security-Policy", "default-src 'unsafe-inline' maxcdn.bootstrapcdn.com; object-src 'none'; script-src 'none'; img-src 'self' dummyimage.com;")
  res.send(html)
})
```

Đồng thời ở `GET /orders`, cần hai điều kiện để lấy được flag đó là phải access từ localhost và đồng thời trong cookie không chứa cookie name là `bot`.


```js
app.get('/orders', (req, res) => {
  if (req.socket.remoteAddress != "::ffff:127.0.0.1") {
    return res.send("Nice try")
  }
  if (req.cookies['bot']) {
    return res.send("Nice try")
  }
  res.setHeader('X-Frame-Options', 'none');
  res.send(process.env.FLAG || 'kalmar{test_flag}')
})
```

Ý tưởng khai thác sẽ là inject meta tag vào trường name trong thông tin customer gửi lên server, bởi vì trường này ngay trong html head tag

```html
<head>
    <title>Invoice for {{ name }}</title>
    ...
```

và redirect đến `http://127.0.0.1:5000/orders` là xong, bởi vì nếu để ý kĩ thì ta thấy cookie được set cho path `localhost:5000` và SameSite level là Strict vì vậy đối với hostname `127.0.0.1` sẽ được tính là  không phải same site -> k gửi kèm theo cookie có name `bot`.

payload:

(payload.png)