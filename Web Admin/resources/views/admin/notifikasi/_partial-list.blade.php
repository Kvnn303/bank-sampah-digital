{{-- resources/views/admin/notifikasi/_partial-list.blade.php --}}

@forelse($notifications ?? collect() as $notif)
    <a href="{{ $notif->url ?? '#' }}"
       class="list-group-item list-group-item-action {{ !$notif->is_read ? 'bg-primary-lt' : '' }}"
       onclick="markRead({{ $notif->id }}, event)">
        <div class="row align-items-center">

            <div class="col-auto">
                <span class="avatar avatar-sm {{ $notif->getIconBgClass() }}">
                    {!! $notif->getIconSvg() !!}
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
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="36" height="36"
             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2-3v-3a7 7 0 0 1 4-6"/>
            <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
        </svg>
        <div class="small">Tidak ada notifikasi</div>
    </div>
@endforelse
