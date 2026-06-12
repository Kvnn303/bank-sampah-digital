# 🎨 Sistem Icon - Bank Sampah Digital

## 📖 Dokumentasi Lengkap

Sistem icon terpusat menggunakan Blade Component untuk konsistensi dan kemudahan maintenance.

---

## 🚀 Cara Penggunaan

### Sintaks Dasar
```blade
<x-icon name="nama-icon" size="24" stroke="2" class="text-primary" />
```

### Parameter
| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `name` | string | 'circle' | Nama icon (lihat daftar di bawah) |
| `size` | int | 20 | Ukuran icon dalam pixel |
| `stroke` | int | 2 | Ketebalan garis icon |
| `class` | string | '' | CSS class tambahan |

---

## 📋 Daftar Icon Lengkap

### 🏠 NAVIGASI & UMUM
| Icon | Nama | Penggunaan |
|------|------|------------|
| 📊 | `dashboard` | Dashboard/Home |
| 👥 | `users` | Data Nasabah/Users |
| 📦 | `package` | Jenis Sampah/Package |
| 📄 | `article` | Artikel/Konten |
| 💼 | `wallet` | Tabungan/Dompet |
| 💰 | `currency` | Penarikan Dana |
| 📋 | `file-text` | Laporan/Dokumen |
| ⚙️ | `settings` | Pengaturan |
| 👤 | `user-cog` | Kelola Admin |
| 🏠 | `home` | Beranda |

### ➕ AKSI & TOMBOL
| Icon | Nama | Penggunaan |
|------|------|------------|
| ➕ | `plus` | Tambah/Add |
| ✏️ | `edit` | Edit/Sunting |
| 🗑️ | `trash` | Hapus/Delete |
| 👁️ | `eye` | Lihat/Detail |
| 📥 | `download` | Download/Unduh |
| 📤 | `upload` | Upload |
| 🔍 | `search` | Cari/Search |
| 🔽 | `filter` | Filter |
| 🔄 | `refresh` | Refresh/Reload |
| ✅ | `check` | Centang/Konfirmasi |
| ❌ | `x` | Tutup/Close |
| 💾 | `save` | Simpan |
| 🔗 | `external-link` | Link Eksternal |

### 🔔 STATUS & NOTIFIKASI
| Icon | Nama | Penggunaan |
|------|------|------------|
| 🔔 | `bell` | Notifikasi |
| 🔕 | `bell-off` | Matikan Notifikasi |
| ✅ | `check-circle` | Berhasil/Success |
| ⚠️ | `alert-circle` | Error/Alert |
| ⚠️ | `alert-triangle` | Warning/Peringatan |
| ℹ️ | `info` | Informasi |

### 💳 DATA & INFORMASI
| Icon | Nama | Penggunaan |
|------|------|------------|
| 💵 | `dollar-sign` | Rupiah/Uang |
| 💳 | `credit-card` | Pembayaran |
| ⚖️ | `scale` | Timbangan/Berat |
| 📅 | `calendar` | Tanggal |
| 🕐 | `clock` | Waktu/Jam |
| 📧 | `mail` | Email |
| 📞 | `phone` | Telepon |
| 📍 | `map-pin` | Lokasi/Alamat |
| 🖼️ | `image` | Gambar/Foto |
| 🪪 | `id-card` | KTP/Identitas |
| 🏢 | `building` | Bank/Institusi |
| 🚚 | `truck` | Pengiriman |
| ♻️ | `recycle` | Daur Ulang |

### 🔐 ADMIN & KEAMANAN
| Icon | Nama | Penggunaan |
|------|------|------------|
| 🛡️ | `shield` | Keamanan |
| 🔒 | `lock` | Terkunci |
| 🔓 | `unlock` | Buka Kunci |
| 🔑 | `key` | Password/Kunci |
| 🚪 | `log-out` | Keluar/Logout |
| 🚪 | `log-in` | Masuk/Login |
| 👤 | `user` | Profil User |
| 🖥️ | `server` | Server |
| 💾 | `database` | Database |

### 💰 TABUNGAN & TRANSAKSI
| Icon | Nama | Penggunaan |
|------|------|------------|
| 🐷 | `piggy-bank` | Tabungan |
| 🧾 | `receipt` | Struk/Kwitansi |
| 📊 | `bar-chart` | Grafik/Chart |
| 📈 | `trending-up` | Naik/Profit |
| 📉 | `trending-down` | Turun/Loss |

---

## 💡 Contoh Penggunaan

### 1. Icon Dasar
```blade
<x-icon name="dashboard" />
```

### 2. Icon dengan Ukuran Custom
```blade
<x-icon name="users" size="24" />
<x-icon name="trash" size="16" />
<x-icon name="check" size="32" />
```

### 3. Icon dengan Warna
```blade
<x-icon name="check-circle" class="text-success" />
<x-icon name="alert-circle" class="text-danger" />
<x-icon name="info" class="text-primary" />
```

### 4. Icon dalam Button
```blade
<button class="btn btn-primary">
    <x-icon name="plus" size="18" class="me-2" />
    Tambah Data
</button>

<button class="btn btn-danger">
    <x-icon name="trash" size="18" class="me-2" />
    Hapus
</button>
```

### 5. Icon dalam Link
```blade
<a href="#" class="nav-link">
    <x-icon name="dashboard" size="20" class="me-2" />
    Dashboard
</a>
```

### 6. Icon dengan Stroke Custom
```blade
<x-icon name="heart" stroke="3" class="text-danger" />
<x-icon name="star" stroke="1.5" class="text-warning" />
```

