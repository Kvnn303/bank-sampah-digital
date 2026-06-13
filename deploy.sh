#!/bin/bash

# Deploy script untuk Railway
set -e

echo "=== Bank Sampah Digital - Deploy Script ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Copy .env.production to .env jika ada
if [ -f ".env.production" ]; then
    echo "Copying .env.production to .env..."
    cp .env.production .env
fi

echo "Running migrations..."
php artisan migrate --force || true

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
