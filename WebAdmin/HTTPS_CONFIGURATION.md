# Railway Environment Configuration untuk HTTPS Fix

## Masalah yang Diperbaiki
✅ **Mixed Content Error** - Asset di-load via HTTP padahal halaman via HTTPS
✅ Browser memblok stylesheet dan script yang tidak aman

## Solusi yang Diterapkan

### 1. Force HTTPS di Production
- Update `AppServiceProvider.php` → `URL::forceScheme('https')`
- Semua asset helper `asset()` akan generate URL dengan `https://`

### 2. Trust Railway Proxies
- Update `bootstrap/app.php` → `trustProxies(at: '*')`
- Laravel percaya header `X-Forwarded-Proto` dari Railway load balancer

### 3. Set APP_URL dengan HTTPS
- Update `.env.example` → `APP_URL=https://bank-sampah-digital-production.up.railway.app`

---

## Environment Variables untuk Railway

Buka **Railway Dashboard → Your App → Variables** dan set:

### Wajib (HARUS ADA):
```
APP_NAME=BankSampahDigital
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bank-sampah-digital-production.up.railway.app
APP_KEY=<dari local .env>

DB_CONNECTION=mysql
DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=3306
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}
```

### Opsional (Recommended):
```
TRUSTED_PROXIES=*
QUEUE_CONNECTION=database
SESSION_DRIVER=cookie
CACHE_DRIVER=file
LOG_CHANNEL=stack
MAIL_DRIVER=log
```

---

## Checklist Setup di Railway

- [ ] Login ke Railway Dashboard
- [ ] Buka aplikasi BankSampahDigital
- [ ] Klik **Variables**
- [ ] Update/Tambahkan environment variables di atas
- [ ] **PENTING:** Set `APP_URL` dengan domain Railway yang benar
- [ ] Redeploy aplikasi (atau trigger dari GitHub push)
- [ ] Test dashboard - style sudah ter-load dengan benar ✅

---

## Testing Lokal

```bash
# Set environment ke production lokal
APP_ENV=production APP_DEBUG=false php artisan serve
```

---

## File-File yang Diubah
1. `bootstrap/app.php` - Trust proxies
2. `app/Providers/AppServiceProvider.php` - Force HTTPS
3. `.env.example` - Update APP_URL to HTTPS
