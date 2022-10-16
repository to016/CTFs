FROM  openjdk:11-slim

COPY ./waf-deser-0.0.1-SNAPSHOT.jar /waf-deser-0.0.1-SNAPSHOT.jar

COPY ./flag /flag
COPY ./readflag /readflag

RUN chmod 0400 /flag
RUN chmod 4755 /readflag

RUN useradd -m app
RUN rm /usr/bin/perl*

USER app

ENTRYPOINT ["java", "-jar", "/waf-deser-0.0.1-SNAPSHOT.jar"]

EXPOSE 8080