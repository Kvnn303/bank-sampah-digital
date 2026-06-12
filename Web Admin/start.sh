#!/bin/bash

# Exit on any error
set -e

echo "Running migrations..."
php artisan migrate:fresh --force --seed

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Optimization..."
php artisan config:cache
php artisan route:cache

echo "Starting Laravel application..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
