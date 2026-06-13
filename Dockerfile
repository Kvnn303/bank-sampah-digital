# ===== Tahap 1: Builder Frontend (Node.js) =====
FROM node:22-alpine AS frontend-builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ===== Tahap 2: Production (Laravel CLI - Tanpa Apache) =====
FROM php:8.4-cli

# 1. Install extensions (ditambah dos2unix)
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip git curl dos2unix \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# 2. Copy skrip, konversi format Windows ke Linux (dos2unix), lalu beri izin eksekusi
COPY start.sh /usr/local/bin/start.sh
RUN dos2unix /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy frontend build
COPY --from=frontend-builder /app/public/build ./public/build

# Use the startup script as the main command
CMD ["/usr/local/bin/start.sh"]
