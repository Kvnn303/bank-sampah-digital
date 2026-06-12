@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* Styling khusus untuk Dashboard */
    .stat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }

    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .card-modern .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        padding: 1.25rem 1.5rem;
    }

    .table-modern th {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #64748b;
        border-bottom-width: 1px;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table-modern td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        vertical-align: middle;
    }

    .badge-modern {
        padding: 0.35em 0.8em;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background-color: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; }
</style>
@endpush

@section('content')

<!-- Baris 1: Statistik Utama -->
<div class="row row-deck row-cards mb-4">

    <!-- Total Nasabah -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Nasabah</div>
                    <div class="icon-shape bg-blue-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                    </div>
                </div>
                <div class="h1 fw-bold mb-3 text-dark fs-1">{{ $totalNasabah }}</div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-amber-lt badge-modern rounded-pill">{{ $nasabahPending }} Pending</span>
                    <span class="badge bg-blue-lt badge-modern rounded-pill">{{ $nasabahVerified ?? 0 }} Verified</span>
                    <span class="badge bg-emerald-lt badge-modern rounded-pill">{{ $nasabahActive }} Aktif</span>
                </div>
                <a href="{{ route('admin.nasabah.index') }}" class="text-blue-modern fw-semibold text-decoration-none d-inline-flex align-items-center" style="font-size: 0.85rem;">
                    Kelola Nasabah
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M9 6l6 6l-6 6"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Sampah -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Sampah Terkumpul</div>
                    <div class="icon-shape bg-emerald-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                    </div>
                </div>
                <div class="h1 fw-bold mb-3 text-dark fs-1">{{ number_format($totalSampahKg, 1) }} <span class="fs-4 text-muted">kg</span></div>
                <div class="d-flex gap-3 mb-3">
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Bulan Ini</div>
                        <div class="fw-bold text-emerald">{{ number_format($sampahBulanIni, 1) }} kg</div>
                    </div>
                    <div class="vr bg-slate-200"></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Jenis Sampah</div>
                        <div class="fw-bold text-emerald">{{ $totalJenisSampah }} jenis</div>
                    </div>
                </div>
                <a href="{{ route('admin.tabungan.index') }}" class="text-emerald fw-semibold text-decoration-none d-inline-flex align-items-center" style="font-size: 0.85rem;">
                    Kelola Tabungan
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M9 6l6 6l-6 6"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Nilai Tabungan -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Nilai Tabungan</div>
                    <div class="icon-shape" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/></svg>
                    </div>
                </div>
                <div class="h1 fw-bold mb-3 fs-1" style="color: #8b5cf6;">Rp {{ number_format($totalNilai) }}</div>
                <div class="d-flex gap-3 mb-3">
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Masuk Bulan Ini</div>
                        <div class="fw-bold" style="color: #8b5cf6;">Rp {{ number_format($nilaiBulanIni) }}</div>
                    </div>
                    <div class="vr bg-slate-200"></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Total Dicairkan</div>
                        <div class="fw-bold text-rose">Rp {{ number_format($totalDicairkan) }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.laporan') }}" class="fw-semibold text-decoration-none d-inline-flex align-items-center" style="font-size: 0.85rem; color: #8b5cf6;">
                    Lihat Laporan
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M9 6l6 6l-6 6"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Penarikan -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Penarikan Dana</div>
                    <div class="icon-shape bg-rose-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1"/><path d="M12 7v10"/></svg>
                    </div>
                </div>
                <div class="h1 fw-bold mb-3 fs-1 text-dark">{{ $penarikanPending }} <span class="fs-5 text-muted fw-normal">Menunggu</span></div>
                <div class="d-flex gap-3 mb-3">
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Pending</div>
                        <div class="fw-bold text-amber">{{ $penarikanPending }}</div>
                    </div>
                    <div class="vr bg-slate-200"></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Diproses</div>
                        <div class="fw-bold text-blue-modern">{{ $penarikanDiproses }}</div>
                    </div>
                    <div class="vr bg-slate-200"></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.75rem;">Selesai</div>
                        <div class="fw-bold text-emerald">{{ $penarikanSelesai }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.penarikan.index') }}" class="text-rose fw-semibold text-decoration-none d-inline-flex align-items-center" style="font-size: 0.85rem;">
                    Kelola Penarikan
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M9 6l6 6l-6 6"/></svg>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Baris 2: Grafik -->
<div class="row row-deck row-cards mb-4">

    <!-- Grafik Sampah Per Bulan -->
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">Statistik Sampah Terkumpul</h3>
                <span class="badge bg-slate-100 text-slate-600 border px-3 py-1 rounded-pill">Tahun {{ now()->year }}</span>
            </div>
            <div class="card-body">
                <canvas id="grafikSampah" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik Jenis Sampah -->
    <div class="col-lg-4">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">Komposisi Jenis Sampah</h3>
                <span class="badge bg-slate-100 text-slate-600 border px-3 py-1 rounded-pill">Top 5</span>
            </div>
            <div class="card-body p-3">
                @if($grafikJenis->count() > 0)
                    <div style="position: relative; height: 200px;">
                        <canvas id="grafikJenis"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="row g-2 text-center">
                            @foreach($grafikJenis as $index => $gj)
                            <div class="col-6 col-md-4 mb-2">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="rounded-circle" style="width: 12px; height: 12px; background-color: {{ ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f43f5e'][$index] ?? '#06b6d4' }};"></span>
                                    <div class="text-start">
                                        <div class="small fw-semibold text-dark" style="font-size: 11px;">{{ Str::limit($gj->jenisSampah->nama ?? '-', 10) }}</div>
                                        <div class="fw-bold text-muted" style="font-size: 12px;">{{ number_format($gj->total_kg, 1) }} kg</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-slate-300" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>
                        <p class="mb-0 small">Belum ada data komposisi sampah</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Baris 3: Grafik Nasabah + Tabel Jenis Sampah -->
<div class="row row-deck row-cards mb-4">

    <!-- Grafik Nasabah -->
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">Pendaftaran Nasabah Baru</h3>
                <span class="badge bg-slate-100 text-slate-600 border px-3 py-1 rounded-pill">Tahun {{ now()->year }}</span>
            </div>
            <div class="card-body">
                <canvas id="grafikNasabah" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Jenis Sampah & Harga -->
    <div class="col-lg-4">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">Harga Sampah (Aktif)</h3>
                <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-sm btn-light fw-semibold text-blue-modern rounded-pill px-3">
                    Kelola
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="ps-4">Jenis</th>
                            <th>Kategori</th>
                            <th class="pe-4 text-end">Harga/kg</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisSampahAktif as $j)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $j->nama }}</td>
                            <td>
                                <span class="badge bg-emerald-lt badge-modern rounded-pill">{{ $j->kategori ?? '-' }}</span>
                            </td>
                            <td class="pe-4 text-emerald fw-bold text-end">
                                Rp {{ number_format($j->harga_per_kg) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data jenis sampah</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Baris 4: Stok Sampah & Tabel Terbaru -->
<div class="row row-deck row-cards mb-4">

    <!-- Stok Tersedia untuk Publik -->
    <div class="col-lg-5">
        <div class="card card-modern border-success" style="border-top-width: 3px;">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom p-4">
                <h3 class="card-title fw-bold m-0 text-dark">Stok Siap Dijual</h3>
                <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-sm btn-light fw-semibold text-emerald rounded-pill px-3">
                    Kelola
                </a>
            </div>
            <div class="card-body p-4">
                {{-- Statistik Singkat --}}
                <div class="row g-2 mb-3">
                    <div class="col-4 text-center">
                        <div class="fw-bold fs-4 text-emerald">{{ number_format($stokStats['total_tersisa_kg'] ?? 0, 1) }}</div>
                        <div class="text-muted small">kg Tersisa</div>
                    </div>
                    <div class="col-4 text-center border-start border-end">
                        <div class="fw-bold fs-4 text-purple">{{ $stokStats['published_count'] ?? 0 }}</div>
                        <div class="text-muted small">Published</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-bold fs-4 text-amber">Rp {{ number_format($stokStats['total_pendapatan'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-muted small">Pendapatan</div>
                    </div>
                </div>
                {{-- Link ke Halaman Publik --}}
                <div class="text-center mb-3">
                    <a href="{{ route('publik.stok') }}" target="_blank" class="btn btn-success btn-sm rounded-pill fw-bold px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Lihat di Website Publik
                    </a>
                </div>
                {{-- Daftar Stok --}}
                <div class="table-responsive">
                    <table class="table table-hover table-modern align-middle mb-0">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="ps-3">Jenis Sampah</th>
                                <th class="text-center">Tersisa</th>
                                <th class="text-center pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokTersediaDashboard as $stok)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark small">{{ $stok->jenisSampah->nama ?? '-' }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Rp {{ number_format($stok->harga_jual_per_kg, 0, ',', '.') }}/kg</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-emerald-lt text-emerald badge-modern rounded-pill">
                                        {{ number_format($stok->stok_tersisa_kg, 1) }} kg
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <span class="badge {{ $stok->is_pres ? 'bg-purple-lt text-purple' : 'bg-slate-100 text-slate-500' }} badge-modern rounded-pill">
                                        {{ $stok->is_pres ? 'PRESS' : 'Draft' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300 mb-1"><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                    <br>Belum ada stok tersedia
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabungan Terbaru -->
    <div class="col-lg-7">
        <div class="card card-modern">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">Setoran Terbaru</h3>
                <a href="{{ route('admin.tabungan.index') }}" class="btn btn-sm btn-light fw-semibold text-blue-modern rounded-pill px-3">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-modern align-middle mb-0">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="ps-4">Nasabah</th>
                            <th>Jenis Sampah</th>
                            <th>Berat</th>
                            <th>Nilai</th>
                            <th class="pe-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tabunganTerbaru as $t)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $t->nasabah->nama_lengkap ?? '-' }}</td>
                            <td>
                                <span class="badge bg-slate-100 text-slate-700 border badge-modern rounded-pill">{{ $t->jenisSampah->nama ?? '-' }}</span>
                            </td>
                            <td class="fw-semibold">{{ $t->berat_kg }} kg</td>
                            <td class="text-emerald fw-bold">Rp {{ number_format($t->nilai_rupiah) }}</td>
                            <td class="pe-4 text-muted small">{{ $t->tanggal_setor->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Belum ada transaksi setoran terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Penarikan Pending -->
    <div class="col-lg-5">
        <div class="card card-modern border-warning" style="border-top-width: 3px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 text-dark">Permintaan Penarikan Dana</h3>
                <a href="{{ route('admin.penarikan.index') }}" class="btn btn-sm btn-light fw-semibold text-rose rounded-pill px-3">
                    Proses
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-modern align-middle mb-0">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="ps-4">Nasabah</th>
                            <th class="text-end">Nominal Tarik</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penarikanTerbaru as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $p->nasabah->nama_lengkap ?? '-' }}</div>
                                <div class="text-muted small">Saldo: <span class="text-emerald fw-semibold">Rp {{ number_format($p->nasabah->saldo ?? 0) }}</span></div>
                            </td>
                            <td class="text-rose fw-bold text-end">
                                Rp {{ number_format($p->nominal) }}
                            </td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('admin.penarikan.show', $p->id) }}" class="btn btn-sm bg-rose-lt rounded-pill fw-bold px-3">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <div class="mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg></div>
                                Semuanya beres! Tidak ada pending.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Konfigurasi default untuk semua chart agar terlihat modern
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15, 23, 42, 0.9)';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.titleFont = { size: 14, weight: 'bold' };
    Chart.defaults.plugins.tooltip.bodyFont = { size: 13 };

    // 1. Grafik Garis (Line Chart) - Sampah
    new Chart(document.getElementById('grafikSampah'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Sampah Terkumpul (kg)',
                data: @json($grafikSampah),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                borderWidth: 3,
                tension: 0.4, // Kurva halus
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { padding: 10 }
                },
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [4, 4], color: '#e2e8f0', drawBorder: false },
                    ticks: { padding: 10 }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // 2. Grafik Donat (Doughnut Chart) - Komposisi Jenis Sampah
    @if($grafikJenis->count() > 0)
    new Chart(document.getElementById('grafikJenis'), {
        type: 'doughnut',
        data: {
            labels: @json($grafikJenis->pluck('jenisSampah.nama')),
            datasets: [{
                data: @json($grafikJenis->pluck('total_kg')),
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f43f5e'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = ((context.raw / total) * 100).toFixed(1);
                            return context.raw + ' kg (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
    @endif

    // 3. Grafik Batang (Bar Chart) - Pendaftaran Nasabah
    new Chart(document.getElementById('grafikNasabah'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Nasabah Baru',
                data: @json($grafikNasabah),
                backgroundColor: '#3b82f6',
                hoverBackgroundColor: '#2563eb',
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { padding: 10 }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, padding: 10 },
                    grid: { borderDash: [4, 4], color: '#e2e8f0', drawBorder: false }
                }
            }
        }
    });
</script>
@endpush
