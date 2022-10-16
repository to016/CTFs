from flask import Flask, request

internal = Flask(__name__)

@internal.route('/flag')
def flag():
    return 'Bsides{FAKE_FLAG}'

internal.run(host='0.0.0.0', port=31337)