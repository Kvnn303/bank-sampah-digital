# ===== Tahap 1: Builder Frontend (Node.js) =====
FROM node:22-alpine AS frontend-builder
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

COPY . .
RUN npm run build

# ===== Tahap 2: Production (PHP CLI) =====
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql pdo_sqlite \
    && pecl install apcu && docker-php-ext-enable apcu

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy built assets first (before full copy to leverage cache)
COPY --from=frontend-builder /app/public/build ./public/build

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Prepare application
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install PostgreSQL client (Railway uses Postgres by default for free tier)
RUN apt-get update && apt-get install -y default-libmysqlclient-dev && docker-php-ext-install mysqli

# Clean up
RUN rm -rf /var/lib/apt/lists/*

EXPOSE ${PORT:-8000}

COPY deploy.sh /app/deploy.sh
RUN chmod +x /app/deploy.sh

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD php artisan up || exit 1

ENTRYPOINT ["/app/deploy.sh"]
