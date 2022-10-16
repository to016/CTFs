def encode_all(string):
    return "".join("%{0:0>2}".format(format(ord(char), "x")) for char in string)

st = 'flag:31337'

print(encode_all(st))