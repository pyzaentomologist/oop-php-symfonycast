# Użycie obrazu PHP 8 z Apache
FROM php:8.2-apache

# Instalacja rozszerzeń PHP wymaganych do obsługi MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Kopiowanie plików projektu
WORKDIR /var/www/html