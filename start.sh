#!/bin/sh

echo "Starting deployment script..."

# 1. Recreate all necessary directories explicitly
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/framework/cache/data
mkdir -p /app/storage/testing
mkdir -p /app/storage/logs
mkdir -p /app/storage/app/public
mkdir -p /app/bootstrap/cache

# 2. Set aggressive permissions so Laravel can write to them
chmod -R 777 /app/storage
chmod -R 777 /app/bootstrap/cache

# 3. Clean up public storage links and recreate it
rm -rf /app/public/storage
php artisan storage:link

# 4. Clear and optimize Laravel caches
php artisan optimize:clear

echo "Directories created and linked successfully."

# 5. Start the built-in PHP server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
