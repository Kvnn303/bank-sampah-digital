#!/bin/bash

# Deploy script untuk Railway
# Aman untuk production - TIDAK menghapus data yang ada

# Jangan keluar otomatis pada error - kita mau log dan lanjut
set +e

echo "=== Bank Sampah Digital - Railway Deploy Script ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# Cek apakah database tersedia
echo "Checking database connection..."
DB_CHECK=$(php artisan db:show --no-ansi 2>&1)
DB_EXIT=$?

if [ $DB_EXIT -eq 0 ]; then
    echo "Database connected. Running migrations..."
    php artisan migrate --force --no-interaction
else
    echo "WARNING: Database not available yet, skipping migrations."
    echo "DB output: $DB_CHECK"
    echo "Migrations will need to run manually after MySQL service is provisioned."
fi

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "Clearing old caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Re-caching for production..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Deploy complete! ==="
