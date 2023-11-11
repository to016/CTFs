FROM  openjdk:11-slim

COPY ./back-0.0.1-SNAPSHOT.jar /back-0.0.1-SNAPSHOT.jar

COPY ./flag /flag
COPY ./readflag /readflag

RUN chmod 0400 /flag
RUN chmod 4755 /readflag

RUN useradd -m app
RUN rm /usr/bin/perl*

USER app

ENTRYPOINT ["java", "-jar", "/back-0.0.1-SNAPSHOT.jar", "--server.port=80"]

EXPOSE 80
