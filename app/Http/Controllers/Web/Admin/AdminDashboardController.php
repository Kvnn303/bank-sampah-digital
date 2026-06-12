<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Wajib ditambahkan untuk mengelola file
use Illuminate\Validation\Rules\Password;

class AdminDashboardController extends Controller
{
    // ====== NOTIFIKASI ======

    public function notifikasiIndex()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.notifikasi.index', compact('notifications'));
    }

    public function notifikasiFetch()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();
        $unreadCount = Notification::unread()->count();

        $html = view('admin.notifikasi._partial-list', compact('notifications'))->render();

        return response()->json([
            'html' => $html,
            'unread_count' => $unreadCount,
        ]);
    }

    public function notifikasiMarkRead($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function notifikasiMarkAllRead()
    {
        Notification::unread()->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function notifikasiDelete($id)
    {
        Notification::findOrFail($id)->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    // ====== PROFILE ADMIN ======

    public function profileShow()
    {
        $admin = auth()->user();
        return view('admin.profile.show', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $admin = auth()->user();

        // Tambahkan validasi untuk foto
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $dataToUpdate = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Proses Upload Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari folder storage jika ada
            if ($admin->foto && Storage::disk('public')->exists($admin->foto)) {
                Storage::disk('public')->delete($admin->foto);
            }

            // Simpan foto baru dan masukkan path-nya ke array data yang akan diupdate
            $dataToUpdate['foto'] = $request->file('foto')->store('profile_photos', 'public');
        }

        $admin->update($dataToUpdate);

        return back()->with('success', 'Profil dan foto berhasil diperbarui.');
    }

    public function profileUpdatePassword(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'current_password'      => 'required|current_password',
            'password'              => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'password_confirmation' => 'required',
        ]);

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
