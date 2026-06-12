<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokSampah;
use App\Models\JenisSampah;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StokSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /admin/stok-sampah
     */
    public function index(Request $request)
    {
        $search      = $request->get('search');
        $status      = $request->get('status');
        $jenisId     = $request->get('jenis_id');
        $tanggalFrom = $request->get('tanggal_from');
        $tanggalTo   = $request->get('tanggal_to');
        $isPres      = $request->get('is_pres');

        $query = StokSampah::with(['jenisSampah', 'dicatatOleh'])
            ->select('stok_sampah.*');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('jenisSampah', function($jq) use ($search) {
                    $jq->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('nama_pembeli', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        if ($jenisId) {
            $query->where('jenis_sampah_id', $jenisId);
        }

        if ($tanggalFrom) {
            $query->whereDate('tanggal_masuk', '>=', $tanggalFrom);
        }
        if ($tanggalTo) {
            $query->whereDate('tanggal_masuk', '<=', $tanggalTo);
        }

        if ($isPres !== null && $isPres !== '') {
            $query->where('is_pres', $isPres == '1');
        }

        $stokSampah = $query->orderByDesc('tanggal_masuk')
                            ->orderByDesc('created_at')
                            ->paginate(10)
                            ->appends($request->query());

        $statistik = StokSampah::statistik();
        $jenisSampahList = JenisSampah::where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('admin.stok-sampah.index', compact(
            'stokSampah',
            'statistik',
            'jenisSampahList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/stok-sampah/create
     */
    public function create()
    {
        $jenisSampahList = JenisSampah::where('is_active', true)
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        return view('admin.stok-sampah.create', compact('jenisSampahList'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /admin/stok-sampah
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_sampah_id'    => 'required|exists:jenis_sampah,id',
            'stok_masuk_kg'      => 'required|numeric|min:0.01',
            'harga_jual_per_kg'  => 'required|numeric|min:0',
            'tanggal_masuk'      => 'required|date',
            'is_pres'            => 'nullable|boolean',
            'is_published'       => 'nullable|boolean',
            'gambar'             => 'nullable|image|max:2048',
            'keterangan'         => 'nullable|string',
        ], [
            'jenis_sampah_id.required' => 'Jenis sampah wajib dipilih',
            'stok_masuk_kg.required'   => 'Berat masuk wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $stokTersisa = $request->stok_masuk_kg;

            $stokData = [
                'jenis_sampah_id'   => $request->jenis_sampah_id,
                'stok_masuk_kg'     => $request->stok_masuk_kg,
                'stok_terjual_kg'   => 0,
                'stok_tersisa_kg'   => $stokTersisa,
                'harga_jual_per_kg' => $request->harga_jual_per_kg,
                'total_pendapatan'  => 0,
                'status'            => 'tersedia',
                'tanggal_masuk'     => $request->tanggal_masuk,
                'is_pres'          => $request->has('is_pres'),
                'is_published'      => $request->has('is_published'),
                'keterangan'        => $request->keterangan,
                'dicatat_oleh'      => auth()->id(),
            ];

            // Handle upload foto
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = 'stok-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('stok-sampah', $filename, 'public');
                $stokData['gambar'] = 'stok-sampah/' . $filename;
            }

            $stok = StokSampah::create($stokData);

            DB::commit();

            // Audit Log
            $jenis = JenisSampah::find($request->jenis_sampah_id);
            $pressStatus = $request->has('is_pres') ? ' (di-press)' : '';
            $publishStatus = $request->has('is_published') ? ' dipublikasikan' : '';

            AuditLogService::log(
                action: 'STOK_TAMBAH',
                module: 'StokSampah',
                description: "Admin menambah stok {$jenis->nama} seberat {$request->stok_masuk_kg} kg{$pressStatus}{$publishStatus}",
            );

            NotificationService::stokDitambahkan(
                $jenis->nama,
                $request->stok_masuk_kg,
                'Rp ' . number_format($request->harga_jual_per_kg, 0, ',', '.') . '/kg'
            );

            return redirect()->route('admin.stok-sampah.index')
                ->with('success', 'Stok sampah berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /admin/stok-sampah/{id}
     */
    public function show($id)
    {
        $stok = StokSampah::with(['jenisSampah', 'dicatatOleh'])
            ->findOrFail($id);

        $statsPerJenis = StokSampah::where('jenis_sampah_id', $stok->jenis_sampah_id)
            ->selectRaw('
                SUM(stok_masuk_kg) as total_masuk,
                SUM(stok_terjual_kg) as total_terjual,
                SUM(stok_tersisa_kg) as total_tersisa,
                SUM(total_pendapatan) as total_pendapatan,
                COUNT(*) as jumlah_transaksi
            ')
            ->first();

        return view('admin.stok-sampah.show', compact('stok', 'statsPerJenis'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /admin/stok-sampah/{id}/edit
     */
    public function edit($id)
    {
        $stok = StokSampah::findOrFail($id);

        $jenisSampahList = JenisSampah::where('is_active', true)
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        return view('admin.stok-sampah.edit', compact('stok', 'jenisSampahList'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /admin/stok-sampah/{id}
     */
    public function update(Request $request, $id)
    {
        $stok = StokSampah::findOrFail($id);

        $request->validate([
            'jenis_sampah_id'    => 'required|exists:jenis_sampah,id',
            'stok_masuk_kg'      => 'required|numeric|min:0.01',
            'harga_jual_per_kg'  => 'required|numeric|min:0',
            'tanggal_masuk'      => 'required|date',
            'is_pres'            => 'nullable|boolean',
            'is_published'       => 'nullable|boolean',
            'keterangan'         => 'nullable|string',
            'gambar'             => 'nullable|image|max:2048', // max 2MB
        ], [
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $oldData = $stok->toArray();

        DB::beginTransaction();
        try {
            $stokTersisa = $stok->stok_terjual_kg > 0
                ? $request->stok_masuk_kg - $stok->stok_terjual_kg
                : $request->stok_masuk_kg;

            $status = 'tersedia';
            if ((float) $stokTersisa <= 0) {
                $status = 'terjual';
            } elseif ($stok->stok_terjual_kg > 0) {
                $status = 'sebagian';
            }

            $updateData = [
                'jenis_sampah_id'   => $request->jenis_sampah_id,
                'stok_masuk_kg'     => $request->stok_masuk_kg,
                'stok_tersisa_kg'   => max(0, $stokTersisa),
                'harga_jual_per_kg' => $request->harga_jual_per_kg,
                'status'            => $status,
                'tanggal_masuk'     => $request->tanggal_masuk,
                'is_pres'          => $request->has('is_pres'),
                'is_published'      => $request->has('is_published'),
                'keterangan'        => $request->keterangan,
            ];

            // Handle upload gambar baru
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($stok->gambar && Storage::disk('public')->exists($stok->gambar)) {
                    Storage::disk('public')->delete($stok->gambar);
                }

                // Simpan gambar baru
                $file = $request->file('gambar');
                $filename = 'stok-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('stok-sampah', $filename, 'public');
                $updateData['gambar'] = 'stok-sampah/' . $filename;
            }

            $stok->update($updateData);

            DB::commit();

            AuditLogService::log(
                action: 'STOK_EDIT',
                module: 'StokSampah',
                description: "Admin mengedit stok ID #{$stok->id}",
                oldData: $oldData,
                newData: $stok->toArray()
            );

            NotificationService::stokDiubah(
                $stok->jenisSampah->nama,
                $stok->id
            );

            return redirect()->route('admin.stok-sampah.index')
                ->with('success', 'Stok sampah berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle publish status.
     * POST /admin/stok-sampah/{id}/toggle-publish
     */
    public function togglePublish($id)
    {
        try {
            $stok = StokSampah::with('jenisSampah')->findOrFail($id);
            $oldStatus = $stok->is_published;
            $stok->update(['is_published' => !$oldStatus]);

            $namaSampah = $stok->jenisSampah->nama ?? 'Unknown';
            $newStatus = !$oldStatus ? 'dipublikasikan' : 'di-nonaktifkan';

            AuditLogService::log(
                action: 'STOK_PUBLISH',
                module: 'StokSampah',
                description: "Admin {$newStatus} stok ID #{$stok->id} ({$namaSampah})",
            );

            NotificationService::toAdmin(
                'stok',
                'Stok Diperbarui',
                "Stok {$namaSampah} {$newStatus} untuk pihak ketiga."
            );

            return redirect()->back()
                ->with('success', "Stok berhasil {$newStatus}!");
        } catch (\Exception $e) {
            \Log::error('Error toggle publish stok: ' . $e->getMessage(), ['stok_id' => $id]);
            return redirect()->back()
                ->with('error', 'Gagal mengubah status publish: ' . $e->getMessage());
        }
    }

    /**
     * Toggle press status.
     * POST /admin/stok-sampah/{id}/toggle-press
     */
    public function togglePress($id)
    {
        try {
            $stok = StokSampah::with('jenisSampah')->findOrFail($id);
            $oldStatus = $stok->is_pres;
            $stok->update(['is_pres' => !$oldStatus]);

            $namaSampah = $stok->jenisSampah->nama ?? 'Unknown';
            $newStatus = !$oldStatus ? 'di-press' : 'dibatalkan press';

            AuditLogService::log(
                action: 'STOK_PRESS',
                module: 'StokSampah',
                description: "Admin {$newStatus} stok ID #{$stok->id} ({$namaSampah})",
            );

            NotificationService::toAdmin(
                'stok',
                'Stok Di-Press',
                "Stok {$namaSampah} {$newStatus}."
            );

            return redirect()->back()
                ->with('success', "Stok berhasil {$newStatus}!");
        } catch (\Exception $e) {
            \Log::error('Error toggle press stok: ' . $e->getMessage(), ['stok_id' => $id]);
            return redirect()->back()
                ->with('error', 'Gagal mengubah status press: ' . $e->getMessage());
        }
    }

    /**
     * Proses penjualan stok.
     * POST /admin/stok-sampah/{id}/jual
     */
    public function prosesJual(Request $request, $id)
    {
        $stok = StokSampah::with('jenisSampah')->findOrFail($id);

        $request->validate([
            'berat_terjual_kg' => 'required|numeric|min:0.01|max:' . $stok->stok_tersisa_kg,
            'nama_pembeli'      => 'required|string|max:255',
            'kontak_pembeli'   => 'nullable|string|max:255',
            'tanggal_jual'     => 'required|date',
        ], [
            'berat_terjual_kg.max' => 'Berat tidak bisa melebihi stok tersisa (' . number_format($stok->stok_tersisa_kg, 2, ',', '.') . ' kg)',
        ]);

        DB::beginTransaction();
        try {
            $beratTerjual = (float) $request->berat_terjual_kg;
            $pendapatan = $beratTerjual * (float) $stok->harga_jual_per_kg;

            $stok->stok_terjual_kg  = bcadd((string) $stok->stok_terjual_kg, (string) $beratTerjual, 2);
            $stok->stok_tersisa_kg  = bcsub((string) $stok->stok_tersisa_kg, (string) $beratTerjual, 2);
            $stok->total_pendapatan = bcadd((string) $stok->total_pendapatan, (string) $pendapatan, 2);
            $stok->nama_pembeli     = $request->nama_pembeli;
            $stok->kontak_pembeli   = $request->kontak_pembeli;
            $stok->tanggal_jual     = $request->tanggal_jual;

            if ((float) $stok->stok_tersisa_kg <= 0) {
                $stok->status = 'terjual';
            } else {
                $stok->status = 'sebagian';
            }

            $stok->save();

            DB::commit();

            $namaSampah = $stok->jenisSampah->nama ?? 'Unknown';
            AuditLogService::log(
                action: 'STOK_TERJUAL',
                module: 'StokSampah',
                description: "Admin mencatat penjualan {$beratTerjual} kg dari stok ID #{$stok->id} ({$namaSampah}). Pendapatan: Rp " . number_format($pendapatan, 0, ',', '.'),
            );

            NotificationService::stokTerjual(
                $namaSampah,
                $beratTerjual,
                $pendapatan,
                $request->nama_pembeli
            );

            return redirect()->back()
                ->with('success', "Penjualan {$beratTerjual} kg berhasil dicatat. Pendapatan: Rp " . number_format($pendapatan, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error proses jual stok: ' . $e->getMessage(), ['stok_id' => $id]);
            return redirect()->back()
                ->with('error', 'Gagal mencatat penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /admin/stok-sampah/{id}
     */
    public function destroy($id)
    {
        try {
            $stok = StokSampah::findOrFail($id);

            if ($stok->stok_terjual_kg > 0) {
                return redirect()->route('admin.stok-sampah.index')
                    ->with('error', 'Stok yang sudah memiliki penjualan tidak bisa dihapus!');
            }

            $oldData = $stok->toArray();
            $namaSampah = $stok->jenisSampah->nama ?? 'Unknown';

            // Hapus gambar jika ada
            if ($stok->gambar && Storage::disk('public')->exists($stok->gambar)) {
                Storage::disk('public')->delete($stok->gambar);
            }

            $stok->delete();

            AuditLogService::log(
                action: 'STOK_HAPUS',
                module: 'StokSampah',
                description: "Admin menghapus stok ID #{$id}",
                oldData: $oldData
            );

            NotificationService::stokDihapus($namaSampah);

            return redirect()->route('admin.stok-sampah.index')
                ->with('success', 'Stok sampah berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Error hapus stok sampah: ' . $e->getMessage(), [
                'stok_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.stok-sampah.index')
                ->with('error', 'Gagal menghapus stok: ' . $e->getMessage());
        }
    }
}
