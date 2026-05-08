<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use App\Models\Nasabah;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PenarikanController extends Controller
{
    // Tampilkan semua penarikan
    public function index(Request $request)
    {
        $query = Penarikan::with(['nasabah', 'diprosesoleh'])
                    ->orderByDesc('created_at');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal
        if ($request->dari_tanggal && $request->sampai_tanggal) {
            $query->whereBetween('created_at', [
                $request->dari_tanggal,
                $request->sampai_tanggal
            ]);
        }

        // Search by nasabah
        if ($request->search) {
            $query->whereHas('nasabah', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%");
            });
        }

        $penarikan        = $query->paginate(10);
        $totalPending     = Penarikan::where('status', 'pending')->count();
        $totalDiproses    = Penarikan::where('status', 'diproses')->count();
        $totalSelesai     = Penarikan::where('status', 'selesai')->count();
        $totalNominal     = Penarikan::where('status', 'selesai')->sum('nominal');

        return view('admin.penarikan.index', compact(
            'penarikan',
            'totalPending',
            'totalDiproses',
            'totalSelesai',
            'totalNominal'
        ));
    }

    // Detail penarikan
    public function show($id)
    {
        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                        ->findOrFail($id);

        return view('admin.penarikan.show', compact('penarikan'));
    }

    // Setujui penarikan
    public function setujui(Request $request, $id)
    {
        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Penarikan ini sudah diproses sebelumnya!');
        }

        // Cek saldo cukup
        $nasabah = $penarikan->nasabah;
        if ($nasabah->saldo < $penarikan->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo nasabah tidak mencukupi!');
        }

        $penarikan->update([
            'status'         => 'diproses',
            'diproses_oleh'  => auth()->id(),
            'tanggal_proses' => now(),
            'catatan_admin'  => $request->catatan_admin,
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');

        AuditLogService::log(
            action: 'PENARIKAN_SETUJUI',
            module: 'Penarikan',
            description: "Admin menyetujui penarikan {$nominal} untuk {$nasabah->nama_lengkap}",
        );

        // ✅ Notif ke admin (log) + nasabah
        NotificationService::penarikanSelesai($nasabah->nama_lengkap, $nominal);
        NotificationService::penarikanDisetujuiNasabah($nasabah->user_id, $nominal);

        return redirect()->route('admin.penarikan.index')
                        ->with('success', 'Penarikan berhasil disetujui!');
    }

    // Selesaikan penarikan
    public function selesai(Request $request, $id)
    {
        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'diproses') {
            return redirect()->back()
                ->with('error', 'Penarikan harus berstatus diproses dulu!');
        }

        $penarikan->update([
            'status'        => 'selesai',
            'catatan_admin' => $request->catatan_admin,
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');
        $tanggal = $penarikan->tanggal_ambil ? $penarikan->tanggal_ambil->format('d M Y') : '-';

        AuditLogService::log(
            action: 'PENARIKAN_SELESAI',
            module: 'Penarikan',
            description: "Penarikan {$nominal} nasabah {$penarikan->nasabah->nama_lengkap} selesai",
        );

        // ✅ Notif ke admin (log) + nasabah
        NotificationService::penarikanSelesai($penarikan->nasabah->nama_lengkap, $nominal);
        NotificationService::penarikanSelesaiNasabah($penarikan->nasabah->user_id, $nominal, $tanggal);

        return redirect()->route('admin.penarikan.index')
                        ->with('success', 'Penarikan selesai, uang sudah diterima nasabah!');
    }

    // Tolak penarikan
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi',
        ]);

        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Penarikan ini sudah diproses sebelumnya!');
        }

        $penarikan->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'diproses_oleh'    => auth()->id(),
            'tanggal_proses'   => now(),
        ]);

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');

        AuditLogService::log(
            action: 'PENARIKAN_TOLAK',
            module: 'Penarikan',
            description: "Admin menolak penarikan {$nominal} nasabah {$penarikan->nasabah->nama_lengkap}. Alasan: {$request->alasan_penolakan}",
        );

        // Notif ke admin (log) + nasabah
        NotificationService::penarikanDitolak($penarikan->nasabah->nama_lengkap, $nominal);
        NotificationService::penarikanDitolakNasabah($penarikan->nasabah->user_id, $nominal, $request->alasan_penolakan);

        return redirect()->route('admin.penarikan.index')
                        ->with('success', 'Penarikan berhasil ditolak!');
    }
}
