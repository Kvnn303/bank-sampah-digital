#!/bin/bash

# Production deployment script for Railway
# Runs during release phase before the app starts

set -e

echo "=== Bank Sampah Digital - Deployment ==="

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
    echo "APP_KEY generated successfully"
fi

echo "Running database migrations (safe - not dropping data)..."
php artisan migrate --force --env=production 2>&1 || echo "WARN: Migration failed, continuing anyway..."

echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || echo "WARN: Storage symlink failed"

echo "Optimizing for production..."
php artisan config:cache 2>&1 || echo "WARN: Config cache failed"
php artisan route:cache 2>&1 || echo "WARN: Route cache failed"

echo "=== Deployment complete! ==="
