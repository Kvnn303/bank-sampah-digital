#!/bin/bash
# Railway deployment initialization script

cd WebAdmin

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

echo "🚀 [1/5] Running migrations (safe)..."
php artisan migrate --force

echo "🔗 [2/5] Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "🏗️ [3/5] Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "⚡ [4/5] Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache 2>/dev/null || true

echo "🔒 [5/5] Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "✅ Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

