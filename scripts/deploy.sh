#!/bin/bash

# Deploy script untuk Railway (production)
# Aman untuk production - TIDAK menghapus data yang ada
# Script ini dijalankan di START phase (bukan release phase)

set -e

echo "=== Bank Sampah Digital - Deploy ==="
echo "Working directory: $(pwd)"

# Generate app key jika belum ada
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --ansi --force 2>/dev/null || true
fi

# Clear old caches first
echo "Clearing old caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Run migrations (safe - hanya menambah table baru)
echo "Running migrations..."
php artisan migrate --force --isolated 2>/dev/null || true

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# Recache for production
echo "Caching for production..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
