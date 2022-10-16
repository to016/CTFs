from flask import Flask, request
from urllib.parse import urlparse
import requests
import sys

app = Flask("API")

@app.route('/')
def index():
    try:
        url = urlparse(request.args.get("url"))
        conn = requests.get('http://'+url.hostname+'/'+url.path, allow_redirects=False)
        return conn.text
    except:
        return ':thinking:'

app.run(host='0.0.0.0', port=9090)