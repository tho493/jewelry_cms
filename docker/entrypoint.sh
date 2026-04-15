#!/usr/bin/env bash
# docker/entrypoint.sh — runs before php-fpm starts

set -e

echo "[entrypoint] Starting Jewelry CMS setup..."

# ── 0a. Sync Vite build assets to shared volume ──────────────────
# /build_source là bản backup trong image — không bị mount override
# Sync sang public/build (shared với nginx qua app_build volume)
if [ -d /build_source ]; then
    echo "Syncing Vite assets to shared volume..."
    mkdir -p public/build
    cp -rf /build_source/. public/build/
fi

# ── 0b. Clear stale dev-generated bootstrap cache ─────────────────
# packages.php có thể chứa dev packages như Pail khi copy từ host
echo "Clearing bootstrap cache..."
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-*.php
rm -f bootstrap/cache/events.php

# ── 1. Wait for MySQL (defensive, compose health check is primary) ──
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
    sleep 2
done
echo "MySQL connected."

# ── 2. Cache config / routes / views ────────────────────────────
# package:discover chạy tự động trong config:cache (production)
if [ "${APP_ENV}" = "production" ]; then
    echo "Caching config, routes, views..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Running package:discover..."
    php artisan package:discover --ansi 2>/dev/null || true
fi

# ── 3. Run migrations ────────────────────────────────────────────
echo "Running migrations..."
php artisan migrate --force --no-interaction || echo "⚠️  Migration warning (non-fatal)"

# ── 4. Create storage symlink ────────────────────────────────────
echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# ── 5. Seed only if DB is empty (first deploy) ───────────────────
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1 || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "Seeding database (first deploy)..."
    php artisan db:seed --force --no-interaction 2>/dev/null || true
fi

echo "Setup complete. Starting PHP-FPM..."
exec "$@"
