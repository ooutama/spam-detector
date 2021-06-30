import os
from api.api import app
from dotenv import load_dotenv


load_dotenv()

# Application port
app_port = int(os.getenv('FLASK_PORT'))

if __name__ == "__main__":
	app.run(port=app_port)
