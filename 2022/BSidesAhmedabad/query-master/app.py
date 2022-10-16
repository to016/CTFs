#!/usr/bin/python3
import random
import string
import subprocess
import sys
from flask import Flask, request

app = Flask(__name__)

def randName():
    return ''.join([random.choice(string.hexdigits) for i in range(16)])

@app.route('/')
def index():
    dbpath = f'/tmp/{randName()}.db'

    query = request.args.get("query")

    print(query, file=sys.stderr)

    if query:
        ban = ['.', 'lo', ';', 'read', 'im']

        for x in ban:
            if x in query:
                return "Filtered.."



        proc = subprocess.Popen(["sqlite3", dbpath, query], stdout=subprocess.PIPE)
        (out, err) = proc.communicate()

        if err:
           print(err, file=sys.stderr) 

        return out
    else:
        return 'no query'

app.run(host='0.0.0.0', port=9000)