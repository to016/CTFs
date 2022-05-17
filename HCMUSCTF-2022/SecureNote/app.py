from flask import Flask, request, session, render_template, url_for, redirect, flash
from flask_mysqldb import MySQL
# from utils.crypto import AESCipher
# from utils.security import waf_filter
import hashlib
import time 
import secrets
import re
import os

app = Flask(__name__)
app.secret_key = "oHhh_n0000OooooO___YoU_shOUldnt_kn0vv_mY_k3333yyyy"
app.config['MYSQL_HOST'] = 'mysqlserver'
app.config['MYSQL_USER'] = 'ctf'
app.config['MYSQL_PASSWORD'] = 'ctf_password'
app.config['MYSQL_DB'] = 'secure_note'

mysql = MySQL(app)

from base64 import b64decode
from base64 import b64encode
from Crypto.Cipher import AES

### added
class AESCipher:
	def __init__(self, key):
		self.key = key

	def encrypt(self, plaintext):
		self.cipher = AES.new(self.key, AES.MODE_GCM)
		c, tag = self.cipher.encrypt_and_digest(plaintext)
		nonce = self.cipher.nonce
		return b64encode(nonce + tag + c)

	def decrypt(self, ciphertext):
		ciphertext = b64decode(ciphertext)
		nonce, tag, c = ciphertext[:16], ciphertext[16:32], ciphertext[32:]
		self.cipher = AES.new(self.key, AES.MODE_GCM, nonce)
		return self.cipher.decrypt_and_verify(c, tag)

def waf_filter(s): 
    forbids = ["'", '"', '*', '\\', '/', '#', ';', '--'] 
    for c in forbids: 
        if c in s: 
            s = s.replace(c, '') 
    return s
### added

@app.before_request
def require_login():
	if request.path == "/login":
		return None
	if request.path == "/register":
		return None
	if session.get("username") is None:
		return redirect("/login")

@app.route('/')
def index():
	return render_template('index.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
	if request.method == 'GET':
		return render_template('login.html')
	elif request.method == 'POST':
		username = waf_filter(request.form['username'])
		print(username)
		password = request.form['password']
		print(f"select * from users where username='{username}'")
		cursor = mysql.connection.cursor()
		cursor.execute(f"select * from users where username='{username}'")
		rv = cursor.fetchall()
		if len(rv) == 0:
			flash('User not found')
		else:
			md5_password = hashlib.md5(password.encode()).hexdigest()
			if rv[0][2] == md5_password:
				session['username'] = username
				session['secret_key'] = rv[0][3]
				return redirect('/')
			flash('Wrong password')
		return redirect('/login')

@app.route('/register', methods=['GET', 'POST'])
def register():
	if request.method == 'GET':
		return render_template('register.html')
	elif request.method == 'POST':
		username = request.form['username']
		password = request.form['password']
		repassword = request.form['repassword']
		secretkey = request.form['secretkey']

		is_valid = True
		if len(username) == 0:
			flash('Please input username', 'error')
			is_valid = False
		if len(username) > 10:
			flash('Username length must be at most 10 characters', 'error')
			is_valid = False
		if len(password) == 0:
			flash('Please input password', 'error')
			is_valid = False
		if len(repassword) == 0:
			flash('Please confirm your password', 'error')
			is_valid = False
		if len(password) > 0 and len(repassword) > 0 and password != repassword:
			flash('Passwords do not match, please retype', 'error')
			is_valid = False
		if len(secretkey) == 0:
			flash('Please input secret key', 'error')
			is_valid = False
		elif re.match(r'^[a-f0-9]{32}$', secretkey) is None:
			flash('Secret key must be 32 characters long, including only a-f, 0-9', 'error')
			is_valid = False

		if is_valid == True:
			username = waf_filter(username)
			secretkey = waf_filter(secretkey)
			cursor = mysql.connection.cursor()
			cursor.execute(f"select * from users where username='{username}'")
			if len(cursor.fetchall()) > 0:
				flash('Username already exists', 'error')
			else:
				md5_password = hashlib.md5(password.encode()).hexdigest()
				cursor.execute(f"insert into users(username, password, secret_key) values ('{username}', '{md5_password}', '{secretkey}')")
				flash('Registered successfully', 'success')
		return redirect('/register')

@app.route('/notes', methods=['GET'])
def notes(): 
	cursor = mysql.connection.cursor()
	print(f"select * from notes where username='{waf_filter(session['username'])}'")
	cursor.execute(f"select * from notes where username='{waf_filter(session['username'])}'")
	rv = cursor.fetchall()
	return render_template('notes.html', notes=rv)

@app.route('/read_note', methods=['GET'])
def read_note():
	filename = request.args.get('filename')
	f = open("notes/" + filename, 'r')
	content = f.read()
	f.close()
	try:
		content = AESCipher(bytes.fromhex(session['secret_key'])).decrypt(content.encode()).decode()
	except:
		print('Decrypt error')
	return render_template('read_note.html', content=content)

@app.route('/write_note', methods=['GET', 'POST'])
def write_note():
	if request.method == 'GET':
		return render_template('write_note.html')
	elif request.method == 'POST':
		title = waf_filter(request.form['title'])
		content = waf_filter(request.form['content'])
		filename = f"{time.time()}_{secrets.token_hex(15)}.txt"
		cursor = mysql.connection.cursor()
		cursor.execute(f"insert into notes(username, title, filename, created_at) values ('{waf_filter(session['username'])}', '{title}', '{filename}', now())")
		try:
			content = AESCipher(bytes.fromhex(session['secret_key'])).encrypt(content.encode()).decode()
		except:
			print('Encrypt error')
		f = open("notes/" + filename, "w")
		f.write(content)
		f.close()
		return redirect('/notes')

@app.route('/logout', methods=['GET'])
def logout():
	session.clear()
	return redirect('/')

if __name__ == '__main__':
	# debug=True: server will be auto-reloaded when you modify source code
	app.run(host="0.0.0.0", port=8000, debug=False)