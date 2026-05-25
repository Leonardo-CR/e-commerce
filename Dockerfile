# syntax=docker/dockerfile:1

# ---------- Etapa 1: build de assets con Vite ----------
FROM node:20-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm ci && npm run build


# ---------- Etapa 2: imagen final con PHP ----------
FROM php:8.2-cli

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libonig-dev libxml2-dev libicu-dev \
        libpng-dev libjpeg-dev libfreetype6-dev libcurl4-openssl-dev \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd intl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-scripts

COPY . .
COPY --from=assets /app/public/build /app/public/build

RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x deploy/start.sh

EXPOSE 8080
CMD ["bash", "deploy/start.sh"]
