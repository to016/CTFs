FROM ubuntu:20.04

RUN apt update
RUN apt install socat python3 python3-pip sqlite3 -y
COPY ./app.py /app.py
RUN chmod +x /app.py
COPY ./flag.txt /flag.txt
RUN pip3 install flask

CMD python3 /app.py

