FROM php:8.1-apache

RUN apt-get update
RUN apt-get install -y apt-utils --no-install-recommends
RUN apt-get install -y unzip
RUN apt-get install -y systemctl
RUN apt-get install -y curl
RUN apt-get install -y git
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    g++

# # RUN docker-php-ext-install -j$(nproc) pdo_pgsql
# # RUN pecl install redis
# # RUN docker-php-ext-enable redis
# # RUN pecl install mongodb
# # RUN docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --2

WORKDIR /var/www/html
# RUN apt-get install -y apt-utils --no-install-recommends

RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y apt-utils
RUN apt-get install -y unzip
RUN apt-get install -y libfreetype-dev
RUN apt-get install -y libjpeg62-turbo-dev
RUN apt-get install -y libmcrypt-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y libicu-dev
RUN apt-get install -y libpq-dev
RUN apt-get install -y libxpm-dev
RUN apt-get install -y libvpx-dev
RUN apt-get install -y libxml2-dev
RUN apt-get install -y libzip-dev
RUN apt-get install -y zip
RUN apt-get install -y git
# RUN apt-get install -y tesseract-ocr
# RUN apt-get install -y libtesseract-dev
RUN apt-get install -y openssl
RUN apt-get install -y libssl-dev
RUN apt-get install -y libcurl4-openssl-dev
RUN apt-get install -y apt-transport-https gnupg2
RUN apt-get install -y unixodbc-dev

RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install gd
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) sockets
RUN docker-php-ext-install -j$(nproc) pgsql
RUN docker-php-ext-install -j$(nproc) pdo_pgsql
RUN docker-php-ext-install -j$(nproc) soap
RUN docker-php-ext-install -j$(nproc) bcmath
RUN docker-php-ext-install -j$(nproc) pcntl
RUN docker-php-ext-install -j$(nproc) ftp
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql

#RUN pecl install xdebug
#RUN docker-php-ext-enable xdebug

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN pecl install mongodb
RUN docker-php-ext-enable mongodb

RUN docker-php-ext-install exif


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --2
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick

# RUN pecl install sqlsrv pdo_sqlsrv
# RUN docker-php-ext-enable sqlsrv
# RUN docker-php-ext-enable pdo_sqlsrv
RUN a2enmod rewrite
RUN service apache2 restart

WORKDIR /var/www/html
