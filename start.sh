#!/bin/sh
echo "Memulai proses persiapan server..."

# 1. BUAT ULANG SEMUA FOLDER WAJIB SETELAH VOLUME DI-MOUNT
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/framework/cache/data
mkdir -p /app/storage/testing
mkdir -p /app/storage/logs
mkdir -p /app/storage/app/public
mkdir -p /app/bootstrap/cache

# 2. BUKA GEMBOK AKSES
chmod -R 777 /app/storage
chmod -R 777 /app/bootstrap/cache

# 3. RESET DAN BUAT ULANG LINK FOTO YANG BENAR
rm -rf /app/public/storage
php artisan storage:link

# 4. BERSIHKAN CACHE SETELAH FOLDER TERBUAT
php artisan optimize:clear

# 5. JALANKAN MIGRASI DATABASE SECARA OTOMATIS
php artisan migrate --force

echo "Folder berhasil dibuat dan dihubungkan!"

# 6. NYALAKAN SERVER
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
