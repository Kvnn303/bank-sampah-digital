<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // Validasi field kosong / format salah
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek role admin
            if (! $user->isAdmin()) {
                Auth::logout();

                AuditLogService::log(
                    action:      'LOGIN_BLOCKED',
                    module:      'Auth',
                    description: "Login ditolak — {$request->email} bukan akun admin.",
                    status:      'failed'
                );

                // PERBAIKAN: tambah withInput agar email tidak hilang
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akun ini tidak memiliki akses admin.']);
            }

            $request->session()->regenerate();

            AuditLogService::log(
                action:      'LOGIN',
                module:      'Auth',
                description: "Admin {$user->name} berhasil login.",
                status:      'success'
            );

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // Kredensial salah (email tidak ada ATAU password salah)
        // Sengaja pakai pesan yang sama untuk keduanya
        // agar tidak memberi petunjuk mana yang salah (security best practice)
        AuditLogService::log(
            action:      'LOGIN_FAILED',
            module:      'Auth',
            description: "Login gagal untuk email: {$request->email}",
            status:      'failed'
        );

        NotificationService::loginGagal($request->email);

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.']);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        AuditLogService::log(
            action:      'LOGOUT',
            module:      'Auth',
            description: "Admin {$user->name} logout.",
            status:      'success'
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }
}
