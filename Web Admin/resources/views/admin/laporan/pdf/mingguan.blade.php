<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mingguan Bank Sampah</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }

        /* KOP SURAT */
        .kop-table {
            width: 100%;
            margin-bottom: 5px;
            border-collapse: collapse;
        }
        .kop-table td { vertical-align: top; }
        .kop-logo-cell { width: 80px; text-align: left; padding-right: 10px; }
        .kop-logo-img { width: 65px; height: auto; }
        .kop-text-cell { text-align: center; }
        .kop-instansi { font-size: 11px; color: #333; font-weight: normal; margin-bottom: 2px; }
        .kop-dinas { font-size: 13px; font-weight: bold; color: #185FA5; text-transform: uppercase; }
        .kop-unit { font-size: 14px; font-weight: bold; color: #185FA5; text-transform: uppercase; letter-spacing: 1px; }
        .kop-alamat { font-size: 9px; color: #555; margin-top: 2px; line-height: 1.2; }

        .garis-kop { border-bottom: 3px double #185FA5; margin-bottom: 15px; }

        /* JUDUL LAPORAN */
        .judul-section { text-align: center; margin-bottom: 15px; }
        .judul-section h2 { font-size: 13px; text-transform: uppercase; margin-bottom: 2px; }
        .judul-section p { font-size: 10px; color: #333; font-weight: bold; }
        .judul-section .sub { font-weight: normal; color: #666; font-size: 9px; margin-top: 2px; }

        /* RINGKASAN STATISTIK */
        .ringkasan {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        .ringkasan-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #dee2e6;
        }
        .ringkasan-item:last-child { border-right: none; }
        .ringkasan-item .label { font-size: 8px; color: #555; text-transform: uppercase; margin-bottom: 3px; }
        .ringkasan-item .nilai { font-size: 14px; font-weight: bold; color: #185FA5; }
        .ringkasan-item .sub { font-size: 8px; color: #777; font-style: italic; margin-top: 2px; }

        /* TABEL STYLING */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            background: #eef2f7;
            padding: 5px 8px;
            border-left: 4px solid #185FA5;
            margin: 12px 0 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background: #185FA5;
            color: white;
            padding: 6px 5px;
            font-size: 9px;
            text-align: left;
            border: 1px solid #185FA5;
        }
        td {
            padding: 5px;
            border: 1px solid #dee2e6;
            font-size: 8px;
        }
        tr:nth-child(even) { background: #f9fbfd; }

        tfoot td {
            background: #eef2f7;
            font-weight: bold;
            font-size: 9px;
            border-top: 2px solid #185FA5;
        }

        /* UTILITIES */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #2fb344; }
        .text-danger { color: #e63946; }

        /* BADGES */
        .badge { padding: 2px 5px; border-radius: 2px; font-size: 7px; text-transform: uppercase; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-info    { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        /* TANDA TANGAN */
        .ttd-section {
            margin-top: 30px;
            width: 100%;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .ttd-table td {
            border: none;
            padding: 0;
            width: 50%;
            vertical-align: top;
            text-align: center;
        }
        .ttd-title { font-size: 10px; margin-bottom: 3px; }
        .ttd-name { font-weight: bold; border-bottom: 1px solid #333; display: inline-block; width: 70%; padding-top: 40px; margin-bottom: 3px; }
        .ttd-nip { font-size: 9px; color: #555; }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            text-align: center;
            font-size: 8px;
            color: #888;
        }

        .no-data {
            text-align: center;
            color: #888;
            padding: 10px;
            font-style: italic;
            border: 1px solid #eee;
            margin-bottom: 10px;
            font-size: 9px;
        }
    </style>
</head>
<body>

    @php
        // Logika untuk mengambil logo dan mengubah ke Base64
        $path = public_path('image/BankSampahlogo.jpg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $base64 = '';
        if (file_exists($path)) {
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // [FIX] Logika fallback jika variabel $tanggalFormat tidak ada dari Controller
        $tanggalTampil = $tanggalFormat ?? date('d F Y');
    @endphp

    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                @if($base64)
                    <img src="{{ $base64 }}" class="kop-logo-img" alt="Logo">
                @endif
            </td>
            <td class="kop-text-cell">
                <div class="kop-instansi">PEMERINTAH KABUPATEN SUBANG</div>
                <div class="kop-instansi">DINAS LINGKUNGAN HIDUP</div>
                <div class="kop-unit">BANK SAMPAH UNIT SUBANG</div>
                <div class="kop-alamat">Jl. Dewi Sartika No. 11 Subang, Jawa Barat 41212<br/>Telp: (0260) 411267 | Email: dlh@subangkab.go.id</div>
            </td>
        </tr>
    </table>

    <div class="garis-kop"></div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-section">
        <h2>Laporan Mingguan Kegiatan Bank Sampah</h2>
        <p>Periode : {{ $tanggalTampil }}</p>
        <p class="sub">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- RINGKASAN STATISTIK -->
    <div class="ringkasan">
        <div class="ringkasan-item">
            <div class="label">Total Transaksi</div>
            <div class="nilai">{{ $tabungan->count() }}</div>
            <div class="sub">Kali Setor</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Sampah Terkumpul</div>
            <div class="nilai">{{ number_format($totalKg, 2) }} Kg</div>
            <div class="sub">Berat Total</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Nilai Tabungan</div>
            <div class="nilai" style="color: #2fb344;">Rp {{ number_format($totalNilai) }}</div>
            <div class="sub">Pendapatan</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Dana Dicairkan</div>
            <div class="nilai" style="color: #e63946;">Rp {{ number_format($totalDicairkan) }}</div>
            <div class="sub">Penarikan</div>
        </div>
    </div>

    <!-- TABEL A: REKAP JENIS SAMPAH -->
    <div class="section-title">A. Rekapitulasi Per Jenis Sampah</div>
    @if($rekapJenis->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 25%;">Jenis Sampah</th>
                <th style="width: 15%;">Kategori</th>
                <th class="text-right" style="width: 15%;">Berat (Kg)</th>
                <th class="text-center" style="width: 10%;">Transaksi</th>
                <th class="text-right" style="width: 20%;">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapJenis as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td><strong>{{ $r->jenisSampah->nama ?? '-' }}</strong></td>
                <td>{{ $r->jenisSampah->kategori ?? '-' }}</td>
                <td class="text-right">{{ number_format($r->total_kg, 2) }}</td>
                <td class="text-center">{{ $r->total_transaksi }}x</td>
                <td class="text-right text-success">{{ number_format($r->total_nilai) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($rekapJenis->sum('total_kg'), 2) }} Kg</td>
                <td class="text-center">{{ $rekapJenis->sum('total_transaksi') }}x</td>
                <td class="text-right">Rp {{ number_format($totalNilai) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
        <p class="no-data">Tidak ada transaksi tabungan pada periode ini</p>
    @endif

    <!-- TABEL B: DETAIL TABUNGAN -->
    <div class="section-title">B. Detail Transaksi Tabungan</div>
    @if($tabungan->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 4%;">No</th>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 18%;">Nama Nasabah</th>
                <th style="width: 14%;">No KTP</th>
                <th style="width: 15%;">Jenis Sampah</th>
                <th class="text-right" style="width: 9%;">Berat</th>
                <th class="text-right" style="width: 10%;">Harga</th>
                <th class="text-right" style="width: 12%;">Nilai</th>
                <th style="width: 10%;">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabungan as $i => $t)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $t->created_at->format('d/m H:i') }}</td>
                <td>{{ $t->nasabah->nama_lengkap ?? '-' }}</td>
                <td>{{ $t->nasabah->no_ktp ?? '-' }}</td>
                <td>{{ $t->jenisSampah->nama ?? '-' }}</td>
                <td class="text-right">{{ number_format($t->berat_kg, 2) }}</td>
                <td class="text-right">{{ number_format($t->harga_per_kg_saat_itu) }}</td>
                <td class="text-right text-success">{{ number_format($t->nilai_rupiah) }}</td>
                <td>{{ $t->admin->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL PERIODE</td>
                <td class="text-right">{{ number_format($totalKg, 2) }} Kg</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($totalNilai) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @else
        <p class="no-data">Tidak ada transaksi tabungan pada periode ini</p>
    @endif

    <!-- TABEL C: DETAIL PENARIKAN -->
    <div class="section-title">C. Detail Penarikan Saldo</div>
    @if($penarikan->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 22%;">Nama Nasabah</th>
                <th style="width: 15%;">No Telepon</th>
                <th class="text-right" style="width: 15%;">Nominal (Rp)</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Diproses Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penarikan as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $p->created_at->format('d/m H:i') }}</td>
                <td>{{ $p->nasabah->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->nasabah->no_telepon ?? '-' }}</td>
                <td class="text-right text-danger">{{ number_format($p->nominal) }}</td>
                <td class="text-center">
                    @if($p->status == 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @elseif($p->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($p->status == 'ditolak')
                        <span class="badge badge-danger">Ditolak</span>
                    @else
                        <span class="badge badge-info">Diproses</span>
                    @endif
                </td>
                <td>{{ $p->diprosesoleh->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTAL PENCAIRAN</td>
                <td class="text-right">Rp {{ number_format($totalDicairkan) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    @else
        <p class="no-data">Tidak ada penarikan pada periode ini</p>
    @endif

    <!-- TANDA TANGAN -->
    <div class="ttd-section">
        <table class="ttd-table">
            <tr>
                <td>
                    <div class="ttd-title">Mengetahui,</div>
                    <div class="ttd-title">Kepala Dinas Lingkungan Hidup</div>
                    <br><br><br><br>
                    <div class="ttd-name">(...................................)</div>
                    <div class="ttd-nip">NIP. ........................</div>
                </td>
                <td>
                    <!-- [FIX] Menggunakan variabel aman $tanggalTampil -->
                    <div class="ttd-title">Subang, {{ now()->format('d F Y') }}</div>
                    <div class="ttd-title">Petugas Bank Sampah</div>
                    <br><br><br><br>
                    <div class="ttd-name">{{ auth()->user()->name ?? '(...................................)' }}</div>
                    <div class="ttd-nip">NIP. ........................</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <i>Laporan ini digenerate secara otomatis oleh Sistem Bank Sampah Digital Kabupaten Subang pada {{ now()->format('d/m/Y H:i:s') }}</i>
    </div>

</body>
</html>
