import os
from app.api import *
from dotenv import load_dotenv


load_dotenv()

# Application port
#os.getenv('FLASK_PORT')
app_port = int(5000)

if __name__ == "__main__":
	app.run(port=app_port)
