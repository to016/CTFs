# Solution

idea bài này khác bài trước một tí, ta sẽ không dùng `fast destruct` nữa mà thay vào đó tìm một buil-in class để tạo một exception và không cho dòng code `ob_end_clean();` được thực thi.

payload: `O:9:"Throwable":0:{}`