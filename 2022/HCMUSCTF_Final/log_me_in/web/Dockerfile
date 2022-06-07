FROM ubuntu:20.04
ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /app

COPY * /app/

RUN apt update && apt install -y python3 python3-pip
RUN apt-get -y install python3-dev default-libmysqlclient-dev build-essential
RUN pip3 install mysqlclient
RUN pip3 install -r requirements.txt

CMD python3 app.py