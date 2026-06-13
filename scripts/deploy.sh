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

# Retry database connection up to 15 times (Railway DB can take time to be ready)
echo "Waiting for database connection..."
MAX_RETRIES=15
RETRY_COUNT=0
DB_READY=false

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php -r "
        \$host = getenv('DB_HOST') ?: 'mysql.railway.internal';
        \$port = getenv('DB_PORT') ?: 3306;
        \$conn = @fsockopen(\$host, \$port, \$errno, \$errstr, 5);
        if (\$conn) { fclose(\$conn); exit(0); }
        exit(1);
    " 2>/dev/null; then
        DB_READY=true
        break
    fi
    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo "  Attempt $RETRY_COUNT/$MAX_RETRIES - database port not reachable, waiting 10s..."
    sleep 10
done

if [ "$DB_READY" = true ]; then
    echo "Database port reachable. Running migrations..."
    php artisan migrate --force --no-interaction

    echo "Creating storage symlink..."
    php artisan storage:link --force 2>/dev/null || true
else
    echo "WARNING: Database port not reachable after $MAX_RETRIES attempts."
    echo "Starting server anyway - migrations can be run via Console."
fi

echo "=== Deploy complete! ==="
