FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY --chown=www-data:www-data src/ /var/www/html/
COPY --chown=www-data:www-data assets/ /var/www/html/assets/

RUN mkdir -p /var/www/html/storage && chmod 777 /var/www/html/storage

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN composer require dompdf/dompdf --no-interaction

RUN chown -R www-data:www-data /var/www/html/vendor
