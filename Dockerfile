# syntax=docker/dockerfile:1

FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        git \
        libonig-dev \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install mbstring pdo_pgsql xml zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["apache2-foreground"]
