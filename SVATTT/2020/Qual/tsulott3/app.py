from flask import Flask, session, request, render_template, render_template_string
from flask_session import Session
from random import randint as ri

app = Flask(__name__)
SESSION_TYPE = 'filesystem'
app.config.from_object(__name__)
Session(app)
cheat = "Pls Don't cheat! "

def check_session(input):
	if session.get(input) == None:
		return ""
	return session.get(input)

@app.route("/", methods=["GET","POST"])
def index():
	try:
		session.pop("name")
		session.pop("jackpot")
	except:
		pass
	if request.method == "POST":
		ok = request.form['ok']
		session["name"] = request.form['name']
		if ok == "Go":
			session["check"] = "access"
			jackpot = " ".join(str(x) for x in [ri(10,99), ri(10,99), ri(10,99), ri(10,99), ri(10,99), ri(10,99)]).strip()
			session["jackpot"] = jackpot
			return render_template_string("Generating jackpot...<script>setInterval(function(){ window.location='/guess'; }, 500);</script>")
	return render_template("start.html")

@app.route('/guess', methods=["GET","POST"])
def guess():
	try:
		if check_session("check") == "":
			return render_template_string(cheat+check_session("name"))
		else:
			if request.method == "POST":
				jackpot_input = request.form['jackpot']
				print(jackpot_input + "\n")
				print(check_session("jackpot"))
				if jackpot_input == check_session("jackpot"):
					mess = "Really? GG "+check_session("name")+", here your flag: ASCIS{xxxxxxxxxxxxxxxxxxxxxxxxx}"
				elif jackpot_input != check_session("jackpot"):
					mess = "May the Luck be with you next time!<script>setInterval(function(){ window.location='/reset_access'; }, 1200);</script>"
				return render_template_string(mess)
			return render_template("guess.html")
	except:
		pass
	return render_template_string(cheat+check_session("name"))

@app.route('/reset_access') 
def reset(): 
	try: 
		session.pop("check") 
		return render_template_string("Reseting...<script>setInterval(function(){ window.location='/'; }, 500);</script>")		
	except: 
		pass
	return render_template_string(cheat+check_session("name"))

if __name__ == "__main__":
	app.secret_key = 'tsudepzaivlhihihi'
	app.run()