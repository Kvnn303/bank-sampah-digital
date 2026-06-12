@extends('layouts.admin')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    /* Styling Modern yang Seragam */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    /* Warna Kustom Modern */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background-color: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; }

    /* Notifikasi Item Styling */
    .notif-item {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
        padding: 1.25rem 1.5rem;
    }

    .notif-item:hover {
        background-color: #f8fafc;
    }

    .notif-item.unread {
        background-color: #f0f9ff;
        border-left-color: #3b82f6;
    }

    .notif-item.unread:hover {
        background-color: #e0f2fe;
    }

    .btn-action-icon {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .badge-modern {
        padding: 0.3em 0.8em;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .icon-bg-lg {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.25rem;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">

        <!-- Header -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark fs-2">Notifikasi Sistem</h2>
                <p class="text-slate-500 mb-0">Pantau semua pemberitahuan dan aktivitas terbaru.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if($unreadCount > 0)
                    <button type="button" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4 d-flex align-items-center" onclick="markAllRead()">
                        <x-icon name="check" size="18" class="me-2" />
                        Tandai Semua Dibaca
                    </button>
                @endif

                @if(\App\Models\Notification::read()->exists())
                    <button type="button" class="btn btn-light border-rose text-rose rounded-pill fw-bold shadow-sm px-4 d-flex align-items-center hover-danger" onclick="clearRead()" style="transition: all 0.2s;">
                        <x-icon name="trash" size="18" class="me-2" />
                        Hapus yang Dibaca
                    </button>
                @endif
            </div>
        </div>

        <!-- Daftar Notifikasi -->
        <div class="card card-modern">
            <div class="list-group list-group-flush border-0">
                @forelse($notifications as $notif)
                    <div class="list-group-item border-bottom border-slate-100 notif-item {{ !$notif->is_read ? 'unread' : '' }}">
                        <div class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">

                            <!-- Icon -->
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0">
                                <div class="avatar avatar-md rounded-circle shadow-sm border border-2 border-white {{ $notif->getIconBgClass() }}" style="width: 48px; height: 48px;">
                                    <x-icon name="{{ $notif->getIconName() }}" size="20" />
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h4 class="mb-0 fw-bold fs-5 text-truncate {{ $notif->is_read ? 'text-slate-600' : 'text-dark' }}">
                                        {{ $notif->title }}
                                    </h4>
                                    <div class="text-slate-400 small whitespace-nowrap ms-3 d-none d-sm-block fw-medium">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <p class="mb-2 text-truncate" style="max-width: 100%; color: #475569; font-size: 0.9rem;">
                                    {{ $notif->message }}
                                </p>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="text-slate-400 small fw-medium d-flex align-items-center">
                                        <x-icon name="calendar" size="14" class="me-1" />
                                        {{ $notif->created_at->translatedFormat('d M Y, H:i') }}
                                    </span>
                                    <span class="text-slate-300 d-none d-sm-inline">•</span>
                                    @if($notif->is_read)
                                        <span class="badge bg-slate-100 text-slate-500 badge-modern border rounded-pill" style="font-size:0.65rem">Sudah Dibaca</span>
                                    @else
                                        <span class="badge bg-blue-lt text-blue-modern badge-modern rounded-pill" style="font-size:0.65rem">Pesan Baru</span>
                                    @endif
                                    <span class="text-slate-400 small fw-medium d-sm-none ms-auto">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex flex-row flex-sm-column gap-2 ms-sm-3 mt-3 mt-sm-0 pt-3 pt-sm-0 border-top border-sm-top-0 border-slate-100 justify-content-end">
                                @if(!$notif->is_read)
                                    <button type="button" class="btn btn-light btn-action-icon text-emerald border shadow-sm" onclick="markRead('{{ $notif->id }}')" data-bs-toggle="tooltip" title="Tandai dibaca">
                                        <x-icon name="check" size="18" />
                                    </button>
                                @endif

                                @if($notif->url)
                                    <a href="{{ $notif->url }}" class="btn btn-light btn-action-icon text-blue-modern border shadow-sm" data-bs-toggle="tooltip" title="Lihat detail">
                                        <x-icon name="arrow-right" size="18" />
                                    </a>
                                @endif

                                <button type="button" class="btn btn-light btn-action-icon text-rose border shadow-sm hover-bg-rose" onclick="deleteNotif('{{ $notif->id }}')" data-bs-toggle="tooltip" title="Hapus">
                                    <x-icon name="trash" size="18" />
                                </button>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center py-5 border-0">
                        <div class="mb-3">
                            <x-icon name="bell-off" size="64" class="text-slate-300" />
                        </div>
                        <h4 class="text-dark fw-bold fs-4">Tidak Ada Notifikasi</h4>
                        <p class="text-slate-500 mb-0">Semua pemberitahuan sudah Anda baca atau dihapus.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// Inisialisasi Tooltip Bootstrap (jika digunakan)
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }
});

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

async function jsonFetch(url, method = 'POST') {
    const res = await fetch(url, {
        method,
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    });
    return res.json();
}

async function markRead(id) {
    const url = '{{ route("admin.notifikasi.read", ":id") }}'.replace(':id', id);
    const data = await jsonFetch(url);
    if (data.success) location.reload();
}

async function markAllRead() {
    const data = await jsonFetch('{{ route("admin.notifikasi.read-all") }}');
    if (data.success) location.reload();
}

async function deleteNotif(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) return;
    const url = '{{ route("admin.notifikasi.destroy", ":id") }}'.replace(':id', id);
    const data = await jsonFetch(url, 'DELETE');
    if (data.success) location.reload();
}

async function clearRead() {
    if (!confirm('Apakah Anda yakin ingin menghapus semua notifikasi yang sudah dibaca?')) return;
    const data = await jsonFetch('{{ route("admin.notifikasi.clear") }}', 'DELETE');
    if (data.success) location.reload();
}
</script>

<style>
    .hover-danger:hover {
        background-color: #fff1f2 !important;
    }
    .hover-bg-rose:hover {
        background-color: #fff1f2 !important;
        border-color: #fecdd3 !important;
    }
</style>
@endpush
