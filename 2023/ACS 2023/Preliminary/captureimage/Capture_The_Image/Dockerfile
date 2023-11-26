FROM ubuntu:20.04
WORKDIR /opt/CTF

COPY src/ /opt/CTF
COPY flag /flag
RUN chmod 777 /opt/CTF/geckodriver
RUN chmod 777 /opt/CTF/geckodriver.log
RUN chmod 777 /opt/CTF/docker-entrypoint.sh

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        python3.9 \
	python3-pip \
        git \
	firefox \
    && update-alternatives --install /usr/bin/python3 python3 /usr/bin/python3.9 1

RUN pip3 install -r requirements.txt
EXPOSE 22225

ENTRYPOINT ["/opt/CTF/docker-entrypoint.sh"]


