#!/bin/bash

# Deploy script untuk Railway
# Dijalankan DI BUILD PHASE (bukan start phase)

set -e

echo "=== Bank Sampah Digital - Pre-start Deployment ==="

# 1. Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# 2. Migrate database
echo "Running migrations..."
php artisan migrate --force --no-interaction

# 3. Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# 4. Clear caches
echo "Clearing old caches..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# 5. Cache for production
echo "Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache 2>/dev/null || true

# 6. Set permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
