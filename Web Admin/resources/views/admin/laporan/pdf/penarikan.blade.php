<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penarikan</title>
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
            width: 33.33%; 
            padding: 10px; 
            text-align: center; 
            border-right: 1px solid #dee2e6;
        }
        .ringkasan-item:last-child { border-right: none; }
        .ringkasan-item .label { font-size: 8px; color: #555; text-transform: uppercase; margin-bottom: 3px; }
        .ringkasan-item .nilai { font-size: 14px; font-weight: bold; color: #185FA5; }
        
        /* TABEL STYLING */
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
        .text-danger { color: #e63946; }

        /* BADGES */
        .badge { padding: 2px 5px; border-radius: 2px; font-size: 7px; text-transform: uppercase; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-info    { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

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
        <h2>Laporan Penarikan Saldo</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($dariTgl)->format('d F Y') }} s.d. {{ \Carbon\Carbon::parse($sampaiTgl)->format('d F Y') }}</p>
    </div>

    <!-- RINGKASAN STATISTIK -->
    <div class="ringkasan">
        <div class="ringkasan-item">
            <div class="label">Total Pengajuan</div>
            <div class="nilai">{{ $penarikan->count() }}</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Total Nominal Diajukan</div>
            <div class="nilai" style="color: #e63946;">Rp {{ number_format($totalNominal) }}</div>
        </div>
        <div class="ringkasan-item">
            <div class="label">Total Dana Dicairkan</div>
            <div class="nilai" style="color: #2fb344;">Rp {{ number_format($totalSelesai) }}</div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 4%;">No</th>
                <th style="width: 12%;">Tgl Ajuan</th>
                <th style="width: 18%;">Nama Nasabah</th>
                <th style="width: 12%;">No Telepon</th>
                <th class="text-right" style="width: 14%;">Nominal (Rp)</th>
                <th style="width: 10%;">Tgl Ambil</th>
                <th class="text-center" style="width: 8%;">Status</th>
                <th style="width: 12%;">Diproses Oleh</th>
                <th style="width: 10%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penarikan as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                <td><strong>{{ $p->nasabah->nama_lengkap ?? '-' }}</strong></td>
                <td>{{ $p->nasabah->no_telepon ?? '-' }}</td>
                <td class="text-right text-danger">{{ number_format($p->nominal) }}</td>
                <td>{{ $p->tanggal_ambil ? $p->tanggal_ambil->format('d/m/Y') : '-' }}</td>
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
                <td>{{ $p->catatan_admin ?? $p->alasan_penolakan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right text-danger">Rp {{ number_format($totalNominal) }}</td>
                <td colspan="3" class="text-left" style="font-size: 8px; font-weight: normal;">Total Dicairkan: Rp {{ number_format($totalSelesai) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <i>Dokumen ini digenerate secara otomatis oleh Sistem Bank Sampah Digital Kabupaten Subang pada {{ now()->format('d/m/Y H:i:s') }}</i>
    </div>

</body>
</html>