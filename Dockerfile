FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/uploads

RUN chmod -R 755 /var/www/html/storage /var/www/html/uploads
