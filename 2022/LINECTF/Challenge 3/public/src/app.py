import hashlib
import logging
import os
from urllib.parse import urlparse
import json
import requests

from flask import (
    Flask,
    render_template,
    render_template_string,
    request,
    jsonify,
    session,
    redirect,
    abort
)
from pymongo import MongoClient
from weasyprint import HTML


def send_log(data):
    params = {"logs": json.dumps(data)} 
    r = requests.get(f"http://{os.getenv('LOG_SERVER_HOST', '127.0.0.1:8000')}/logs", params=params)
    return r.json()["result"]


def auth_check(uid, upw):
    params = {
        "uid": uid,
        "upw": upw
    }
    r = requests.get(f"http://{os.getenv('LOG_SERVER_HOST', '127.0.0.1:8000')}/auth", params=params)
    return r.json()


def make_random_hex_value():
    return hashlib.sha256(os.urandom(32)).hexdigest()


log = logging.getLogger(__name__)

logging.info("")
app = Flask(__name__, static_url_path="/static/")
app.secret_key = os.urandom(32)

client = MongoClient("mongodb://db:27017")
db = client["test"]
col = db["users"]
col.delete_many({})
admin_cred = {"username": "admin", "password": make_random_hex_value()}
col.insert_one(admin_cred)


@app.route("/", methods=["GET"])
def index_page():
    username = ""
    if "username" in session.keys():
        username = session["username"]

    return render_template("index.html", username=username)


@app.route("/login", methods=["GET", "POST"])
def login_page():
    if request.method == "GET":
        username = ""
        if "username" in session.keys():
            username = session["username"]
        return render_template("login.html", username=username)
    else:
        data = request.json
        if "username" in data.keys() and "password" in data.keys():
            login_cred = {"username": data["username"], "password": data["password"]}
            find_cred = dict()

            for i in col.find(login_cred):
                find_cred = i
                break

            if "username" in find_cred.keys():
                session["username"] = find_cred["username"]
                session["is_admin"] = False
                result = {"status": 200, "msg": "Login Success.", "return": "/"}

            else:
                result = {
                    "status": 403,
                    "msg": "Please check username or password.",
                    "return": "/login",
                }

        else:
            result = {
                "status": 403,
                "msg": "Missing username or password.",
                "return": "/login",
            }

        return jsonify(result)


@app.route("/admin", methods=["GET", "POST"])
def admin_page():
    if "is_admin" not in session.keys() or "username" not in session.keys():
        return redirect("/login")

    if session["username"] != "admin":
        return "It can use only admin, Sorry :)"

    if session["is_admin"] == False:
        if request.method == "GET":
            username = ""
            if "username" in session.keys():
                username = session["username"]
            return render_template("admin_login.html", username=username)
        else:
            data = request.json
            if "password" not in data.keys():
                result = {"status": 403, "msg": "Missing password.", "return": "/admin"}
                return jsonify(result)
            else:
                for i in col.find({"username": {"$eq": session["username"]}}):
                    admin_password = i["password"]
                    break

                if data["password"] == admin_password:
                    session["is_admin"] = True
                    result = {"status": 200, "msg": "Verfied.", "return": "/admin"}
                else:
                    result = {
                        "status": 403,
                        "msg": "Incorrect password.",
                        "return": "/admin",
                    }

            return jsonify(result)

    else:
        if request.method == "GET":
            username = ""
            if "username" in session.keys():
                username = session["username"]
            return render_template("admin.html", username=username)
        else:
            data = request.json
            if "url" in data.keys():
                if urlparse(data["url"]).scheme.lower() not in ["http", "https"]:
                    result = {"status": 403, "msg": "You can only use http or https."}
                else:
                    filename = make_random_hex_value()
                    HTML(data["url"]).write_pdf(f"static/output/{filename}.pdf")
                    data = {
                        "filename": filename,
                        "ip": request.remote_addr
                    }
                    send_log(data)
                    result = {
                        "status": 200,
                        "output": f'<a href="/static/output/{filename}.pdf">static/output/{filename}.pdf</a>',
                    }
            else:
                result = {"status": 403}
            return jsonify(result)


@app.route("/admin/_/auth", methods=["POST"])
def intra_auth():
    data = request.json
    if "uid" in data.keys() and "upw" in data.keys():
        res = auth_check(data["uid"], data["upw"])
        return jsonify(res)
    else:
        return abort(403)



@app.errorhandler(404)
def not_found(e):
    return f"404 Not Found: {request.path}"


if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=False)
