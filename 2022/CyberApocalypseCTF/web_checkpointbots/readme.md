# Solution

Bài này là dạng lỗi log4j trong java, có thể thấy ở 2 endpoint `check-in` và `sheet` đều dùng `log.error` để log ra lỗi nếu token từ request param là không hợp lệ. Vì vậy ta có thể trigger log4j ở endpoint nào cũng được.

```java
@GetMapping(value="/api/checkpointbot/check-in", produces="application/json")
public ResponseEntity<String> handlerCheckIn(@RequestParam("token") String token) {

    Map<String, String> json = new HashMap<String, String>();
    CheckpointBot bot;

    try{
        UUID.fromString(token);
        bot = cRepo.findByToken(token).get(0);
    } catch (IllegalArgumentException exception){
        log.error("Invalid token supplied: " + token);
        json.put("message", "Invalid token supplied");
        return new ResponseEntity<String>(gson.toJson(json),HttpStatus.UNAUTHORIZED);
    }
...
```


```java
@GetMapping("/api/checkpointbot/sheet")
public ResponseEntity<?> download(@RequestParam("token") String token) throws Exception {

    Map<String, String> json = new HashMap<String, String>();
    CheckpointBot bot;
    CheckInUtility checkInUtility;

    try{
        UUID.fromString(token);
    } catch (IllegalArgumentException exception){
        log.error("Invalid token supplied: " + token);
        json.put("message", "Invalid token supplied");
        return new ResponseEntity<String>(gson.toJson(json),HttpStatus.UNAUTHORIZED);
    }
    ...
```

Nhưng nếu làm theo cách thông thường: tạo ldap server với class để exploit (ví dụ là Exp)sau đó trigger một jndi lookup tới `/Exp` thì sẽ failed. Lí do là vì:

![reason](https://user-images.githubusercontent.com/77546253/174995110-9f3c269e-5edb-4776-b6f7-775f73913fd7.png)


Sau khi build docker xong ta thấy version của jdk là `11.0.15`

```
$ java -version
openjdk version "11.0.15" 2022-04-19
OpenJDK Runtime Environment 18.9 (build 11.0.15+10)
OpenJDK 64-Bit Server VM 18.9 (build 11.0.15+10, mixed mode, sharing)
```

=> Chỉ còn cách tạo một ldap server transfer serialized payload.

Tiếp theo ta cần mix thêm một chút java deserialization, bởi vì bản chất của cách này là log4j sẽ deserialize nếu data nhận được từ ldap server ở dạng serialized.

Sau một hồi tìm trong source code thì mình nhận thấy ở `utils/CheckInUtility.java` có đoạn code để tự định nghĩa method `readObject` của class `CheckInUtility`


```java
    private void readObject(ObjectInputStream inputStream) throws Exception
    {
        inputStream.defaultReadObject();

        CSVWriter writer = new CSVWriter(new FileWriter(this.sheetFile, true), '|', '\0','\0',"\n");

        LocalDateTime date = LocalDateTime.now();
        DateTimeFormatter format = DateTimeFormatter.ofPattern("dd-MM-yyyy HH:mm:ss");
        this.check_in = date.format(format);

        writer.writeRow(new String[] {this.token, this.check_in});
        writer.close();

        this.fileData = readFile(this.sheetFile);
    }
```

=> ta có thể chỉnh sửa một tí để overwrite file `index.html` và đọc flag bằng path traversal + template injection.

Cụ thể hơn thì ở thư mục `solve` mình có chỉnh lại code của `CheckInUtility` đồng thời có code để tạo một ldap server

Host ldapserver:
![host_ldap](https://user-images.githubusercontent.com/77546253/174995162-4e14ce0f-e5f1-4a3a-b61d-af1cc060acee.png)


Gửi payload:

![send_payload](https://user-images.githubusercontent.com/77546253/174995666-220ad48d-aa10-46dc-8ee6-273e7b1039ea.png)

Overwrite `index.html` thành công:

![final](https://user-images.githubusercontent.com/77546253/174995239-a1b538b9-87c9-4e11-a930-9f0ca5f5c24d.png)


