@extends('layouts.admin')

@section('title', 'Detail Jenis Sampah')
@section('page-title', 'Detail Jenis Sampah')

@push('styles')
<style>
    /* Styling Modern yang Seragam */
    .stat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .icon-shape {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }

    .table-modern th {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem;
    }

    .table-modern td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .badge-modern {
        padding: 0.4em 0.8em;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 0.75rem;
    }

    .info-row {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    /* Warna Kustom Modern */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background-color: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; }

    .text-purple { color: #8b5cf6 !important; }
    .bg-purple-lt { background-color: rgba(139, 92, 246, 0.1) !important; color: #8b5cf6 !important; }
</style>
@endpush

@section('content')

<div class="row g-4">

    <!-- Kolom Kiri: Info Jenis Sampah -->
    <div class="col-lg-4">
        <div class="card card-modern">
            <div class="card-body text-center p-4 p-xl-5">
                <!-- Ikon Utama -->
                <div class="icon-shape bg-emerald-lt mx-auto mb-4" style="width: 90px; height: 90px; border-radius: 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                    </svg>
                </div>

                <h2 class="fw-bold text-dark mb-2 fs-3">{{ $sampah->nama }}</h2>
                <div class="mb-3">
                    @php
                        $badgeColor = match(strtolower($sampah->kategori)) {
                            'organik' => 'bg-emerald-lt',
                            'anorganik' => 'bg-blue-lt',
                            'b3' => 'bg-rose-lt',
                            default => 'bg-slate-100 text-slate-700 border'
                        };
                    @endphp
                    <span class="badge {{ $badgeColor }} badge-modern rounded-pill px-3">
                        {{ $sampah->kategori ?? 'Tanpa Kategori' }}
                    </span>
                </div>

                <div class="p-3 bg-slate-50 border rounded-4 my-4">
                    <div class="text-slate-500 small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.5px;">Harga Beli Aktif</div>
                    <div class="h1 text-emerald fw-bold mb-0 fs-1">
                        Rp {{ number_format($sampah->harga_per_kg) }}<span class="fs-5 fw-medium text-slate-400">/kg</span>
                    </div>
                </div>

                <div class="mb-4">
                    @if($sampah->is_active)
                        <span class="badge bg-emerald-lt badge-modern rounded-pill px-3 py-2 border border-emerald">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Status: Aktif Diterima
                        </span>
                    @else
                        <span class="badge bg-rose-lt badge-modern rounded-pill px-3 py-2 border border-rose">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Status: Nonaktif
                        </span>
                    @endif
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.jenis-sampah.edit', $sampah->id) }}" class="btn btn-primary rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        Edit Jenis & Harga
                    </a>
                    <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light border rounded-pill fw-semibold shadow-sm text-slate-600">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Detail Lanjutan -->
            <div class="card-header bg-slate-50 border-top border-bottom p-3 px-4">
                <h4 class="card-title fw-bold text-slate-600 m-0 fs-6 text-uppercase" style="letter-spacing: 0.5px;">Detail Lanjutan</h4>
            </div>
            <div class="card-body p-4 text-start">
                <div class="info-row pt-0">
                    <div class="text-slate-500 small fw-semibold mb-1 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="6" x2="3" y2="6"/><line x1="15" y1="12" x2="3" y2="12"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                        Keterangan
                    </div>
                    <div class="text-dark fw-medium small ps-4 ms-1">{{ $sampah->keterangan ?? 'Tidak ada keterangan khusus.' }}</div>
                </div>

                <div class="info-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span class="small fw-semibold">Total Transaksi</span>
                    </div>
                    <div class="fw-bold text-dark">{{ number_format($sampah->tabungan_count) }} kali</div>
                </div>

                <div class="info-row d-flex justify-content-between align-items-center pb-0">
                    <div class="d-flex align-items-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span class="small fw-semibold">Tanggal Ditambahkan</span>
                    </div>
                    <div class="fw-bold text-dark">{{ $sampah->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Statistik & Riwayat -->
    <div class="col-lg-8">

        <!-- Statistik Transaksi -->
        <div class="row row-cards mb-4 g-3">
            <div class="col-sm-6">
                <div class="card stat-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Terkumpul</div>
                            <div class="ms-auto icon-shape bg-blue-lt" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 14 4-4"/><path d="M3.3 7H18a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H3.3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/><path d="m8 14 4-4"/></svg>
                            </div>
                        </div>
                        <div class="h2 text-dark fw-bold mb-1 fs-2">
                            {{ number_format($totalKg, 1) }} <span class="fs-4 text-slate-400 fw-medium">kg</span>
                        </div>
                        <div class="text-slate-500 fw-semibold small d-flex align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            Total Nilai: Rp {{ number_format($totalNilai) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card stat-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Terkumpul Bulan Ini</div>
                            <div class="ms-auto icon-shape bg-amber-lt" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="5" width="16" height="16" rx="2" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M10 16h4" /></svg>
                            </div>
                        </div>
                        <div class="h2 text-dark fw-bold mb-1 fs-2">
                            {{ number_format($bulanIniKg, 1) }} <span class="fs-4 text-slate-400 fw-medium">kg</span>
                        </div>
                        <div class="text-amber fw-semibold small d-flex align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M3 17l6 -6l4 4l8 -8"/><path d="M14 7l7 0l0 7"/></svg>
                            Sepanjang Tahun Ini: {{ number_format($tahunIniKg, 1) }} kg
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Harga -->
        <div class="card card-modern mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                <div class="icon-shape bg-purple-lt me-3" style="width: 42px; height: 42px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l3 3"/></svg>
                </div>
                <div>
                    <h3 class="card-title fw-bold text-dark m-0 fs-4">Riwayat Perubahan Harga</h3>
                    <p class="text-slate-500 small m-0 mt-1">Log audit untuk setiap pembaruan harga jenis sampah ini.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="ps-4">Tanggal & Waktu</th>
                            <th>Harga Lama</th>
                            <th>Harga Baru</th>
                            <th>Selisih</th>
                            <th>Alasan Perubahan</th>
                            <th class="pe-4">Diubah Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $r->created_at->format('d M Y, H:i') }}</td>
                            <td class="text-slate-500">Rp {{ number_format($r->harga_lama) }}</td>
                            <td class="text-emerald fw-bold">Rp {{ number_format($r->harga_baru) }}</td>
                            <td>
                                @php $selisih = $r->harga_baru - $r->harga_lama; @endphp
                                @if($selisih > 0)
                                    <span class="badge bg-emerald-lt badge-modern rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m18 15-6-6-6 6"/></svg>
                                        Rp {{ number_format($selisih) }}
                                    </span>
                                @elseif($selisih < 0)
                                    <span class="badge bg-rose-lt badge-modern rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m6 9 6 6 6-6"/></svg>
                                        Rp {{ number_format(abs($selisih)) }}
                                    </span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-600 badge-modern rounded-pill border">Tetap</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-slate-600 small d-block text-truncate" style="max-width: 200px;" title="{{ $r->alasan }}">
                                    {{ $r->alasan ?? '-' }}
                                </span>
                            </td>
                            <td class="pe-4 text-slate-600 small fw-medium">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm rounded-circle bg-slate-200 text-slate-600 fw-bold border" style="font-size: 0.75rem; width: 24px; height: 24px;">
                                        {{ strtoupper(substr($r->diubahOleh->name ?? 'A', 0, 1)) }}
                                    </div>
                                    {{ $r->diubahOleh->name ?? '-' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><path d="M9 14v4"/><path d="M15 14v4"/></svg>
                                </div>
                                <h4 class="text-dark fw-bold fs-5">Belum ada riwayat harga</h4>
                                <p class="text-slate-500 small mb-0">Harga jenis sampah ini belum pernah mengalami perubahan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabungan Terbaru -->
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-amber-lt me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1"/><path d="M8.536 11l-.732 -2.732l-2.732 .732"/><path d="M7.804 8.268l-4.5 7.794a2 2 0 0 0 1.504 2.97l1.141 .011"/><path d="M15.464 11l.732 -2.732l2.732 .732"/><path d="M16.196 8.268l4.5 7.794a2 2 0 0 1 -1.504 2.97l-1.141 .011"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Setoran Terbaru</h3>
                        <p class="text-slate-500 small m-0 mt-1">Daftar transaksi tabungan terakhir untuk jenis sampah ini.</p>
                    </div>
                </div>
                <a href="{{ route('admin.tabungan.index', ['jenis_sampah_id' => $sampah->id]) }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-3 py-1 text-blue-modern text-decoration-none small">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="ps-4">Tanggal Setor</th>
                            <th>Nama Nasabah</th>
                            <th class="text-end">Berat Disetor</th>
                            <th class="text-end">Harga Saat Itu</th>
                            <th class="pe-4 text-end">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tabunganTerbaru as $t)
                        <tr>
                            <td class="ps-4 text-muted fw-medium">{{ $t->tanggal_setor->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-blue-lt text-blue-modern rounded-circle fw-bold shadow-sm border me-2" style="font-size: 0.75rem;">
                                        {{ strtoupper(substr($t->nasabah->nama_lengkap ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $t->nasabah->nama_lengkap ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-end fw-semibold text-dark">{{ number_format($t->berat_kg, 1) }} kg</td>
                            <td class="text-end text-slate-500 small">Rp {{ number_format($t->harga_per_kg_saat_itu) }}</td>
                            <td class="pe-4 text-end text-emerald fw-bold fs-6">Rp {{ number_format($t->nilai_rupiah) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/></svg>
                                </div>
                                <h4 class="text-dark fw-bold fs-5">Belum ada transaksi</h4>
                                <p class="text-slate-500 small mb-0">Belum ada nasabah yang menyetorkan sampah jenis ini.</p>
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
