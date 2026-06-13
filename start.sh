#!/bin/sh

echo "Memulai proses persiapan server..."

# 1. Buat folder wajib
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/framework/cache/data
mkdir -p /app/storage/testing
mkdir -p /app/storage/logs
mkdir -p /app/storage/app/public
mkdir -p /app/bootstrap/cache

# 2. Buka izin
chmod -R 777 /app/storage
chmod -R 777 /app/bootstrap/cache

# 3. Jalankan Migrasi di latar belakang agar tidak memblokir startup
php artisan migrate --force &

# 4. Jalankan server PHP
echo "Menyalakan server..."
exec php -S 0.0.0.0:${PORT:-8080} -t public
