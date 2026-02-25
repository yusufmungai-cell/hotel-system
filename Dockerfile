FROM php:8.2-apache

Install system dependencies

RUN apt-get update && apt-get install -y 
git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev 
&& docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring zip exif pcntl bcmath gd

Enable Apache rewrite

RUN a2enmod rewrite

WORKDIR /var/www/html

Copy project

COPY . /var/www/html

Install composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

Install Laravel dependencies

RUN composer install --no-dev --optimize-autoloader

Clear cached config (VERY IMPORTANT)

RUN rm -f bootstrap/cache/*.php

Set permissions

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]