<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenarikanController extends Controller
{
    // Tampilkan semua penarikan
    public function index(Request $request)
    {
        // Subquery untuk saldo real-time per nasabah (total tabungan - penarikan selesai/diproses)
        $tabunganSub = DB::table('tabungan')
            ->select('nasabah_id', DB::raw('SUM(nilai_rupiah) as total_tabungan'))
            ->groupBy('nasabah_id');

        $penarikanSub = DB::table('penarikan')
            ->select('nasabah_id', DB::raw('SUM(nominal) as total_penarikan'))
            ->where('status', 'selesai')
            ->groupBy('nasabah_id');

        $query = Penarikan::with(['nasabah', 'diprosesoleh'])
            ->leftJoinSub($tabunganSub, 't', function($join) {
                $join->on('penarikan.nasabah_id', '=', 't.nasabah_id');
            })
            ->leftJoinSub($penarikanSub, 'p', function($join) {
                $join->on('penarikan.nasabah_id', '=', 'p.nasabah_id');
            })
            ->select(
                'penarikan.*',
                DB::raw('COALESCE(t.total_tabungan, 0) - COALESCE(p.total_penarikan, 0) AS nasabah_saldo_realtime')
            )
            ->orderByDesc('penarikan.created_at');

        // Filter by status
        if ($request->status) {
            $query->where('penarikan.status', $request->status);
        }

        // Filter by tanggal
        if ($request->dari_tanggal && $request->sampai_tanggal) {
            $query->whereBetween('penarikan.created_at', [
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

        // Hitung saldo real-time langsung dari DB (bukan dari accessor)
        $saldoRealtime = $this->hitungSaldoRealtime($penarikan->nasabah_id);

        return view('admin.penarikan.show', compact('penarikan', 'saldoRealtime'));
    }

    // Helper: Hitung saldo real-time langsung dari DB
    private function hitungSaldoRealtime($nasabahId): float
    {
        $totalTabungan = (float) Tabungan::where('nasabah_id', $nasabahId)->sum('nilai_rupiah');
        $totalPenarikan = (float) Penarikan::where('nasabah_id', $nasabahId)
                            ->where('status', 'selesai')
                            ->sum('nominal');

        return $totalTabungan - $totalPenarikan;
    }

    // Setujui penarikan
    public function setujui(Request $request, $id)
    {
        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Penarikan ini sudah diproses sebelumnya!');
        }

        // ✅ PERBAIKAN: Cek saldo real-time langsung dari DB
        $nasabah = $penarikan->nasabah;
        $saldoRealtime = $this->hitungSaldoRealtime($nasabah->id);

        if ($saldoRealtime < $penarikan->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo nasabah tidak mencukupi! Saldo saat ini: Rp ' . number_format($saldoRealtime, 0, ',', '.'));
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
        try {
            $request->validate([
                'alasan_penolakan' => 'required|string|min:10|max:500',
            ], [
                'alasan_penolakan.required' => 'Alasan penolakan wajib diisi',
                'alasan_penolakan.min' => 'Alasan penolakan minimal 10 karakter',
                'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter',
            ]);

            $penarikan = Penarikan::with('nasabah')->findOrFail($id);

            if ($penarikan->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Penarikan ini sudah diproses sebelumnya!');
            }

            // Pastikan nasabah memiliki relasi user_id
            if (!$penarikan->nasabah || !$penarikan->nasabah->user_id) {
                return redirect()->back()
                    ->with('error', 'Data nasabah tidak valid!');
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali input Anda.');
        } catch (\Exception $e) {
            \Log::error('Error tolak penarikan: ' . $e->getMessage(), [
                'penarikan_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak penarikan. Silakan coba lagi.');
        }
    }
}
