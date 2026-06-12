#!/bin/bash
# Railway deployment initialization script

cd "Web Admin"

echo "🚀 [1/4] Running migrations..."
php artisan migrate:fresh --force

echo "🔗 [2/4] Creating storage symlink..."
php artisan storage:link

echo "🏗️ [3/4] Caching configuration..."
php artisan config:cache
php artisan route:cache

echo "✅ [4/4] Starting server on port ${PORT:-8000}..."
php -S 0.0.0.0:${PORT:-8000} public/index.php
