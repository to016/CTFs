# LOG4J

Sau khi đọc qua source code mình thấy ở hàm `chat()`

![set_system_property](https://user-images.githubusercontent.com/77546253/177235296-e8867047-073e-431f-98cb-4f76baf85949.png)

system property `cmd` được set dựa trên `text` từ POST request sau đó chạy file `app-1.0-SNAPSHOT.jar`, tiếp tục để ý ở file config của log4j `log4j2.xml`

![log4j_lookup](https://user-images.githubusercontent.com/77546253/177235305-2fb7fd0a-ab9b-4cc3-b9c7-2517978e862b.png)

-> ta có thể perform log4j lookup ở đây.

Tiếp theo lợi dụng việc `java:` prefix chỉ hỗ trợ một vài lookup:

![java_prefix_lookup](https://user-images.githubusercontent.com/77546253/177235324-06e6db96-5c9f-44fd-b851-fdaf5b06a1b2.png)

-> `${java:${env:FLAG}}` throw ra lỗi và lấy được flag

![flag](https://user-images.githubusercontent.com/77546253/177235358-b933b1c8-ba74-4c37-a42c-b070e85476c5.png)

___

Sau khi end giải thì mình thấy có một câu hỏi trên server discord của googlectf khá hay mà mình cũng không rõ câu trả lời:

![question](https://user-images.githubusercontent.com/77546253/177235467-c168a7f7-0e73-4192-83fe-20f2908120a8.png)

Loay hoay đi tìm hiểu một hồi thì tóm lại có thể hiểu như sau: khi java bị crash thì sẽ print stackstrace ra stdout và stdout này sẽ được trả về thông qua:

![return_stdout](https://user-images.githubusercontent.com/77546253/177235493-82ce26dd-540a-4bc0-9346-b14a6dd041cc.png)

vì vậy mà ta có thể xem được error của nó.

