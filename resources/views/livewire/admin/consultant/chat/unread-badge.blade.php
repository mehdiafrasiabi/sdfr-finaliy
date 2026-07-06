<div wire:poll.30s="loadCount" class="d-inline">
    @if($count > 0)
        <span class="badge bg-danger rounded-pill ms-auto">{{ $count > 9 ? '9+' : $count }}</span>
    @endif
</div>
