@extends('layouts.admin')

@section('title', 'Kelola Stok Sampah')
@section('page-title', 'Kelola Stok Sampah')

@push('styles')
<style>
    .stat-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); transition: transform 0.3s; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
    .icon-shape { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 14px; }
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .table-modern th { text-transform: uppercase; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 1rem; }
    .table-modern td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .badge-modern { padding: 0.4em 0.8em; font-weight: 600; letter-spacing: 0.3px; font-size: 0.75rem; }
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background: rgba(16,185,129,0.1) !important; color: #10b981 !important; }
    .text-blue { color: #3b82f6 !important; }
    .bg-blue-lt { background: rgba(59,130,246,0.1) !important; color: #3b82f6 !important; }
    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background: rgba(245,158,11,0.1) !important; color: #f59e0b !important; }
    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background: rgba(244,63,94,0.1) !important; color: #f43f5e !important; }
    .text-purple { color: #8b5cf6 !important; }
    .bg-purple-lt { background: rgba(139,92,246,0.1) !important; color: #8b5cf6 !important; }
    .text-slate-400 { color: #94a3b8; }
    .dropdown-item { padding: 8px 16px; border-radius: 6px; font-weight: 500; }
    .dropdown-item:hover { background: #f1f5f9; }
</style>
@endpush

@section('content')

{{-- STATISTIK --}}
<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase small">Total Masuk</div>
                    <div class="ms-auto icon-shape bg-blue-lt"><x-icon name="box" class="text-blue" size="24" /></div>
                </div>
                <div class="h1 mb-0 fs-1 text-dark fw-bold">{{ number_format($statistik['total_masuk_kg'], 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">kg</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase small">Di Press</div>
                    <div class="ms-auto icon-shape bg-purple-lt"><x-icon name="layers" class="text-purple" size="24" /></div>
                </div>
                <div class="h1 mb-0 fs-1 text-purple fw-bold">{{ number_format($statistik['pres_count']) }} <span class="fs-6 fw-normal text-muted">stok</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase small">Tersedia</div>
                    <div class="ms-auto icon-shape bg-emerald-lt"><x-icon name="check" class="text-emerald" size="24" /></div>
                </div>
                <div class="h1 mb-0 fs-1 text-emerald fw-bold">{{ number_format($statistik['tersedia_count']) }} <span class="fs-6 fw-normal text-muted">stok</span></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase small">Total Pendapatan</div>
                    <div class="ms-auto icon-shape bg-amber-lt"><x-icon name="currency" class="text-amber" size="24" /></div>
                </div>
                <div class="h1 mb-0 fs-1 text-amber fw-bold">Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER & TOMBOL TAMBAH --}}
<div class="card card-modern mb-4">
    <div class="card-body p-4">
        <div class="row align-items-end g-3">
            <div class="col-12 col-xl-9">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Pencarian</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Nama sampah, pembeli..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted fw-semibold small">Status Stok</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                            <option value="terjual" {{ request('status') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted fw-semibold small">Status Press</label>
                        <select name="is_pres" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ request('is_pres') == '1' ? 'selected' : '' }}>Sudah Di-Press</option>
                            <option value="0" {{ request('is_pres') == '0' ? 'selected' : '' }}>Belum Press</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Jenis Sampah</label>
                        <select name="jenis_id" class="form-select">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSampahList as $js)
                                <option value="{{ $js->id }}" {{ request('jenis_id') == $js->id ? 'selected' : '' }}>{{ $js->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1 text-white shadow-sm fw-semibold">Cari</button>
                        <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light border shadow-sm" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-12 col-xl-3 ms-auto text-xl-end mt-3 mt-xl-0 border-top pt-3 border-xl-0 pt-xl-0 border-slate-100">
                <a href="{{ route('admin.stok-sampah.create') }}" class="btn btn-primary shadow-sm fw-bold w-100 w-xl-auto d-inline-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Stok
                </a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white p-4">
        <h3 class="card-title fw-bold text-dark m-0">Daftar Stok Sampah</h3>
        <span class="badge bg-slate-100 text-slate-700 border px-3 py-1 rounded-pill fw-semibold">{{ $stokSampah->total() }} Data</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-modern mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4 text-center" width="50">No</th>
                    <th>Jenis Sampah</th>
                    <th class="text-center">Berat Masuk</th>
                    <th class="text-center">Tersisa</th>
                    <th class="text-center">Press</th>
                    <th class="text-center">Publish</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal</th>
                    <th class="pe-4 text-center" width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stokSampah as $index => $stok)
                <tr class="{{ !$stok->is_published ? 'opacity-50' : '' }}">
                    <td class="ps-4 text-center text-muted fw-medium">{{ $stokSampah->firstItem() + $index }}</td>

                    <td>
                        <div class="fw-bold text-dark">{{ $stok->jenisSampah->nama ?? '-' }}</div>
                        <div class="text-muted small">{{ $stok->jenisSampah->kategori ?? '' }}</div>
                    </td>

                    <td class="text-center">
                        <div class="fw-bold text-dark">{{ number_format($stok->stok_masuk_kg, 2, ',', '.') }} kg</div>
                    </td>

                    <td class="text-center">
                        @php
                            $sisa = (float) $stok->stok_tersisa_kg;
                            $badgeClass = match(true) {
                                $sisa == 0 => 'bg-rose-lt text-rose',
                                $sisa < ($stok->stok_masuk_kg / 2) => 'bg-amber-lt text-amber',
                                default => 'bg-emerald-lt text-emerald'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} badge-modern rounded-pill">
                            {{ number_format($sisa, 2, ',', '.') }} kg
                        </span>
                    </td>

                    <td class="text-center">
                        @if($stok->is_pres)
                            <span class="badge bg-purple-lt text-purple badge-modern rounded-pill">Sudah Press</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-500 badge-modern rounded-pill">Belum</span>
                        @endif
                    </td>

                    <td class="text-center">
                        @if($stok->is_published)
                            <span class="badge bg-emerald-lt text-emerald badge-modern rounded-pill">Published</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-500 badge-modern rounded-pill">Draft</span>
                        @endif
                    </td>

                    <td class="text-center">
                        @php
                            $statusBadge = match($stok->status) {
                                'tersedia' => 'bg-emerald-lt text-emerald',
                                'sebagian' => 'bg-amber-lt text-amber',
                                'terjual' => 'bg-rose-lt text-rose',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }} badge-modern rounded-pill px-3">{{ ucfirst($stok->status) }}</span>
                    </td>

                    <td class="text-center">
                        <div class="text-dark small">{{ $stok->tanggalMasukShort }}</div>
                    </td>

                    <td class="pe-4 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm rounded-pill px-3 fw-semibold border d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-500" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                                <span>Aksi</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; padding: 8px; min-width: 180px;">
                                <li>
                                    <a href="{{ route('admin.stok-sampah.show', $stok->id) }}" class="dropdown-item d-flex align-items-center text-dark py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-blue" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Lihat Detail
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.stok-sampah.edit', $stok->id) }}" class="dropdown-item d-flex align-items-center text-dark py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-amber" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit Data
                                    </a>
                                </li>

                                {{-- Toggle Publish --}}
                                <li>
                                    <form method="POST" action="{{ route('admin.stok-sampah.toggle-publish', $stok->id) }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center py-2 {{ $stok->is_published ? 'text-rose' : 'text-emerald' }} w-100">
                                            @if($stok->is_published)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                                Nonaktifkan Publish
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64A9 9 0 0 1 20.77 15"/><path d="M6.16 6.16a9 9 0 1 0 12.68 12.68"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                                Publikasikan
                                            @endif
                                        </button>
                                    </form>
                                </li>

                                {{-- Toggle Press --}}
                                <li>
                                    <form method="POST" action="{{ route('admin.stok-sampah.toggle-press', $stok->id) }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center py-2 {{ $stok->is_pres ? 'text-purple' : 'text-blue' }} w-100">
                                            @if($stok->is_pres)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                                Batalkan Press
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                                Tandai Press
                                            @endif
                                        </button>
                                    </form>
                                </li>

                                @if($stok->stok_tersisa_kg > 0)
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center py-2 text-emerald fw-semibold" onclick="showJualModal({{ $stok->id }}, '{{ addslashes($stok->jenisSampah->nama ?? '') }}', {{ $stok->stok_tersisa_kg }}, {{ $stok->harga_jual_per_kg }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                        Catat Penjualan
                                    </button>
                                </li>
                                @endif

                                @if($stok->stok_terjual_kg == 0)
                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.stok-sampah.destroy', $stok->id) }}" class="d-inline w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center py-2 text-rose fw-semibold w-100" onclick="return confirm('Yakin hapus stok ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            Hapus Permanen
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="mb-3"><x-icon name="box" size="48" class="text-slate-300" /></div>
                        <h3 class="text-dark fw-bold fs-5">Belum Ada Stok</h3>
                        <p class="text-muted small mb-3">Stok sampah belum ditambahkan.</p>
                        <a href="{{ route('admin.stok-sampah.create') }}" class="btn btn-primary rounded-pill fw-semibold px-4"><x-icon name="plus" size="16" class="me-1" />Tambah Sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stokSampah->hasPages())
    <div class="card-footer bg-white border-top p-4 d-flex justify-content-between">
        <p class="m-0 text-muted small d-none d-md-block">Menampilkan {{ $stokSampah->firstItem() }} - {{ $stokSampah->lastItem() }} dari {{ $stokSampah->total() }}</p>
        {{ $stokSampah->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- MODAL JUAL --}}
<div class="modal fade" id="modalJual" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark"><x-icon name="currency" class="text-emerald me-2" size="20" />Catat Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formJual">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert bg-emerald-lt border-0 mb-4">
                        <div class="fw-bold text-emerald small">Stok Tersedia</div>
                        <div class="fw-bold text-dark fs-5" id="modalStokTersisa"></div>
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
                            <label class="form-label text-dark fw-bold small">Estimasi Pendapatan</label>
                            <div class="form-control bg-light border-0 fw-bold text-emerald" id="estimasiPendapatan" style="height: 42px; display: flex; align-items: center;">Rp 0</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small required">Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" class="form-control shadow-sm" placeholder="Nama lengkap pembeli" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Kontak Pembeli</label>
                        <input type="text" name="kontak_pembeli" class="form-control shadow-sm" placeholder="No. HP / WhatsApp">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold small required">Tanggal Jual</label>
                        <input type="date" name="tanggal_jual" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
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
function showJualModal(stokId, namaSampah, stokTersisa, hargaJual) {
    document.getElementById('modalStokTersisa').textContent = parseFloat(stokTersisa).toLocaleString('id-ID', {minimumFractionDigits: 2}) + ' kg';
    document.getElementById('beratTerjualInput').max = stokTersisa;
    document.getElementById('beratTerjualInput').value = '';
    document.getElementById('estimasiPendapatan').textContent = 'Rp 0';
    document.getElementById('formJual').action = '/admin/stok-sampah/' + stokId + '/jual';

    document.getElementById('beratTerjualInput').oninput = function() {
        const berat = parseFloat(this.value) || 0;
        document.getElementById('estimasiPendapatan').textContent = 'Rp ' + (berat * hargaJual).toLocaleString('id-ID');
    };
    new bootstrap.Modal(document.getElementById('modalJual')).show();
}
</script>
@endpush
