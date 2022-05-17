FROM php:7.3-apache

RUN apt update
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

WORKDIR /var/www/html

COPY ./src .
