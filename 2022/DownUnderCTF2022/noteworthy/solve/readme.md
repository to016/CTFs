# Noteworthy

Để ý ngay tại route `/edit`, `Note.findOne(q)` - `q` ở đây là req.query nhận từ người dùng vì vậy ta có thể dễ dàng khai thác nosqli. 

`?noteId=1337&contents[$regex]=^D`

Nếu câu query trả về kết quả, thì sẽ in ra dòng chữ `You are not the owner of this note!` ngược lại in ra `Note does not exist!`

Script: `solve.py`