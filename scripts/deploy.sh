#!/bin/bash
set -e

echo "=== Bank Sampah Digital - Deploy Script ==="
echo "Script ini sekarang opsional. Railway menjalankan release phase otomatis."
echo ""

echo "Running migrations..."
php artisan migrate --force 2>/dev/null || echo "Migration skipped (might already be applied)"

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || echo "Storage link creation skipped"

echo "Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Re-caching for production..."
php artisan config:cache
php artisan route:cache

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
