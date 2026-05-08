<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use App\Models\Nasabah;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PenarikanController extends Controller
{
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

        // Cek saldo nasabah cukup
        $nasabah = $penarikan->nasabah;
        if ($nasabah->saldo < $penarikan->nominal) {
            return response()->json([
                'message' => 'Saldo nasabah tidak mencukupi',
                'saldo'   => $nasabah->saldo,
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

        // ✅ Notif ke admin (log) + nasabah
        NotificationService::penarikanSelesai($nasabah->nama_lengkap, $nominal);
        NotificationService::penarikanDisetujuiNasabah($nasabah->user_id, $nominal);

        return response()->json([
            'message'   => 'Penarikan berhasil disetujui',
            'penarikan' => $penarikan,
        ]);
    }

    // PUT selesaikan penarikan → nasabah sudah ambil uang
    public function selesai(Request $request, $id)
    {
        $penarikan = Penarikan::with('nasabah')->findOrFail($id);

        if ($penarikan->status !== 'diproses') {
            return response()->json([
                'message' => 'Penarikan harus berstatus diproses dulu'
            ], 400);
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
            oldData: ['status' => 'diproses'],
            newData: ['status' => 'selesai']
        );

        //  Notif ke admin (log) + nasabah
        NotificationService::penarikanSelesai($penarikan->nasabah->nama_lengkap, $nominal);
        NotificationService::penarikanSelesaiNasabah($penarikan->nasabah->user_id, $nominal, $tanggal);

        return response()->json([
            'message'   => 'Penarikan selesai, uang sudah diterima nasabah',
            'penarikan' => $penarikan,
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

        // ✅ Notif ke admin (log) + nasabah
        NotificationService::penarikanDitolak($penarikan->nasabah->nama_lengkap, $nominal);
        NotificationService::penarikanDitolakNasabah($penarikan->nasabah->user_id, $nominal, $request->alasan_penolakan);

        return response()->json([
            'message'   => 'Penarikan berhasil ditolak',
            'penarikan' => $penarikan,
        ]);
    }
}
