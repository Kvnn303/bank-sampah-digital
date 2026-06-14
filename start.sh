#!/bin/bash

# Start script - fallback (tidak digunakan lagi)
# Gunakan Procfile sebagai sumber utama

set -e

echo "Starting Laravel application..."
php -S 0.0.0.0:${PORT:-8000} -t public
