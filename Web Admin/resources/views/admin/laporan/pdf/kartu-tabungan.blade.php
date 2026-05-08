<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Tabungan - {{ $nasabah->nama_lengkap }}</title>
    <style>
        /*
         * CSS ini dioptimasi untuk DomPDF:
         * - Tidak ada flexbox / grid (tidak didukung DomPDF)
         * - Tidak ada linear-gradient (tidak didukung DomPDF)
         * - Semua layout pakai float atau display:table
         * - Font hanya DejaVu / sans-serif bawaan DomPDF
         */

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #2d2d2d;
            line-height: 1.5;
            background: #fff;
        }

        .container {
            width: 100%;
            padding: 12mm 14mm;
        }

        /* ── HEADER ─────────────────────────────────────── */
        .header {
            width: 100%;
            border-bottom: 2.5px solid #2fb344;
            padding-bottom: 10px;
            margin-bottom: 12px;
            overflow: hidden; /* clearfix */
        }

        .header-logo {
            float: left;
            width: 55px;
        }

        .header-logo img {
            width: 52px;
            height: auto;
        }

        .header-text {
            margin-left: 65px; /* bukan float agar tidak overlap */
        }

        .header-text h1 {
            font-size: 15px;
            color: #2fb344;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .header-text h2 {
            font-size: 11px;
            color: #333;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .header-text p {
            font-size: 8.5px;
            color: #666;
        }

        .header-badge {
            float: right;
            text-align: right;
            font-size: 8px;
            color: #888;
            margin-top: 4px;
        }

        /* ── PROFIL NASABAH ──────────────────────────────── */
        .profile-card {
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            padding: 9px 12px;
            margin-bottom: 12px;
            background: #f8f9fa;
        }

        .profile-title {
            font-size: 9px;
            font-weight: bold;
            color: #2fb344;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 1px dashed #bbb;
            padding-bottom: 4px;
            margin-bottom: 7px;
        }

        /* Tabel profil 2 kolom */
        .profile-table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-table td {
            font-size: 9.5px;
            padding: 2.5px 0;
            vertical-align: top;
            width: 50%;
        }

        .lbl {
            color: #555;
            display: inline-block;
            width: 80px;
        }

        .val {
            font-weight: bold;
            color: #111;
        }

        /* ── SALDO BOX ───────────────────────────────────── */
        .balance-wrap {
            border: 2px solid #2fb344;
            border-radius: 5px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .balance-head {
            background: #2fb344;
            color: #fff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 5px 10px;
        }

        .balance-body {
            background: #f0fff4;
            padding: 12px 10px 10px;
            text-align: center;
            border-bottom: 1px solid #c3e6cb;
        }

        .balance-label {
            font-size: 8.5px;
            color: #555;
            margin-bottom: 3px;
        }

        .balance-amount {
            font-size: 22px;
            font-weight: bold;
            color: #1a6e2e;
        }

        /* 4 stat di bawah saldo — pakai display:table */
        .stat-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .stat-cell {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 7px 4px;
            border-right: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        .stat-cell:last-child { border-right: none; }

        .stat-lbl {
            font-size: 7.5px;
            color: #777;
            display: block;
            margin-bottom: 2px;
        }

        .stat-val {
            font-size: 10.5px;
            font-weight: bold;
            color: #333;
            display: block;
        }

        .c-green  { color: #1a7d34; }
        .c-red    { color: #c0392b; }

        /* ── SECTION TITLE ───────────────────────────────── */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            background: #4a4a4a;
            padding: 4px 8px;
            margin: 14px 0 0;
            border-radius: 3px 3px 0 0;
        }

        /* ── TABEL RIWAYAT ───────────────────────────────── */
        table.tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin-bottom: 6px;
        }

        table.tbl th {
            background: #f0f0f0;
            color: #333;
            font-weight: bold;
            padding: 5px 5px;
            border: 1px solid #ccc;
            text-align: left;
        }

        table.tbl td {
            padding: 4px 5px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
            color: #2d2d2d;
        }

        table.tbl tr:nth-child(even) td { background: #fafafa; }

        table.tbl tfoot td {
            background: #f0f0f0;
            font-weight: bold;
            border: 1px solid #ccc;
        }

        table.tbl td.empty {
            text-align: center;
            color: #999;
            padding: 10px;
        }

        .tr { text-align: right; }
        .tc { text-align: center; }

        /* ── BADGE STATUS ────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
        }

        .b-success { background: #d4edda; color: #155724; border: 1px solid #b8dbbe; }
        .b-warning { background: #fff3cd; color: #7d5a00; border: 1px solid #f0d785; }
        .b-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f0b4b8; }
        .b-info    { background: #d1ecf1; color: #0c5460; border: 1px solid #9fd5de; }

        /* ── TANDA TANGAN ────────────────────────────────── */
        .sign-section {
            margin-top: 28px;
            width: 100%;
            overflow: hidden;
        }

        .sign-box {
            float: left;
            width: 48%;
            text-align: center;
        }

        .sign-box.right { float: right; }

        .sign-space { height: 55px; }

        .sign-line {
            display: inline-block;
            width: 65%;
            border-top: 1px solid #333;
            padding-top: 3px;
            font-size: 9.5px;
            font-weight: bold;
            color: #111;
        }

        .sign-role {
            font-size: 8.5px;
            color: #555;
            margin-top: 1px;
        }

        /* ── FOOTER ──────────────────────────────────────── */
        .doc-footer {
            margin-top: 22px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            text-align: center;
            font-size: 7.5px;
            color: #999;
        }

        /* ── PAGE BREAK ──────────────────────────────────── */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
@php
    $logoPath = public_path('image/BankSampahlogo.jpg');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $ext = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    $totalMasuk    = $nasabah->tabungan->sum('nilai_rupiah');
    $totalKeluar   = $nasabah->penarikan->whereIn('status', ['selesai', 'diproses'])->sum('nominal');
    $totalKeluar   = $nasabah->penarikan->where('status', 'selesai')->sum('nominal');
    $totalBerat    = $nasabah->tabungan->sum('berat_kg');
    $totalTrx      = $nasabah->tabungan->count();
    $saldo         = $nasabah->saldo ?? ($totalMasuk - $totalKeluar);
@endphp

<div class="container">

    {{-- ═══ HEADER ═══════════════════════════════════════ --}}
    <div class="header">
        @if($logoBase64)
            <div class="header-logo">
                <img src="{{ $logoBase64 }}" alt="Logo">
            </div>
        @endif
        <div class="header-text">
            <h1>Bank Sampah Digital Subang</h1>
            <h2>Kartu Tabungan Nasabah</h2>
            <p>Dinas Lingkungan Hidup Kabupaten Subang</p>
        </div>
        <div class="header-badge">
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- ═══ PROFIL NASABAH ════════════════════════════════ --}}
    <div class="profile-card">
        <div class="profile-title">&#9608; Identitas Nasabah</div>
        <table class="profile-table">
            <tr>
                <td>
                    <span class="lbl">Nama Lengkap</span>:
                    <span class="val">{{ $nasabah->nama_lengkap }}</span>
                </td>
                <td>
                    <span class="lbl">Status Akun</span>:
                    <span class="val">{{ ucfirst($nasabah->status_akun) }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="lbl">No. KTP</span>:
                    {{ $nasabah->no_ktp ?? '-' }}
                </td>
                <td>
                    <span class="lbl">No. Telepon</span>:
                    {{ $nasabah->no_telepon ?? '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="lbl">Alamat</span>:
                    {{ $nasabah->alamat ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="lbl">Tgl Bergabung</span>:
                    {{ $nasabah->tanggal_bergabung ? \Carbon\Carbon::parse($nasabah->tanggal_bergabung)->format('d M Y') : '-' }}
                </td>
                <td>
                    <span class="lbl">Sumber Daftar</span>:
                    {{ ucfirst($nasabah->sumber_daftar ?? '-') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══ SALDO BOX ══════════════════════════════════════ --}}
    <div class="balance-wrap">
        <div class="balance-head">&#9608; Ringkasan Saldo</div>
        <div class="balance-body">
            <div class="balance-label">Saldo Aktif Saat Ini</div>
            <div class="balance-amount">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        </div>
        <div class="stat-row">
            <div class="stat-cell">
                <span class="stat-lbl">Total Sampah</span>
                <span class="stat-val c-green">{{ number_format($totalBerat, 1) }} Kg</span>
            </div>
            <div class="stat-cell">
                <span class="stat-lbl">Total Masuk</span>
                <span class="stat-val c-green">+Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
            </div>
            <div class="stat-cell">
                <span class="stat-lbl">Total Keluar</span>
                <span class="stat-val c-red">-Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
            </div>
            <div class="stat-cell">
                <span class="stat-lbl">Jml Setoran</span>
                <span class="stat-val">{{ $totalTrx }} Kali</span>
            </div>
        </div>
    </div>

    {{-- ═══ RIWAYAT SETORAN ════════════════════════════════ --}}
    <div class="section-title">&#9608; Riwayat Setoran Sampah</div>
    <table class="tbl">
        <thead>
            <tr>
                <th class="tc" style="width:4%">No</th>
                <th style="width:13%">Tanggal</th>
                <th style="width:33%">Jenis Sampah</th>
                <th class="tr" style="width:12%">Berat (Kg)</th>
                <th class="tr" style="width:16%">Harga/Kg</th>
                <th class="tr" style="width:22%">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nasabah->tabungan->sortByDesc('tanggal_setor') as $i => $t)
            <tr>
                <td class="tc">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal_setor)->format('d/m/Y') }}</td>
                <td>{{ $t->jenisSampah->nama ?? '-' }}</td>
                <td class="tr">{{ number_format($t->berat_kg, 2) }}</td>
                <td class="tr">{{ number_format($t->harga_per_kg_saat_itu, 0, ',', '.') }}</td>
                <td class="tr c-green">{{ number_format($t->nilai_rupiah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty">Belum ada riwayat setoran</td>
            </tr>
            @endforelse
        </tbody>
        @if($nasabah->tabungan->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3" class="tr">TOTAL SETORAN</td>
                <td class="tr">{{ number_format($totalBerat, 2) }} Kg</td>
                <td></td>
                <td class="tr">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- ═══ RIWAYAT PENARIKAN ══════════════════════════════ --}}
    <div class="section-title" style="margin-top:16px">&#9608; Riwayat Penarikan Dana</div>
    <table class="tbl">
        <thead>
            <tr>
                <th class="tc" style="width:4%">No</th>
                <th style="width:13%">Tgl Ajuan</th>
                <th style="width:13%">Tgl Cair</th>
                <th class="tr" style="width:20%">Nominal (Rp)</th>
                <th class="tc" style="width:13%">Status</th>
                <th style="width:37%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nasabah->penarikan->sortByDesc('created_at') as $i => $p)
            <tr>
                <td class="tc">{{ $i + 1 }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                <td>{{ $p->tanggal_ambil ? \Carbon\Carbon::parse($p->tanggal_ambil)->format('d/m/Y') : '-' }}</td>
                <td class="tr c-red">{{ number_format($p->nominal, 0, ',', '.') }}</td>
                <td class="tc">
                    @if($p->status === 'selesai')
                        <span class="badge b-success">Selesai</span>
                    @elseif($p->status === 'pending')
                        <span class="badge b-warning">Pending</span>
                    @elseif($p->status === 'ditolak')
                        <span class="badge b-danger">Ditolak</span>
                    @else
                        <span class="badge b-info">Diproses</span>
                    @endif
                </td>
                <td>{{ $p->catatan_admin ?? $p->alasan_penolakan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty">Belum ada riwayat penarikan</td>
            </tr>
            @endforelse
        </tbody>
        @if($nasabah->penarikan->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3" class="tr">TOTAL DICAIRKAN</td>
                <td class="tr">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- ═══ TANDA TANGAN ═══════════════════════════════════ --}}
    <div class="sign-section">
        <div class="sign-box">
            <div class="sign-space"></div>
            <div>
                <span class="sign-line">{{ $nasabah->nama_lengkap }}</span>
                <div class="sign-role">Nasabah</div>
            </div>
        </div>
        <div class="sign-box right">
            <div class="sign-role">Subang, {{ now()->translatedFormat('d F Y') }}</div>
            <div class="sign-space"></div>
            <div>
                <span class="sign-line">{{ auth()->user()->name ?? 'Admin Bank Sampah' }}</span>
                <div class="sign-role">Petugas Bank Sampah</div>
            </div>
        </div>
    </div>

    {{-- ═══ FOOTER DOKUMEN ══════════════════════════════════ --}}
    <div class="doc-footer">
        Dokumen ini dicetak secara otomatis oleh Sistem Bank Sampah Digital Subang
        dan dinyatakan sah tanpa tanda tangan basah. &nbsp;|&nbsp;
        Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB
    </div>

</div>
</body>
</html>
