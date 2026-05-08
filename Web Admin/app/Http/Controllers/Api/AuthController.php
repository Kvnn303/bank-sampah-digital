<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nasabah;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\AuditLog;

class AuthController extends Controller
{
    // Register Nasabah
    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6|confirmed',
            'nama_lengkap' => 'required|string',
            'alamat'       => 'nullable|string',
            'no_telepon'   => 'nullable|string',
            'no_ktp'       => 'nullable|string|unique:nasabah',
            'foto_ktp'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'nasabah',
        ]);

        Nasabah::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $request->nama_lengkap,
            'alamat'           => $request->alamat,
            'no_telepon'       => $request->no_telepon,
            'no_ktp'           => $request->no_ktp,
            'foto_ktp'         => $fotoKtpPath,
            'status_akun'      => 'pending',
            'sumber_daftar'    => 'mandiri',
            'tanggal_bergabung'=> now()->toDateString(),
        ]);

        AuditLogService::log(
            action: 'REGISTER',
            module: 'Auth',
            description: "Nasabah baru mendaftar: {$user->name}",
            status: 'success'
        );

        return response()->json([
            'message' => 'Registrasi berhasil! Menunggu verifikasi admin.',
            'user'    => $user,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            AuditLog::create([
                'user_name'   => $request->email,
                'action'      => 'LOGIN_FAILED',
                'module'      => 'Auth',
                'description' => "Login gagal untuk email: {$request->email}",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Cek akun dinonaktifkan
        if ($user->email_verified_at === null) {
            AuditLog::create([
                'user_name'   => $user->name,
                'action'      => 'LOGIN_BLOCKED',
                'module'      => 'Auth',
                'description' => "Login ditolak, akun {$user->name} dinonaktifkan",
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'failed',
            ]);

            throw ValidationException::withMessages([
                'email' => ['Akun Anda dinonaktifkan. Hubungi super admin.'],
            ]);
        }

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        AuditLogService::log(
            action: 'LOGIN',
            module: 'Auth',
            description: "User {$user->name} ({$user->role}) berhasil login",
            status: 'success'
        );

        return response()->json([
            'message'           => 'Login berhasil',
            'user'              => $user,
            'role'              => $user->role,
            'token'             => $token,
            'must_change_password' => $user->isAdmin() && ! $user->password_changed,
        ]);
    }

    // Profile
    public function profile(Request $request)
    {
        $user = $request->user();
        $nasabah = $user->nasabah;

        return response()->json([
            'user'    => $user,
            'nasabah' => $nasabah,
            'saldo'   => $nasabah?->saldo ?? 0,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        AuditLogService::log(
            action: 'LOGOUT',
            module: 'Auth',
            description: "User {$request->user()->name} logout",
            status: 'success'
        );

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
