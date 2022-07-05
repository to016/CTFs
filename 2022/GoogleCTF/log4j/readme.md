# LOG4J

Sau khi đọc qua source code mình thấy ở hàm `chat()`

(set_system_property)

system property `cmd` được set dựa trên data nhận từ POST sau đó chạy file `app-1.0-SNAPSHOT.jar`, tiếp tục để ý ở file config của log4j `log4j2.xml`

(log4j_lookup)

-> ta có thể perform log4j lookup ở đây.


Lợi dụng việc `java:` prefix chỉ hỗ trợ một vài lookup:

(java prefix lookup)

-> `${java:${env:FLAG}}` throw ra lỗi và lấy được flag

(flag.png)

___

Sau khi end giải thì mình thấy có một câu hỏi trên server discord của googlectf khá hay mà mình cũng không rõ câu trả lời:

(question.png)

Loay hoay đi tìm hiểu một hồi thì tóm lại có thể hiểu như sau: khi java bị crash thì sẽ print stackstrace ra stdout và stdout này sẽ được trả về thông qua

(return_stdout.png)


vì vậy mà ta có thể xem được error của nó.

