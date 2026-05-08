<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Nasabah\SaldoController;
use App\Http\Controllers\Api\Nasabah\RiwayatController;
use App\Http\Controllers\Api\Nasabah\PenarikanNasabahController;
use App\Http\Controllers\Api\NotificationController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/profile',  [AuthController::class, 'profile']);
    Route::post('/logout',  [AuthController::class, 'logout']);

    // Nasabah
    Route::prefix('nasabah')->group(function () {
        Route::get('/saldo',     [SaldoController::class, 'index']);
        Route::get('/statistik', [SaldoController::class, 'statistik']);
        Route::get('/riwayat',           [RiwayatController::class, 'semua']);
        Route::get('/riwayat/tabungan',  [RiwayatController::class, 'tabungan']);
        Route::get('/riwayat/penarikan', [RiwayatController::class, 'penarikan']);
        Route::get('/penarikan',          [PenarikanNasabahController::class, 'index']);
        Route::post('/penarikan',         [PenarikanNasabahController::class, 'store']);
        Route::delete('/penarikan/{id}',  [PenarikanNasabahController::class, 'batalkan']);
    });

    // Notifikasi
    Route::get('/notifications',              [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read',   [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all',    [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}',      [NotificationController::class, 'destroy']);

});
