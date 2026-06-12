# Railway Static Files & Assets Configuration

## Masalah: 404 pada /build/assets/ dan /storage/

### Root Cause
1. Storage symlink (`public/storage` → `storage/app/public`) belum dibuat di production
2. Build artifacts ada di Git tapi symlink storage belum ter-setup

### Solusi Implementasi

## File Changes

### 1. Procfile - Setup Storage Link
```
release: php artisan migrate:fresh --force && php artisan storage:link
web: php -S 0.0.0.0:${PORT:-8000} public/index.php
```

**Penjelasan:**
- `release:` berjalan SEKALI setelah build, sebelum `web:` dimulai
- `migrate:fresh --force` - Reset database
- `storage:link` - Buat symlink `public/storage` → `storage/app/public`
- `web:` - Jalankan PHP built-in server

---

## Deployment Flow di Railway

```
1. GitHub Push
   ↓
2. Railway detects changes
   ↓
3. Build phase
   - Clone repo
   - composer install
   - npm install (jika ada)
   ↓
4. Release phase (Procfile release:)
   - php artisan migrate:fresh --force
   - php artisan storage:link  ✅ CREATE SYMLINK HERE
   ↓
5. Start phase (Procfile web:)
   - PHP server starts
   - Static files di public/ sudah bisa di-akses
   ↓
6. App Live ✅
```

---

## Verifikasi di Production

### Cek di Railway Logs:
```
[release] Running migrate:fresh...
[release] Application ready! Booting the application...
[release] Created symlink [/app/public/storage] -> [/app/storage/app/public]
[release] Symlink created successfully.
[web] Listening on port XXXX
```

### Browser Check:
```
✅ CSS: https://bank-sampah-digital-production.up.railway.app/build/assets/app-BqCPQDBJ.css
✅ JS: https://bank-sampah-digital-production.up.railway.app/build/assets/app-CDJExHXj.js
✅ Images: https://bank-sampah-digital-production.up.railway.app/storage/profile_photos/...
```

---

## Jika Masih 404 Setelah Update

### Step 1: Check Git Commits
```bash
git log --oneline --name-status | grep "public/build"
```

### Step 2: Force Redeploy di Railway
- Dashboard → Settings → Redeploy
- Atau push empty commit: `git commit --allow-empty -m "Trigger redeploy"`

### Step 3: Monitor Railway Logs
- Lihat apakah `storage:link` sudah berjalan
- Cek untuk errors di release phase

### Step 4: Check Vite Config
Pastikan `resources/js/app.js` atau entry point benar di `vite.config.js`

---

## File Structure yang Harus Ada

```
public/
├── build/              ✅ Committed ke Git
│   ├── assets/
│   │   ├── app-BqCPQDBJ.css
│   │   └── app-CDJExHXj.js
│   └── manifest.json
├── storage  → (symlink, created during release)
│   └── profile_photos/
├── index.php
└── ...

storage/
└── app/
    └── public/         ← Target of symlink
        └── profile_photos/
            └── TdCu...jpg
```

---

## Troubleshooting

### Problem: Storage symlink tidak di-create
**Solution:** Pastikan Procfile syntax benar, urutan command benar

### Problem: Build assets masih 404
**Solution:** 
1. Verifikasi files ada di `git ls-files public/build/`
2. Force redeploy via Railway
3. Check `.gitignore` tidak exclude `/public/build`

### Problem: Images di storage masih 404
**Solution:** Pastikan images sudah di-upload ke storage dan symlink terbuat

---

## Next Deployment

Semua konfigurasi sudah ready:
- ✅ Procfile dengan storage:link
- ✅ Commit ke GitHub
- ✅ Railway akan auto-redeploy

**Tunggu 2-5 menit, cek apakah 404 sudah hilang!**
