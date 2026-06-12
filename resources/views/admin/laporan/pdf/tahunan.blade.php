<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Tahunan {{ $tahun }}</title>
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
        .judul-section h2 { font-size: 13px; text-transform: uppercase; margin-bottom: 2px; text-decoration: underline; }
        .judul-section p { font-size: 10px; color: #333; }

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
            vertical-align: top;
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

        // PERBAIKAN: Definisikan tanggal manual untuk Laporan Tahunan
        $dariTgl = $tahun . '-01-01';
        $sampaiTgl = $tahun . '-12-31';
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
                <div class="kop-unit">BANK SAMPAH INDUK SUBANG</div>
                <div class="kop-alamat">Jl. Dewi Sartika No. 11 Subang, Jawa Barat 41212<br/>Telp: (0260) 411267 | Email: dlh@subangkab.go.id</div>
            </td>
        </tr>
    </table>
    
    <div class="garis-kop"></div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-section">
        <h2>Rekap Laporan Tahunan</h2>
        <p>Tahun: {{ $tahun }}</p>
        <p class="sub" style="font-weight: normal; font-size: 9px;">Periode: 01 Januari {{ $tahun }} s.d. 31 Desember {{ $tahun }}</p>
    </div>

    <!-- TABEL A: REKAP PER BULAN -->
    <div class="section-title">A. Rekapitulasi Kegiatan Per Bulan</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 15%;">Bulan</th>
                <th class="text-right" style="width: 15%;">Sampah (Kg)</th>
                <th class="text-right" style="width: 20%;">Nilai Masuk (Rp)</th>
                <th class="text-center" style="width: 10%;">Transaksi</th>
                <th class="text-right" style="width: 20%;">Dana Dicairkan (Rp)</th>
                <th class="text-center" style="width: 10%;">Nasabah Baru</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapPerBulan as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td><strong>{{ $r['bulan'] }}</strong></td>
                <td class="text-right">{{ number_format($r['total_kg'], 2) }}</td>
                <td class="text-right text-success">{{ number_format($r['total_nilai']) }}</td>
                <td class="text-center">{{ $r['total_transaksi'] }}</td>
                <td class="text-right text-danger">{{ number_format($r['total_dicairkan']) }}</td>
                <td class="text-center">{{ $r['nasabah_baru'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right">TOTAL TAHUN {{ $tahun }}</td>
                <td class="text-right">{{ number_format(collect($rekapPerBulan)->sum('total_kg'), 2) }} Kg</td>
                <td class="text-right">Rp {{ number_format(collect($rekapPerBulan)->sum('total_nilai')) }}</td>
                <td class="text-center">{{ collect($rekapPerBulan)->sum('total_transaksi') }}</td>
                <td class="text-right">Rp {{ number_format(collect($rekapPerBulan)->sum('total_dicairkan')) }}</td>
                <td class="text-center">{{ collect($rekapPerBulan)->sum('nasabah_baru') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- TABEL B: REKAP PER JENIS -->
    <div class="section-title">B. Rekapitulasi Per Jenis Sampah</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 25%;">Jenis Sampah</th>
                <th style="width: 15%;">Kategori</th>
                <th class="text-right" style="width: 15%;">Total (Kg)</th>
                <th class="text-center" style="width: 10%;">Transaksi</th>
                <th class="text-right" style="width: 20%;">Total Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapJenis as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $r->jenisSampah->nama ?? '-' }}</td>
                <td>{{ $r->jenisSampah->kategori ?? '-' }}</td>
                <td class="text-right">{{ number_format($r->total_kg, 2) }}</td>
                <td class="text-center">{{ $r->total_transaksi }}</td>
                <td class="text-right text-success">{{ number_format($r->total_nilai) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right">{{ number_format($rekapJenis->sum('total_kg'), 2) }} Kg</td>
                <td class="text-center">{{ $rekapJenis->sum('total_transaksi') }}</td>
                <td class="text-right">Rp {{ number_format($rekapJenis->sum('total_nilai')) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- TANDA TANGAN -->
    <div class="ttd-section">
        <table class="ttd-table">
            <tr>
                <td>
                    <!-- Kolom Kiri -->
                    <div class="ttd-title">Mengetahui,</div>
                    <div class="ttd-title">Kepala Dinas Lingkungan Hidup</div>
                    <br><br><br><br>
                    <div class="ttd-name">(...................................)</div>
                    <div class="ttd-nip">NIP. ........................</div>
                </td>
                <td>
                    <!-- Kolom Kanan -->
                    <div class="ttd-title">Subang, {{ now()->translatedFormat('d F Y') }}</div>
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