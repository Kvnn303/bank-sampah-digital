<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * GET /api/notifications
     * Mengembalikan seluruh notifikasi milik user yang sedang login (terbaru dulu).
     */
    public function index(): JsonResponse
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'data' => $notifications,
        ]);
    }

    /**
     * POST /api/notifications/{id}/read
     * Mengubah is_read = true, status = 'read', dan read_at = now() untuk satu notifikasi.
     */
    public function markAsRead(string $id): JsonResponse
    {
        $notif = Notification::where('user_id', auth()->id())->find($id);

        if (!$notif) {
            return response()->json(['message' => 'Notifikasi tidak ditemukan'], 404);
        }

        // Sinkronisasi pembaruan untuk kedua kolom
        if ($notif->status === 'unread' || !$notif->is_read) {
            $notif->update([
                'is_read' => true,
                'status'  => 'read',
                'read_at' => now()
            ]);
        }

        return response()->json([
            'message' => 'Notifikasi ditandai sudah dibaca',
        ]);
    }

    /**
     * POST /api/notifications/read-all
     * Mengubah semua is_read = true dan status = 'read' untuk seluruh notifikasi milik user yang belum dibaca.
     */
    public function markAllAsRead(): JsonResponse
    {
        // Cari yang is_read-nya false atau statusnya 'unread', lalu perbarui keduanya
        $updated = Notification::where('user_id', auth()->id())
            ->where(function($query) {
                $query->where('is_read', false)
                      ->orWhere('status', 'unread');
            })
            ->update([
                'is_read' => true,
                'status'  => 'read',
                'read_at' => now()
            ]);

        return response()->json([
            'message' => "{$updated} notifikasi ditandai sudah dibaca",
        ]);
    }
}
