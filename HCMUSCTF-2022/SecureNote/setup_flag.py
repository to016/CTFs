import requests
import secrets
import time

URL = 'http://127.0.0.1:8000'
USERNAME = "admin"
PASSWORD = 'to^'
SECRETKEY = '1111111111111111aaaaaaaaaaaaaaaa'

print('[+] Working...')

time.sleep(10)

s = requests.Session()

r = s.post(URL + '/register', data={
    'username': USERNAME,
    'password': PASSWORD,
    'repassword': PASSWORD,
    'secretkey': SECRETKEY,
})

s.post(URL + '/login', data={
    'username': USERNAME,
    'password': PASSWORD,
})

s.post(URL + '/write_note', data={
    'title': 'Flag hereeeeee',
    'content': open('flag.txt', 'r').read(),
})

print('[+] Setup flag done!')