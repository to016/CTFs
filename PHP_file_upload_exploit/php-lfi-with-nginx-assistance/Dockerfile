# Running locally:
# 1) docker build -t php-lfi-with-nginx-assistance .
# 2) docker run -p 1337:80 --rm -it php-lfi-with-nginx-assistance

FROM debian:bullseye

RUN DEBIAN_FRONTEND=noninteractive apt-get update && \
    apt-get install -y \
        nginx \
        php-fpm \
    && rm -rf /var/lib/apt/lists/

# configure nginx & php-fpm
COPY docker-stuff/default /etc/nginx/sites-enabled/default
COPY docker-stuff/www.conf /etc/php/7.4/fpm/pool.d/www.conf

RUN rm -rf /var/www/html/*
COPY index.php /var/www/html/

# make sure you can't write tmp / session files
RUN chown -R root:root /tmp /var/tmp /var/lib/php/sessions && \
    chmod -R 000 /tmp /var/tmp /var/lib/php/sessions

RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80
CMD /etc/init.d/php7.4-fpm start && \
    nginx -g 'daemon off;'
