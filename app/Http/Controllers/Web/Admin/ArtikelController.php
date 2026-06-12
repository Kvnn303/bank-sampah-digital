<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\ArtikelGaleri;
use App\Models\AuditLog;
use App\Services\NotificationService;
use App\Traits\NotifiableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    use NotifiableTrait;

    public function index(Request $request)
    {
        $artikels = Artikel::with('author')
            ->when($request->search, fn($q) => $q->where('judul', 'like', '%' . $request->search . '%'))
            ->when($request->status !== null && $request->status !== '', fn($q) => $q->where('is_published', $request->status))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->latest()
            ->paginate(10);

        return view('admin.artikels.index', compact('artikels'));
    }

    public function create()
    {
        $kategoris = ['edukasi', 'panduan', 'berita', 'harga_sampah'];
        return view('admin.artikels.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'               => 'required|string|max:255',
            'konten'              => 'required',
            'kategori'            => 'required|in:edukasi,panduan,berita,harga_sampah',
            'gambar'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'galeri.*'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'galeri_keterangan.*' => 'nullable|string|max:255',
        ]);

        // Upload gambar sampul
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('artikels', 'public');
        }

        $artikel = Artikel::create([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul),
            'konten'       => $request->konten,
            'kategori'     => $request->kategori,
            'gambar'       => $gambarPath,
            'is_published' => $request->has('is_published') ? 1 : 0,
            'author_id'    => auth()->id(),
        ]);

        // Upload galeri dokumentasi
        if ($request->hasFile('galeri')) {
            foreach ($request->file('galeri') as $index => $file) {
                $path = $file->store('artikels/galeri', 'public');
                ArtikelGaleri::create([
                    'artikel_id' => $artikel->id,
                    'gambar'     => $path,
                    'keterangan' => $request->galeri_keterangan[$index] ?? null,
                    'urutan'     => $index,
                ]);
            }
        }

        AuditLog::create([
            'user_id'     => auth()->id(),
            'user_name'   => auth()->user()->name,
            'role'        => auth()->user()->role ?? 'admin',
            'action'      => 'create',
            'module'      => 'Artikel',
            'description' => 'Menambahkan artikel baru: ' . $artikel->judul,
            'old_data'    => null,
            'new_data'    => [
                'judul'        => $artikel->judul,
                'kategori'     => $artikel->kategori,
                'is_published' => $artikel->is_published,
                'total_galeri' => $artikel->galeri()->count(),
            ],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'status'      => 'success',
        ]);

        // ✅ Notif ke admin
        NotificationService::artikelDibuat($artikel->judul);

        // 🔔 TRIGGER MOBILE BANKING: Kirim notifikasi massal ke Nasabah jika artikel dipublikasikan
        if ($artikel->is_published) {
            $this->notifyArtikelBaru($artikel->judul, $artikel->kategori);
        }

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function show(Artikel $artikel)
    {
        $artikel->load(['author', 'galeri']);
        return view('admin.artikels.show', compact('artikel'));
    }

    public function edit(Artikel $artikel)
    {
        $artikel->load('galeri');
        $kategoris = ['edukasi', 'panduan', 'berita', 'harga_sampah'];
        return view('admin.artikels.edit', compact('artikel', 'kategoris'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul'               => 'required|string|max:255',
            'konten'              => 'required',
            'kategori'            => 'required|in:edukasi,panduan,berita,harga_sampah',
            'gambar'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'galeri.*'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'galeri_keterangan.*' => 'nullable|string|max:255',
            'hapus_galeri.*'      => 'nullable|integer|exists:artikel_galeri,id',
        ]);

        // Simpan data lama untuk audit log
        $oldData = [
            'judul'        => $artikel->judul,
            'kategori'     => $artikel->kategori,
            'is_published' => $artikel->is_published,
            'total_galeri' => $artikel->galeri()->count(),
        ];

        // Update gambar sampul jika ada yang baru
        $gambarPath = $artikel->gambar;
        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $gambarPath = $request->file('gambar')->store('artikels', 'public');
        }

        $artikel->update([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul),
            'konten'       => $request->konten,
            'kategori'     => $request->kategori,
            'gambar'       => $gambarPath,
            'is_published' => $request->has('is_published') ? 1 : 0,
        ]);

        // Hapus item galeri yang dipilih admin
        if ($request->filled('hapus_galeri')) {
            $hapusList = ArtikelGaleri::whereIn('id', $request->hapus_galeri)
                ->where('artikel_id', $artikel->id)
                ->get();

            foreach ($hapusList as $item) {
                Storage::disk('public')->delete($item->gambar);
                $item->delete();
            }
        }

        // Tambah galeri baru jika ada
        if ($request->hasFile('galeri')) {
            $urutanTerakhir = $artikel->galeri()->max('urutan') ?? 0;
            foreach ($request->file('galeri') as $index => $file) {
                $path = $file->store('artikels/galeri', 'public');
                ArtikelGaleri::create([
                    'artikel_id' => $artikel->id,
                    'gambar'     => $path,
                    'keterangan' => $request->galeri_keterangan[$index] ?? null,
                    'urutan'     => $urutanTerakhir + $index + 1,
                ]);
            }
        }

        AuditLog::create([
            'user_id'     => auth()->id(),
            'user_name'   => auth()->user()->name,
            'role'        => auth()->user()->role ?? 'admin',
            'action'      => 'update',
            'module'      => 'Artikel',
            'description' => 'Memperbarui artikel: ' . $artikel->judul,
            'old_data'    => $oldData,
            'new_data'    => [
                'judul'        => $artikel->judul,
                'kategori'     => $artikel->kategori,
                'is_published' => $artikel->is_published,
                'total_galeri' => $artikel->galeri()->count(),
            ],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'status'      => 'success',
        ]);

        // Notif ke admin
        NotificationService::artikelDiedit($artikel->judul);

        // 🔔 TRIGGER MOBILE BANKING: Kirim notifikasi massal ke Nasabah jika artikel baru dipublikasikan
        $wasPublished = $oldData['is_published'] == 1;
        $nowPublished = $artikel->is_published == 1;
        if (!$wasPublished && $nowPublished) {
            $this->notifyArtikelBaru($artikel->judul, $artikel->kategori);
        }

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        $judulLama = $artikel->judul;

        $oldData = [
            'judul'        => $artikel->judul,
            'kategori'     => $artikel->kategori,
            'is_published' => $artikel->is_published,
        ];

        // Hapus gambar sampul
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }

        // Hapus semua file gambar galeri dari storage
        foreach ($artikel->galeri as $item) {
            Storage::disk('public')->delete($item->gambar);
        }

        // Cascade delete galeri otomatis karena onDelete('cascade') di migration
        $artikel->delete();

        AuditLog::create([
            'user_id'     => auth()->id(),
            'user_name'   => auth()->user()->name,
            'role'        => auth()->user()->role ?? 'admin',
            'action'      => 'delete',
            'module'      => 'Artikel',
            'description' => 'Menghapus artikel: ' . $judulLama,
            'old_data'    => $oldData,
            'new_data'    => null,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'status'      => 'success',
        ]);

        // ✅ Notif ke admin (pakai judul lama karena sudah dihapus)
        NotificationService::artikelDihapus($judulLama);

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil dihapus.');
    }

    // Hapus satu item galeri via AJAX (opsional)
    public function destroyGaleri(Artikel $artikel, ArtikelGaleri $galeri)
    {
        if ($galeri->artikel_id !== $artikel->id) {
            abort(403);
        }

        Storage::disk('public')->delete($galeri->gambar);
        $galeri->delete();

        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
    }
}
