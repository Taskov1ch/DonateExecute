from datetime import datetime, timedelta
from donationalerts import DonationAlertsAPI, Scopes
from dotenv import load_dotenv
from flask import Flask, redirect, render_template_string, abort, request
from os import getenv

load_dotenv()

app = Flask(__name__)

# I'm not a frontend master, so the templates are simple xD
SUCCESS_TEMPLATE = """
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Authorization Completed</title>
	<style>
	body {
		background: #f4f4f9;
		font-family: Arial, sans-serif;
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
		margin: 0;
	}
	.container {
		background: #fff;
		border-radius: 10px;
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
		padding: 30px;
		text-align: center;
		max-width: 500px;
		width: 90%;
	}
	.token {
		background: #e9ecef;
		border: none;
		padding: 10px;
		font-size: 14px;
		border-radius: 5px;
		width: 100%;
		resize: none;
		margin-bottom: 15px;
	}
	.copy-button {
		background: #007bff;
		color: #fff;
		border: none;
		padding: 10px 20px;
		border-radius: 5px;
		cursor: pointer;
		font-size: 14px;
	}
	.copy-button:hover {
		background: #0056b3;
	}
	.info {
		margin-top: 20px;
		font-size: 16px;
	}
	</style>
</head>
<body>
	<div class="container">
	<h1>Authorization Completed!</h1>
	<p>Your token:</p>
	<textarea class="token" id="token" rows="3" readonly>{{ your_token }}</textarea>
	<br>
	<button class="copy-button" onclick="copyToken()">Copy Token</button>
	<div class="info">
		<p>Token expiration date: <strong>{{ expiration_date }}</strong></p>
		<p>Duration: <strong>{{ expires_in_readable }}</strong></p>
	</div>
	</div>
	<script>
	function copyToken() {
		const tokenField = document.getElementById('token');
		tokenField.select();
		tokenField.setSelectionRange(0, 99999); // for mobile devices
		document.execCommand('copy');
		alert('Token copied to clipboard!');
	}
	</script>
</body>
</html>
"""

ERROR_TEMPLATE = """
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Authorization Error</title>
	<style>
		body {
		background: #f4f4f9;
		font-family: Arial, sans-serif;
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
		margin: 0;
		}
		.container {
		background: #fff;
		border-radius: 10px;
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
		padding: 30px;
		text-align: center;
		max-width: 500px;
		width: 90%;
		}
		.error {
		color: #dc3545;
		font-size: 24px;
		margin-bottom: 10px;
		}
		.info {
		font-size: 16px;
		}
		.login-button {
		background: #007bff;
		color: #fff;
		border: none;
		padding: 10px 20px;
		border-radius: 5px;
		cursor: pointer;
		font-size: 14px;
		margin-top: 20px;
		}
		.login-button:hover {
		background: #0056b3;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="error">Authorization Error!</div>
		<div class="info">{{ message }}</div>
		<a href="{{ login_url }}"><button class="login-button">Go to Authorization</button></a>
	</div>
</body>
</html>
"""

api = DonationAlertsAPI(
	getenv("CLIENT_ID"),
	getenv("CLIENT_SECRET"),
	"http://127.0.0.1:5000/login", # Don't forget to specify your application address
	Scopes.ALL_SCOPES # All available scopes
)

@app.get("/")
def index():
	return redirect(api.login())

@app.get("/login")
def login():
	try:
		code = request.args.get("code")

		if not code:
			abort(401)


		data = api.get_access_token(code, full_json=True)
		expires_in = data.expires_in
		expiration_date = datetime.now() + timedelta(seconds=expires_in)
		expires_in_readable = str(timedelta(seconds=expires_in))

		print(api.user(data.access_token).avatar)

		return render_template_string(
			SUCCESS_TEMPLATE,
			your_token=data.access_token,
			expiration_date=expiration_date.strftime("%d.%m.%Y %H:%M:%S"),
			expires_in_readable=expires_in_readable
		)

	except Exception as e:
		abort(401)

@app.errorhandler(401)
def unauthorized_error(e):
	return render_template_string(
		ERROR_TEMPLATE,
		message="It looks like you accessed a protected resource without authorization.",
		login_url=api.login()
	), 401

@app.get("/test_error")
def test_error():
	abort(401)

if __name__ == "__main__":
	app.run(debug=True)
