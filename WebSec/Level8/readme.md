# Solution

- Chức năng upload file và include file vừa upload: `include_once($uploadedFile);`
- Server giới hạn file upload chỉ là file gif

Nhưng có một điều đặc biệt ở gif chính là magic byte của nó: `GIF89a` và khi check thì nếu các byte đầu thỏa mãn magic byte này thì file đó chính là gif.

Lợi dụng điều đó ta tạo một file với các byte đầu là `GIF89a` + php shellcode.


**WEBSEC{Try_it_yourself_dont_copy_and_paste}**

