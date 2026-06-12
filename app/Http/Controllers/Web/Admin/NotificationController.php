<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi.
     */
    public function index(Request $request)
    {
        $adminId = auth()->id();

        // AJAX fetch dari navbar — kembalikan HTML partial + unread count
        if ($request->ajax() || $request->wantsJson()) {
            $notifications = Notification::forAdmin($adminId)
                                    ->latest()
                                    ->take(10)
                                    ->get();

            $html = view('admin.notifikasi._partial-list', compact('notifications'))->render();

            return response()->json([
                'success'      => true,
                'html'         => $html,
                'unread_count' => Notification::forAdmin($adminId)->unread()->count(),
            ]);
        }

        // Halaman penuh
        $notifications = Notification::forAdmin($adminId)
                                ->latest()
                                ->paginate(15);
        $unreadCount = Notification::forAdmin($adminId)->unread()->count();

        return view('admin.notifikasi.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead(string $id)
    {
        $notif = Notification::findOrFail($id);
        $adminId = auth()->id();

        // Pastikan notifikasi ini milik admin
        if ($notif->target_role !== 'admin' || ($notif->user_id !== null && $notif->user_id !== $adminId)) {
            abort(403, 'Unauthorized');
        }

        if (!$notif->is_read) {
            $notif->update([
                'is_read' => true,
                'status'  => Notification::STATUS_READ,
                'read_at' => now(),
            ]);
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'      => true,
                'unread_count' => Notification::forAdmin($adminId)->unread()->count(),
            ]);
        }

        return $notif->url ? redirect($notif->url) : back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllAsRead()
    {
        $adminId = auth()->id();

        Notification::forAdmin($adminId)->unread()->update([
            'is_read' => true,
            'status'  => Notification::STATUS_READ,
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
        $notif = Notification::findOrFail($id);
        $adminId = auth()->id();

        // Pastikan notifikasi ini milik admin
        if ($notif->target_role !== 'admin' || ($notif->user_id !== null && $notif->user_id !== $adminId)) {
            abort(403, 'Unauthorized');
        }

        $notif->delete();

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
        $adminId = auth()->id();

        Notification::forAdmin($adminId)->read()->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi yang sudah dibaca telah dihapus.');
    }

    /**
     * Hapus semua notifikasi yang sudah dibaca (alias).
     */
    public function clearRead()
    {
        return $this->destroyRead();
    }
}