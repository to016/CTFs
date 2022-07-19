from pwn import *


r = remote('pycrib.chal.imaginaryctf.org', 1337)

print(r.recvline(2))
r.sendlineafter("escape.\n".encode(), b"aaa")
print(r.recvline(2))
