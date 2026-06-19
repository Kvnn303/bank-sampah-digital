<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileNotificationController extends Controller
{
    /**
     * GET /api/notifications
     * Ambil 10 notifikasi terbaru milik user yang sedang login
     * (yang sudah dibaca maupun yang belum dibaca).
     * Query params opsional:
     * - unread_only=true   → hanya yang belum dibaca
     * - per_page=10        → default 10 (maks 50)
     * - type=transaksi     → filter berdasarkan type
     */
    public function index(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $query = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($request->boolean('unread_only')) {
            // Menggunakan kolom enum status agar lebih akurat
            $query->where('status', 'unread');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $perPage = min($request->integer('per_page', 10), 50);
        $notifications = $query->take($perPage)->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();

        return response()->json([
            'success'      => true,
            'data'         => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * GET /api/notifications/unread-count
     * Badge count untuk tab-bar / navbar mobile.
     */
    public function unreadCount(): JsonResponse
    {
        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('status', 'unread')
            ->count();

        return response()->json([
            'success'      => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * POST /api/notifications/read/{id}
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(string $id): JsonResponse
    {
        $userId = auth()->id();
        $notif  = Notification::where('user_id', $userId)->find($id);

        if (!$notif) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        // Sinkronisasi kolom is_read dan status
        if ($notif->status === 'unread' || !$notif->is_read) {
            $notif->update([
                'is_read' => true,
                'status'  => 'read',
                'read_at' => now()
            ]);
        }

        $unreadCount = Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();

        return response()->json([
            'success'      => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * POST /api/notifications/read-all
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllAsRead(): JsonResponse
    {
        $userId = auth()->id();

        // Sinkronisasi massal untuk is_read dan status
        Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->update([
                'is_read' => true,
                'status'  => 'read',
                'read_at' => now()
            ]);

        return response()->json([
            'success'      => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * DELETE /api/notifications/{id}
     * Hapus satu notifikasi.
     */
    public function destroy(string $id): JsonResponse
    {
        $userId = auth()->id();
        $notif  = Notification::where('user_id', $userId)->find($id);

        if (!$notif) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        $notif->delete();

        $unreadCount = Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();

        return response()->json([
            'success'      => true,
            'unread_count' => $unreadCount,
        ]);
    }
}
