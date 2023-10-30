import os
import json
import hashlib
import sys
import hmac
import base64
import string
import requests
from Crypto.Cipher import AES


#https://gist.github.com/bluetechy/5580fab27510906711a2775f3c4f5ce3

def mcrypt_decrypt(value, iv):
    global key
    AES.key_size = [len(key)]
    crypt_object = AES.new(key=key, mode=AES.MODE_CBC, IV=iv)
    return crypt_object.decrypt(value)


def mcrypt_encrypt(value, iv):
    global key
    AES.key_size = [len(key)]
    crypt_object = AES.new(key=key, mode=AES.MODE_CBC, IV=iv)
    return crypt_object.encrypt(value)


def decrypt(bstring):
    global key
    dic = json.loads(base64.b64decode(bstring).decode())
    mac = dic['mac']
    value = bytes(dic['value'], 'utf-8')
    iv = bytes(dic['iv'], 'utf-8')
    if mac == hmac.new(key, iv+value, hashlib.sha256).hexdigest():
        return mcrypt_decrypt(base64.b64decode(value), base64.b64decode(iv))
        #return loads(mcrypt_decrypt(base64.b64decode(value), base64.b64decode(iv))).decode()
    return ''


def encrypt(string):
    global key
    iv = os.urandom(16)
    #string = dumps(string)
    padding = 16 - len(string) % 16
    string += bytes(chr(padding) * padding, 'utf-8')
    value = base64.b64encode(mcrypt_encrypt(string, iv))
    iv = base64.b64encode(iv)
    mac = hmac.new(key, iv+value, hashlib.sha256).hexdigest()
    dic = {'iv': iv.decode(), 'value': value.decode(), 'mac': mac}
    return base64.b64encode(bytes(json.dumps(dic), 'utf-8'))

app_key ='VPZ22MXsMxVEynGwKgwNb80xnuWqNejGvmgYsCpyGPs='
key = base64.b64decode(app_key)
print(decrypt('eyJpdiI6InhwT0Z5YmljZUJaUlJRMk40Mm1Wd2c9PSIsInZhbHVlIjoic1cvSkNhVHlSZkJUMHlRbCs3dXdWUU5JZkJYRm96YWJra1lFTzdsakcySThoT3Y4VG5SU3NVVWtHMksza3Y4YkVZa2N3eXRONU1QWnRJSytYYitzR1gwWGRSTitmM2JIczVZRFJOQXFPc1FFVkIvdnh0N2VVanlHdUVUaHc0V2UiLCJtYWMiOiJiMDYxODNlY2I5ZTdhNGNkMzU0YzAwZTBkNWY0ZGIxYjU3NmYyZDNkZTlmMDg0OTg1MDUwM2RjMmExMTZiN2U2IiwidGFnIjoiIn0='))
#b'{"data":"a:6:{s:6:\\"_token\\";s:40:\\"vYzY0IdalD2ZC7v9yopWlnnYnCB2NkCXPbzfQ3MV\\";s:8:\\"username\\";s:8:\\"guestc32\\";s:5:\\"order\\";s:2:\\"id\\";s:9:\\"direction\\";s:4:\\"desc\\";s:6:\\"_flash\\";a:2:{s:3:\\"old\\";a:0:{}s:3:\\"new\\";a:0:{}}s:9:\\"_previous\\";a:1:{s:3:\\"url\\";s:38:\\"http:\\/\\/206.189.25.23:31031\\/api\\/configs\\";}}","expires":1605140631}\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e\x0e'
#encrypt(b'{"data":"a:6:{s:6:\\"_token\\";s:40:\\"RYB6adMfWWTSNXaDfEw74ADcfMGIFC2SwepVOiUw\\";s:8:\\"username\\";s:8:\\"guest60e\\";s:5:\\"order\\";s:8:\\"lolololo\\";s:9:\\"direction\\";s:4:\\"desc\\";s:6:\\"_flash\\";a:2:{s:3:\\"old\\";a:0:{}s:3:\\"new\\";a:0:{}}s:9:\\"_previous\\";a:1:{s:3:\\"url\\";s:38:\\"http:\\/\\/206.189.25.23:31031\\/api\\/configs\\";}}","expires":1605141157}')