<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Notification;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class AuthController extends Controller
{
    // Register Nasabah
    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6|confirmed',
            'nama_lengkap' => 'required|string',
            'alamat'       => 'nullable|string',
            'no_telepon'   => 'nullable|string',
            'no_ktp'       => 'nullable|string|unique:nasabah',
            'foto_ktp'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'nasabah',
            'is_active' => 1, // Pastikan defaultnya aktif
        ]);

        Nasabah::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $request->nama_lengkap,
            'alamat'           => $request->alamat,
            'no_telepon'       => $request->no_telepon,
            'no_ktp'           => $request->no_ktp,
            'foto_ktp'         => $fotoKtpPath,
            'status_akun'      => 'pending',
            'sumber_daftar'    => 'mandiri',
            'tanggal_bergabung'=> now()->toDateString(),
        ]);

        AuditLogService::log(
            action: 'REGISTER',
            module: 'Auth',
            description: "Nasabah baru mendaftar: {$user->name}",
            status: 'success'
        );

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Menunggu verifikasi admin.',
            'user'    => $user,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Muat user sekalian dengan relasi nasabahnya
        $user = User::with('nasabah')->where('email', $request->email)->first();

        // 1. Cek Ketersediaan User & Keamanan Password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            AuditLog::create([
                'user_name'   => $request->email,
                'action'      => 'LOGIN_FAILED',
                'module'      => 'Auth',
                'description' => "Login gagal untuk email: {$request->email}",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            // Gunakan JSON 401 (Unauthorized) untuk API, BUKAN Exception
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 2. Cek Akun Dinonaktifkan (Perbaikan Logika Utama)
        $nasabah = $user->nasabah;
        if (! $user->is_active || ($nasabah && $nasabah->status_akun === 'nonaktif')) {
            AuditLog::create([
                'user_name'   => $user->name,
                'action'      => 'LOGIN_BLOCKED',
                'module'      => 'Auth',
                'description' => "Login ditolak, akun {$user->name} dinonaktifkan",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            // Gunakan JSON 403 (Forbidden)
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan. Hubungi Admin Bank Sampah.'
            ], 403);
        }

        // 3. Generate Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        AuditLogService::log(
            action: 'LOGIN',
            module: 'Auth',
            description: "User {$user->name} ({$user->role}) berhasil login dari aplikasi Mobile",
            status: 'success'
        );

        // Catat juga ke tabel notifications agar badge & list modal Log Aktivitas mobile sinkron
        NotificationService::send(
            targetRole: $user->role,
            type:        Notification::TYPE_AUTH,
            title:       'Login Berhasil',
            message:     "{$user->name} baru saja login ke aplikasi Mobile.",
            url:         null,
            userId:      $user->id,
            status:      'unread',
            priority:    'normal'
        );

        return response()->json([
            'success'              => true,
            'message'              => 'Login berhasil',
            'user'                 => $user,
            'role'                 => $user->role,
            'token'                => $token,
            'must_change_password' => $user->isAdmin() && ! $user->password_changed,
        ], 200);
    }

    // Profile & Cek Saldo Real-time + Statistik
    public function profile(Request $request)
    {
        $user = $request->user();
        $nasabah = $user->nasabah;

        $saldoAkhir = 0;
        $totalKg = 0;           // Variabel untuk menyimpan total Kg
        $countPenarikan = 0;    // Variabel untuk menyimpan jumlah penarikan sukses

        // Jika user ini benar-benar punya data nasabah, kita hitung datanya
        if ($nasabah) {
            // 1. Hitung total uang masuk (setoran sampah)
            $totalTabungan = \App\Models\Tabungan::where('nasabah_id', $nasabah->id)->sum('nilai_rupiah');

            // 2. Hitung uang keluar — HANYA penarikan berstatus 'selesai'
            $totalPenarikan = \App\Models\Penarikan::where('nasabah_id', $nasabah->id)
                                ->where('status', 'selesai')
                                ->sum('nominal');

            // 3. Saldo Akhir = Uang Masuk - Uang Keluar (yang ditahan/selesai)
            $saldoAkhir = $totalTabungan - $totalPenarikan;

            // 4. Hitung total berat (Kg) semua sampah yang pernah disetor
            $totalKg = \App\Models\Tabungan::where('nasabah_id', $nasabah->id)->sum('berat_kg');

            // 5. Hitung berapa kali nasabah sukses melakukan penarikan
            $countPenarikan = \App\Models\Penarikan::where('nasabah_id', $nasabah->id)
                                ->where('status', 'selesai')
                                ->count();
        }


        return response()->json([
            'success'         => true,
            'user'            => $user,
            'nasabah'         => $nasabah,
            'nik'             => $nasabah?->no_ktp,
            'saldo'           => (float) $saldoAkhir,
            'total_kg'        => (float) $totalKg,
            'count_penarikan' => (int) $countPenarikan,
        ], 200);
    }

    // --- FITUR BARU: UPDATE PROFIL & FOTO ---
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $nasabah = $user->nasabah;

        $request->validate([
            'name'         => 'required|string|max:255',
            'nama_lengkap' => 'required|string',
            'no_telepon'   => 'required|string',
            'alamat'       => 'required|string',
            // Validasi foto (opsional)
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 1. Update Username (Tabel Users)
        $user->update(['name' => $request->name]);

        // 2. Update Data Nasabah
        if ($nasabah) {
            $nasabahData = [
                'nama_lengkap' => $request->nama_lengkap,
                'no_telepon'   => $request->no_telepon,
                'alamat'       => $request->alamat,
            ];

            // 3. Simpan foto jika user memilih foto baru
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada (opsional, untuk menghemat storage)
                // if ($nasabah->foto) { Storage::disk('public')->delete($nasabah->foto); }

                $path = $request->file('foto')->store('foto_profil', 'public');
                $nasabahData['foto'] = $path; // Akan disimpan di kolom 'foto' tabel nasabah
            }

            $nasabah->update($nasabahData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diupdate',
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        $user = $request->user();

        AuditLogService::log(
            action: 'LOGOUT',
            module: 'Auth',
            description: "User {$user->name} logout dari Mobile",
            status: 'success'
        );

        // Catat juga ke tabel notifications agar sinkron dengan badge & list mobile
        NotificationService::send(
            targetRole: $user->role,
            type:        Notification::TYPE_AUTH,
            title:       'Logout Berhasil',
            message:     "{$user->name} telah keluar dari aplikasi Mobile.",
            url:         null,
            userId:      $user->id,
            status:      'unread',
            priority:    'low'
        );

        // Hapus token yang sedang digunakan
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
