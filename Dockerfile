# ===== Builder Stage: Frontend =====
FROM node:22-alpine AS frontend-builder

WORKDIR /app

# Copy package files and install dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy source files
COPY . .

# Build frontend assets for production
RUN npm run build

# ===== Production Stage: Laravel + Nginx =====
FROM webdevops/php-nginx:8.4-alpine

# Set working directory
WORKDIR /app

# Set environment variables
ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_ROOT=/app/public

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP extensions needed by Laravel
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-dev \
    freetype-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Laravel application code
COPY . .

# Copy built frontend assets from builder stage
COPY --from=frontend-builder /app/public/build ./public/build

# Create storage directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs \
    && chmod -R 775 storage bootstrap/cache

# Copy .env.example to .env
RUN cp .env.example .env || true

# Generate app key if not set
RUN php artisan key:generate --no-interaction --force 2>/dev/null || true

# Expose port 8080 (webdevops default)
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD php artisan up || exit 1
