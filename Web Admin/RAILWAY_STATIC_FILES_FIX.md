# Railway Deployment Troubleshooting - Static Files 404

## Masalah yang Terjadi
```
❌ GET /build/assets/app-BqCPQDBJ.css - 404 Not Found
❌ GET /build/assets/app-CDJExHXj.js - 404 Not Found  
❌ GET /storage/profile_photos/... - 404 Not Found
❌ GET /image/BankSampahlogo.jpg - 404 Not Found
```

## Root Cause Analysis

### 1. Storage Symlink Tidak Terbuat
- `public/storage` adalah symlink ke `storage/app/public`
- Di production, symlink perlu di-create manually via `php artisan storage:link`
- Jika tidak ada, request ke `/storage/...` akan 404

### 2. Release Phase Tidak Berjalan  
- Procfile `release:` phase tidak berjalan pada deployment awal
- Ini adalah fase initialization yang WAJIB berjalan sekali SEBELUM `web:` phase
- Jika terlewat → symlink tidak terbuat → storage files 404

### 3. Build Assets Perlu di-Track
- `/public/build/` adalah hasil dari `npm run build` (Vite compilation)
- Files ini HARUS di-commit ke Git agar ter-deploy
- ✅ Sudah ter-fix di deployment `71c132f`

---

## Solusi yang Diterapkan

### File: Procfile (Heroku-style)
```procfile
release: php artisan migrate:fresh --force && php artisan storage:link && php artisan config:cache && php artisan route:cache
web: php -S 0.0.0.0:${PORT:-8000} public/index.php
```

**Penjelasan:**
- `release:` → Berjalan SEKALI sebelum start, tidak blocking
- `web:` → Main process yang handle requests

**Execution Order:**
```
1. Build complete
2. release: migrate → storage:link → config:cache → route:cache
3. web: PHP server starts
4. App ready to serve requests ✅
```

### File: railway.toml (Railway-specific)
```toml
[build]
cmd = "composer install --no-dev --optimize-autoloader && npm install && npm run build"

[deploy]
startCommand = "php -S 0.0.0.0:${PORT:-8000} public/index.php"
restartPolicyType = "on_failure"
restartPolicyMaxRetries = 5
```

**Note:** Railway prioritize Procfile jika ada, tapi railway.toml adalah backup

---

## Deployment Flow Chart

```
┌─────────────────────────────────────┐
│  GitHub Push (commit 09cc6cf)        │
└────────────┬────────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│  Railway Detects Change              │
│  - Reads Procfile & railway.toml     │
│  - Starts build phase                │
└────────────┬────────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│  [BUILD PHASE]                       │
│  - git clone                         │
│  - composer install --no-dev         │
│  - npm install                       │
│  - npm run build                     │
│  Result: /public/build/assets/...    │
└────────────┬────────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│  [RELEASE PHASE] Procfile: release   │
│  1️⃣  migrate:fresh --force            │
│  2️⃣  storage:link ← CREATE SYMLINK   │
│  3️⃣  config:cache ← CACHE CONFIG     │
│  4️⃣  route:cache ← CACHE ROUTES      │
│  Duration: ~30-60 seconds (one-time) │
│  🔗 /public/storage → /storage/app/public
└────────────┬────────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│  [START PHASE] Procfile: web         │
│  - php -S 0.0.0.0:8000              │
│  ✅ App Ready to Handle Requests    │
│  ✅ Storage symlink active           │
│  ✅ Build assets served              │
│  ✅ DB initialized                   │
└─────────────────────────────────────┘
```

---

## Verification Checklist

### Step 1: Check Deploy Logs
Railway Dashboard → Logs tab

**Harus ada:**
```
[release] Running migrate:fresh...
[release] Migration table created successfully.
[release] Migrated: 0001_01_01_000000_create_users_table
... (27 migrations)
[release] Created symlink [/app/public/storage] -> [/app/storage/app/public]
[release] Symlink created successfully
[web] Listening on port 8080
```

**Jika ada error:**
```
[release] SQLSTATE[42S01]... (migration conflict)
[release] Cannot create symlink... (permission issue)
```

### Step 2: Test in Browser
```
https://bank-sampah-digital-production.up.railway.app/admin/dashboard
```

**Check:**
- ✅ Page loads (HTTPS)
- ✅ CSS ter-load (styling visible)
- ✅ JS ter-load (no console errors)
- ✅ No 404 errors in console

### Step 3: Check Network Tab (F12)
```
GET /build/assets/app-BqCPQDBJ.css → 200 OK
GET /build/assets/app-CDJExHXj.js → 200 OK
GET /storage/profile_photos/... → 200 OK
```

---

## Debugging if Still 404

### Issue: CSS/JS still 404
```bash
# Check if build artifacts exist
git ls-files public/build/

# Expected:
# public/build/manifest.json
# public/build/assets/app-*.css
# public/build/assets/app-*.js
```

**Solution:** If not there:
```bash
npm run build  # Rebuild locally
git add public/build/
git commit -m "Rebuild assets"
git push origin main
```

### Issue: Storage files still 404
```bash
# Check Rails logs for storage:link output
# Railway Dashboard → Logs
# Search for: "Created symlink"
```

**Solution:** If no output:
1. Force redeploy: Railway Dashboard → Redeploy
2. Or: `git commit --allow-empty -m "trigger redeploy" && git push`

### Issue: Migration conflicts
```bash
# Error: "Base table 'users' already exists"
```

**Solution:** Procfile already has `migrate:fresh --force`:
1. Manually run in Railway terminal (if available):
   ```bash
   php artisan migrate:fresh --force --seed
   ```
2. Or force redeploy to retry

---

## Git Status

| Commit | Changes |
|--------|---------|
| `09cc6cf` | ✅ Fix Procfile release phase + update railway.toml |
| `918682d` | ✅ Add storage:link to Procfile |
| `4b127e9` | ✅ Fix HTTPS: force HTTPS, trust proxies |
| `71c132f` | ✅ Include build artifacts in Git |
| `c82eeed` | ✅ Fix migration order (FK dependencies) |

---

## Next Steps

### 1. Wait for Railway Redeploy
- Auto-triggered by push
- Takes 2-5 minutes

### 2. Monitor Logs
- Railway Dashboard → Logs
- Wait for "Symlink created successfully"

### 3. Test in Browser
- Open dashboard
- Verify CSS/JS loaded
- Check Network tab for 200 OK status

### 4. Report Results
- If 404 resolved → ✅ Success!
- If still 404 → Check logs & share deployment errors

---

## Files Modified This Session

```
Procfile          → Added storage:link to release phase
railway.toml      → Updated with explicit deploy config
start-railway.sh  → Created initialization script
```

All changes committed and pushed ✅

---

## Technical Notes

### Why `storage:link`?
- Laravel separates public assets from storage files
- `public/storage` is a symlink to `storage/app/public`
- Production needs explicit symlink creation

### Why `config:cache` & `route:cache`?
- Caching improves performance in production
- Required once during release phase
- Automatically invalidated on code deploy

### Why `migrate:fresh`?
- Railway has ephemeral storage
- Each deployment gets fresh filesystem
- Database is persistent (separate MySQL service)
- Fresh migrations ensure clean schema

### Why NOT nginx?
- Railway can run PHP built-in server
- Simpler setup for rapid deployment
- Sufficient for development-level apps

---

## Production Recommendations (Future)

1. **Use proper web server** (nginx) instead of PHP built-in
2. **Setup persistent file storage** (Railway volumes or S3)
3. **Add health checks** to Procfile  
4. **Use production DB** (not reset every deploy)
5. **Setup logging** to centralized service

