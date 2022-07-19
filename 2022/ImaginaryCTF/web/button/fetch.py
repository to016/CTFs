import requests

URL = "http://button.chal.imaginaryctf.org/"

r = requests.get(URL)

with open("index.html", "w") as f:
    f.write(r.text)