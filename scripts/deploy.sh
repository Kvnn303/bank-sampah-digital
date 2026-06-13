#!/bin/bash

# Setup script for Railway (sudah tidak dijalankan di startCommand)
# Hanya dijalankan manual jika diperlukan

set -e

echo "=== Bank Sampah Digital - Setup ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Clearing old caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "=== Setup complete! ==="
