<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExpoPushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * PushTokenController
 * Menangani registrasi dan penghapusan token Expo Push Notification.
 *
 * Alur:
 * 1. Saat aplikasi mobile pertama kali dibuka / login, kirim token ke endpoint ini
 * 2. Backend menyimpan token ke kolom expo_push_token di tabel users dan nasabah
 * 3. Saat ada event (setoran, penarikan, dll), server kirim push ke token ini
 * 4. Saat user logout atau uninstall, hapus token via DELETE /api/push-token
 */
class PushTokenController extends Controller
{
    /**
     * POST /api/push-token
     * Daftarkan atau perbarui Expo push token untuk user yang sedang login.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'expo_push_token' => 'required|string|max:200',
        ]);

        $token = $request->expo_push_token;
        $user  = $request->user();

        // Validasi format token
        if (!ExpoPushNotificationService::isValidToken($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Format token tidak valid. Gunakan Expo Push Token yang benar.',
            ], 422);
        }

        // Simpan ke tabel users
        $user->update(['expo_push_token' => $token]);

        // Simpan juga ke tabel nasabah jika ada (untuk kemudahan query push ke nasabah)
        if ($user->nasabah) {
            $user->nasabah->update(['expo_push_token' => $token]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Push notification token berhasil didaftarkan.',
        ]);
    }

    /**
     * DELETE /api/push-token
     * Hapus Expo push token (misalnya saat logout atau user mematikan notifikasi).
     */
    public function remove(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->update(['expo_push_token' => null]);

        if ($user->nasabah) {
            $user->nasabah->update(['expo_push_token' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Push notification token berhasil dihapus.',
        ]);
    }
}
