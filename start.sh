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

# 3. Reset link foto
rm -rf /app/public/storage
php artisan storage:link

# 4. Bersihkan cache
php artisan optimize:clear

# 5. Migrate database
php artisan migrate --force

echo "Persiapan selesai, menyalakan server Native PHP..."

# 6. INI KUNCINYA: Gunakan PHP Native, bukan artisan serve
exec php -S 0.0.0.0:8080 -t public
