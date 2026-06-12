<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\RiwayatHargaSampah;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use App\Traits\NotifiableTrait;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    use NotifiableTrait;
    // GET semua jenis sampah
    public function index()
    {
        $data = JenisSampah::orderBy('kategori')->get();
        return response()->json(['data' => $data]);
    }

    // POST tambah jenis sampah
    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string',
            'kategori'     => 'nullable|string',
            'harga_per_kg' => 'required|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        $sampah = JenisSampah::create($request->all());

        AuditLogService::log(
            action: 'SAMPAH_TAMBAH',
            module: 'JenisSampah',
            description: "Admin menambahkan jenis sampah: {$sampah->nama}",
            newData: $sampah->toArray()
        );

        // ✅ Notif ke admin
        $harga = 'Rp' . number_format($sampah->harga_per_kg, 0, ',', '.');
        NotificationService::sampahDitambah($sampah->nama, $harga);

        return response()->json([
            'message' => 'Jenis sampah berhasil ditambahkan',
            'data'    => $sampah
        ], 201);
    }

    // PUT update jenis sampah
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'nullable|string',
            'kategori'   => 'nullable|string',
            'keterangan' => 'nullable|string',
            'is_active'  => 'nullable|boolean',
        ]);

        $sampah  = JenisSampah::findOrFail($id);
        $oldData = $sampah->toArray();

        $sampah->update($request->only([
            'nama', 'kategori', 'keterangan', 'is_active'
        ]));

        AuditLogService::log(
            action: 'SAMPAH_EDIT',
            module: 'JenisSampah',
            description: "Admin mengedit jenis sampah: {$sampah->nama}",
            oldData: $oldData,
            newData: $sampah->toArray()
        );

        // ✅ Notif kalau status aktif/nonaktif berubah (ke Admin)
        if ($sampah->wasChanged('is_active')) {
            if ($sampah->is_active) {
                NotificationService::sampahDiaktifkan($sampah->nama);
            } else {
                NotificationService::sampahDinonaktifkan($sampah->nama);
            }
        }

        return response()->json([
            'message' => 'Jenis sampah berhasil diperbarui',
            'data'    => $sampah
        ]);
    }

    // PUT update harga sampah
    public function updateHarga(Request $request, $id)
    {
        $request->validate([
            'harga_per_kg' => 'required|numeric|min:0',
            'alasan'       => 'nullable|string',
        ]);

        $sampah    = JenisSampah::findOrFail($id);
        $hargaLama = $sampah->harga_per_kg;

        // Catat riwayat harga
        RiwayatHargaSampah::create([
            'jenis_sampah_id' => $sampah->id,
            'harga_lama'      => $hargaLama,
            'harga_baru'      => $request->harga_per_kg,
            'alasan'          => $request->alasan ?? 'Tidak ada keterangan',
            'diubah_oleh'     => $request->user()->id,
        ]);

        // Update harga aktif
        $sampah->update(['harga_per_kg' => $request->harga_per_kg]);

        AuditLogService::log(
            action: 'HARGA_UPDATE',
            module: 'JenisSampah',
            description: "Harga {$sampah->nama} diubah dari Rp{$hargaLama} → Rp{$request->harga_per_kg}",
            oldData: ['harga_per_kg' => $hargaLama],
            newData: ['harga_per_kg' => $request->harga_per_kg]
        );

        $old = 'Rp' . number_format($hargaLama, 0, ',', '.');
        $new = 'Rp' . number_format($request->harga_per_kg, 0, ',', '.');

        // ✅ Notif ke admin
        NotificationService::sampahDiubah($sampah->nama, $old, $new);

        // 🔔 PERBAIKAN: Notif MASSAL ke Seluruh Nasabah
        NotificationService::hargaSampahBerubahNasabah($sampah->nama, $new);

        // 🔔 TRIGGER MOBILE BANKING: Notifikasi harga sampah berubah
        $this->notifyHargaBerubah($sampah->nama, $new);

        return response()->json([
            'message'    => 'Harga berhasil diperbarui',
            'data'       => $sampah,
            'harga_lama' => $hargaLama,
            'harga_baru' => $request->harga_per_kg,
        ]);
    }

    // GET riwayat harga
    public function riwayatHarga($id)
    {
        $sampah  = JenisSampah::findOrFail($id);
        $riwayat = RiwayatHargaSampah::with('diubahOleh:id,name')
                    ->where('jenis_sampah_id', $id)
                    ->orderByDesc('created_at')
                    ->get();

        return response()->json([
            'jenis_sampah' => $sampah->nama,
            'harga_aktif'  => $sampah->harga_per_kg,
            'riwayat'      => $riwayat,
        ]);
    }

    // DELETE jenis sampah
    public function destroy(Request $request, $id)
    {
        $sampah = JenisSampah::findOrFail($id);

        AuditLogService::log(
            action: 'SAMPAH_HAPUS',
            module: 'JenisSampah',
            description: "Admin menghapus jenis sampah: {$sampah->nama}",
            oldData: $sampah->toArray()
        );

        $sampah->delete();

        return response()->json(['message' => 'Jenis sampah berhasil dihapus']);
    }
}
