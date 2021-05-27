from flask import Flask, render_template, request

import re
import string
import csv
from joblib import load

import numpy as np

import pandas as pd

import nltk
from nltk import SnowballStemmer
from nltk.corpus import stopwords
from nltk.tokenize import sent_tokenize
from nltk.stem import WordNetLemmatizer

from sklearn import metrics
from sklearn.base import BaseEstimator, TransformerMixin
from sklearn.pipeline import Pipeline, FeatureUnion
from sklearn.naive_bayes import MultinomialNB
from sklearn.neighbors import KNeighborsClassifier
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split, GridSearchCV, cross_val_score
from sklearn.feature_extraction.text import TfidfVectorizer, CountVectorizer
from sklearn.metrics import classification_report


app = Flask(__name__)

class FeaturesExtractor(BaseEstimator, TransformerMixin):

    def __init__(self):
      nltk.download('punkt', quiet=True)
    
    def count_words(self, input_text):
        # remove punctuation, tokenize and return the number of tokens (words)
        message = input_text.translate(str.maketrans('', '', string.punctuation))
        return len(nltk.word_tokenize(input_text))

    def count_sentences(self, input_text):
        return len(sent_tokenize(input_text.lower()))

    def count_brackets(self, input_text):
        return len(re.findall(r'<[a-zA-Z0-9\s]+>+', input_text.lower()))

    def count_links(self, input_text):
        return len(re.findall(r'https?://\S+|www\.\S+', input_text.lower()))

    def count_phone(self, input_text):
        return len(re.findall(r'\d{5,}', input_text.lower()))

    def count_money(self, input_text):
        return len(re.findall(r'[$|£|€]\d+', input_text.lower()))+len(re.findall(r'\d+[$|£|€]', input_text.lower()))

    def transform(self, df, y=None):
        """The workhorse of this feature extractor"""
        df['word_count'] = df.message.apply(self.count_words)
        df['sentence_count'] = df.message.apply(self.count_sentences)
        df['brackets_count'] = df.message.apply(self.count_brackets)
        df['links_count'] = df.message.apply(self.count_links)
        df['phone_count'] = df.message.apply(self.count_phone)
        df['money_count'] = df.message.apply(self.count_money)
        return df
    def fit(self, df, y=None):
        """Returns `self` unless something different happens in train and test"""
        return self


class CleanText(BaseEstimator, TransformerMixin):
    
	def __init__(self):
		nltk.download('stopwords', quiet=True)
		nltk.download('wordnet', quiet=True)
	
	def to_lower(self, input_text):
		return input_text.lower()

	def replace_brackets(self, input_text):
		# Replace text between brackets with 'bracketstext' (spam messages)
		return re.sub('<.*?>+', ' bracketstext ', input_text)

	def replace_money(self, input_text):
		# Replace money amounts ($123 or 1£) with 'moneytext'
		input_text = re.sub(r'[$|£|€]\d+', ' moneytext ', input_text)
		return re.sub(r'\d+[$|£|€]', ' moneytext ', input_text)

	def replace_currency(self, input_text):
		# Replace remaining currency symbols with 'currsymb'
		return re.sub(r'[$|£|€]', ' currsymb ', input_text)

	def replace_urls(self, input_text):
		# Replace links with 'weblink'
		link_regex = r'(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)'
		link_regex1 = r'https?://\S+|www\.\S+'
		link_regex2 = r'http.?://[^\s]+[\s]?'
		return re.sub(link_regex1, ' weblink ', input_text)

	def replace_phone_numbers(self, input_text):
		# Replace phone numbers with 'phonenumber'
		return re.sub(r'\d{5,}', ' phonenumber ', input_text)

	def replace_emojis(self, input_text):
		# TODO :Replace emojis with their code
		pass

	def replace_punctuation(self, input_text):
		# Replace punctuation with a space
		return input_text.translate(str.maketrans(dict.fromkeys(list(string.punctuation),' ')))
	
	def remove_extra_whitespace(self, input_text):
		# Remove extra whitespaces
		return re.sub(r'\s+', ' ', input_text)

	def remove_digits(self, input_text):
		return re.sub('\d+', '', input_text)

	def remove_stopwords(self, input_text):
		stop_words = stopwords.words('english')

		words = input_text.split()
		clean_words = [word for word in words if word not in stop_words]
		return ' '.join(clean_words)

	def apply_stemming(self, input_text):
		stemmer = SnowballStemmer('english')
		
		words = input_text.split()
		stemmed_words = [stemmer.stem(word) for word in words]
		return ' '.join(stemmed_words)

	def apply_lemmatization(self, input_text):
		lemmatizer = WordNetLemmatizer()

		words = input_text.split()
		lemmatized_words = [lemmatizer.lemmatize(word, pos="v") for word in words]
		return ' '.join(lemmatized_words)


	def fit(self, X, y=None, **fit_params):
		return self

	def transform(self, X, **transform_params):
		clean_X = X.apply(self.to_lower)
		clean_X = clean_X.apply(self.replace_brackets)
		clean_X = clean_X.apply(self.replace_money)
		clean_X = clean_X.apply(self.replace_currency)
		clean_X = clean_X.apply(self.replace_urls)
		clean_X = clean_X.apply(self.replace_phone_numbers)
		# clean_X = clean_X.apply(self.replace_emojis)
		clean_X = clean_X.apply(self.replace_punctuation)
		clean_X = clean_X.apply(self.remove_extra_whitespace)
		clean_X = clean_X.apply(self.remove_digits)
		clean_X = clean_X.apply(self.remove_stopwords)
		clean_X = clean_X.apply(self.apply_stemming)
		return clean_X

@app.route('/predict', methods=['POST'])
def predict():
    print(request.form)
    message= [request.form['message']]
    return model('../model/model.joblib', message)[0]

def model(model_path, data):
    df = pd.DataFrame(data, columns=['message'])
    tc = FeaturesExtractor()
    ct = CleanText()

    df_counts = tc.transform(df)
    df_clean = ct.transform(df.message)
    df = df_counts
    df['message_clean'] = df_clean
    
    model = load(model_path)
    predictions = model.predict(df).tolist()
    predictions = [ ['ham','spam'][prediction] for prediction in predictions]
    return predictions

if __name__ == '__main__':
    app.run(debug=True)