from flask import Blueprint, render_template, session, request, redirect, url_for, flash, send_from_directory
import requests
from urllib.parse import urlparse
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
import threading
from uuid import uuid4
from headless import visit_with_cookies, visit_with_screencapture
import json

with open('config.json', "r") as file:
    content = file.read()
    config = json.loads(content)
    


links = Blueprint("links", __name__, template_folder = "static/templates")


@links.route('/')
def redirect_():
    return redirect("/submit")

@links.route('/submit', methods = ["GET", "POST"])
def submit():
    message = ""

    if request.method == "POST":

        link_submitted = request.form.get('link')
        if link_submitted == None:
            return render_template('submit.html', message = "Link is required.", config = config)
        block_schemes = ["file", "gopher", "blob", "ftp", "glob", "data"]
        block_host = ["localhost"]
        input_scheme = urlparse(link_submitted).scheme
        input_hostname = urlparse(link_submitted).hostname
        print(input_scheme, flush=True)
        print(input_hostname, flush=True)
        if '://' not in link_submitted or input_scheme in block_schemes or input_hostname in block_host:
            return render_template('submit.html', message = "Link is not correct.", config = config)

        message = "Will visit your site in a bit!"

        #headless browser code
        try:
            if request.form.get('archive') == 'Y':
                uid = str(uuid4())
                message = message + "\nUID : " + uid
                t1 = threading.Thread(target = visit_with_screencapture, args = (link_submitted,request.form['secret'],uid,))
                t1.start()
            else:
                t1 = threading.Thread(target = visit_with_cookies, args = (link_submitted,))
                t1.start()

        except Exception as e:
            print(e, flush=True)
            return render_template('submit.html', message = "An error happened. Please try again.", config = config)

        return render_template("submit.html", message = message, config = config)
    
    elif request.method == "GET":
        return render_template("submit.html", config = config)

@links.route('/api/test')
def test():
    if "key" in dict(request.args):
        key = request.args.get('key')
        if key == None or key.strip() == '':
            key = 'Nothing'
        key = key.strip().lower()
        key = key.replace('script','--').replace('onerror','--').replace('frame','--')
        return key + " better~"
    
    else:
        return "Use the parameter 'key' to make it reflect."

@links.route('/captures', methods = ["POST"])
def captures():
    filename = request.form.get('filename')
    if filename:
        return send_from_directory('captures', filename)
    return render_template('notfound.html', some_value = "File not found.")

@links.route('/<some_value>')
def not_found(some_value):
    return render_template("notfound.html", some_value = some_value)
