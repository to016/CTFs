FROM gitlab/gitlab-ee:13.10.2-ee.0

ADD flag.txt /
ADD readflag /

RUN chown 0:1337 /flag.txt /readflag && \
    chmod 040 /flag.txt && \
    chmod 2555 /readflag
