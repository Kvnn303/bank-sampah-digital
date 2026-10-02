<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Notification;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use App\Services\ExpoPushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\AuditLog;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Register Nasabah
    public function register(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|min:8|confirmed', // Min 8 karakter untuk keamanan
            'nama_lengkap'      => 'required|string|max:255',
            'alamat'            => 'required|string',          // Wajib untuk verifikasi domisili
            'no_telepon'        => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/', // Validasi format telepon
            'no_ktp'            => 'nullable|string|size:16|regex:/^[0-9]{16}$/', // NIK harus 16 digit angka
            // Foto KTP wajib, minimal 50KB (untuk cegah foto buram/asal), maks 5MB
            'foto_ktp'          => 'required|image|mimes:jpg,jpeg,png|max:5120|min:10',
            // Koordinat GPS opsional tapi disimpan jika ada
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'lokasi_registrasi' => 'nullable|string|max:500',
        ], [
            'no_ktp.size'       => 'Nomor KTP harus tepat 16 digit.',
            'no_ktp.regex'      => 'Nomor KTP hanya boleh berisi angka.',
            'foto_ktp.required' => 'Foto KTP wajib diunggah untuk verifikasi identitas.',
            'foto_ktp.min'      => 'Foto KTP terlalu kecil/buram. Gunakan foto yang jelas.',
            'password.min'      => 'Password minimal 8 karakter untuk keamanan akun.',
        ]);

        // ── Validasi tambahan: cek ukuran file KTP minimal 50KB (anti foto abal-abal) ──
        if ($request->hasFile('foto_ktp')) {
            $ktpFile = $request->file('foto_ktp');
            if ($ktpFile->getSize() < 50 * 1024) { // < 50KB
                return response()->json([
                    'success' => false,
                    'message' => 'Foto KTP terlalu kecil atau buram. Harap foto ulang dengan pencahayaan yang baik.',
                    'errors'  => ['foto_ktp' => ['Foto KTP minimal 50KB. Pastikan foto jelas dan terbaca.']]
                ], 422);
            }
        }

        // ── Validasi: pastikan no_ktp dari daerah yang valid (2 digit pertama = kode provinsi) ──
        if ($request->filled('no_ktp')) {
            $noKtp = $request->no_ktp;
            $kodeProvinsi = (int) substr($noKtp, 0, 2);
            if ($kodeProvinsi < 11 || $kodeProvinsi > 99) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['no_ktp' => ['Nomor KTP tidak valid. Periksa kembali NIK Anda.']]
                ], 422);
            }
        }

        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'nasabah',
            'is_active' => 1,
        ]);

        Nasabah::create([
            'user_id'              => $user->id,
            'nama_lengkap'         => $request->nama_lengkap,
            'alamat'               => $request->alamat,
            'no_telepon'           => $request->no_telepon,
            'no_ktp'               => $request->no_ktp,
            'foto_ktp'             => $fotoKtpPath,
            'status_akun'          => 'pending',
            'sumber_daftar'        => 'mandiri',
            'tanggal_bergabung'    => now()->toDateString(),
            // Simpan data lokasi GPS jika disediakan
            'latitude'             => $request->latitude,
            'longitude'            => $request->longitude,
            'lokasi_registrasi'    => $request->lokasi_registrasi,
            // Status verifikasi KTP dimulai dari pending
            'ktp_verification_status' => 'pending',
        ]);

        // Notifikasi ke admin ada nasabah baru
        NotificationService::nasabahBaru($request->nama_lengkap);

        AuditLogService::log(
            action: 'REGISTER',
            module: 'Auth',
            description: "Nasabah baru mendaftar: {$user->name} (KTP: " . ($request->no_ktp ?? 'belum diisi') . ")",
            status: 'success'
        );

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Akun Anda sedang menunggu verifikasi KTP oleh admin (1-2 hari kerja).',
            'user'    => $user,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'            => 'required|email',
            'password'         => 'required',
            'expo_push_token'  => 'nullable|string|max:200', // Token device untuk push notification
        ]);

        // ── Brute Force Protection: Rate limit 5 percobaan per IP per 15 menit ──
        $rateLimitKey = 'login_attempt:' . Str::lower($request->email) . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $menit   = ceil($seconds / 60);

            AuditLog::create([
                'user_name'   => $request->email,
                'action'      => 'LOGIN_RATE_LIMITED',
                'module'      => 'Auth',
                'description' => "Login diblokir akibat terlalu banyak percobaan untuk: {$request->email}",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            return response()->json([
                'success'      => false,
                'message'      => "Terlalu banyak percobaan login. Coba lagi dalam {$menit} menit.",
                'retry_after'  => $seconds,
            ], 429);
        }

        // Muat user sekalian dengan relasi nasabahnya
        $user = User::with('nasabah')->where('email', $request->email)->first();

        // 1. Cek Ketersediaan User & Keamanan Password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Tambah counter rate limit
            RateLimiter::hit($rateLimitKey, 900); // Decay 15 menit

            AuditLog::create([
                'user_name'   => $request->email,
                'action'      => 'LOGIN_FAILED',
                'module'      => 'Auth',
                'description' => "Login gagal untuk email: {$request->email}",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // ── Cek apakah akun terkunci akibat brute force sebelumnya ──
        if ($user->locked_until && Carbon::parse($user->locked_until)->isFuture()) {
            $menit = Carbon::now()->diffInMinutes(Carbon::parse($user->locked_until), false);
            return response()->json([
                'success' => false,
                'message' => "Akun terkunci sementara. Coba lagi dalam {$menit} menit atau hubungi admin.",
            ], 403);
        }

        // 2. Cek Validasi Status Aktivasi & Verifikasi Nasabah
        $nasabah = $user->nasabah;

        // A. Pengecekan jika user dinonaktifkan secara global di tabel users
        if (! $user->is_active) {
            AuditLog::create([
                'user_name'   => $user->name,
                'action'      => 'LOGIN_BLOCKED',
                'module'      => 'Auth',
                'description' => "Login ditolak, user_id {$user->id} berstatus is_active = 0",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan. Hubungi Admin Bank Sampah.'
            ], 403);
        }

        // B. Pengecekan khusus untuk data nasabah
        if ($nasabah) {
            // Blokir jika status masih PENDING (Belum Diverifikasi)
            if ($nasabah->status_akun === 'pending') {
                AuditLog::create([
                    'user_name'   => $user->name,
                    'action'      => 'LOGIN_PENDING',
                    'module'      => 'Auth',
                    'description' => "Login ditolak, akun nasabah {$user->name} masih berstatus pending/belum diverifikasi",
                    'ip_address'  => $request->ip(),
                    'user_agent'  => $request->userAgent(),
                    'status'      => 'failed',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diverifikasi oleh Admin. Mohon tunggu hingga proses verifikasi selesai.'
                ], 403);
            }

            // Blokir jika status diubah menjadi NONAKTIF oleh admin
            if ($nasabah->status_akun === 'nonaktif') {
                AuditLog::create([
                    'user_name'   => $user->name,
                    'action'      => 'LOGIN_BLOCKED',
                    'module'      => 'Auth',
                    'description' => "Login ditolak, akun nasabah {$user->name} dinonaktifkan oleh admin",
                    'ip_address'  => $request->ip(),
                    'user_agent'  => $request->userAgent(),
                    'status'      => 'failed',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda dinonaktifkan. Hubungi Admin Bank Sampah.'
                ], 403);
            }
        }

        // 3. Generate Token Sanctum jika status_akun === 'active' (Lolos semua validasi di atas)
        $token = $user->createToken('auth_token')->plainTextToken;

        // ── Reset rate limiter setelah login berhasil ──
        RateLimiter::clear($rateLimitKey);

        // ── Simpan expo_push_token dan tracking login ──
        $updates = [
            'last_login_at'     => now(),
            'last_login_ip'     => $request->ip(),
            'failed_login_count' => 0,
            'locked_until'      => null,
        ];
        if ($request->filled('expo_push_token') && ExpoPushNotificationService::isValidToken($request->expo_push_token)) {
            $updates['expo_push_token'] = $request->expo_push_token;

            // Juga simpan ke tabel nasabah jika ada
            if ($user->nasabah) {
                $user->nasabah->update(['expo_push_token' => $request->expo_push_token]);
            }
        }
        $user->update($updates);

        AuditLogService::log(
            action: 'LOGIN',
            module: 'Auth',
            description: "User {$user->name} ({$user->role}) berhasil login dari aplikasi Mobile | IP: {$request->ip()}",
            status: 'success'
        );

        // Notifikasi in-app (tersimpan di database)
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

        // ── Push notification ke perangkat lain jika token ada (login baru) ──
        // Hanya kirim jika token berbeda dari yang tersimpan (login di perangkat baru)
        $storedToken = $user->expo_push_token;
        $newToken    = $request->expo_push_token;
        if ($storedToken && $newToken && $storedToken !== $newToken && ExpoPushNotificationService::isValidToken($storedToken)) {
            ExpoPushNotificationService::notifyLoginBaru($storedToken, $user->name, $request->ip());
        }

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

    // --- FITUR BARU: UBAH PASSWORD DARI PROFIL ---
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = $request->user();

        // 1. Cek apakah password lama yang diinputkan sesuai dengan di database
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini yang Anda masukkan salah.',
            ], 400);
        }

        // 2. Cek apakah password baru sama dengan password lama
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors'  => [
                    'password' => ['Password baru tidak boleh sama dengan password saat ini.']
                ]
            ], 422);
        }

        // 3. Update password di tabel users
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // 4. Catat ke Audit Log
        AuditLogService::log(
            action: 'CHANGE_PASSWORD',
            module: 'Auth',
            description: "User {$user->name} berhasil mengubah password dari aplikasi Mobile",
            status: 'success'
        );

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan gunakan password baru Anda.',
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
