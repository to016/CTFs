# Issues

Bài này thì na ná bài jwt ở giải ductf, ngay tại `api.py` cơ chế hoạt động sẽ như sau, đầu tiên sẽ yêu cầu ta cung cấp một token ở header `Authorization` dạng `Authorization: Bearer <token>`.

```python
def authorize_request(token):
    pubkey_url = get_public_key_url(token)
    if has_valid_alg(token) is False:
        raise Exception("Invalid algorithm. Only {valid_algo} allowed.".format(valid_algo=valid_algo))

    pubkey = get_public_key(pubkey_url)
    pubkey = "-----BEGIN PUBLIC KEY-----\n{pubkey}\n-----END PUBLIC KEY-----".format(pubkey=pubkey).encode()
    decoded_token = jwt.decode(token, pubkey, algorithms=["RS256"])
    if "user" not in decoded_token:
        raise Exception("user claim missing")
    if decoded_token["user"] == "admin":
        return True

    return False
```

Token sẽ được đưa qua hàm `authorize_request`
- `get_public_key_url()`

```python
def get_public_key_url(token):
    is_valid_issuer = lambda issuer: urlparse(issuer).netloc == valid_issuer_domain

    header = jwt.get_unverified_header(token)
    if "issuer" not in header:
        raise Exception("issuer not found in JWT header")
    token_issuer = header["issuer"]

    if not is_valid_issuer(token_issuer):
        raise Exception(
            "Invalid issuer netloc: {issuer}. Should be: {valid_issuer}".format(
                issuer=urlparse(token_issuer).netloc, valid_issuer=valid_issuer_domain
            )
        )
    pubkey_url = "{host}/.well-known/jwks.json".format(host=token_issuer)
    return pubkey_url
```
hàm này lấy url từ trường `issuer` trong token header và check điều kiện `urlparse(issuer).netloc == valid_issuer_domain` (= `localhost:8080`), nếu thỏa thì trả về giá trị `{host}/.well-known/jwks.json` với `host` là giá trị của trường `issuer`.
- `has_valid_alg()`: check jwt token xem có đúng là sử dụng thuật toán RS256 hay không ? 
- `get_public_key()`: parse file `jwks.json` được trỏ tới bởi `pubkey_url` và lấy về public key
- Cuối cùng là jwt decode và check nếu `decoded_token["user"] == "admin"` thì access tới `/flag` là có được flag


Về vuln của bài này, ta đã biết jwt dùng public key để verify, vậy nếu ta tạo ra một cặp public-private key để sign token và làm cho server sử dụng public key của ta để verify thì chuyện gì xảy ra ? => Thành công bypass được tính năng xác thực

Để server sử dụng public key của ta thì phải làm sao cho trường `issuer` trỏ đến file `<VPS>/.well_known/jwks.json` (VPS là server của attacker) nhưng vẫn thỏa điều kiện `urlparse(issuer).netloc == valid_issuer_domain`. Lúc đầu mình nghĩ đến cách lợi dụng url parser nhưng sau đó liếc thấy một route bị lỗi open redirect

```python
@app.route("/logout")
def logout():
    session.clear()
    redirect_uri = request.args.get('redirect', url_for('home'))
    return redirect(redirect_uri)
```

=> `issuer: http://localhost:8000/logout?redirect=<VPS>/.well_known/jwks.json`


Forge jwt:

(jwt_sign.png)

và flag:

(flag.png)