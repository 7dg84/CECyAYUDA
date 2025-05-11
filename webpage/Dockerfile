FROM php:8.2-fpm

# Copiar los archivos de la aplicación al contenedor
COPY . /var/www/html

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli