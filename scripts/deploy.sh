#!/bin/bash
set -e

echo "=== Bank Sampah Digital - Railway Deploy ==="

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force 2>/dev/null
fi

echo "Running migrations..."
php artisan migrate --force

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
