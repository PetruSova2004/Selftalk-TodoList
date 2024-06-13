FROM php:8.1-apache

RUN apt-get update && apt-get install -y libzip-dev unzip
RUN docker-php-ext-install pdo pdo_mysql zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock

WORKDIR /var/www/html
RUN composer install --no-interaction --no-plugins --no-scripts

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite
