#!/bin/bash

# Deploy script untuk Railway
# Aman untuk production - TIDAK menghapus data yang ada

set +e

LOG_FILE="/tmp/deploy.log"
exec > >(tee -a "$LOG_FILE") 2>&1

echo "=== Bank Sampah Digital - Railway Deploy Script ==="
echo "=== $(date) ==="

# Generate app key jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
fi

# Retry database connection up to 5 times
echo "Waiting for database connection..."
MAX_RETRIES=5
RETRY_COUNT=0
DB_READY=false

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php artisan db:show --no-ansi > /dev/null 2>&1; then
        DB_READY=true
        break
    fi
    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo "  Attempt $RETRY_COUNT/$MAX_RETRIES - database not ready, waiting 10s..."
    sleep 10
done

if [ "$DB_READY" = true ]; then
    echo "Database connected. Running migrations..."
    php artisan migrate --force --no-interaction

    # Check if migrations succeeded
    MIGRATE_EXIT=$?
    if [ $MIGRATE_EXIT -ne 0 ]; then
        echo "WARNING: Migration failed with exit code $MIGRATE_EXIT"
        echo "This may be due to tables already existing. Running migrate status..."
        php artisan migrate:status --no-ansi 2>/dev/null || true
    else
        echo "Migrations completed successfully."
    fi
else
    echo "ERROR: Database could not be connected after $MAX_RETRIES attempts."
    echo "The application will start without migrations."
    echo "You will need to run migrations manually."
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
