<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Data Nasabah</title>
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
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px; 
        }
        th { 
            background: #185FA5; 
            color: white; 
            padding: 5px 3px;
            font-size: 8px;   
            text-align: left; 
            border: 1px solid #185FA5;
            vertical-align: middle;
        }
        td { 
            padding: 4px 3px; 
            border: 1px solid #dee2e6; 
            font-size: 8px;   
            vertical-align: top;
        }
        tr:nth-child(even) { background: #f9fbfd; }
        
        tfoot td { 
            background: #eef2f7; 
            font-weight: bold; 
            font-size: 8px; 
            border-top: 2px solid #185FA5; 
        }

        /* UTILITIES */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #2fb344; }
        .text-danger { color: #e63946; }

        /* STATUS BADGE */
        .badge {
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 7px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .badge-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

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
        <h2>Rekap Data Nasabah</h2>
        <p>Per Tanggal: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 3%;">No</th>
                <th style="width: 12%;">Nama Lengkap</th>
                <th style="width: 10%;">No KTP</th>
                <th style="width: 8%;">No Telepon</th>
                <th style="width: 15%;">Alamat</th>
                <th class="text-center" style="width: 7%;">Tgl Gabung</th>
                <th class="text-center" style="width: 6%;">Status</th>
                <th class="text-right" style="width: 7%;">Sampah (Kg)</th>
                <th class="text-right" style="width: 10%;">Tabungan (Rp)</th>
                <th class="text-right" style="width: 10%;">Penarikan (Rp)</th>
                <th class="text-right" style="width: 10%;">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nasabah as $i => $n)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td><strong>{{ $n->nama_lengkap }}</strong></td>
                <td>{{ $n->no_ktp ?? '-' }}</td>
                <td>{{ $n->no_telepon ?? '-' }}</td>
                <td>{{ $n->alamat ?? '-' }}</td>
                <td class="text-center">{{ $n->tanggal_bergabung ? $n->tanggal_bergabung->format('d/m/y') : '-' }}</td>
                
                <!-- PERBAIKAN STATUS: Menggunakan strtolower agar tidak case sensitive -->
                <td class="text-center">
                    @php
                        // Mengubah status menjadi huruf kecil semua untuk pengecekan
                        $status = strtolower($n->status_akun ?? '');
                    @endphp

                    @if($status == 'aktif' || $status == 'active')
                        <span class="badge badge-success">Aktif</span>
                    @elseif($status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @else
                        <span class="badge badge-danger">Nonaktif</span>
                    @endif
                </td>
                
                <td class="text-right">{{ number_format($n->total_sampah, 1) }}</td>
                <td class="text-right">{{ number_format($n->tabungan->sum('nilai_rupiah')) }}</td>
                <td class="text-right text-danger">{{ number_format($n->penarikan->whereIn('status',['selesai','diproses'])->sum('nominal')) }}</td>
                <td class="text-right text-success"><strong>{{ number_format($n->saldo) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right">TOTAL KESELURUHAN ({{ $nasabah->count() }} Nasabah)</td>
                <td class="text-right">{{ number_format($nasabah->sum('total_sampah'), 1) }} Kg</td>
                <td class="text-right">Rp {{ number_format($nasabah->sum(fn($n) => $n->tabungan->sum('nilai_rupiah'))) }}</td>
                <td class="text-right">Rp {{ number_format($nasabah->sum(fn($n) => $n->penarikan->whereIn('status',['selesai','diproses'])->sum('nominal'))) }}</td>
                <td class="text-right text-success">Rp {{ number_format($nasabah->sum(fn($n) => $n->saldo)) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <i>Dokumen ini digenerate secara otomatis oleh Sistem Bank Sampah Digital Kabupaten Subang pada {{ now()->format('d/m/Y H:i:s') }}</i>
    </div>

</body>
</html>