import re


def check_email(email):
    regex = '[A-Za-z0-9._+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}'
    if re.match(regex, email) == None:
        print("Invalid Email")
        return False
    return True


print(check_email(r"a@gm.com</b></span><br/><iframe src='file://config.py' height='500' width='500'><span><b>"))