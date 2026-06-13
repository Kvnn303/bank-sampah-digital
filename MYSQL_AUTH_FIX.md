# MySQL Authentication Fix

## Masalah
`caching_sha2_password` — MySQL 8.0 default authentication method yang belum didukung PHP versi lama di Railway container.

## Solusi

### Opsi 1: Tambah MYSQL_ROOT_AUTHENTICATION_PLUGIN (Recommended)

Di Railway Dashboard → MySQL Service → **Variables** tab:

Tambahkan variable:
```
MYSQL_ROOT_AUTHENTICATION_PLUGIN=mysql_native_password
```

Kemudian **Redeploy** MySQL service.

### Opsi 2: Switch ke MariaDB (Alternatif)

Di Railway Dashboard:
1. Hapus MySQL service saat ini
2. Tambah **MariaDB** service (lebih compatible dengan PHP versi lama)
3. Update `DB_PASSWORD` env var yang baru

### Opsi 3: Gunakan Environment Variable `MYSQL_AUTH_PLUGIN`

Beberapa versi Railway MySQL support variable ini — coba:
```
MYSQL_AUTH_PLUGIN=mysql_native_password
```

## Setelah MySQL Service Redeploy

1. Redeploy application (push code baru atau klik Redeploy)
2. Migration akan jalan otomatis via `scripts/deploy.sh`
3. `/` akan load halaman welcome
