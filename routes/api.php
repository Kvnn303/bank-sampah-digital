<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Nasabah\SaldoController;
use App\Http\Controllers\Api\Nasabah\RiwayatController;
use App\Http\Controllers\Api\Nasabah\PenarikanNasabahController;
use App\Http\Controllers\Api\MobileNotificationController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\PasswordResetOtpController;
use App\Http\Controllers\Api\Nasabah\KatalogController;
use App\Http\Controllers\Api\PushTokenController;

// ==========================================
// PUBLIC ROUTES
// ==========================================

// Register: dibatasi 3 kali per jam per IP (anti spam pendaftaran)
Route::middleware('throttle:3,60')->post('/register', [AuthController::class, 'register']);

// Login: dibatasi 10 kali per menit per IP (brute force tambahan)
Route::middleware('throttle:10,1')->post('/login', [AuthController::class, 'login']);

// Lupa Password: dibatasi 5 kali per 15 menit (anti OTP abuse)
Route::middleware('throttle:5,15')->group(function () {
    Route::post('/forgot-password/request-otp',  [PasswordResetOtpController::class, 'requestOtp']);
    Route::post('/forgot-password/send-otp',     [PasswordResetOtpController::class, 'requestOtp']); // alias
    Route::post('/forgot-password/verify-otp',   [PasswordResetOtpController::class, 'verifyOtp']);
    Route::post('/forgot-password/reset-password', [PasswordResetOtpController::class, 'resetPassword']);
});

// Artikel (Publik) - dibatasi 60 per menit
Route::middleware('throttle:60,1')->get('/artikels', [ArtikelController::class, 'index']);

// ==========================================
// PROTECTED ROUTES
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profil (Berlaku untuk Admin & Nasabah)
    Route::get('/profile',                  [AuthController::class, 'profile']);
    Route::post('/profile/update',          [AuthController::class, 'updateProfile']);
    Route::post('/profile/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout',                  [AuthController::class, 'logout']);

    // ── Push Notification Token ──
    // Endpoint untuk update/register token Expo Push Notification dari device
    Route::post('/push-token', [PushTokenController::class, 'update']);
    Route::delete('/push-token', [PushTokenController::class, 'remove']);

    // Notifikasi (Mobile Banking)
    Route::prefix('notifications')->group(function () {
        Route::get('/',               [MobileNotificationController::class, 'index']);
        Route::get('/unread-count',   [MobileNotificationController::class, 'unreadCount']);
        Route::post('/read-all',      [MobileNotificationController::class, 'markAllAsRead']);
        Route::post('/read/{id}',     [MobileNotificationController::class, 'markAsRead']);
        Route::delete('/{id}',        [MobileNotificationController::class, 'destroy']);
    });

    // Modul Nasabah (Hanya Nasabah)
    Route::prefix('nasabah')->group(function () {
        // Saldo & Statistik
        Route::get('/saldo',     [SaldoController::class, 'index']);
        Route::get('/statistik', [SaldoController::class, 'statistik']);

        // Riwayat Transaksi
        Route::get('/riwayat',           [RiwayatController::class, 'semua']);
        Route::get('/riwayat/tabungan',  [RiwayatController::class, 'tabungan']);
        Route::get('/riwayat/penarikan', [RiwayatController::class, 'penarikan']);

        // Katalog Harga Sampah
        Route::get('/katalog-sampah', [KatalogController::class, 'index']);

        // Penarikan Saldo
        Route::get('/penarikan',         [PenarikanNasabahController::class, 'index']);
        Route::post('/penarikan',        [PenarikanNasabahController::class, 'store']);
        Route::delete('/penarikan/{id}', [PenarikanNasabahController::class, 'batalkan']);
    });
});

