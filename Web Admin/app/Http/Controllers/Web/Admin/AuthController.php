<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah admin
            if (!$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun admin.'
                ]);
            }

            $request->session()->regenerate();

            // Catat audit log
            AuditLogService::log(
                action: 'LOGIN',
                module: 'Auth',
                description: "Admin {$user->name} berhasil login",
                status: 'success'
            );

            return redirect()->route('admin.dashboard')
                        ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // Catat login gagal
        AuditLogService::log(
            action: 'LOGIN_FAILED',
            module: 'Auth',
            description: "Login gagal untuk email: {$request->email}",
            status: 'failed'
        );

        // Notif ke admin
        NotificationService::loginGagal($request->email);

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput($request->only('email'));
    }

    // Logout
    public function logout(Request $request)
    {
        $user = Auth::user();

        AuditLogService::log(
            action: 'LOGOUT',
            module: 'Auth',
            description: "Admin {$user->name} logout",
            status: 'success'
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
                        ->with('success', 'Anda berhasil logout.');
    }
}
