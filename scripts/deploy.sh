#!/bin/bash

echo "=== Bank Sampah Digital - Railway Release Script ==="

# 1. Bangun ulang struktur folder di dalam Volume yang kosong
echo "Membuat struktur folder storage..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/views
mkdir -p storage/framework/sessions
mkdir -p storage/logs

# Beri izin tulis agar Laravel bisa menyimpan file
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 2. Jalankan Migrasi Database
echo "Running migrations (safe - tidak hapus data)..."
php artisan migrate --force

# 3. Bersihkan dan Buat Cache Baru
echo "Clearing caches..."
php artisan optimize:clear
php artisan view:cache
php artisan route:cache

echo "=== Release complete! ==="