# ═══════════════════════════════════════════════════════════════
# Stage 1: Node – Build Vite assets
# ═══════════════════════════════════════════════════════════════
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/ resources/

# Requires APP_URL to be known at build time; pass via --build-arg
ARG VITE_APP_NAME=cms
ENV VITE_APP_NAME=$VITE_APP_NAME

RUN npm run build


# ═══════════════════════════════════════════════════════════════
# Stage 2: Composer – Install PHP dependencies (no dev)
# ═══════════════════════════════════════════════════════════════
FROM composer:2.8 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts


# ═══════════════════════════════════════════════════════════════
# Stage 3: Final PHP-FPM image
# ═══════════════════════════════════════════════════════════════
FROM php:8.4-fpm-alpine AS app

LABEL maintainer="tho493"

# ── System dependencies ───────────────────────────────────────
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    shadow

# ── PHP extensions ────────────────────────────────────────────
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    xml \
    opcache

# ── PHP-FPM & OPcache config ──────────────────────────────────
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

RUN { \
    echo 'upload_max_filesize=128M'; \
    echo 'post_max_size=132M'; \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=120'; \
    } > /usr/local/etc/php/conf.d/custom.ini

# ── Create non-root user ──────────────────────────────────────
RUN addgroup -g 1000 laravel && adduser -u 1000 -G laravel -s /bin/bash -D laravel

# ── Copy app ──────────────────────────────────────────────────
WORKDIR /var/www/html

COPY --from=composer-builder --chown=laravel:laravel /app .
COPY --from=node-builder     --chown=laravel:laravel /app/public/build ./public/build

# ── Storage / bootstrap cache dirs ───────────────────────────
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R laravel:laravel storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN mkdir -p /var/www/html/public/build

# ── Backup Vite build (survives shared volume mount overrides) ────
RUN cp -r public/build /build_source

# ── Docker entrypoint ─────────────────────────────────────────
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

USER laravel

EXPOSE 9000

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
