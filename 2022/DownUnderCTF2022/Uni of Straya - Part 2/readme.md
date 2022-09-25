# Uni of Straya - Part 2

Sau một hồi fuzzing, mình nhận ra ta có thể thêm một assignment với submission type là zip, type này hỗ trợ upload các file ở dạng `.zip`, `.tar`, `.tar.gz` -> có thể bỏ một symlink đến `/proc/self/cwd/flag.txt` vào một tar file và upload lên server sau đó có thể thành công đọc flag.