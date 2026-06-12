#!/bin/bash

# Deploy script untuk Railway
# Aman untuk production - TIDAK menghapus data yang ada

set -e

echo "=== Bank Sampah Digital - Railway Deploy Script ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

echo "Running migrations (safe - tidak hapus data)..."
php artisan migrate --force

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Clearing old caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Deploy complete! ==="
