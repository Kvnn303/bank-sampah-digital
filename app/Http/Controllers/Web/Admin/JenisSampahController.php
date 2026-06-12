<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\RiwayatHargaSampah;
use App\Models\Tabungan;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use App\Traits\NotifiableTrait;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    use NotifiableTrait;
    // Tampilkan semua jenis sampah DENGAN PAGINATION
    public function index(Request $request)
    {
        // Ambil parameter filter
        $search   = $request->get('search');
        $status   = $request->get('status');
        $kategori = $request->get('kategori');

        // Query dengan filter
        $query = JenisSampah::withCount('tabungan');

        // Filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status == '1');
        }

        // Filter kategori
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Gunakan paginate(10) bukannya get()
        $jenisSampah = $query->orderBy('kategori')
                             ->orderBy('nama')
                             ->paginate(10)
                             ->appends($request->query()); // Mempertahankan filter di pagination

        $totalAktif    = JenisSampah::where('is_active', true)->count();
        $totalNonaktif = JenisSampah::where('is_active', false)->count();

        return view('admin.jenis-sampah.index', compact(
            'jenisSampah',
            'totalAktif',
            'totalNonaktif'
        ));
    }

    // Detail jenis sampah
    public function show($id)
    {
        $sampah  = JenisSampah::withCount('tabungan')->findOrFail($id);
        $riwayat = RiwayatHargaSampah::with('diubahOleh:id,name')
                    ->where('jenis_sampah_id', $id)
                    ->orderByDesc('created_at')
                    ->get();

        // Statistik penggunaan
        $totalKg    = Tabungan::where('jenis_sampah_id', $id)->sum('berat_kg');
        $totalNilai = Tabungan::where('jenis_sampah_id', $id)->sum('nilai_rupiah');
        $bulanIniKg = Tabungan::where('jenis_sampah_id', $id)
                        ->whereMonth('tanggal_setor', now()->month)
                        ->whereYear('tanggal_setor', now()->year)
                        ->sum('berat_kg');
        $tahunIniKg = Tabungan::where('jenis_sampah_id', $id)
                        ->whereYear('tanggal_setor', now()->year)
                        ->sum('berat_kg');

        // Tabungan terbaru pakai jenis ini
        $tabunganTerbaru = Tabungan::with('nasabah')
                            ->where('jenis_sampah_id', $id)
                            ->orderByDesc('tanggal_setor')
                            ->take(10)
                            ->get();

        return view('admin.jenis-sampah.show', compact(
            'sampah',
            'riwayat',
            'totalKg',
            'totalNilai',
            'bulanIniKg',
            'tahunIniKg',
            'tabunganTerbaru'
        ));
    }

    // Form tambah
    public function create()
    {
        return view('admin.jenis-sampah.create');
    }

    // Simpan
    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:255',
            'harga_per_kg' => 'required|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ], [
            'nama.required'         => 'Nama jenis sampah wajib diisi',
            'harga_per_kg.required' => 'Harga per kg wajib diisi',
            'harga_per_kg.min'      => 'Harga tidak boleh negatif',
        ]);

        $sampah = JenisSampah::create([
            'nama'         => $request->nama,
            'kategori'     => $request->kategori,
            'harga_per_kg' => $request->harga_per_kg,
            'keterangan'   => $request->keterangan,
            'is_active'    => true,
        ]);

        AuditLogService::log(
            action: 'SAMPAH_TAMBAH',
            module: 'JenisSampah',
            description: "Admin menambahkan jenis sampah: {$sampah->nama} Rp{$sampah->harga_per_kg}/kg",
        );

        // ✅ Notif ke admin
        $harga = 'Rp' . number_format($sampah->harga_per_kg, 0, ',', '.');
        NotificationService::sampahDitambah($sampah->nama, $harga);

        return redirect()->route('admin.jenis-sampah.index')
                        ->with('success', 'Jenis sampah berhasil ditambahkan!');
    }

    // Form edit
    public function edit($id)
    {
        $sampah  = JenisSampah::findOrFail($id);
        $riwayat = RiwayatHargaSampah::with('diubahOleh:id,name')
                    ->where('jenis_sampah_id', $id)
                    ->orderByDesc('created_at')
                    ->take(5)
                    ->get();

        return view('admin.jenis-sampah.edit', compact('sampah', 'riwayat'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active'  => 'nullable|boolean',
        ]);

        $sampah  = JenisSampah::findOrFail($id);
        $oldData = $sampah->toArray();

        $sampah->update([
            'nama'       => $request->nama,
            'kategori'   => $request->kategori,
            'keterangan' => $request->keterangan,
            'is_active'  => $request->has('is_active'),
        ]);

        AuditLogService::log(
            action: 'SAMPAH_EDIT',
            module: 'JenisSampah',
            description: "Admin mengedit jenis sampah: {$sampah->nama}",
            oldData: $oldData,
            newData: $sampah->toArray()
        );

        // ✅ Notif kalau status aktif/nonaktif berubah
        if ($sampah->wasChanged('is_active')) {
            if ($sampah->is_active) {
                NotificationService::sampahDiaktifkan($sampah->nama);
            } else {
                NotificationService::sampahDinonaktifkan($sampah->nama);
            }
        }

        return redirect()->route('admin.jenis-sampah.index')
                        ->with('success', 'Jenis sampah berhasil diperbarui!');
    }

    // Update harga
    public function updateHarga(Request $request, $id)
    {
        $request->validate([
            'harga_per_kg' => 'required|numeric|min:0',
            'alasan'       => 'required|string|max:500',
        ], [
            'harga_per_kg.required' => 'Harga per kg wajib diisi',
            'harga_per_kg.numeric'  => 'Harga harus berupa angka',
            'harga_per_kg.min'      => 'Harga tidak boleh negatif',
            'alasan.required'       => 'Alasan perubahan wajib diisi untuk dokumentasi',
            'alasan.max'            => 'Alasan terlalu panjang, maksimal 500 karakter',
        ]);

        $sampah = JenisSampah::findOrFail($id);
        $hargaLama = (float) $sampah->harga_per_kg;
        $hargaBaru = (float) $request->input('harga_per_kg');

        // Cek apakah harga benar-benar berubah
        if (abs($hargaLama - $hargaBaru) < 0.01) {
            return redirect()->route('admin.jenis-sampah.index')
                            ->with('error', 'Harga baru sama dengan harga lama. Silakan masukkan harga yang berbeda!');
        }

        // Update harga aktif
        $sampah->update(['harga_per_kg' => $hargaBaru]);

        // Buat riwayat harga
        RiwayatHargaSampah::create([
            'jenis_sampah_id' => $sampah->id,
            'harga_lama'      => $hargaLama,
            'harga_baru'      => $hargaBaru,
            'alasan'          => $request->input('alasan'),
            'diubah_oleh'     => auth()->id(),
        ]);

        // Audit log
        AuditLogService::log(
            action: 'HARGA_UPDATE',
            module: 'JenisSampah',
            description: "Harga {$sampah->nama} diubah dari Rp{$hargaLama} ke Rp{$hargaBaru}. Alasan: {$request->input('alasan')}",
            oldData: ['harga_per_kg' => $hargaLama],
            newData: ['harga_per_kg' => $hargaBaru]
        );

        // Notif ke admin
        $old = 'Rp' . number_format($hargaLama, 0, ',', '.');
        $new = 'Rp' . number_format($hargaBaru, 0, ',', '.');
        NotificationService::sampahDiubah($sampah->nama, $old, $new);

        // Notif massal ke seluruh nasabah
        NotificationService::hargaSampahBerubahNasabah($sampah->nama, $new);

        // 🔔 TRIGGER MOBILE BANKING: Notifikasi harga sampah berubah
        $this->notifyHargaBerubah($sampah->nama, $new);

        return redirect()->route('admin.jenis-sampah.index')
                        ->with('success', "Harga {$sampah->nama} berhasil diperbarui dari {$old} menjadi {$new}");
    }

    // Toggle status aktif/nonaktif
    public function toggleStatus($id)
    {
        $sampah    = JenisSampah::findOrFail($id);
        $newStatus = !$sampah->is_active;
        $sampah->update(['is_active' => $newStatus]);

        $action = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        AuditLogService::log(
            action: 'SAMPAH_TOGGLE',
            module: 'JenisSampah',
            description: "Admin {$action} jenis sampah: {$sampah->nama}",
        );

        // Notif ke admin
        if ($newStatus) {
            NotificationService::sampahDiaktifkan($sampah->nama);
        } else {
            NotificationService::sampahDinonaktifkan($sampah->nama);
        }

        return redirect()->route('admin.jenis-sampah.index')
                        ->with('success', "Jenis sampah {$sampah->nama} berhasil {$action}!");
    }

    // Hapus
    public function destroy($id)
    {
        $sampah = JenisSampah::findOrFail($id);

        if ($sampah->tabungan()->count() > 0) {
            return redirect()->route('admin.jenis-sampah.index')
                            ->with('error', "Jenis sampah {$sampah->nama} tidak bisa dihapus karena sudah pernah digunakan di {$sampah->tabungan()->count()} transaksi tabungan!");
        }

        AuditLogService::log(
            action: 'SAMPAH_HAPUS',
            module: 'JenisSampah',
            description: "Admin menghapus jenis sampah: {$sampah->nama}",
        );

        $sampah->delete();

        return redirect()->route('admin.jenis-sampah.index')
                        ->with('success', 'Jenis sampah berhasil dihapus!');
    }
}
