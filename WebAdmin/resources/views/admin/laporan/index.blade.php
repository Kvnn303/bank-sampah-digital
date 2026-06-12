@extends('layouts.admin')

@section('title', 'Laporan & Analitik')
@section('page-title', 'Rekap Laporan')

@push('styles')
<style>
    /* ── DESIGN TOKENS ── */
    :root {
        --lp-green:       #16a34a;
        --lp-green-light: #dcfce7;
        --lp-green-text:  #14532d;
        --lp-red:         #dc2626;
        --lp-red-light:   #fee2e2;
        --lp-red-text:    #7f1d1d;
        --lp-blue:        #2563eb;
        --lp-blue-light:  #dbeafe;
        --lp-blue-text:   #1e3a8a;
        --lp-amber:       #d97706;
        --lp-amber-light: #fef3c7;
        --lp-amber-text:  #78350f;
        --lp-border:      #e5e7eb;
        --lp-bg:          #f9fafb;
        --lp-card:        #ffffff;
        --lp-text:        #111827;
        --lp-muted:       #6b7280;
        --lp-radius:      12px;
        --lp-radius-sm:   8px;
        --font: 'Plus Jakarta Sans', sans-serif;
    }

    .lp * { font-family: var(--font); }

    /* ── SECTION TITLES ── */
    .lp-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--lp-muted);
        margin: 0 0 12px;
    }

    /* ── FILTER CARD ── */
    .lp-filter-card {
        background: var(--lp-card);
        border: 1px solid var(--lp-border);
        border-radius: var(--lp-radius);
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .lp-filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 180px auto;
        gap: 12px;
        align-items: end;
    }

    @media (max-width: 900px) {
        .lp-filter-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .lp-filter-grid { grid-template-columns: 1fr; }
    }

    .lp-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--lp-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .lp-input {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1px solid var(--lp-border);
        border-radius: var(--lp-radius-sm);
        font-size: 0.875rem;
        font-family: var(--font);
        color: var(--lp-text);
        background: var(--lp-card);
        outline: none;
        transition: border-color .15s;
        box-sizing: border-box;
    }
    .lp-input:focus { border-color: var(--lp-green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }

    .lp-btn-row { display: flex; gap: 8px; }

    .lp-btn {
        height: 40px;
        padding: 0 20px;
        border-radius: var(--lp-radius-sm);
        font-size: 0.875rem;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .15s;
        border: none;
        white-space: nowrap;
    }
    .lp-btn-primary { background: var(--lp-green); color: #fff; }
    .lp-btn-primary:hover { background: var(--lp-green-text); }
    .lp-btn-ghost { background: var(--lp-bg); color: var(--lp-muted); border: 1px solid var(--lp-border); padding: 0 12px; }
    .lp-btn-ghost:hover { background: var(--lp-border); color: var(--lp-text); }
    .lp-btn-danger { background: var(--lp-red); color: #fff; }
    .lp-btn-danger:hover { background: var(--lp-red-text); }
    .lp-btn-success { background: var(--lp-green); color: #fff; }
    .lp-btn-success:hover { background: var(--lp-green-text); }
    .lp-btn-outline-danger { background: transparent; color: var(--lp-red); border: 1px solid var(--lp-red); }
    .lp-btn-outline-danger:hover { background: var(--lp-red-light); }
    .lp-btn-outline-success { background: transparent; color: var(--lp-green); border: 1px solid var(--lp-green); }
    .lp-btn-outline-success:hover { background: var(--lp-green-light); }
    .lp-btn-sm { height: 34px; padding: 0 14px; font-size: 0.8rem; }

    /* ── STAT CARDS ── */
    .lp-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) { .lp-stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .lp-stats-grid { grid-template-columns: 1fr; } }

    .lp-stat-card {
        background: var(--lp-card);
        border: 1px solid var(--lp-border);
        border-radius: var(--lp-radius);
        padding: 20px;
        transition: transform .2s, box-shadow .2s;
    }
    .lp-stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.07); }

    .lp-stat-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }

    .lp-stat-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .lp-stat-icon svg { width: 18px; height: 18px; }
    .icon-blue   { background: var(--lp-blue-light);  color: var(--lp-blue); }
    .icon-green  { background: var(--lp-green-light); color: var(--lp-green); }
    .icon-red    { background: var(--lp-red-light);   color: var(--lp-red); }
    .icon-amber  { background: var(--lp-amber-light); color: var(--lp-amber); }

    .lp-stat-label { font-size: 0.75rem; font-weight: 600; color: var(--lp-muted); text-transform: uppercase; letter-spacing: .5px; margin: 0 0 4px; }
    .lp-stat-value { font-size: 1.6rem; font-weight: 800; color: var(--lp-text); line-height: 1; margin: 0 0 10px; letter-spacing: -0.03em; }
    .lp-stat-value small { font-size: 0.85rem; font-weight: 500; color: var(--lp-muted); }

    .lp-progress { height: 4px; background: var(--lp-border); border-radius: 99px; margin-bottom: 8px; overflow: hidden; }
    .lp-progress-bar { height: 100%; border-radius: 99px; transition: width .6s ease; }

    .lp-stat-sub { font-size: 0.75rem; color: var(--lp-muted); }

    .lp-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 20px;
    }
    .badge-green  { background: var(--lp-green-light); color: var(--lp-green-text); }
    .badge-red    { background: var(--lp-red-light);   color: var(--lp-red-text); }
    .badge-blue   { background: var(--lp-blue-light);  color: var(--lp-blue-text); }
    .badge-amber  { background: var(--lp-amber-light); color: var(--lp-amber-text); }
    .badge-gray   { background: #f3f4f6; color: #374151; }

    /* ── MAIN GRID ── */
    .lp-row-2 { display: grid; grid-template-columns: 300px 1fr; gap: 16px; margin-bottom: 24px; }
    @media (max-width: 900px) { .lp-row-2 { grid-template-columns: 1fr; } }

    /* ── CARDS ── */
    .lp-card {
        background: var(--lp-card);
        border: 1px solid var(--lp-border);
        border-radius: var(--lp-radius);
        overflow: hidden;
    }
    .lp-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--lp-border);
    }
    .lp-card-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--lp-text);
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }
    .lp-card-title svg { width: 16px; height: 16px; color: var(--lp-muted); }
    .lp-card-body { padding: 20px; }

    /* Stat mini grid */
    .lp-mini-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .lp-mini-item { background: var(--lp-bg); border-radius: var(--lp-radius-sm); padding: 12px; }
    .lp-mini-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: .5px; color: var(--lp-muted); font-weight: 600; margin-bottom: 4px; }
    .lp-mini-value { font-size: 1.2rem; font-weight: 800; color: var(--lp-text); letter-spacing: -0.02em; }
    .lp-mini-value.green { color: var(--lp-green); }
    .lp-mini-value.red   { color: var(--lp-red); }
    .lp-mini-value.blue  { color: var(--lp-blue); }

    /* ── EXPORT SECTION ── */
    .lp-export-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) { .lp-export-grid { grid-template-columns: 1fr; } }

    .lp-export-card {
        background: var(--lp-card);
        border: 1px solid var(--lp-border);
        border-radius: var(--lp-radius);
        overflow: hidden;
    }

    .lp-export-card-header {
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--lp-border);
    }
    .lp-export-card-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: var(--lp-muted);
        margin: 0;
    }

    .lp-export-item {
        padding: 14px 16px;
        border-bottom: 1px solid var(--lp-border);
    }
    .lp-export-item:last-child { border-bottom: none; }

    .lp-export-item-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--lp-text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .lp-export-item-name svg { width: 14px; height: 14px; color: var(--lp-muted); }

    .lp-export-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .lp-export-controls .lp-input { flex: 1; min-width: 0; height: 34px; font-size: 0.8rem; }

    .lp-btn-pair { display: flex; gap: 6px; }
    .lp-btn-pair .lp-btn { flex: 1; justify-content: center; }

    /* ── TABLE ── */
    .lp-table-wrap { overflow-x: auto; }
    .lp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
    }
    .lp-table thead th {
        padding: 10px 16px;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--lp-muted);
        background: var(--lp-bg);
        border-bottom: 1px solid var(--lp-border);
        white-space: nowrap;
    }
    .lp-table thead th.right { text-align: right; }

    .lp-table tbody tr {
        border-bottom: 1px solid var(--lp-border);
        transition: background .1s;
    }
    .lp-table tbody tr:last-child { border-bottom: none; }
    .lp-table tbody tr:hover { background: var(--lp-bg); }
    .lp-table tbody td {
        padding: 10px 16px;
        color: var(--lp-text);
        vertical-align: middle;
    }
    .lp-table tbody td.right { text-align: right; }
    .lp-table tbody td.muted { color: var(--lp-muted); }

    .lp-name-cell .main { font-weight: 600; color: var(--lp-text); }
    .lp-name-cell .sub  { font-size: 0.72rem; color: var(--lp-muted); margin-top: 1px; }

    .lp-empty { text-align: center; padding: 32px !important; color: var(--lp-muted); font-size: 0.85rem; }

    /* ── ROW 2 TABLES ── */
    .lp-tables-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) { .lp-tables-row { grid-template-columns: 1fr; } }

    /* ── AUDIT LOG ── */
    .lp-audit-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 20px;
        border-bottom: 1px solid var(--lp-border);
    }
    .lp-audit-item:last-child { border-bottom: none; }
    .lp-audit-item:hover { background: var(--lp-bg); }

    .lp-audit-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }
    .dot-success { background: var(--lp-green); }
    .dot-fail    { background: var(--lp-red); }

    .lp-audit-time { font-size: 0.7rem; color: var(--lp-muted); white-space: nowrap; margin-top: 2px; flex-shrink: 0; min-width: 80px; }
    .lp-audit-action { font-size: 0.8rem; font-weight: 600; color: var(--lp-text); }
    .lp-audit-desc { font-size: 0.75rem; color: var(--lp-muted); margin-top: 2px; }
    .lp-audit-meta { margin-left: auto; text-align: right; flex-shrink: 0; }

    code { font-size: 0.72rem; background: var(--lp-bg); border: 1px solid var(--lp-border); padding: 1px 5px; border-radius: 4px; color: var(--lp-text); }

    /* ── PAGINATION ── */
    .lp-pagination { padding: 12px 20px; border-top: 1px solid var(--lp-border); }

    /* ── TABS ── */
    .lp-tabs {
        display: flex;
        gap: 2px;
        padding: 4px;
        background: var(--lp-bg);
        border-radius: var(--lp-radius-sm);
        margin-bottom: 20px;
    }
    .lp-tab {
        flex: 1;
        padding: 7px 16px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--lp-muted);
        cursor: pointer;
        text-align: center;
        border: none;
        background: transparent;
        font-family: var(--font);
        transition: all .15s;
    }
    .lp-tab.active { background: var(--lp-card); color: var(--lp-text); box-shadow: 0 1px 3px rgba(0,0,0,.1); }

    /* ── COLLAPSIBLE SECTION ── */
    .lp-section-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }
    .lp-toggle-icon { transition: transform .2s; width: 16px; height: 16px; color: var(--lp-muted); }
    .lp-toggle-icon.rotated { transform: rotate(180deg); }
