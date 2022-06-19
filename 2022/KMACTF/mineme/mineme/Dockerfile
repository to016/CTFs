FROM ubuntu:18.04
RUN apt-get update -y && apt-get install curl supervisor openjdk-8-jdk-headless -y

RUN useradd mineme && mkdir /app

COPY ./app /app
COPY ./supervisor.conf /etc/supervisor.conf
COPY ./flag.txt /flag
RUN chown root:root /app
RUN chmod 1777 /app

EXPOSE 25565

CMD ["supervisord", "-c", "/etc/supervisor.conf"]
