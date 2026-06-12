<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PasswordResetOtpController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 5;
    private const OTP_LENGTH = 6;

    /**
     * Step 1: Request OTP via email + nomor WhatsApp.
     * Frontend calls: POST /api/forgot-password/request-otp
     * Body: { email, nomor_wa }
     */
    public function requestOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email:rfc,dns',
            'nomor_wa'  => 'required|string|min:10|max:20',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'nomor_wa.min'      => 'Nomor WhatsApp minimal 10 digit.',
            'nomor_wa.max'      => 'Nomor WhatsApp maksimal 20 digit.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $email    = strtolower(trim($request->email));
        $nomor_wa = self::normalizePhone($request->nomor_wa);

        // ── Langkah 1: Cek apakah email ada di tabel users ──
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar di sistem.',
            ], 404);
        }

        // ── Langkah 2: Cek kecocokan nomor WA ──
        // Cek di users.login_username (jika terisi) ATAU di tabel relasi nasabah.no_telepon
        $nomorMatch = false;
        $nomorTujuan = null;

        if ($user->login_username) {
            if (self::normalizePhone($user->login_username) === $nomor_wa) {
                $nomorMatch = true;
                $nomorTujuan = self::normalizePhone($user->login_username);
            }
        }

        if (!$nomorMatch && $user->nasabah) {
            $dbNomor = $user->nasabah->no_telepon ?? '';
            if ($dbNomor && self::normalizePhone($dbNomor) === $nomor_wa) {
                $nomorMatch = true;
                $nomorTujuan = self::normalizePhone($dbNomor);
            }
        }

        if (!$nomorMatch) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi email dan nomor WhatsApp tidak ditemukan.',
            ], 404);
        }

        // Hapus semua OTP lama user ini
        PasswordResetOtp::where('email', $email)->delete();

        // Generate OTP 6 digit
        $otp = str_pad((string) random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email'     => $email,
            'nomor_wa'  => $nomor_wa,
            'otp'       => $otp,
            'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'is_verified' => false,
        ]);

        // Mode simulasi lokal: OTP hanya disimpan ke database (password_reset_otps),
        // tidak dikirim via WhatsApp/Email agar mudah didemokan.
        Log::info("OTP [simulasi] untuk {$email} → kode: {$otp}, berlaku " . self::OTP_EXPIRY_MINUTES . ' menit.');

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi telah diproses di sistem, silakan cek database.',
            'otp'     => $otp,
        ], 200);
    }

    /**
     * Step 2: Verifikasi OTP.
     * Frontend calls: POST /api/forgot-password/verify-otp
     * Body: { email, nomor_wa, otp }
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email:rfc,dns',
            'nomor_wa'  => 'required|string|min:10|max:20',
            'otp'       => 'required|string|size:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'otp.required'      => 'Kode OTP wajib diisi.',
            'otp.size'          => 'Kode OTP harus 6 digit.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $email    = strtolower(trim($request->email));
        $nomor_wa = self::normalizePhone($request->nomor_wa);
        $otp      = $request->otp;

        $record = PasswordResetOtp::where('email', $email)
            ->where('nomor_wa', $nomor_wa)
            ->where('otp', $otp)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa.',
            ], 400);
        }

        $record->update(['is_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil. Silakan masukkan password baru.',
        ], 200);
    }

    /**
     * Step 3: Reset password setelah OTP terverifikasi.
     * Frontend calls: POST /api/forgot-password/reset-password
     * Body: { email, nomor_wa, password, password_confirmation }
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email:rfc,dns',
            'nomor_wa'  => 'required|string|min:10|max:20',
            'password'  => 'required|string|min:6|confirmed',
        ], [
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'nomor_wa.required'    => 'Nomor WhatsApp wajib diisi.',
            'password.required'    => 'Password baru wajib diisi.',
            'password.min'         => 'Password minimal 6 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $email    = strtolower(trim($request->email));
        $nomor_wa = self::normalizePhone($request->nomor_wa);

        $record = PasswordResetOtp::where('email', $email)
            ->where('nomor_wa', $nomor_wa)
            ->where('is_verified', true)
            ->latest()
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memverifikasi kode OTP.',
            ], 403);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        DB::transaction(function () use ($user, $email, $request) {
            $user->update(['password' => Hash::make($request->password)]);
            PasswordResetOtp::where('email', $email)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ], 200);
    }

    /**
     * Normalisasi nomor WA: hapus semua karakter non-digit.
     *Hasil: angka 10-12 digit tanpa prefix (08 / +62 / 62).
     */
    private static function normalizePhone(string $phone): string
    {
        // Ambil hanya digit
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Hilangkan prefix 62 di awal → ganti jadi 0
        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }

    /**
     * (Tidak digunakan lagi) Kirim pesan WhatsApp via gateway Fonnte (cURL).
     * Disimpan sebagai referensi historis. Mode saat ini hanya simulasi database.
     */
    // private function sendWaMessage(string $nomor_wa, string $otp): bool
    // {
    //     $token  = env('FONNTE_TOKEN', '');
    //     $target = '62' . ltrim($nomor_wa, '0');
    //     ...
    // }
}