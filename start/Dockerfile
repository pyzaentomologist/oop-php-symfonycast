# Użycie obrazu PHP 8 z Apache
FROM php:8.2-apache

# Aktualizacja pakietów systemowych i instalacja niezbędnych narzędzi
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql mbstring gd

# Instalacja Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Kopiowanie plików projektu
WORKDIR /var/www/html

# Kopiowanie plików projektu
COPY ./app .

# Instalacja zależności przez Composer
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader && composer update