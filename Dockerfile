FROM php:8.0-apache

# Paigaldame MySQL-i jaoks vajalikud laiendused.
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Lülitame Apache ümberkirjutamise sisse puhtamate URL-ide jaoks.
RUN a2enmod rewrite

COPY . /var/www/html/