### 7. Icon dalam Alert
```blade
<div class="alert alert-success">
    <x-icon name="check-circle" size="24" class="me-2" />
    Data berhasil disimpan!
</div>

<div class="alert alert-danger">
    <x-icon name="alert-circle" size="24" class="me-2" />
    Terjadi kesalahan!
</div>
```

### 8. Icon dalam Card Header
```blade
<div class="card">
    <div class="card-header">
        <x-icon name="users" size="20" class="me-2 text-primary" />
        <h3 class="card-title">Data Nasabah</h3>
    </div>
</div>
```

### 9. Icon dalam Dropdown
```blade
<div class="dropdown-menu">
    <a class="dropdown-item" href="#">
        <x-icon name="eye" size="18" class="me-2" />
        Lihat Detail
    </a>
    <a class="dropdown-item" href="#">
        <x-icon name="edit" size="18" class="me-2" />
        Edit
    </a>
    <a class="dropdown-item text-danger" href="#">
        <x-icon name="trash" size="18" class="me-2" />
        Hapus
    </a>
</div>
```

### 10. Icon dalam Stat Card
```blade
<div class="card stat-card">
    <div class="card-body">
        <div class="icon-shape bg-primary-lt">
            <x-icon name="users" size="24" class="text-primary" />
        </div>
        <h3>1,234</h3>
        <p>Total Nasabah</p>
    </div>
</div>
```

---

## 🎨 Ukuran Icon yang Disarankan

| Konteks | Size | Contoh |
|---------|------|--------|
| Sidebar Navigation | 20px | `<x-icon name="dashboard" size="20" />` |
| Button Icon | 18px | `<x-icon name="plus" size="18" />` |
| Dropdown Menu | 18px | `<x-icon name="edit" size="18" />` |
| Alert/Notification | 24-28px | `<x-icon name="check-circle" size="24" />` |
| Stat Card | 24-32px | `<x-icon name="users" size="24" />` |
| Empty State | 48-64px | `<x-icon name="inbox" size="48" />` |
| Small Icon | 16px | `<x-icon name="info" size="16" />` |

---

## 🔄 Migrasi dari SVG Inline

### Sebelum (SVG Inline):
```blade
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
    <circle cx="9" cy="7" r="4"/>
    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
</svg>
```

### Sesudah (Komponen):
```blade
<x-icon name="users" size="20" />
```

**Keuntungan:**
- ✅ Lebih ringkas (1 baris vs 7 baris)
- ✅ Mudah maintenance
- ✅ Konsisten di seluruh aplikasi
- ✅ Mudah diganti icon-nya
- ✅ Lebih mudah dibaca

---

## 🛠️ Tips & Best Practices

### 1. Konsistensi Ukuran
Gunakan ukuran yang konsisten untuk konteks yang sama:
```blade
<!-- Semua icon di sidebar menggunakan size 20 -->
<x-icon name="dashboard" size="20" />
<x-icon name="users" size="20" />
<x-icon name="package" size="20" />
```

### 2. Warna Semantik
Gunakan warna yang sesuai dengan konteks:
```blade
<!-- Success = hijau -->
<x-icon name="check-circle" class="text-success" />

<!-- Danger = merah -->
<x-icon name="trash" class="text-danger" />

<!-- Warning = kuning -->
<x-icon name="alert-triangle" class="text-warning" />

<!-- Info = biru -->
<x-icon name="info" class="text-info" />
```

### 3. Spacing
Tambahkan margin untuk spacing yang baik:
```blade
<!-- Icon di sebelah kiri text -->
<x-icon name="plus" class="me-2" /> Tambah Data

<!-- Icon di sebelah kanan text -->
Lihat Detail <x-icon name="arrow-right" class="ms-2" />
```

### 4. Accessibility
Icon sudah include `aria-hidden="true"` secara default. Pastikan ada text label:
```blade
<!-- GOOD: Ada text label -->
<button>
    <x-icon name="trash" />
    Hapus
</button>

<!-- BAD: Icon saja tanpa text -->
<button>
    <x-icon name="trash" />
</button>

<!-- GOOD: Icon saja dengan aria-label -->
<button aria-label="Hapus">
    <x-icon name="trash" />
</button>
```

---

## 📦 Icon yang Paling Sering Digunakan

### Top 20 Icon:
1. `dashboard` - Dashboard
2. `users` - Data Nasabah
3. `package` - Jenis Sampah
4. `wallet` - Tabungan
5. `currency` - Penarikan
6. `plus` - Tambah
7. `edit` - Edit
8. `trash` - Hapus
9. `eye` - Lihat
10. `check` - Konfirmasi
11. `x` - Tutup
12. `bell` - Notifikasi
13. `search` - Cari
14. `filter` - Filter
15. `download` - Download
16. `calendar` - Tanggal
17. `user` - Profil
18. `log-out` - Keluar
19. `check-circle` - Berhasil
20. `alert-circle` - Error

---

## 🎯 Kesimpulan

Dengan sistem icon terpusat ini:
- ✅ **Konsisten** - Semua icon menggunakan style yang sama
- ✅ **Mudah Maintenance** - Update 1 file, semua icon berubah
- ✅ **Mudah Digunakan** - Sintaks sederhana dan jelas
- ✅ **L80+ icon untuk berbagai kebutuhan
- ✅ **Dokumentasi Jelas** - Setiap icon punya deskripsi penggunaan
- ✅ **Performance** - Tidak perlu load icon library eksternal

**Mulai gunakan sekarang!** 🚀
