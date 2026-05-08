<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Penarikan;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PenarikanNasabahController extends Controller
{
    // GET daftar pengajuan penarikan nasabah
    public function index(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        $penarikan = Penarikan::where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->paginate(10);

        return response()->json($penarikan);
    }

    // POST ajukan penarikan
    public function store(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // Cek status akun
        if ($nasabah->status_akun !== 'active') {
            return response()->json([
                'message' => 'Akun belum aktif, belum bisa melakukan penarikan'
            ], 400);
        }

        $request->validate([
            'nominal'          => 'required|numeric|min:10000',
            'tanggal_ambil'    => 'required|date|after:today',
            'catatan_nasabah'  => 'nullable|string',
        ]);

        // Cek saldo cukup
        if ($nasabah->saldo < $request->nominal) {
            return response()->json([
                'message' => 'Saldo tidak mencukupi',
                'saldo'   => $nasabah->saldo,
                'nominal' => $request->nominal,
            ], 400);
        }

        // Cek tidak ada penarikan pending
        $penarikanPending = Penarikan::where('nasabah_id', $nasabah->id)
                                ->where('status', 'pending')
                                ->exists();

        if ($penarikanPending) {
            return response()->json([
                'message' => 'Masih ada pengajuan penarikan yang belum diproses'
            ], 400);
        }

        $penarikan = Penarikan::create([
            'nasabah_id'      => $nasabah->id,
            'nominal'         => $request->nominal,
            'status'          => 'pending',
            'tanggal_ambil'   => $request->tanggal_ambil,
            'catatan_nasabah' => $request->catatan_nasabah,
        ]);

        AuditLogService::log(
            action: 'PENARIKAN_AJUKAN',
            module: 'Penarikan',
            description: "Nasabah {$nasabah->nama_lengkap} mengajukan penarikan Rp{$request->nominal}",
            newData: $penarikan->toArray()
        );

        // Notif ke admin
        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');
        NotificationService::penarikanBaru($nasabah->nama_lengkap, $nominal);

        return response()->json([
            'message'   => 'Pengajuan penarikan berhasil dikirim',
            'penarikan' => $penarikan,
        ], 201);
    }

    // DELETE batalkan penarikan (hanya yang masih pending)
    public function batalkan(Request $request, $id)
    {
        $nasabah   = $request->user()->nasabah;
        $penarikan = Penarikan::where('nasabah_id', $nasabah->id)
                        ->findOrFail($id);

        if ($penarikan->status !== 'pending') {
            return response()->json([
                'message' => 'Penarikan tidak bisa dibatalkan karena sudah diproses'
            ], 400);
        }

        $penarikan->delete();

        AuditLogService::log(
            action: 'PENARIKAN_BATAL',
            module: 'Penarikan',
            description: "Nasabah {$nasabah->nama_lengkap} membatalkan penarikan Rp{$penarikan->nominal}",
        );

        return response()->json([
            'message' => 'Pengajuan penarikan berhasil dibatalkan'
        ]);
    }
}
