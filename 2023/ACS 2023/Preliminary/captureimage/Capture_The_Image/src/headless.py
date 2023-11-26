from selenium.webdriver.common.keys import Keys
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from time import sleep
import sys
import json


with open('config.json', "r") as file:
    content = file.read()
    config = json.loads(content)



def visit_with_cookies(link_submitted):
    url = link_submitted.strip()
    print('visit_with_cookies called', flush=True)
    print(url, flush=True)

    options = Options()
    options.headless = True
    browser = webdriver.Firefox(executable_path = '/opt/CTF/geckodriver', service_log_path="/opt/CTF/geckodriver.log", options = options)
    try:
        browser.set_page_load_timeout(15)
        browser.get(config['host'])
        cookie = {'name' : 'secret', 'value': config['secret']}
        browser.add_cookie(cookie)
        browser.get(url)
        browser.quit()
    except Exception as e:
        print(e, flush=True)
        browser.quit()
        print('Came in the exception loop.', flush=True)

    print('visit_with_cookies function successfully executed', flush=True)


def visit_with_screencapture(link_submitted, secret, uid):
    url = link_submitted.strip()
    print('visit_with_screencapture called', flush=True)
    print(url, flush=True)

    if secret == config['secret']:
        options = Options()
        options.headless = True
        browser = webdriver.Firefox(executable_path = '/opt/CTF/geckodriver', service_log_path="/opt/CTF/geckodriver.log", options = options)
        try:
            browser.set_page_load_timeout(15)
            browser.get(config['host'])
            browser.get(url)
            sleep(1)
            filename = "captures/"+uid+".png"
            print(filename, flush=True)
            browser.get_screenshot_as_file(filename)
            browser.quit()
        except Exception as e:
            print(e, flush=True)
            browser.quit()
            print('Came in the exception loop.', flush=True)

    print('visit_with_screencapture function successfully executed', flush=True)