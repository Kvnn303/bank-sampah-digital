#!/bin/bash

# Production start script
# Migration sudah dijalankan di release phase (deploy.sh)
# Script ini hanya menjalankan Laravel server

set -e

echo "Starting Laravel application on 0.0.0.0:${PORT:-8000}..."

php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
