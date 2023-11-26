from flask import Flask, render_template
from links import links
import secrets

def create_app():
    app = Flask(__name__)
    app.secret_key = secrets.token_hex(16)

    return app

app = create_app()

app.register_blueprint(links)

if __name__ == "__main__":
    app.run(debug=False,)