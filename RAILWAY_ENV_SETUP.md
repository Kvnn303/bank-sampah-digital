# Railway Environment Variables Setup

## Environment Variables yang Harus Diset di Railway Dashboard

Masuk ke Railway → Project kamu → **Variables** tab, lalu tambahkan variable-variable berikut.

### App Configuration
```
APP_NAME=BankSampahDigital
APP_ENV=production
APP_KEY=base64:GENERATE_DENGAN_php_artisan_key:generate
APP_DEBUG=false
APP_URL=https://bank-sampah-digital-production.up.railway.app
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_LEVEL=info
```

### Database Configuration
Railway sudah otomatis membuat MySQL service. Variable `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD` tersedia otomatis.

Set variable Laravel untuk konek ke MySQL Railway:
```
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}
```

### Session, Cache, Queue
```
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
```

### Mail
```
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@banksampah.app
MAIL_FROM_NAME="${APP_NAME}"
```

### Fonnte Token (WhatsApp API)
```
FONNTE_TOKEN=your_fonnte_token_here
```

---

## Cara Generate APP_KEY

Jalankan secara lokal:
```bash
php artisan key:generate --show
```

Copy outputnya (dimulai dengan `base64:`) dan paste ke variable `APP_KEY` di Railway.

Atau set di Railway shell:
```bash
php artisan key:generate --force
```

Variable `APP_KEY` akan ter-set otomatis.

---

## Troubleshooting

### "Database connection refused"
- Pastikan MySQL service sudah running di Railway
- Cek `DB_HOST` nya apakah `mysql.railway.internal` atau `${{MYSQLHOST}}` (Railway otomatis resolve)
- Cek `DB_USERNAME` dan `DB_PASSWORD` match

### "APP_KEY not set"
- Generate dengan `php artisan key:generate --show` secara lokal
- Atau jalankan command di Railway shell

### "Storage link not found"
- `php artisan storage:link` akan jalan otomatis di `scripts/deploy.sh`
- Pastikan folder `public/storage` ada
