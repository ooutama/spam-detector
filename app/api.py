from app.spam_ham import model
from flask import Flask, request, jsonify

import re
import os
import string
from joblib import load

# from dotenv import load_dotenv

import pandas as pd

import nltk
from nltk import SnowballStemmer
from nltk.corpus import stopwords
from nltk.tokenize import sent_tokenize

from sklearn.base import BaseEstimator, TransformerMixin

# load_dotenv()

# Get model filename from .env file
model_filename = "model.joblib" #os.getenv('MODEL_FILENAME')

model_path = os.path.join(os.getcwd(), "model", model_filename)

print(__name__)

app = Flask(__name__)


@app.route('/predict', methods=['POST'])
def predict():
	print(model_path)
	print(request.form)
	message = [request.form['message']]
	response = model(model_path, message)
	print(f'{message} : {["ham","spam"][response["spam"]]}')
	return jsonify(response)
