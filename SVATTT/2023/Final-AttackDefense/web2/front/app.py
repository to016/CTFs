import re
from urllib.parse import urlparse, urlunparse
from flask import Flask, render_template, request, abort, Response, redirect, jsonify
import logging
import subprocess


app = Flask(__name__)
logging.basicConfig(level=logging.INFO)
FILTERED_HOSTS = ["back"]

FILTERED_PATHS = ["debug", "info", "ticket"]


@app.route("/<path:url>", methods=["GET"])
def proxy(url):
    r = make_request(
        str(url), request.method, dict(request.headers), request.get_data()
    )
    return r


def make_request(url, method, headers={}, data=None):
    if not is_approved(url):
        abort(403)

    curl_command = ["curl", "-L", "-X", method, url]

    for key, value in headers.items():
        if key.lower() == "host":
            continue
        curl_command.extend(["-H", f"{key}: {value}"])

    if data:
        curl_command.extend(["--data", data.decode()])

    try:
        print(curl_command)
        result = subprocess.run(
            curl_command, shell=False, timeout=10, capture_output=True
        )
        return result.stdout, 200
    except Exception as e:
        print(e)
        return jsonify({"error": f"Error executing curl"}), 500


def is_approved(url):
    """Indicates whether the given URL is allowed to be fetched.  This
    prevents the server from becoming an open proxy"""
    parts = urlparse(url)
    host = parts.hostname
    path = parts.path

    if not parts.scheme in ["http", "https"]:
        return False

    if host in FILTERED_HOSTS:
        return False
    for filter_path in FILTERED_PATHS:
        if filter_path in path:
            return False
    return True


# if __name__ == "__main__":
#     app.run(debug=True, host='0.0.0.0', port=8000)
