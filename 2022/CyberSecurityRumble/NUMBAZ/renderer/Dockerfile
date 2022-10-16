FROM python:3.7.3-slim

RUN apt-get update
RUN apt-get install gunicorn3 libffi-dev libfreetype6-dev build-essential zlib1g-dev libjpeg-dev -y
RUN pip install --upgrade pip
ADD requirements.txt /requirements.txt

RUN pip install -r /requirements.txt

ADD . /app/
WORKDIR /app/
EXPOSE 1024
ENTRYPOINT gunicorn --bind 0.0.0.0:5002 server:app
