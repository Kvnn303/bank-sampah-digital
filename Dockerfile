# ===== Tahap 1: Builder Frontend (Node.js) =====
FROM node:22-alpine AS frontend-builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ===== Tahap 2: Production (Laravel CLI - Tanpa Apache) =====
FROM php:8.4-cli

# 1. Install ekstensi sistem yang dibutuhkan Debian & PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# 2. Install Composer langsung dari sumber resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 3. Copy seluruh kodingan Laravel
COPY . .

# 4. Install dependensi PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 5. Ambil hasil build frontend (CSS/JS Vite) dari Tahap 1
COPY --from=frontend-builder /app/public/build ./public/build

# 6. Buat folder wajib sebagai pondasi awal
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/testing storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# 7. JALAN PINTAS EKSTREM: Startup Script langsung di dalam CMD!
# Ini akan berjalan SETELAH Volume Railway terpasang, memastikan folder tidak akan pernah hilang.
CMD sh -c "mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache && chmod -R 777 storage bootstrap/cache && php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
