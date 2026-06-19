<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use App\Traits\NotifiableTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenarikanController extends Controller
{
    use NotifiableTrait;

    // GET semua pengajuan penarikan
    public function index(Request $request)
    {
        $query = Penarikan::with(['nasabah', 'diprosesoleh'])
                    ->orderByDesc('created_at');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $penarikan = $query->paginate(10);

        return response()->json($penarikan);
    }

    // GET detail penarikan
    public function show($id)
    {
        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                        ->findOrFail($id);

        return response()->json($penarikan);
    }

    // PUT validasi penarikan → setujui
    public function setujui(Request $request, $id)
    {
        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        // Cek status masih pending
        if ($penarikan->status !== 'pending') {
            return response()->json([
                'message' => 'Penarikan ini sudah diproses sebelumnya'
            ], 400);
        }

        $nasabah = $penarikan->nasabah;

        // Hitung saldo manual agar tidak Error 500
        $totalTabungan = Tabungan::where('nasabah_id', $nasabah->id)->sum('nilai_rupiah');

        // Hitung penarikan lain yang sudah selesai (selain ID ini)
        $totalPenarikanLain = Penarikan::where('nasabah_id', $nasabah->id)
                                ->where('status', 'selesai')
                                ->where('id', '!=', $penarikan->id)
                                ->sum('nominal');

        $saldoTersedia = $totalTabungan - $totalPenarikanLain;

        // Cek saldo nasabah cukup
        if ($saldoTersedia < $penarikan->nominal) {
            return response()->json([
                'message' => 'Saldo nasabah tidak mencukupi',
                'saldo'   => $saldoTersedia,
                'nominal' => $penarikan->nominal,
            ], 400);
        }

        $penarikan->update([
            'status'         => 'diproses',
            'diproses_oleh'  => $request->user()->id,
            'tanggal_proses' => now(),
            'catatan_admin'  => $request->catatan_admin,
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');

        AuditLogService::log(
            action: 'PENARIKAN_SETUJUI',
            module: 'Penarikan',
            description: "Admin menyetujui penarikan {$nominal} untuk nasabah {$nasabah->nama_lengkap}",
            oldData: ['status' => 'pending'],
            newData: ['status' => 'diproses']
        );

        // 🔔 TRIGGER MOBILE BANKING: Notifikasi transaksi disetujui → Status ACC
        $this->notifyPenarikanDisetujui($nasabah->user_id, $nominal);

        return response()->json([
            'message'   => 'Penarikan berhasil disetujui',
            'penarikan' => $penarikan,
        ]);
    }

    // PUT selesaikan penarikan → nasabah sudah ambil uang / admin transfer bukti
    public function selesai(Request $request, $id)
    {
        // Memuat relasi diprosesoleh untuk mendapatkan data nama profil admin yang memvalidasi
        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])->findOrFail($id);

        if ($penarikan->status !== 'diproses') {
            return response()->json([
                'message' => 'Penarikan harus berstatus diproses dulu'
            ], 400);
        }

        // 1. Validasi input file gambar bukti transfer dan catatan administrasi
        $request->validate([
            'catatan_admin'  => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Batasan ukuran file maksimal 2MB
        ]);

        // 2. Pemrosesan penyimpanan file gambar bukti transfer ke direktori publik
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('bukti_penarikan', 'public');
        }

        // 3. Memperbarui rekaman data transaksi penarikan di database
        $penarikan->update([
            'status'         => 'selesai',
            'catatan_admin'  => $request->catatan_admin,
            'bukti_transfer' => $buktiPath,
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');
        $tanggal = $penarikan->tanggal_ambil ? Carbon::parse($penarikan->tanggal_ambil)->format('d M Y') : '-';

        // 4. Pencatatan rekam jejak aktivitas ke dalam Audit Log sistem
        AuditLogService::log(
            action: 'PENARIKAN_SELESAI',
            module: 'Penarikan',
            description: "Penarikan {$nominal} nasabah {$penarikan->nasabah->nama_lengkap} selesai" . ($buktiPath ? " (Disertai Bukti Foto)" : ""),
            oldData: ['status' => 'diproses'],
            newData: ['status' => 'selesai', 'bukti_transfer' => $buktiPath]
        );

        // 5. 🔔 TRIGGER MOBILE BANKING: Kirim notifikasi konfirmasi penarikan berhasil dicairkan ke aplikasi nasabah
        $this->notifyPenarikanSelesai($penarikan->nasabah->user_id, $nominal, $tanggal);

        // 6. Penyusunan data Struk Digital terstruktur untuk kebutuhan otentikasi di aplikasi mobile
        $namaAdmin = $penarikan->diprosesoleh ? $penarikan->diprosesoleh->name : 'Admin Sistem';
        $nomorReferensi = 'TRX-WD-' . date('Ymd', strtotime($penarikan->tanggal_proses)) . '-' . str_pad($penarikan->id, 4, '0', STR_PAD_LEFT);
        $waktuProses = Carbon::parse($penarikan->tanggal_proses)->format('d M Y, H:i:s') . ' WIB';

        return response()->json([
            'message'   => 'Penarikan selesai, uang sudah dicairkan ke nasabah',
            'penarikan' => $penarikan,
            'struk_digital' => [
                'nomor_referensi' => $nomorReferensi,
                'status'          => 'BERHASIL',
                'waktu_proses'    => $waktuProses,
                'diproses_oleh'   => $namaAdmin,
                'nominal_cair'    => $nominal,
                'metode'          => $penarikan->catatan_nasabah ?? 'Ambil Tunai',
                'catatan_admin'   => $penarikan->catatan_admin ?? '-',
                'link_foto_bukti' => $buktiPath ? asset('storage/' . $buktiPath) : null,
            ]
        ]);
    }

    // PUT tolak penarikan
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return response()->json([
                'message' => 'Penarikan ini sudah diproses sebelumnya'
            ], 400);
        }

        $penarikan->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'diproses_oleh'    => $request->user()->id,
            'tanggal_proses'   => now(),
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');

        AuditLogService::log(
            action: 'PENARIKAN_TOLAK',
            module: 'Penarikan',
            description: "Admin menolak penarikan {$nominal} nasabah {$penarikan->nasabah->nama_lengkap}. Alasan: {$request->alasan_penolakan}",
            oldData: ['status' => 'pending'],
            newData: ['status' => 'ditolak']
        );

        // Notif ke admin (log notifikasi panel admin)
        NotificationService::penarikanDitolak($penarikan->nasabah->nama_lengkap, $nominal);

        // 🔔 TRIGGER MOBILE BANKING: Notifikasi transaksi ditolak ke Nasabah
        $this->notifyPenarikanDitolak($penarikan->nasabah->user_id, $nominal, $request->alasan_penolakan);

        return response()->json([
            'message'   => 'Penarikan berhasil ditolak',
            'penarikan' => $penarikan,
        ]);
    }
}
