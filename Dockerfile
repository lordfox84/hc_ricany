# Vite-compiled frontend assets (public/build) are gitignored and not in the repo, but Breeze's
# auth pages (login, register, ...) use @vite() and 500 without them — build them here.
FROM node:20-bookworm-slim AS assets
WORKDIR /app
COPY . .
RUN npm ci && npm run build

FROM php:8.4-cli-bookworm

# System packages + PHP extensions Laravel needs (sqlite driver, uploads, XML/zip for Composer packages)
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libsqlite3-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring xml zip gd bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Ephemeral SQLite file + writable dirs (recreated fresh on every deploy/restart — this is a
# throwaway test deployment, not production; data does not persist across restarts)
RUN mkdir -p database \
    && touch database/database.sqlite \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/testing storage/framework/views storage/logs \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 8080

# db:seed is not safe to re-run (creates the admin user unconditionally), which matters if
# Railway restarts this container without a full rebuild — "|| true" keeps that from crashing
# the boot if the data is already there from a previous start.
CMD php artisan migrate --force \
    && (php artisan db:seed --force || true) \
    && php artisan storage:link --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
