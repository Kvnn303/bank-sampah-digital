<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        if ($request->filled('password_changed') && $request->password_changed !== '') {
            $query->where('password_changed', $request->password_changed === '1');
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total'      => User::where('role', 'admin')->count(),
            'aktif'      => User::where('role', 'admin')->where('is_active', true)->count(),
            'nonaktif'   => User::where('role', 'admin')->where('is_active', false)->count(),
            'default_pw' => User::where('role', 'admin')->where('password_changed', false)->count(),
        ];

        return view('admin.kelola-admin.index', compact('admins', 'stats'));
    }

    public function create()
    {
        return view('admin.kelola-admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => $validated['password'],
            'role'              => 'admin',
            'is_active'         => true,
            'password_changed'  => false,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.kelola-admin.index')->with('success', "Admin \"{$validated['name']}\" berhasil ditambahkan");
    }

    public function show(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.kelola-admin.view', compact('admin'));
    }

    public function edit(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.kelola-admin.edit', compact('admin'));
    }

    public function update(Request $request, string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($admin->id)],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password         = $validated['password'];
            $admin->password_changed = true;
        }

        $admin->save();

        return redirect()->route('admin.kelola-admin.index')->with('success', "Admin \"{$admin->name}\" berhasil diperbarui");
    }

    public function destroy(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if (auth()->id() === $admin->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $nama = $admin->name;
        $admin->delete();

        return redirect()->route('admin.kelola-admin.index')->with('success', "Admin \"{$nama}\" berhasil dihapus");
    }

    public function resetPassword(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->password         = 'admin123';
        $admin->password_changed = false;
        $admin->save();

        return back()->with('success', "Password \"{$admin->name}\" berhasil direset ke: admin123");
    }

    public function toggleStatus(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if (auth()->id() === $admin->id) {
            return back()->with('error', 'Tidak bisa nonaktifkan akun sendiri');
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Admin \"{$admin->name}\" berhasil {$status}");
    }
}