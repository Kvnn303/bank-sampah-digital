<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
    // Tampilkan semua nasabah
    public function index(Request $request)
    {
        // Load saldo real-time via subquery (HANYA 3 query total, anti N+1):
        // 1) SELECT nasabah + with('user')
        // 2) SELECT SUM(nilai_rupiah), SUM(berat_kg) GROUP BY nasabah_id
        // 3) SELECT SUM(nominal) WHERE status IN (...) GROUP BY nasabah_id
        $query = Nasabah::with('user')
            ->withSum([
                'tabungan as tabungan_sum_nilai_rupiah' => function ($q) {
                    $q->select(DB::raw('COALESCE(SUM(nilai_rupiah), 0)'));
                },
                'tabungan as tabungan_sum_berat_kg' => function ($q) {
                    $q->select(DB::raw('COALESCE(SUM(berat_kg), 0)'));
                },
            ], null)
            ->withSum([
                'penarikan as penarikan_aktif_sum_nominal' => function ($q) {
                    $q->select(DB::raw('COALESCE(SUM(nominal), 0)'))
                      ->where('status', 'selesai');
                },
            ], null)
            ->orderByDesc('nasabah.created_at');

        // Filter by status
        if ($request->status) {
            $query->where('nasabah.status_akun', $request->status);
        }

        // Filter by sumber
        if ($request->sumber) {
            $query->where('nasabah.sumber_daftar', $request->sumber);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nasabah.nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('nasabah.no_ktp', 'like', "%{$request->search}%")
                  ->orWhere('nasabah.no_telepon', 'like', "%{$request->search}%");
            });
        }

        $nasabah      = $query->paginate(10);
        $totalNasabah = Nasabah::count();
        $totalPending = Nasabah::where('status_akun', 'pending')->count();
        $totalActive  = Nasabah::where('status_akun', 'active')->count();
        $totalNonaktif= Nasabah::where('status_akun', 'nonaktif')->count();

        return view('admin.nasabah.index', compact(
            'nasabah',
            'totalNasabah',
            'totalPending',
            'totalActive',
            'totalNonaktif'
        ));
    }

    // Detail nasabah
    public function show($id)
    {
        $nasabah = Nasabah::with([
            'user',
            'tabungan.jenisSampah',
            'penarikan'
        ])->findOrFail($id);

        return view('admin.nasabah.show', compact('nasabah'));
    }

    // Form tambah nasabah
    public function create()
    {
        return view('admin.nasabah.create');
    }

    // Simpan nasabah baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'email'        => 'required|email|unique:users',
            'no_telepon'   => 'nullable|string',
            'no_ktp'       => 'nullable|string|unique:nasabah',
            'alamat'       => 'nullable|string',
            'foto_ktp'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required'        => 'Email wajib diisi',
            'email.unique'          => 'Email sudah terdaftar',
            'no_ktp.unique'         => 'No KTP sudah terdaftar',
            'foto.image'            => 'File harus berupa gambar',
            'foto.max'              => 'Ukuran foto maksimal 2MB',
        ]);

        // Simpan foto KTP
        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        // Simpan foto profil
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-nasabah', 'public');
        }

        // Password default
        $passwordDefault = $request->no_telepon ?? $request->no_ktp ?? 'banksampah123';

        // Buat akun user
        $user = User::create([
            'name'             => $request->nama_lengkap,
            'email'            => $request->email,
            'password'         => Hash::make($passwordDefault),
            'role'             => 'nasabah',
            'password_changed' => false,
        ]);

        // Buat data nasabah
        $nasabah = Nasabah::create([
            'user_id'          => $user->id,
            'nama_lengkap'     => $request->nama_lengkap,
            'alamat'           => $request->alamat,
            'no_telepon'       => $request->no_telepon,
            'no_ktp'           => $request->no_ktp,
            'foto_ktp'         => $fotoKtpPath,
            'foto'             => $fotoPath,
            'status_akun'      => 'verified',
            'sumber_daftar'    => 'admin',
            'tanggal_bergabung'=> now()->toDateString(),
        ]);

        AuditLogService::log(
            action: 'NASABAH_TAMBAH',
            module: 'Nasabah',
            description: "Admin menambahkan nasabah: {$nasabah->nama_lengkap}",
        );

        // Notif ke admin
        NotificationService::nasabahBaru($nasabah->nama_lengkap);

        return redirect()->route('admin.nasabah.index')
                        ->with('success', 'Nasabah berhasil ditambahkan! Password default: ' . $passwordDefault);
    }

    // Form edit nasabah
    public function edit($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        return view('admin.nasabah.edit', compact('nasabah'));
    }

    // Update nasabah
    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $request->validate([
            'nama_lengkap'  => 'nullable|string',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string',
            'no_ktp'        => 'nullable|string|unique:nasabah,no_ktp,' . $nasabah->id,
            'status_akun'   => 'nullable|in:pending,verified,active,nonaktif',
            'catatan_admin' => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'no_ktp.unique' => 'No KTP sudah terdaftar',
            'foto.image'    => 'File harus berupa gambar',
            'foto.max'      => 'Ukuran foto maksimal 2MB',
        ]);

        // Handle upload foto profil baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($nasabah->foto && Storage::disk('public')->exists($nasabah->foto)) {
                Storage::disk('public')->delete($nasabah->foto);
            }
            $nasabah->foto = $request->file('foto')->store('foto-nasabah', 'public');
        }

        $nasabah->update($request->only([
            'nama_lengkap', 'alamat',
            'no_telepon', 'no_ktp', 'status_akun', 'catatan_admin'
        ]));

        AuditLogService::log(
            action: 'NASABAH_EDIT',
            module: 'Nasabah',
            description: "Admin mengedit nasabah: {$nasabah->nama_lengkap}",
        );

        return redirect()->route('admin.nasabah.index')
                        ->with('success', 'Data nasabah berhasil diperbarui!');
    }

    // Verifikasi nasabah
    public function verifikasi(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $status  = $request->status ?? 'verified';

        $nasabah->update([
            'status_akun'   => $status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        AuditLogService::log(
            action: 'NASABAH_VERIFIKASI',
            module: 'Nasabah',
            description: "Admin memverifikasi nasabah: {$nasabah->nama_lengkap}",
        );

        // Notif ke admin + nasabah
        if ($status === 'verified' || $status === 'active') {
            NotificationService::nasabahVerifikasi($nasabah->nama_lengkap);
            NotificationService::akunDiverifikasi($nasabah->user_id);
        }

        return redirect()->route('admin.nasabah.index')
                        ->with('success', 'Nasabah berhasil diverifikasi!');
    }

    // Nonaktifkan nasabah
    public function nonaktifkan($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->update(['status_akun' => 'nonaktif']);

        AuditLogService::log(
            action: 'NASABAH_NONAKTIF',
            module: 'Nasabah',
            description: "Admin menonaktifkan nasabah: {$nasabah->nama_lengkap}",
        );

        // Notif ke admin + nasabah
        NotificationService::nasabahNonaktif($nasabah->nama_lengkap);
        NotificationService::akunDinonaktifkan($nasabah->user_id);

        return redirect()->route('admin.nasabah.index')
                        ->with('success', 'Nasabah berhasil dinonaktifkan!');
    }

    // Aktifkan kembali nasabah
    public function aktifkan($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->update(['status_akun' => 'active']);

        AuditLogService::log(
            action: 'NASABAH_AKTIF',
            module: 'Nasabah',
            description: "Admin mengaktifkan kembali nasabah: {$nasabah->nama_lengkap}",
        );

        // Notif ke admin
        NotificationService::nasabahAktifkan($nasabah->nama_lengkap);

        return redirect()->route('admin.nasabah.index')
                        ->with('success', 'Nasabah berhasil diaktifkan kembali!');
    }

    // Reset password nasabah
    public function resetPassword($id)
    {
        $nasabah      = Nasabah::with('user')->findOrFail($id);
        $passwordBaru = $nasabah->no_telepon ?? $nasabah->no_ktp ?? 'banksampah123';

        $nasabah->user->update([
            'password'         => Hash::make($passwordBaru),
            'password_changed' => false,
        ]);

        AuditLogService::log(
            action: 'NASABAH_RESET_PASSWORD',
            module: 'Nasabah',
            description: "Admin mereset password nasabah: {$nasabah->nama_lengkap}",
        );

        // Notif ke nasabah
        NotificationService::passwordDireset($nasabah->user_id);

        return redirect()->route('admin.nasabah.index')
                        ->with('success', "Password direset! Password baru: {$passwordBaru}");
    }
}
