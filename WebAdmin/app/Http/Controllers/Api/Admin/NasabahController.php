<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NasabahController extends Controller
{
    // GET semua nasabah
    public function index(Request $request)
    {
        $query = Nasabah::with('user')
                    ->orderByDesc('created_at');

        // Filter by status
        if ($request->status) {
            $query->where('status_akun', $request->status);
        }

        // Search by nama atau no_ktp
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('no_ktp', 'like', "%{$request->search}%")
                  ->orWhere('no_telepon', 'like', "%{$request->search}%");
            });
        }

        $nasabah = $query->paginate(10);

        return response()->json($nasabah);
    }

    // GET detail nasabah
    public function show($id)
    {
        $nasabah = Nasabah::with([
            'user',
            'tabungan.jenisSampah',
            'penarikan'
        ])->findOrFail($id);

        return response()->json([
            'nasabah' => $nasabah,
            'saldo'   => $nasabah->saldo,
            'total_sampah' => $nasabah->total_sampah,
        ]);
    }

    // POST tambah nasabah manual oleh admin
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'email'        => 'required|email|unique:users',
            'no_telepon'   => 'nullable|string',
            'no_ktp'       => 'nullable|string|unique:nasabah',
            'alamat'       => 'nullable|string',
            'foto_ktp'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan foto KTP
        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        // Password default = no_telepon atau no_ktp
        $passwordDefault = $request->no_telepon ?? $request->no_ktp ?? 'banksampah123';

        // Buat akun user
        $user = User::create([
            'name'             => $request->nama_lengkap,
            'email'            => $request->email,
            'password'         => Hash::make($passwordDefault),
            'role'             => 'nasabah',
            'password_changed' => false,
        ]);

        // Buat data nasabah
        $nasabah = Nasabah::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $request->nama_lengkap,
            'alamat'           => $request->alamat,
            'no_telepon'       => $request->no_telepon,
            'no_ktp'           => $request->no_ktp,
            'foto_ktp'         => $fotoKtpPath,
            'status_akun'      => 'verified',
            'sumber_daftar'    => 'admin',
            'tanggal_bergabung'=> now()->toDateString(),
        ]);

        AuditLogService::log(
            action: 'NASABAH_TAMBAH',
            module: 'Nasabah',
            description: "Admin menambahkan nasabah baru: {$nasabah->nama_lengkap}",
            newData: $nasabah->toArray()
        );

        // ✅ Notif ke admin
        NotificationService::nasabahBaru($nasabah->nama_lengkap);

        return response()->json([
            'message'          => 'Nasabah berhasil ditambahkan',
            'nasabah'          => $nasabah,
            'password_default' => $passwordDefault,
        ], 201);
    }

    // PUT verifikasi nasabah
    public function verifikasi(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $oldStatus = $nasabah->status_akun;

        $request->validate([
            'status'         => 'required|in:verified,active,pending',
            'catatan_admin'  => 'nullable|string',
        ]);

        $nasabah->update([
            'status_akun'   => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        AuditLogService::log(
            action: 'NASABAH_VERIFIKASI',
            module: 'Nasabah',
            description: "Status nasabah {$nasabah->nama_lengkap} diubah dari {$oldStatus} ke {$request->status}",
            oldData: ['status_akun' => $oldStatus],
            newData: ['status_akun' => $request->status]
        );

        // ✅ Notif ke admin + nasabah sesuai status
        if ($request->status === 'verified' || $request->status === 'active') {
            NotificationService::nasabahVerifikasi($nasabah->nama_lengkap);
            NotificationService::akunDiverifikasi($nasabah->user_id);
        }

        return response()->json([
            'message' => 'Status nasabah berhasil diperbarui',
            'nasabah' => $nasabah,
        ]);
    }

    // PUT edit nasabah
    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'nullable|string',
            'alamat'       => 'nullable|string',
            'no_telepon'   => 'nullable|string',
            'catatan_admin'=> 'nullable|string',
        ]);

        $oldData = $nasabah->toArray();
        $nasabah->update($request->only([
            'nama_lengkap', 'alamat',
            'no_telepon', 'catatan_admin'
        ]));

        AuditLogService::log(
            action: 'NASABAH_EDIT',
            module: 'Nasabah',
            description: "Admin mengedit data nasabah: {$nasabah->nama_lengkap}",
            oldData: $oldData,
            newData: $nasabah->toArray()
        );

        return response()->json([
            'message' => 'Data nasabah berhasil diperbarui',
            'nasabah' => $nasabah,
        ]);
    }

    // DELETE nasabah
    public function destroy(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        AuditLogService::log(
            action: 'NASABAH_HAPUS',
            module: 'Nasabah',
            description: "Admin menghapus nasabah: {$nasabah->nama_lengkap}",
            oldData: $nasabah->toArray()
        );

        // Hapus user juga
        $nasabah->user?->delete();
        $nasabah->delete();

        return response()->json([
            'message' => 'Nasabah berhasil dihapus'
        ]);
    }

    // PUT reset password nasabah oleh admin
    public function resetPassword(Request $request, $id)
    {
        $nasabah = Nasabah::with('user')->findOrFail($id);

        $passwordBaru = $nasabah->no_telepon ?? $nasabah->no_ktp ?? 'banksampah123';

        $nasabah->user->update([
            'password'         => Hash::make($passwordBaru),
            'password_changed' => false,
        ]);

        AuditLogService::log(
            action: 'NASABAH_RESET_PASSWORD',
            module: 'Nasabah',
            description: "Admin mereset password nasabah: {$nasabah->nama_lengkap}",
        );

        // Notif ke nasabah
        NotificationService::passwordDireset($nasabah->user_id);

        return response()->json([
            'message'          => 'Password berhasil direset',
            'password_default' => $passwordBaru,
        ]);
    }
}
