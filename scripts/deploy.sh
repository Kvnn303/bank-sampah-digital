#!/bin/bash

# Deploy script untuk Railway - release phase
# Aman untuk production - TIDAK menghapus data

echo "=== Bank Sampah Digital - Railway Deploy Script ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

echo "Running migrations (safe - tidak hapus data)..."
php artisan migrate --force || true

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Caching for production..."
php artisan config:cache || true
php artisan route:cache || true

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
