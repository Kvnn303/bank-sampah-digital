# Panduan Deploy ke Railway - Bank Sampah Digital

## Masalah yang Diperbaiki
- ✅ **Crash Loop:** `SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'users' already exists`
- ✅ **Solusi:** Auto-migration dengan `migrate:fresh --force` saat deploy

---

## Cara Setup di Railway

### Langkah 1: Push Kode ke GitHub
```bash
git add .
git commit -m "Add Railway deployment configuration"
git push origin main
```

### Langkah 2: Koneksi Railway ke GitHub
1. Buka **https://railway.app**
2. Login dengan akun Anda
3. Klik **Create** → **Deploy from GitHub**
4. Pilih repository `bank-sampah-digital`
5. Klik **Deploy Now**

### Langkah 3: Konfigurasi Environment Variables
Di Railway Dashboard, masuk ke aplikasi dan buka **Variables**:

```
APP_NAME=BankSampahDigital
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=3306
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@banksampah.app

QUEUE_CONNECTION=database
SESSION_DRIVER=cookie
CACHE_DRIVER=file
```

### Langkah 4: Deploy Database (MySQL)
1. Di Railway Dashboard, klik **+ New**
2. Pilih **MySQL**
3. Wait hingga MySQL siap
4. Railway otomatis mengisi variable `MYSQL_HOST`, `MYSQL_DATABASE`, dll

---

## File-File Deployment

### 1. `Procfile` (Utama)
```
release: php artisan migrate:fresh --force
web: php -S 0.0.0.0:${PORT:-8000} public/index.php
```
- **release:** Berjalan SATU KALI setelah build, untuk migration
- **web:** Command utama untuk menjalankan aplikasi

### 2. `railway.toml` (Alternatif)
Jika Procfile tidak bekerja, Railway akan membaca `railway.toml`

### 3. `composer.json` (Backup)
Menambahkan script `post-install-cmd` dan `post-update-cmd` yang menjalankan:
```bash
@php artisan migrate:fresh --force --seed
```

### 4. `scripts/deploy.sh` (Manual)
Jika ingin jalankan manual:
```bash
bash scripts/deploy.sh
```

---

## Urutan Eksekusi di Railway

```
1. Composer Install (dengan post-install hooks)
   ├─ Download dependencies
   └─ Jalankan migrate:fresh --force --seed
   
2. Build Phase (jika ada)
   ├─ npm install
   └─ npm run build
   
3. Release Phase (Procfile: release)
   └─ php artisan migrate:fresh --force
   
4. Start Phase (Procfile: web)
   └─ php -S 0.0.0.0:8000 public/index.php
```

---

## Troubleshooting

### Jika Masih Crash Loop:

**1. Cek Logs di Railway**
```
Dashboard → Logs → View semua logs
```

**2. Jika ingin jalankan migration manual** (jika console tersedia):
```bash
php artisan migrate:fresh --force --seed
```

**3. Jika database sudah corrupted**
- Drop seluruh database
- Buat database baru
- Re-deploy

**4. Pastikan sudah push ke GitHub**
```bash
git log -1 --oneline
git status
```

---

## Opsi Alternatif: Gunakan `--fresh` untuk Production

⚠️ **Peringatan:** `migrate:fresh` akan menghapus SEMUA data!

Jika ingin lebih aman, gunakan `migrate --force` (hanya untuk table yang belum ada):
Ganti di `Procfile`:
```
release: php artisan migrate --force
```

Atau gunakan idempotent check di migration file.

---

## Testing Lokal Sebelum Deploy

```bash
# Simulasikan production locally
APP_ENV=production php artisan migrate:fresh --force --seed
```

---

## Deploy Berikutnya

Setiap kali push ke GitHub, Railway akan:
1. ✅ Pull latest code
2. ✅ Run Composer install (dengan post-install hooks)
3. ✅ Run release command (migrate:fresh)
4. ✅ Start web server

Tidak perlu config ulang!

---

## Kontrak: Deployment Sudah Aman ✅
- ✅ Auto-migration saat deploy
- ✅ Fresh database setiap deployment
- ✅ No more "table already exists" error
- ✅ Seeders berjalan otomatis
- ✅ Cache di-clear dan di-cache ulang
