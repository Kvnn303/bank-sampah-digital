@extends('layouts.admin')

@section('title', 'Detail Stok Sampah')
@section('page-title', 'Detail Stok Sampah')

@push('styles')
<style>
    .stat-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background: rgba(16,185,129,0.1) !important; color: #10b981 !important; }
    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background: rgba(245,158,11,0.1) !important; color: #f59e0b !important; }
    .text-purple { color: #8b5cf6 !important; }
    .bg-purple-lt { background: rgba(139,92,246,0.1) !important; color: #8b5cf6 !important; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light btn-icon shadow-sm rounded-3">
        <x-icon name="arrow-left" size="20" />
    </a>
    <div class="flex-grow-1">
        <h2 class="fw-bold text-dark mb-0">Detail Stok Sampah</h2>
        <p class="text-muted mb-0 small">ID #{{ $stok->id }} — {{ $stok->jenisSampah->nama ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        @if($stok->is_pres)
            <span class="badge bg-purple-lt text-purple badge-modern rounded-pill px-3 py-2"><x-icon name="layers" size="14" class="me-1" />Di-Press</span>
        @endif
        @if($stok->is_published)
            <span class="badge bg-emerald-lt text-emerald badge-modern rounded-pill px-3 py-2"><x-icon name="check" size="14" class="me-1" />Published</span>
        @else
            <span class="badge bg-slate-100 text-slate-500 badge-modern rounded-pill px-3 py-2">Draft</span>
        @endif
    </div>
</div>

{{-- STATISTIK --}}
<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="text-muted fw-bold text-uppercase small">Berat Masuk</div>
                    <div class="ms-auto"><x-icon name="box" class="text-blue" size="20" /></div>
                </div>
                <div class="h3 mb-0 text-dark fw-bold">{{ number_format($stok->stok_masuk_kg, 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">kg</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="text-muted fw-bold text-uppercase small">Terjual</div>
                    <div class="ms-auto"><x-icon name="check" class="text-emerald" size="20" /></div>
                </div>
                <div class="h3 mb-0 text-emerald fw-bold">{{ number_format($stok->stok_terjual_kg, 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">kg</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="text-muted fw-bold text-uppercase small">Tersisa</div>
                    <div class="ms-auto"><x-icon name="weight" class="text-amber" size="20" /></div>
                </div>
                <div class="h3 mb-0 text-amber fw-bold">{{ number_format($stok->stok_tersisa_kg, 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">kg</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="text-muted fw-bold text-uppercase small">Pendapatan</div>
                    <div class="ms-auto"><x-icon name="currency" class="text-purple" size="20" /></div>
                </div>
                <div class="h3 mb-0 text-purple fw-bold">Rp {{ number_format($stok->total_pendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title fw-bold text-dark mb-0">Informasi Stok</h3>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Jenis Sampah</div>
                            <div class="fw-bold text-dark">{{ $stok->jenisSampah->nama ?? '-' }}</div>
                            <div class="text-muted small">{{ $stok->jenisSampah->kategori ?? '' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Harga Jual</div>
                            <div class="fw-bold text-emerald fs-5">Rp {{ number_format($stok->harga_jual_per_kg, 0, ',', '.') }}/kg</div>
                        </div>
                        <div class="mb-0">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Tanggal Masuk</div>
                            <div class="fw-bold text-dark">{{ $stok->tanggalMasukDisplay }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($stok->nama_pembeli)
                        <div class="mb-3">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Nama Pembeli</div>
                            <div class="fw-bold text-dark">{{ $stok->nama_pembeli }}</div>
                        </div>
                        @endif
                        @if($stok->kontak_pembeli)
                        <div class="mb-3">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Kontak</div>
                            <div class="fw-bold text-dark">{{ $stok->kontak_pembeli }}</div>
                        </div>
                        @endif
                        @if($stok->keterangan)
                        <div class="mb-0">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Keterangan</div>
                            <div class="fw-bold text-dark">{{ $stok->keterangan }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($stok->stok_masuk_kg > 0)
                @php $persenTerjual = ($stok->stok_terjual_kg / $stok->stok_masuk_kg) * 100; @endphp
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold text-muted small">PROGRESS</span>
                        <span class="fw-bold text-dark">{{ number_format($persenTerjual, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 6px; overflow: hidden;">
                        <div class="progress-bar" style="width: {{ $persenTerjual }}%; background: #10b981;"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-emerald small">Terjual: {{ number_format($persenTerjual, 1) }}%</span>
                        <span class="text-amber small">Tersisa: {{ number_format(100 - $persenTerjual, 1) }}%</span>
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2 mt-4 pt-4 border-top">
                    <a href="{{ route('admin.stok-sampah.edit', $stok->id) }}" class="btn btn-outline-primary fw-semibold">
                        <x-icon name="edit" size="16" class="me-1" />Edit
                    </a>
                    @if($stok->stok_tersisa_kg > 0)
                    <button type="button" class="btn btn-success fw-semibold" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalJual"
                        data-stok-id="{{ $stok->id }}"
                        data-sisa="{{ $stok->stok_tersisa_kg }}"
                        data-harga="{{ $stok->harga_jual_per_kg }}">
                        <x-icon name="currency" size="16" class="me-1" />Catat Penjualan
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title fw-bold text-dark mb-0">Info Tambahan</h3>
            </div>
            <div class="card-body p-4">
                @if($stok->gambar)
                <div class="mb-4">
                    <div class="text-muted small fw-semibold text-uppercase mb-2">Foto Sampah</div>
                    <a href="{{ asset('storage/' . $stok->gambar) }}" target="_blank">
                        <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto Stok {{ $stok->jenisSampah->nama }}" class="rounded-3 w-100" style="max-height: 180px; object-fit: cover; border: 1px solid #e2e8f0;">
                    </a>
                    <div class="text-center mt-1">
                        <span class="text-muted small">Klik untuk memperbesar</span>
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <div class="text-muted small fw-semibold text-uppercase mb-2">Statistik Jenis Ini</div>
                    <div class="bg-light rounded-3 p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Masuk</span>
                            <span class="fw-bold text-dark">{{ number_format($statsPerJenis->total_masuk ?? 0, 2, ',', '.') }} kg</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Terjual</span>
                            <span class="fw-bold text-emerald">{{ number_format($statsPerJenis->total_terjual ?? 0, 2, ',', '.') }} kg</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Tersisa</span>
                            <span class="fw-bold text-amber">{{ number_format($statsPerJenis->total_tersisa ?? 0, 2, ',', '.') }} kg</span>
                        </div>
                        <hr class="my-2 border-slate-200">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Transaksi</span>
                            <span class="fw-bold text-dark">{{ $statsPerJenis->jumlah_transaksi ?? 0 }}x</span>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Dicatat</div>
                    <div class="text-dark small">{{ $stok->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</div>
                    <div class="text-muted small">{{ $stok->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL JUAL --}}
<div class="modal fade" id="modalJual" tabindex="-1" aria-labelledby="modalJualLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark" id="modalJualLabel"><x-icon name="currency" class="text-emerald me-2" size="20" />Catat Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="formJual">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert bg-emerald-lt border-0 mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <x-icon name="info" class="text-emerald flex-shrink-0" size="18" />
                            <div>
                                <div class="fw-bold text-emerald small">Stok Tersedia</div>
                                <div class="fw-bold text-dark fs-5" id="modalStokTersisa"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-dark fw-bold small required">Berat Terjual (kg)</label>
                            <div class="input-group shadow-sm">
                                <input type="number" name="berat_terjual_kg" id="beratTerjualInput" class="form-control fw-bold text-emerald" placeholder="0.00" step="0.01" min="0.01" required>
                                <span class="input-group-text bg-white fw-semibold">kg</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark fw-bold small">Estimasi</label>
                            <div class="form-control bg-light border-0 fw-bold text-emerald" id="estimasiPendapatan" style="height: 42px; display: flex; align-items: center;">Rp 0</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small required">Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" class="form-control shadow-sm" placeholder="Nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Kontak</label>
                        <input type="text" name="kontak_pembeli" class="form-control shadow-sm" placeholder="No. HP">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold small required">Tanggal</label>
                        <input type="date" name="tanggal_jual" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border fw-semibold shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4"><x-icon name="check" size="16" class="me-1" />Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Cara ini adalah standar resmi Bootstrap 5 & Tabler UI. 
// Dijamin tombol Batal, Close, dan background hitam (backdrop) akan berjalan normal.
document.addEventListener('DOMContentLoaded', function() {
    const modalJual = document.getElementById('modalJual');
    
    if (modalJual) {
        modalJual.addEventListener('show.bs.modal', function (event) {
            // Tangkap tombol yang diklik
            const button = event.relatedTarget;
            
            // Ambil data dari atribut tombol
            const stokId = button.getAttribute('data-stok-id');
            const stokTersisa = parseFloat(button.getAttribute('data-sisa'));
            const hargaJual = parseFloat(button.getAttribute('data-harga'));

            // Masukkan data ke dalam modal
            document.getElementById('modalStokTersisa').textContent = stokTersisa.toLocaleString('id-ID', {minimumFractionDigits: 2}) + ' kg';
            
            const inputBerat = document.getElementById('beratTerjualInput');
            inputBerat.max = stokTersisa;
            inputBerat.value = ''; // Reset form saat dibuka
            
            document.getElementById('estimasiPendapatan').textContent = 'Rp 0';
            
            // Set tujuan submit form
            document.getElementById('formJual').action = '/admin/stok-sampah/' + stokId + '/jual';

            // Hitung estimasi pendapatan secara langsung
            inputBerat.oninput = function() {
                const berat = parseFloat(this.value) || 0;
                document.getElementById('estimasiPendapatan').textContent = 'Rp ' + (berat * hargaJual).toLocaleString('id-ID');
            };
        });
    }
});
</script>
@endpush