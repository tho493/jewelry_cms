# ═══════════════════════════════════════════════════════════════
# Stage 1: Node – Build Vite assets
# ═══════════════════════════════════════════════════════════════
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/ resources/

ARG VITE_APP_NAME=cms
ENV VITE_APP_NAME=$VITE_APP_NAME

RUN npm run build


# ═══════════════════════════════════════════════════════════════
# Stage 2: Composer – Install PHP dependencies
# ═══════════════════════════════════════════════════════════════
FROM composer:2.8 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-autoloader

COPY . .

RUN mkdir -p bootstrap/cache \
    && chmod -R 775 bootstrap/cache

RUN composer dump-autoload --optimize


# ═══════════════════════════════════════════════════════════════
# Stage 3: Final PHP-FPM image
# ═══════════════════════════════════════════════════════════════
FROM php:8.4-fpm-alpine AS app

LABEL maintainer="tho493"

# ── System deps ───────────────────────────────────────────────
RUN apk add --no-cache \
    bash curl git \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    icu-dev oniguruma-dev libxml2-dev zip unzip shadow

# ── PHP extensions ────────────────────────────────────────────
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql mbstring exif pcntl bcmath gd intl xml opcache

# ── PHP config ────────────────────────────────────────────────
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "memory_limit=256M" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=128M" >> /usr/local/etc/php/conf.d/custom.ini

# ── User ──────────────────────────────────────────────────────
RUN addgroup -g 1000 laravel \
    && adduser -u 1000 -G laravel -s /bin/sh -D laravel

WORKDIR /var/www/html

# ── Copy source code ──────────────────────────────────────────
COPY --from=composer-builder --chown=laravel:laravel /app ./

# ── Copy built assets ─────────────────────────────────────────
COPY --from=node-builder --chown=laravel:laravel /app/public/build ./public/build

# 🔥 QUAN TRỌNG: đảm bảo folder tồn tại (fix mount error nếu có)
RUN mkdir -p public/build

# ── Storage permissions ───────────────────────────────────────
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R laravel:laravel storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ── Entrypoint ───────────────────────────────────────────────
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

USER laravel

EXPOSE 9000

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]