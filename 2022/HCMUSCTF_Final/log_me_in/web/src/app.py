from typing import Hashable
from flask import Flask, request, redirect, session
from flask_mysqldb import MySQL
import os 
import uuid
import hashlib
import sys

app = Flask(__name__)
app.secret_key = os.getenv("SECRET_KEY")
app.config['MYSQL_HOST'] = 'db'
app.config['MYSQL_USER'] = 'db_user'
app.config['MYSQL_PASSWORD'] = 'db_password'
app.config['MYSQL_DB'] = 'myDB'
mysql = MySQL(app)

@app.before_request
def require_login():
	if request.path == "/login" or request.path == "/register" or request.path == "/forgot":
		return None
	if session.get("username") is None:
		return redirect("/login")

@app.route('/')
def index():
    if session['is_admin'] == 1:
        return os.getenv("FLAG")
    else:
        return "Hello guest"

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'GET':
        return "<html><body><img src='https://i.kym-cdn.com/photos/images/newsfeed/001/461/623/b21.png'></body></html>"
    elif request.method == 'POST':
        data = request.json
        if "username" in data.keys() and "password" in data.keys():
            username = data['username']
            password = data['password']
            cursor = mysql.connection.cursor()
            cursor.execute("select is_admin from users where usrname=%s and pssword=%s", (username, password))
            result = cursor.fetchall()
            if len(result) == 0:
                return "Wrong username or password"
            else:
                session['username'] = username
                session['is_admin'] = result[0][0]
                return redirect('/')
        return "Missing username or password"
    
@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'GET':
        return "<html><body><img src='https://www.memecreator.org/static/images/memes/5230996.jpg'></body></html>"
    elif request.method == 'POST':
        data = request.json
        if "username" in data.keys() and "password" in data.keys():
            username = data['username']
            password = data['password']
            cursor = mysql.connection.cursor()
            cursor.execute("select * from users where usrname=%s", [username])
            if len(cursor.fetchall()) > 0:
                return "Username already exists"
            else:
                cursor.execute("insert into users values (%s, md5(%s), %s)", (username, password, False))
                mysql.connection.commit()
                return "Registered successfully"
        return "Missing username or password"


@app.route('/forgot', methods=['GET', 'POST'])
def forgot():
    if request.method == 'GET':
        return "<html><body><img src='http://www.quickmeme.com/img/c8/c8babb6bed9f0a0d72c154c66a36f2ed04ce9b20fb8e9254fb0bcf3f4ca4a1a3.jpg'></body></html>"
    elif request.method == 'POST':
        data = request.json
        if "step" in data.keys():
            step = data["step"]
            if step == 1:
                if "username" in data.keys():
                    username = data["username"]
                    cursor = mysql.connection.cursor()
                    cursor.execute("select * from users where usrname=%s", [username])
                    result = cursor.fetchall()
                    if len(result) == 0:
                        return "Username does not exists"
                    else:
                        reset_id = uuid.uuid4()
                        print(result[0][1][0:6].encode(), file=sys.stderr)
                        reset_token = hashlib.md5(result[0][1][0:6].encode()).hexdigest()
                        cursor.execute("insert into reset_password values (%s, %s)", (reset_id, reset_token))
                        # TODO: implement send email function here
                        # return send_mail("admin@ctf.hcmus.edu.vn", reset_id, reset_token)
                        return f"{reset_id}|{reset_token}"
                return "Missing username"
            elif step == 2:
                if "reset_id" in data.keys() and "reset_token" in data.keys():
                    reset_id = data["reset_id"]
                    reset_token = data["reset_token"]
                    cursor = mysql.connection.cursor()
                    cursor.execute("select reset_token from reset_password where reset_id=%s", [reset_id])
                    result = cursor.fetchall()
                    if len(result) == 0:
                        return "Id not found"
                    else:
                        real_token = result[0][0]
                        if reset_token == real_token:
                            # TODO: implement reset password here
                            pass
                        return "Ok"
                return "Missing id or token"
        return "Missing step"

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8000)