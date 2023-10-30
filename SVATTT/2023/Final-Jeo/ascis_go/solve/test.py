import requests


r = requests.get("https://csgo.teklab.one/")
print(r.text)