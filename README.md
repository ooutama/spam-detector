# spam-detector :email:

> Spam classification using Python.

## Prerequisites

- Python 3 :snake: or later
- pipenv, to install it use `$ pip install pipenv` _(optional)_
#### <small> You can use any virtualenv tool, use the requirements.txt file to install requirements.</small>

## Usage
- Install requirements using:
```cosnole
$ pipenv install
or
$ pip install -r requirements.txt
```
- Create a `.env` copy of `.env.sample` located in `api` folder, and edit the values.

- Run the notebook to retrain the model or run `wsgi.py` to start the Flask API.

- You're good to go :rocket:

#### Run the server
```bash
$ python wsgi.py
```
- Send a `POST` request to `/predict` with `message` argument containing the message string (form encoded)

- The response will be a json object like this
```json
{
  "spam": 0 #or 1
}
```
