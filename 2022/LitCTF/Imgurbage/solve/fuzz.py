import string    
import random 
from hashlib import md5
 
while True:
    ran = ''.join(random.choices(string.ascii_uppercase + string.digits + string.ascii_lowercase, k = 15))
    output = md5(ran.encode()).hexdigest()[0:6]
    if output == "decade":    
        print(f"FOUND: " + str(ran))
        break
    else:
        print(output, end = "\r")

# f0pu0bEicPFBhOE