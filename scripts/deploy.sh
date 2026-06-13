#!/bin/bash

# Deploy script untuk Railway
# Aman untuk production - TIDAK menghapus data yang ada

set -e

echo "=== Bank Sampah Digital - Railway Deploy Script ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

echo "Running migrations (safe - tidak hapus data)..."
php artisan migrate --force --no-interaction

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Clearing old caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache 2>/dev/null || true

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
