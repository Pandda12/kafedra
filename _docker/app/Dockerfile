# --- Stage 1: берем node ---
FROM node:22.20.0 AS node

# --- Stage 2: основной образ с PHP-FPM ---
FROM php:8.4.3-fpm

# Базовые пакеты
RUN apt-get update && apt-get -y install --no-install-recommends \
    git curl zip unzip vim nano \
    libzip-dev libicu-dev libonig-dev libxml2-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP-расширения
RUN docker-php-ext-install \
    bcmath exif pcntl intl zip pdo

# PostgreSQL (pdo_pgsql, pgsql)
RUN docker-php-ext-install pdo_pgsql pgsql

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin

# Копируем node/npm из stage "node"
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
 && ln -s /usr/local/lib/node_modules/corepack/dist/corepack.js /usr/local/bin/corepack

# Опционально обновим npm
RUN npm i -g npm@latest

# Настройки PHP, если есть
COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www
