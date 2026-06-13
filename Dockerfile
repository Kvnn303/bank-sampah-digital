# ===== Tahap 1: Builder Frontend (Node.js) =====
FROM node:22-alpine AS frontend-builder
WORKDIR /app

# Copy package files dan install dependencies NPM
COPY package.json package-lock.json ./
RUN npm ci

# Copy seluruh file dan build aset untuk production
COPY . .
RUN npm run build

# ===== Tahap 2: Production (Laravel + Apache) =====
FROM php:8.4-apache

# 1. Install ekstensi sistem yang dibutuhkan Debian & PHP
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip git && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd zip pdo_mysql

# 2. Install Composer langsung dari sumber resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Setup DocumentRoot Apache agar mengarah ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Disable semua MPM kecuali prefork (hanya boleh satu MPM aktif)
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# 5. Aktifkan mod_rewrite (Wajib untuk routing Laravel)
RUN a2enmod rewrite

# 6. Konfigurasi Port Dinamis untuk Railway
RUN sed -i "s/Listen 80/Listen \${PORT:-80}/g" /etc/apache2/ports.conf
RUN sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:\${PORT:-80}>/g" /etc/apache2/sites-available/000-default.conf

# 7. Set folder kerja utama
WORKDIR /var/www/html

# 8. Copy seluruh kodingan Laravel
COPY . .

# 9. Copy file composer dan install dependensi PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 10. Ambil hasil build frontend (CSS/JS Vite) dari Tahap 1
COPY --from=frontend-builder /app/public/build ./public/build

# 10. Buat folder yang wajib ada dan atur perizinannya untuk server
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
