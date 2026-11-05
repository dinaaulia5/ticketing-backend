# =========================================================
# Stage 1 — Build frontend assets (Vue 3 + Vite + Tailwind)
# =========================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js components.json jsconfig.json ./
COPY public ./public

RUN npm run build


# =========================================================
# Stage 2 — PHP application (Laravel)
# =========================================================
FROM php:8.3-cli-alpine AS app

RUN apk add --no-cache \
        git curl bash \
        libpng-dev libzip-dev zip unzip \
        oniguruma-dev icu-dev sqlite-dev postgresql-dev \
    && docker-php-ext-install \
        pdo pdo_sqlite pdo_mysql pdo_pgsql \
        mbstring zip gd bcmath \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first (better layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copy application source
COPY . .

# Bring in the compiled frontend assets from stage 1
COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/cache storage/framework/sessions \
       storage/framework/testing storage/framework/views storage/logs \
       bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
