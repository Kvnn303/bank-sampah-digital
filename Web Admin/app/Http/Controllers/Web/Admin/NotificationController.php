<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        // AJAX fetch dari navbar — kembalikan HTML partial + unread count
        if ($request->ajax() || $request->wantsJson()) {
            $notifications = Notification::latest()->take(10)->get();

            $html = view('admin.notifikasi._partial-list', compact('notifications'))->render();

            return response()->json([
                'success'      => true,
                'html'         => $html,
                'unread_count' => Notification::unread()->count(),
            ]);
        }

        // Halaman penuh
        $notifications = Notification::latest()->paginate(15);
        $unreadCount   = Notification::unread()->count();

        return view('admin.notifikasi.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead(string $id)
    {
        $notif = Notification::findOrFail($id);

        if (! $notif->is_read) {
            $notif->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'      => true,
                'unread_count' => Notification::unread()->count(),
            ]);
        }

        return $notif->url ? redirect($notif->url) : back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllAsRead()
    {
        Notification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'      => true,
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }

    /**
     * Hapus satu notifikasi.
     */
    public function destroy(string $id)
    {
        Notification::findOrFail($id)->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Hapus semua notifikasi yang sudah dibaca.
     */
    public function destroyRead()
    {
        Notification::read()->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi yang sudah dibaca telah dihapus.');
    }
}
