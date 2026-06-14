#!/bin/bash

# Deploy script untuk Railway - RELEASE PHASE
# Hanya dijalankan SATU KALI saat deployment, bukan setiap restart

set -e

echo "=== Bank Sampah Digital - Railway Release Script ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

echo "Running migrations (safe - tidak hapus data)..."
php artisan migrate --force

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Caching config/routes for production..."
php artisan config:cache
php artisan route:cache

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Release complete! ==="
