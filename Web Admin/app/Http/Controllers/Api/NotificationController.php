<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * GET /api/notifications
     */
    public function index(Request $request)
    {
        $nasabahId = auth()->id();
        $query = Notification::forNasabah($nasabahId);

        if ($request->has('page')) {
            $notifications = $query->latest()->paginate(10);
        } else {
            $notifications = $query->latest()->take(10)->get();
        }

        return response()->json([
            'success'      => true,
            'data'         => $notifications,
            'unread_count' => Notification::forNasabah($nasabahId)->unread()->count(),
        ]);
    }

    /**
     * POST /api/notifications/{id}/read
     */
    public function markAsRead(string $id)
    {
        $notif = Notification::forNasabah(auth()->id())->findOrFail($id);

        if (! $notif->is_read) {
            $notif->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json([
            'success'      => true,
            'unread_count' => Notification::forNasabah(auth()->id())->unread()->count(),
        ]);
    }

    /**
     * POST /api/notifications/read-all
     */
    public function markAllAsRead()
    {
        Notification::forNasabah(auth()->id())->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true, 'unread_count' => 0]);
    }

    /**
     * DELETE /api/notifications/{id}
     */
    public function destroy(string $id)
    {
        Notification::forNasabah(auth()->id())->findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
