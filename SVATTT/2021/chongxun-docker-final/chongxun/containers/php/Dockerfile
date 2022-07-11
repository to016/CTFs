FROM ubuntu:20.04
RUN DEBIAN_FRONTEND=noninteractive TZ=Asia/Singapore apt-get -y update 
RUN apt-get -y install tzdata
RUN apt-get install -y php7.4 libapache2-mod-php php-mysql curl
RUN apt-get install -y apache2
RUN rm /var/www/html/index.html

COPY ./web02-daemon-final /var/www/html

RUN chmod 1777 -R /var/www/html/ && chown root:root -R /var/www/html/

RUN chmod 775 -R /var/www/html/css/
RUN chmod 775 -R /var/www/html/fonts/
RUN chmod 775 -R /var/www/html/images/
RUN chmod 775 -R /var/www/html/js/

RUN cd /var/www/html/ && chmod 774 /var/www/html/*.php

RUN chmod -R 777 /var/www/html/images/upload/

ADD ./web02-daemon-final/php.ini /etc/php/7.4/apache2/php.ini
ADD ./web02-daemon-final/000-default.conf /etc/apache2/sites-available/000-default.conf
CMD ["apachectl", "-D", "FOREGROUND"]
