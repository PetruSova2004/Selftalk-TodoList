# Dockerfile для backend
FROM php:8.1-apache

# Установка необходимых расширений и Composer
RUN apt-get update && apt-get install -y libzip-dev unzip
RUN docker-php-ext-install pdo pdo_mysql zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копирование composer.json и composer.lock
COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock

# Установка зависимостей
WORKDIR /var/www/html
RUN composer install --no-interaction --no-plugins --no-scripts

# Копирование исходного кода backend
COPY . /var/www/html

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite
