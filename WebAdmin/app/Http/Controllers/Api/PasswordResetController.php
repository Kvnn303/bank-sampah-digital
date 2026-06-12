<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Tambahkan ini untuk enkripsi password baru

class PasswordResetController extends Controller
{
    // --- TAHAP 1: REQUEST OTP (SUDAH BERHASIL) ---
    public function requestOtp(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'no_telepon' => 'required'
        ]);

        $user = User::where('name', $request->username)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Username tidak ditemukan di sistem kami.'
            ], 404);
        }

        $nasabah = DB::table('nasabah')
                    ->where('user_id', $user->id)
                    ->where('no_telepon', $request->no_telepon)
                    ->first();

        if (!$nasabah) {
            return response()->json([
                'message' => 'Nomor WhatsApp tidak cocok dengan username tersebut.'
            ], 404);
        }

        $otp = rand(100000, 999999);

        OtpCode::create([
            'username' => $request->username,
            'no_telepon' => $request->no_telepon,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_verified' => false
        ]);

        return response()->json([
            'message' => 'OTP berhasil dibuat.',
            'otp_sementara' => $otp
        ], 200);
    }

    // --- TAHAP 2: VERIFIKASI OTP ---
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'otp' => 'required'
        ]);

        // Cari OTP terbaru yang belum terverifikasi dan belum kedaluwarsa
        $otpRecord = OtpCode::where('username', $request->username)
                            ->where('otp', $request->otp)
                            ->where('is_verified', false)
                            ->where('expires_at', '>', Carbon::now())
                            ->latest()
                            ->first();

        if (!$otpRecord) {
            return response()->json([
                'message' => 'Kode OTP salah atau sudah kedaluwarsa!'
            ], 400);
        }

        // Tandai OTP telah sukses diverifikasi
        $otpRecord->update(['is_verified' => true]);

        return response()->json([
            'message' => 'Kode OTP cocok! Silakan buat password baru.'
        ], 200);
    }

    // --- TAHAP 3: UPDATE PASSWORD BARU ---
    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        // Pastikan user ini memang sudah sukses melewati tahap OTP sebelumnya
        $validOtp = OtpCode::where('username', $request->username)
                           ->where('is_verified', true)
                           ->latest()
                           ->first();

        if (!$validOtp) {
            return response()->json([
                'message' => 'Akses ditolak! Anda belum memverifikasi OTP.'
            ], 403);
        }

        // Update password user di tabel users
        $user = User::where('name', $request->username)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Hapus atau bersihkan OTP milik user ini agar tidak bisa dipakai lagi
            OtpCode::where('username', $request->username)->delete();

            return response()->json([
                'message' => 'Password baru berhasil disimpan.'
            ], 200);
        }

        return response()->json([
            'message' => 'Gagal mengubah password. User tidak ditemukan.'
        ], 404);
    }
}
