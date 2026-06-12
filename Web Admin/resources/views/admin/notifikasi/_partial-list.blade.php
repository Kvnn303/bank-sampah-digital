{{-- resources/views/admin/notifikasi/_partial-list.blade.php --}}

@forelse($notifications ?? collect() as $notif)
    <a href="{{ $notif->url ?? '#' }}"
       class="list-group-item list-group-item-action {{ !$notif->is_read ? 'bg-primary-lt' : '' }}"
       onclick="markRead('{{ $notif->id }}', event)">
        <div class="row align-items-center">

            <div class="col-auto">
                <span class="avatar avatar-sm {{ $notif->getIconBgClass() }}">
                    <x-icon name="{{ $notif->getIconName() }}" size="16" />
                </span>
            </div>

            <div class="col text-truncate">
                <div class="fw-semibold {{ $notif->is_read ? 'text-muted' : 'text-body' }}"
                     style="font-size: 0.82rem;">
                    {{ $notif->title }}
                </div>
                <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                    {{ Str::limit($notif->message, 60) }}
                </div>
                <div class="text-muted" style="font-size: 0.65rem;">
                    {{ $notif->created_at->diffForHumans() }}
                </div>
            </div>

            @if(!$notif->is_read)
                <div class="col-auto">
                    <span class="badge bg-primary rounded-circle"
                          style="width:8px;height:8px;padding:0;min-width:8px;display:inline-block;">
                    </span>
                </div>
            @endif

        </div>
    </a>
@empty
    <div class="list-group-item text-center text-muted py-4">
        <x-icon name="bell-off" size="36" class="mb-2 text-muted" />
        <div class="small">Tidak ada notifikasi</div>
    </div>
@endforelse