</style>
@endpush

@section('content')
<div class="lp">

{{-- ── FILTER BAR ── --}}
<div class="lp-filter-card">
    <form method="GET" action="{{ route('admin.laporan') }}">
        <div class="lp-filter-grid">
            <div>
                <label class="lp-label">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="lp-input" value="{{ $dariTgl }}">
            </div>
            <div>
                <label class="lp-label">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" class="lp-input" value="{{ $sampaiTgl }}">
            </div>
            <div>
                <label class="lp-label">Tahun Grafik</label>
                <select name="tahun" class="lp-input" style="cursor:pointer;">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="lp-label" style="visibility:hidden;">-</label>
                <div class="lp-btn-row">
                    <button type="submit" class="lp-btn lp-btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="10" cy="10" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('admin.laporan') }}" class="lp-btn lp-btn-ghost" title="Reset">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 11A8.1 8.1 0 0 0 4.5 9M4 5v4h4M4 13a8.1 8.1 0 0 0 15.5 2M20 19v-4h-4"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ── STAT CARDS ── --}}
<div class="lp-stats-grid">
    @php $percent = ($ringkasan['total_nasabah'] ?? 0) > 0 ? ($ringkasan['nasabah_aktif'] / $ringkasan['total_nasabah'] * 100) : 0; @endphp

    <div class="lp-stat-card">
        <div class="lp-stat-header">
            <div>
                <p class="lp-stat-label">Total Nasabah</p>
                <p class="lp-stat-value">{{ $ringkasan['total_nasabah'] ?? 0 }}</p>
            </div>
            <div class="lp-stat-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/></svg>
            </div>
        </div>
        <div class="lp-progress"><div class="lp-progress-bar" style="width:{{ $percent }}%; background: var(--lp-blue);"></div></div>
        <p class="lp-stat-sub">
            <span class="lp-badge badge-green">{{ $ringkasan['nasabah_aktif'] ?? 0 }} Aktif</span>
            &nbsp;
            <span class="lp-badge badge-amber">{{ $ringkasan['nasabah_pending'] ?? 0 }} Pending</span>
        </p>
    </div>

    <div class="lp-stat-card">
        <div class="lp-stat-header">
            <div>
                <p class="lp-stat-label">Total Sampah</p>
                <p class="lp-stat-value">{{ number_format($ringkasan['total_sampah_kg'] ?? 0, 0) }} <small>Kg</small></p>
            </div>
            <div class="lp-stat-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </div>
        </div>
        <p class="lp-stat-sub" style="margin-top:6px;">Nilai: <strong style="color:var(--lp-green)">Rp {{ number_format($ringkasan['total_nilai'] ?? 0, 0, ',', '.') }}</strong></p>
    </div>

    <div class="lp-stat-card">
        <div class="lp-stat-header">
            <div>
                <p class="lp-stat-label">Total Dicairkan</p>
                <p class="lp-stat-value" style="color:var(--lp-red)">Rp {{ number_format($ringkasan['total_dicairkan'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="lp-stat-icon icon-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><path d="M7 15h.01M11 15h2"/></svg>
            </div>
        </div>
        @if(($ringkasan['penarikan_pending'] ?? 0) > 0)
            <span class="lp-badge badge-amber">{{ $ringkasan['penarikan_pending'] }} Pengajuan Pending</span>
        @else
            <p class="lp-stat-sub" style="margin-top:6px;">Semua diproses</p>
        @endif
    </div>

    <div class="lp-stat-card">
        <div class="lp-stat-header">
            <div>
                <p class="lp-stat-label">Saldo Tersisa</p>
                <p class="lp-stat-value" style="color:var(--lp-amber)">Rp {{ number_format($ringkasan['saldo_tersisa'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="lp-stat-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 8v-3a1 1 0 0 0-1-1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1-1 1h-12a2 2 0 1 1 0-4h12a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/><path d="M20 12v4h-4a2 2 0 0 1 0-4h4"/></svg>
            </div>
        </div>
        <p class="lp-stat-sub" style="margin-top:6px;">Dana belum dicairkan</p>
    </div>
</div>

{{-- ── GRAFIK + STATISTIK PERIODE ── --}}
<div class="lp-row-2" style="margin-bottom:24px;">

    {{-- Mini Stats --}}
    <div class="lp-card">
        <div class="lp-card-header">
            <h3 class="lp-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M12 17V5"/><path d="M6 17v-3"/></svg>
                Statistik Periode
            </h3>
        </div>
        <div class="lp-card-body">
            <div class="lp-mini-stats">
                <div class="lp-mini-item">
                    <div class="lp-mini-label">Sampah</div>
                    <div class="lp-mini-value green">{{ number_format($statistikPeriode['sampah_kg'] ?? 0, 1) }}<span style="font-size:.8rem;font-weight:500;color:var(--lp-muted)"> Kg</span></div>
                </div>
                <div class="lp-mini-item">
                    <div class="lp-mini-label">Dicairkan</div>
                    <div class="lp-mini-value red" style="font-size:1rem;">Rp {{ number_format($statistikPeriode['dicairkan'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="lp-mini-item">
                    <div class="lp-mini-label">Transaksi</div>
                    <div class="lp-mini-value">{{ $statistikPeriode['transaksi'] ?? 0 }}<span style="font-size:.8rem;font-weight:500;color:var(--lp-muted)"> Kali</span></div>
                </div>
                <div class="lp-mini-item">
                    <div class="lp-mini-label">Nasabah Baru</div>
                    <div class="lp-mini-value blue">+{{ $statistikPeriode['nasabah_baru'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="lp-card">
        <div class="lp-card-header">
            <h3 class="lp-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Tren Bulanan {{ $tahun }}
            </h3>
        </div>
        <div class="lp-card-body" style="padding-bottom:16px;">
            <div style="position:relative; height:220px;">
                <canvas id="grafikBulanan" role="img" aria-label="Grafik tren bulanan sampah dan nilai tahun {{ $tahun }}">Data tren bulanan</canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── PUSAT EXPORT ── --}}
<p class="lp-section-title" style="margin-bottom:12px;">Pusat Export Laporan</p>
<div class="lp-export-grid">

    {{-- Berbasis Waktu --}}
    <div class="lp-export-card">
        <div class="lp-export-card-header">
            <p class="lp-export-card-title">Berbasis Waktu</p>
        </div>

        {{-- Harian --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M3 12h1m8-9v1m8 8h1m-9 8v1M5.6 5.6l.7.7m12.1-.7-.7.7m0 11.4.7.7m-12.1-.7-.7.7"/></svg>
                Harian
            </div>
            <div class="lp-export-controls">
                <input type="date" id="tanggalHarian" class="lp-input" value="{{ now()->toDateString() }}">
                <div class="lp-btn-pair">
                    <button onclick="downloadHarian('pdf')" class="lp-btn lp-btn-outline-danger lp-btn-sm">PDF</button>
                    <button onclick="downloadHarian('excel')" class="lp-btn lp-btn-outline-success lp-btn-sm">Excel</button>
                </div>
            </div>
        </div>

        {{-- Mingguan --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Mingguan
            </div>
            <div class="lp-export-controls">
                <input type="date" id="tanggalMingguan" class="lp-input" value="{{ now()->toDateString() }}">
                <div class="lp-btn-pair">
                    <button onclick="downloadMingguan('pdf')" class="lp-btn lp-btn-outline-danger lp-btn-sm">PDF</button>
                    <button onclick="downloadMingguan('excel')" class="lp-btn lp-btn-outline-success lp-btn-sm">Excel</button>
                </div>
            </div>
        </div>

        {{-- Bulanan --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Bulanan Resmi
            </div>
            <div class="lp-export-controls" style="flex-wrap:wrap; gap:6px;">
                <select id="bulanPdf" class="lp-input" style="flex:1; min-width:100px; cursor:pointer;">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ now()->month == $b ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <select id="tahunPdf" class="lp-input" style="width:80px; cursor:pointer;">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <div class="lp-btn-pair" style="width:100%;">
                    <button onclick="downloadBulanan('pdf')" class="lp-btn lp-btn-danger lp-btn-sm" style="flex:1;justify-content:center;">PDF</button>
                    <button onclick="downloadBulanan('excel')" class="lp-btn lp-btn-success lp-btn-sm" style="flex:1;justify-content:center;">Excel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Data & Tahunan --}}
    <div class="lp-export-card">
        <div class="lp-export-card-header">
            <p class="lp-export-card-title">Data & Tahunan</p>
        </div>

        {{-- Tahunan --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M12 17V5"/><path d="M6 17v-3"/></svg>
                Rekap Tahunan
            </div>
            <div class="lp-export-controls">
                <select id="tahunTahunan" class="lp-input" style="cursor:pointer;">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <div class="lp-btn-pair">
                    <button onclick="downloadTahunan('pdf')" class="lp-btn lp-btn-outline-danger lp-btn-sm">PDF</button>
                    <button onclick="downloadTahunan('excel')" class="lp-btn lp-btn-outline-success lp-btn-sm">Excel</button>
                </div>
            </div>
        </div>

        {{-- Setoran Periode --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l8 4.5v9L12 21l-8-4.5v-9z"/><path d="M12 12l8-4.5M12 12v9M12 12l-8-4.5"/></svg>
                Setoran — Periode Dipilih
            </div>
            <div class="lp-btn-pair">
                <a href="{{ route('admin.laporan.pdf-tabungan', ['dari_tanggal' => $dariTgl, 'sampai_tanggal' => $sampaiTgl]) }}" target="_blank" class="lp-btn lp-btn-outline-danger lp-btn-sm">PDF</a>
                <a href="{{ route('admin.laporan.excel-tabungan', ['dari_tanggal' => $dariTgl, 'sampai_tanggal' => $sampaiTgl]) }}" class="lp-btn lp-btn-outline-success lp-btn-sm">Excel</a>
            </div>
        </div>

        {{-- Penarikan Periode --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><path d="M7 15h.01M11 15h2"/></svg>
                Penarikan — Periode Dipilih
            </div>
            <div class="lp-btn-pair">
                <a href="{{ route('admin.laporan.pdf-penarikan', ['dari_tanggal' => $dariTgl, 'sampai_tanggal' => $sampaiTgl]) }}" target="_blank" class="lp-btn lp-btn-outline-danger lp-btn-sm">PDF</a>
                <a href="{{ route('admin.laporan.excel-penarikan', ['dari_tanggal' => $dariTgl, 'sampai_tanggal' => $sampaiTgl]) }}" class="lp-btn lp-btn-outline-success lp-btn-sm">Excel</a>
            </div>
        </div>
    </div>

    {{-- Laporan Khusus --}}
    <div class="lp-export-card">
        <div class="lp-export-card-header">
            <p class="lp-export-card-title">Laporan Khusus</p>
        </div>

        {{-- Rekap Nasabah --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
                Rekap Nasabah
            </div>
            <p style="font-size:.75rem;color:var(--lp-muted);margin:0 0 8px;">Data lengkap nasabah & saldo saat ini.</p>
            <div class="lp-btn-pair">
                <a href="{{ route('admin.laporan.pdf-nasabah') }}" target="_blank" class="lp-btn lp-btn-outline-danger lp-btn-sm">PDF</a>
                <a href="{{ route('admin.laporan.excel-nasabah') }}" class="lp-btn lp-btn-outline-success lp-btn-sm">Excel</a>
            </div>
        </div>

        {{-- Kartu Tabungan --}}
        <div class="lp-export-item">
            <div class="lp-export-item-name">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M7 15h.01M11 15h2"/></svg>
                Kartu Tabungan Nasabah
            </div>
            <div class="lp-export-controls" style="flex-direction:column; align-items:stretch; gap:8px;">
                <select id="nasabahKartu" class="lp-input" style="cursor:pointer;">
                    <option value="">— Pilih Nasabah —</option>
                    @foreach(\App\Models\Nasabah::select('id', 'nama_lengkap')->orderBy('nama_lengkap')->get() as $n)
                        <option value="{{ $n->id }}">{{ $n->nama_lengkap }}</option>
                    @endforeach
                </select>
                <button onclick="downloadKartu()" class="lp-btn lp-btn-primary lp-btn-sm" style="justify-content:center; width:100%;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Cetak Kartu
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ── REKAP JENIS & TOP NASABAH ── --}}
<div class="lp-tables-row">

    <div class="lp-card">
        <div class="lp-card-header">
            <h3 class="lp-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                Rekap Jenis Sampah
            </h3>
        </div>
        <div class="lp-table-wrap">
            <table class="lp-table">
                <thead><tr>
                    <th>Jenis</th>
                    <th class="right">Berat</th>
                    <th class="right">Nilai</th>
                </tr></thead>
                <tbody>
                    @forelse($rekapJenis as $r)
                    <tr>
                        <td>
                            <div class="lp-name-cell">
                                <div class="main">{{ $r->jenisSampah->nama ?? '-' }}</div>
                                <div class="sub">{{ strtoupper($r->jenisSampah->kategori ?? '-') }}</div>
                            </div>
                        </td>
                        <td class="right" style="font-weight:700;">{{ number_format($r->total_kg, 1) }} Kg</td>
                        <td class="right" style="font-weight:700;color:var(--lp-green);">Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="lp-empty">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="lp-card">
        <div class="lp-card-header">
            <h3 class="lp-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                Top 10 Nasabah
            </h3>
        </div>
        <div class="lp-table-wrap">
            <table class="lp-table">
                <thead><tr>
                    <th>Nasabah</th>
                    <th class="right">Berat</th>
                    <th class="right">Nilai</th>
                </tr></thead>
                <tbody>
                    @forelse($rekapNasabah->take(10) as $r)
                    <tr>
                        <td>
                            <div class="lp-name-cell">
                                <div class="main">{{ $r->nasabah->nama_lengkap ?? '-' }}</div>
                                <div class="sub">{{ $r->nasabah->no_telepon ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="right" style="font-weight:700;">{{ number_format($r->total_kg, 1) }} Kg</td>
                        <td class="right" style="font-weight:700;color:var(--lp-green);">Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="lp-empty">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── DETAIL TRANSAKSI TABUNGAN ── --}}
<div class="lp-card" style="margin-bottom:16px;">
    <div class="lp-card-header">
        <h3 class="lp-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l8 4.5v9L12 21l-8-4.5v-9z"/></svg>
            Detail Setoran Sampah
        </h3>
        <span class="lp-badge badge-green">{{ $laporanTabungan->total() }} data</span>
    </div>
    <div class="lp-table-wrap">
        <table class="lp-table">
            <thead><tr>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th>Jenis</th>
                <th class="right">Berat</th>
                <th class="right">Nilai</th>
                <th>Petugas</th>
            </tr></thead>
            <tbody>
                @forelse($laporanTabungan as $t)
                <tr>
                    <td class="muted" style="white-space:nowrap;">{{ $t->tanggal_setor->format('d/m/Y') }}</td>
                    <td>
                        <div class="lp-name-cell">
                            <div class="main">{{ $t->nasabah->nama_lengkap ?? '-' }}</div>
                            <div class="sub">{{ $t->nasabah->no_telepon ?? '-' }}</div>
                        </div>
                    </td>
                    <td><span class="lp-badge badge-green">{{ $t->jenisSampah->nama ?? '-' }}</span></td>
                    <td class="right" style="font-weight:700;">{{ $t->berat_kg }} Kg</td>
                    <td class="right" style="font-weight:700;color:var(--lp-green);">Rp {{ number_format($t->nilai_rupiah, 0, ',', '.') }}</td>
                    <td class="muted">{{ $t->admin->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="lp-empty">Tidak ada data transaksi pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($laporanTabungan, 'hasPages') && $laporanTabungan->hasPages())
        <div class="lp-pagination">{{ $laporanTabungan->appends(request()->query())->links() }}</div>
    @endif
</div>

{{-- ── DETAIL PENARIKAN ── --}}
<div class="lp-card" style="margin-bottom:16px;">
    <div class="lp-card-header">
        <h3 class="lp-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><path d="M7 15h.01M11 15h2"/></svg>
            Detail Penarikan Dana
        </h3>
        <span class="lp-badge badge-red">{{ $laporanPenarikan->total() }} data</span>
    </div>
    <div class="lp-table-wrap">
        <table class="lp-table">
            <thead><tr>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th class="right">Nominal</th>
                <th>Status</th>
                <th>Diproses Oleh</th>
                <th>Catatan</th>
            </tr></thead>
            <tbody>
                @forelse($laporanPenarikan as $p)
                <tr>
                    <td class="muted" style="white-space:nowrap;">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-weight:600;">{{ $p->nasabah->nama_lengkap ?? '-' }}</td>
                    <td class="right" style="font-weight:700;color:var(--lp-red);">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span class="lp-badge badge-amber">Pending</span>
                        @elseif($p->status == 'diproses')
                            <span class="lp-badge badge-blue">Diproses</span>
                        @elseif($p->status == 'selesai')
                            <span class="lp-badge badge-green">Selesai</span>
                        @else
                            <span class="lp-badge badge-red">Ditolak</span>
                        @endif
                    </td>
                    <td class="muted">{{ $p->diprosesoleh->name ?? '-' }}</td>
                    <td class="muted" style="max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $p->catatan_admin ?? $p->alasan_penolakan ?? '-' }}">
                        {{ $p->catatan_admin ?? $p->alasan_penolakan ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="lp-empty">Tidak ada data penarikan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($laporanPenarikan, 'hasPages') && $laporanPenarikan->hasPages())
        <div class="lp-pagination">{{ $laporanPenarikan->appends(request()->query())->links() }}</div>
    @endif
</div>

{{-- ── AUDIT LOG ── --}}
<div class="lp-card">
    <div class="lp-card-header">
        <h3 class="lp-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
            Audit Log Sistem
        </h3>
        <a href="{{ route('admin.laporan.excel-auditlog', ['dari_tanggal' => $dariTgl, 'sampai_tanggal' => $sampaiTgl]) }}" class="lp-btn lp-btn-outline-success lp-btn-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6M9 15l3 3 3-3"/></svg>
            Export Excel
        </a>
    </div>

    <div>
        @forelse($auditLog as $log)
        <div class="lp-audit-item">
            <div class="lp-audit-dot {{ $log->status == 'success' ? 'dot-success' : 'dot-fail' }}"></div>
            <div class="lp-audit-time">{{ $log->created_at->format('d/m H:i') }}</div>
            <div style="flex:1; min-width:0;">
                <div class="lp-audit-action">{{ $log->description }}</div>
                <div class="lp-audit-desc">
                    <code>{{ $log->action }}</code>
                    &nbsp;·&nbsp;
                    <span class="lp-badge badge-gray" style="font-size:.65rem;">{{ $log->module }}</span>
                    &nbsp;·&nbsp;
                    {{ $log->ip_address ?? '-' }}
                </div>
            </div>
            <div class="lp-audit-meta">
                <div style="font-size:.78rem;font-weight:600;color:var(--lp-text);">{{ $log->user_name ?? '-' }}</div>
                <span class="lp-badge {{ $log->role == 'admin' ? 'badge-blue' : 'badge-gray' }}" style="font-size:.62rem;">{{ $log->role }}</span>
            </div>
        </div>
        @empty
        <div class="lp-empty" style="padding:32px;">Belum ada log aktivitas pada periode ini.</div>
        @endforelse
    </div>

    @if(method_exists($auditLog, 'hasPages') && $auditLog->hasPages())
        <div class="lp-pagination">{{ $auditLog->appends(request()->query())->links() }}</div>
    @endif
</div>

</div>{{-- end .lp --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('grafikBulanan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json(collect($grafikBulanan)->pluck('bulan')),
        datasets: [
            {
                label: 'Sampah (Kg)',
                data: @json(collect($grafikBulanan)->pluck('sampah')),
                backgroundColor: 'rgba(22, 163, 74, 0.15)',
                borderColor: '#16a34a',
                borderWidth: 2,
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Nilai (Rp)',
                data: @json(collect($grafikBulanan)->pluck('nilai')),
                type: 'line',
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#2563eb',
                yAxisID: 'y1',
                borderDash: [],
            },
            {
                label: 'Dicairkan (Rp)',
                data: @json(collect($grafikBulanan)->pluck('cairkan')),
                type: 'line',
                borderColor: '#dc2626',
                borderWidth: 2,
                borderDash: [5, 3],
                tension: 0.4,
                fill: false,
                pointRadius: 3,
                pointBackgroundColor: '#dc2626',
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111827',
                titleFont: { size: 12, weight: 'bold' },
                bodyFont: { size: 11 },
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            y: {
                type: 'linear',
                position: 'left',
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { font: { size: 11 }, color: '#9ca3af' },
                title: { display: true, text: 'Kg', font: { size: 10 }, color: '#9ca3af' }
            },
            y1: {
                type: 'linear',
                position: 'right',
                beginAtZero: true,
                grid: { drawOnChartArea: false },
                ticks: { font: { size: 11 }, color: '#9ca3af', callback: v => 'Rp' + (v/1000).toFixed(0) + 'K' },
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 }, color: '#9ca3af', autoSkip: false, maxRotation: 0 }
            }
        }
    }
});

/* Custom legend */
const legendHtml = `
<div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:10px;font-size:12px;color:#6b7280;">
  <span style="display:flex;align-items:center;gap:5px;"><span style="width:10px;height:10px;border-radius:2px;background:#16a34a;display:inline-block;"></span>Sampah (Kg)</span>
  <span style="display:flex;align-items:center;gap:5px;"><span style="width:10px;height:2px;background:#2563eb;display:inline-block;"></span>Nilai (Rp)</span>
  <span style="display:flex;align-items:center;gap:5px;"><span style="width:10px;height:2px;background:#dc2626;display:inline-block;border-top:2px dashed #dc2626;"></span>Dicairkan (Rp)</span>
</div>`;
document.getElementById('grafikBulanan').closest('.lp-card-body').insertAdjacentHTML('beforeend', legendHtml);

function downloadBulanan(type) {
    const bulan = document.getElementById('bulanPdf').value;
    const tahun = document.getElementById('tahunPdf').value;
    const base = type === 'pdf' ? "{{ url('admin/laporan/pdf-bulanan') }}" : "{{ url('admin/laporan/excel-bulanan') }}";
    window.open(`${base}?bulan=${bulan}&tahun=${tahun}`, '_blank');
}
function downloadTahunan(type) {
    const tahun = document.getElementById('tahunTahunan').value;
    const base = type === 'pdf' ? "{{ route('admin.laporan.pdf-tahunan') }}" : "{{ route('admin.laporan.excel-tahunan') }}";
    window.open(`${base}?tahun=${tahun}`, '_blank');
}
function downloadKartu() {
    const id = document.getElementById('nasabahKartu').value;
    if(!id){ alert('Pilih nasabah terlebih dahulu!'); return; }
    let url = "{{ route('admin.laporan.kartu-tabungan', ['id' => 'ID_PLACEHOLDER']) }}";
    window.open(url.replace('ID_PLACEHOLDER', id), '_blank');
}
function downloadHarian(type) {
    const tgl = document.getElementById('tanggalHarian').value;
    if(!tgl){ alert('Pilih tanggal terlebih dahulu!'); return; }
    const base = type === 'pdf' ? "{{ url('admin/laporan/pdf-harian') }}" : "{{ url('admin/laporan/excel-harian') }}";
    window.open(`${base}?tanggal=${tgl}`, '_blank');
}
function downloadMingguan(type) {
    const tgl = document.getElementById('tanggalMingguan').value;
    if(!tgl){ alert('Pilih tanggal terlebih dahulu!'); return; }
    const base = type === 'pdf' ? "{{ url('admin/laporan/pdf-mingguan') }}" : "{{ url('admin/laporan/excel-mingguan') }}";
    window.open(`${base}?tanggal=${tgl}`, '_blank');
}
</script>
@endpush
