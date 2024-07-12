# PHP rasmiy obrazidan foydalanish
FROM php:8.1

# Kerakli paketlarni o'rnatish
RUN apt-get update -y && apt-get install -y openssl zip unzip git libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev

# PHP kengaytmalarini o'rnatish
# GD uchun konfiguratsiya va o'rnatish
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# ZIP kengaytmasini o'rnatish
RUN docker-php-ext-install zip

# mysqli, pdo, pdo_mysql kengaytmalarini o'rnatish
RUN docker-php-ext-install mysqli pdo pdo_mysql

# mcrypt kengaytmasini o'rnatish (agar kerak bo'lsa)
RUN pecl install mcrypt-1.0.5 \
    && docker-php-ext-enable mcrypt

# Composer o'rnatish
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravel Envoy o'rnatish
RUN composer global require "laravel/envoy=~1.0"

# Composer cache'ni tozalash (ixtiyoriy)
RUN composer clear-cache

# Composer update (ixtiyoriy)
RUN composer update

# Proyekt direktoriyasini yaratish
WORKDIR /var/www

# Proyektni optimallashtirish buyrug'i
CMD php artisan optimize

# Portni ochish (agar kerak bo'lsa)
EXPOSE 80