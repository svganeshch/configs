FROM php:8.0-apache as base

COPY ./ /var/www/html

EXPOSE 8080