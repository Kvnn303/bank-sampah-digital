<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenarikanController extends Controller
{
    // Halaman daftar penarikan
    public function index(Request $request)
    {
        $query = Penarikan::with('nasabah', 'diprosesoleh')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('nasabah', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('created_at', [
                $request->dari_tanggal . ' 00:00:00',
                $request->sampai_tanggal . ' 23:59:59'
            ]);
        }

        $penarikan = $query->paginate(10);

        // Data statistik untuk card di atas tabel
        $totalPending = Penarikan::where('status', 'pending')->count();
        $totalDiproses = Penarikan::where('status', 'diproses')->count();
        $totalSelesai = Penarikan::where('status', 'selesai')->count();
        $totalNominal = Penarikan::where('status', 'selesai')->sum('nominal');

        return view('admin.penarikan.index', compact(
            'penarikan',
            'totalPending',
            'totalDiproses',
            'totalSelesai',
            'totalNominal'
        ));
    }

    // Halaman detail penarikan
    public function show($id)
    {
        $penarikan = Penarikan::with('nasabah', 'diprosesoleh')->findOrFail($id);

        // Ambil saldo realtime (tabungan selesai dikurangi penarikan selesai)
        $totalTabungan = \App\Models\Tabungan::where('nasabah_id', $penarikan->nasabah_id)->sum('nilai_rupiah');
        $totalPenarikan = Penarikan::where('nasabah_id', $penarikan->nasabah_id)
            ->where('status', 'selesai')
            ->sum('nominal');

        $saldoRealtime = $totalTabungan - $totalPenarikan;

        return view('admin.penarikan.show', compact('penarikan', 'saldoRealtime'));
    }

    // Setujui penarikan
    public function setujui($id)
    {
        $penarikan = Penarikan::findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return redirect()->back()->with('error', 'Status penarikan sudah berubah.');
        }

        $penarikan->update([
            'status' => 'diproses',
            'diproses_oleh' => auth()->id(),
            'tanggal_proses' => now(),
        ]);

        \App\Services\AuditLogService::log(
            'PENARIKAN_SETUJUI',
            'Penarikan',
            "Setujui penarikan Rp " . number_format($penarikan->nominal, 0, ',', '.') . " nasabah {$penarikan->nasabah->nama_lengkap}"
        );

        return redirect()->back()->with('success', 'Penarikan berhasil disetujui dan sedang diproses.');
    }

    // Selesaikan penarikan (beserta upload foto bukti)
    public function selesai(Request $request, $id)
    {
        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'diproses') {
            return redirect()->back()->with('error', 'Penarikan harus berstatus diproses terlebih dahulu.');
        }

        // 1. Validasi input
        $request->validate([
            'catatan_admin'  => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Max 2MB
        ]);

        // 2. Upload file jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('bukti_penarikan', 'public');
        }

        // 3. Update Database
        $penarikan->update([
            'status'         => 'selesai',
            'catatan_admin'  => $request->catatan_admin,
            'bukti_transfer' => $buktiPath, // <--- FOTO DISIMPAN DI SINI
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');

        // 4. Catat ke Audit Log
        \App\Services\AuditLogService::log(
            action: 'PENARIKAN_SELESAI',
            module: 'Penarikan',
            description: "Penarikan {$nominal} nasabah {$penarikan->nasabah->nama_lengkap} selesai" . ($buktiPath ? " (Disertai Bukti Foto)" : ""),
            oldData: ['status' => 'diproses'],
            newData: ['status' => 'selesai', 'bukti_transfer' => $buktiPath]
        );

        return redirect()->back()->with('success', 'Penarikan berhasil diselesaikan dan uang telah dicairkan.');
    }

    // Tolak penarikan
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        $penarikan = Penarikan::findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return redirect()->back()->with('error', 'Status penarikan sudah berubah.');
        }

        $penarikan->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'diproses_oleh' => auth()->id(),
            'tanggal_proses' => now(),
        ]);

        \App\Services\AuditLogService::log(
            'PENARIKAN_TOLAK',
            'Penarikan',
            "Tolak penarikan Rp " . number_format($penarikan->nominal, 0, ',', '.') . " nasabah {$penarikan->nasabah->nama_lengkap}. Alasan: {$request->alasan_penolakan}"
        );

        return redirect()->back()->with('success', 'Penarikan berhasil ditolak.');
    }
}
