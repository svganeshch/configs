FROM php:8.0-apache as base

#Install mysqli
RUN docker-php-ext-install mysqli

COPY ./ /var/www/html

EXPOSE 8080