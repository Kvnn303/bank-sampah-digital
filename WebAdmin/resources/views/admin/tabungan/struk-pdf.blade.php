<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Setoran #{{ $tabungan->id }}</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.4;
            padding: 20px;
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
        .kop-instansi { font-size: 12px; color: #333; font-weight: normal; margin-bottom: 2px; }
        .kop-dinas { font-size: 14px; font-weight: bold; color: #185FA5; text-transform: uppercase; }
        .kop-unit { font-size: 16px; font-weight: bold; color: #185FA5; text-transform: uppercase; letter-spacing: 1px; }
        .kop-alamat { font-size: 10px; color: #555; margin-top: 2px; line-height: 1.2; }
        
        .garis-kop { border-bottom: 3px double #185FA5; margin-bottom: 20px; }

        /* JUDUL STRUK */
        .judul-section { text-align: center; margin-bottom: 25px; }
        .judul-section h2 { font-size: 15px; text-transform: uppercase; margin-bottom: 3px; text-decoration: underline; color: #333; }
        .judul-section p { font-size: 11px; color: #555; }

        /* INFO BOX STYLING */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 120px;
            font-weight: bold;
            color: #444;
        }

        /* TABEL ITEM */
        .item-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .item-table th { 
            background: #185FA5; 
            color: white; 
            padding: 8px 5px;
            font-size: 11px;   
            text-align: right; 
            border: 1px solid #185FA5;
            vertical-align: middle;
        }
        .item-table th.text-left { text-align: left; }
        .item-table th.text-center { text-align: center; }
        
        .item-table td { 
            padding: 8px 5px; 
            border: 1px solid #dee2e6; 
            font-size: 11px;   
            vertical-align: middle;
            text-align: right;
        }
        .item-table td.text-left { text-align: left; }
        .item-table td.text-center { text-align: center; }
        .item-table tr:nth-child(even) { background: #f9fbfd; }
        
        .item-table tfoot td { 
            background: #eef2f7; 
            font-weight: bold; 
            font-size: 12px; 
            border-top: 2px solid #185FA5; 
            padding: 10px 5px;
        }

        /* UTILITIES */
        .text-success { color: #2fb344; }
        .text-danger { color: #e63946; }

        /* CATATAN */
        .catatan-box {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 10px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .catatan-box strong { color: #185FA5; }

        /* TANDA TANGAN */
        .ttd-table {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .ttd-table td {
            width: 50%;
            vertical-align: bottom;
        }
        .ttd-space { height: 70px; }
        .ttd-name { font-weight: bold; text-decoration: underline; }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #888;
        }
    </style>
</head>
<body>

    @php
        // Logika untuk mengambil logo dan mengubah ke Base64 (Sama seperti template laporan)
        $path = public_path('image/BankSampahlogo.jpg');
        $base64 = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp

    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                @if($base64)
                    <img src="{{ $base64 }}" class="kop-logo-img" alt="Logo">
                @endif
            </td>
            <td class="kop-text-cell">
                <div class="kop-instansi">PEMERINTAH KABUPATEN SUBANG</div>
                <div class="kop-dinas">DINAS LINGKUNGAN HIDUP</div>
                <div class="kop-unit">BANK SAMPAH DIGITAL SUBANG</div>
                <div class="kop-alamat">Jl. Contoh Alamat No. 123 Subang, Jawa Barat 41212<br/>Telp: (0260) 123456 | Email: dlh@subangkab.go.id</div>
            </td>
        </tr>
    </table>
    
    <div class="garis-kop"></div>

    <div class="judul-section">
        <h2>Bukti Setoran Sampah</h2>
        <p>No. Referensi: <strong>TRX-{{ str_pad($tabungan->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Nasabah</td>
            <td>: <strong>{{ $tabungan->nasabah->nama_lengkap ?? '-' }}</strong></td>
            <td class="label">Tanggal Transaksi</td>
            <td>: {{ $tabungan->tanggal_setor->format('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">No. Rekening</td>
            <td>: {{ $tabungan->nasabah->no_rekening ?? '-' }}</td>
            <td class="label">Petugas Admin</td>
            <td>: {{ $tabungan->admin->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Telepon</td>
            <td colspan="3">: {{ $tabungan->nasabah->no_telepon ?? '-' }}</td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th class="text-left" style="width: 45%;">Jenis Sampah (Kategori)</th>
                <th style="width: 15%;">Harga / Kg</th>
                <th style="width: 15%;">Berat (Kg)</th>
                <th style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-left">
                    <strong>{{ $tabungan->jenisSampah->nama ?? '-' }}</strong><br>
                    <span style="font-size: 9px; color:#555;">Kategori: {{ ucfirst($tabungan->jenisSampah->kategori ?? '-') }}</span>
                </td>
                <td>Rp {{ number_format($tabungan->harga_per_kg_saat_itu, 0, ',', '.') }}</td>
                <td>{{ number_format($tabungan->berat_kg, 2, ',', '.') }}</td>
                <td>Rp {{ number_format($tabungan->nilai_rupiah, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTAL NILAI SETORAN</td>
                <td class="text-success">Rp {{ number_format($tabungan->nilai_rupiah, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    @if($tabungan->catatan)
    <div class="catatan-box">
        <strong>Catatan Tambahan:</strong><br>
        {{ $tabungan->catatan }}
    </div>
    @endif

    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Nasabah Penyetor</strong>
                <div class="ttd-space"></div>
                <div class="ttd-name">{{ $tabungan->nasabah->nama_lengkap ?? '....................................' }}</div>
            </td>
            <td>
                Subang, {{ $tabungan->tanggal_setor->format('d F Y') }}<br>
                <strong>Petugas Bank Sampah</strong>
                <div class="ttd-space"></div>
                <div class="ttd-name">{{ $tabungan->admin->name ?? '....................................' }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <i>Dokumen ini adalah bukti sah transaksi di Bank Sampah Digital Kabupaten Subang. Simpan bukti ini dengan baik.<br>
        Dicetak otomatis oleh Sistem pada {{ now()->format('d/m/Y H:i:s') }}</i>
    </div>

</body>
</html>