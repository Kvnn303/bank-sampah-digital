# ===== Tahap 1: Builder Frontend (Node.js) =====
FROM node:22-alpine AS frontend-builder
WORKDIR /app

# Copy package files dan install dependencies NPM
COPY package.json package-lock.json ./
RUN npm ci

# Copy seluruh file dan build aset untuk production
COPY . .
RUN npm run build

# ===== Tahap 2: Production (Laravel CLI - Tanpa Apache) =====
FROM php:8.4-cli

# 1. Install ekstensi sistem yang dibutuhkan Debian & PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# 2. Install Composer langsung dari sumber resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Set folder kerja utama ke /app (bukan /var/www/html lagi)
WORKDIR /app

# 4. Copy seluruh kodingan Laravel
COPY . .

# 5. Install dependensi PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Ambil hasil build frontend (CSS/JS Vite) dari Tahap 1
COPY --from=frontend-builder /app/public/build ./public/build

# 7. Buat folder yang wajib ada dan atur perizinannya
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs \
    && chmod -R 777 storage bootstrap/cache

# 8. JALAN TOL: Gunakan server bawaan Laravel yang anti-rewel!
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
