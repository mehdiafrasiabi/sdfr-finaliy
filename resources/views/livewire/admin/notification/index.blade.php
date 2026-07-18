<div class="container-fluid py-3" wire:poll.10s>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="mb-1">اعلانات من</h5>
                <div class="text-muted small">پیام‌های مربوط به فعالیت دانش‌آموزان اختصاص‌داده‌شده به شما</div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button"
                        wire:click="setFilter('unread')"
                        class="btn btn-sm {{ $filter === 'unread' ? 'btn-primary' : 'btn-outline-primary' }}">
                    خوانده‌نشده
                    @if($unreadCount > 0)
                        <span class="badge bg-light text-primary ms-1">{{ $unreadCount }}</span>
                    @endif
                </button>
                <button type="button"
                        wire:click="setFilter('all')"
                        class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                    همه
                    <span class="badge bg-light text-primary ms-1">{{ $totalCount }}</span>
                </button>
                @if($unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead" class="btn btn-sm btn-success">
                        همه خوانده شد
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item py-3 {{ $notification->is_read ? '' : 'bg-primary-subtle' }}">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                            <div class="d-flex gap-3 align-items-start">
                                <span class="avatar avatar-sm rounded-circle {{ $notification->is_read ? 'bg-secondary' : 'bg-primary' }} text-white">
                                    <i class="fi fi-rr-bell"></i>
                                </span>
                                <div>
                                    <div class="fw-bold">{{ $notification->title }}</div>
                                    @if($notification->body)
                                        <div class="text-muted small mt-1">{{ $notification->body }}</div>
                                    @endif
                                    <div class="small text-muted mt-2">
                                        {{ jdate($notification->created_at)->format('Y/m/d H:i') }}
                                        @if($notification->read_at)
                                            <span class="badge bg-success-subtle text-success border ms-2">خوانده شده</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border ms-2">خوانده نشده</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @if($notification->url)
                                    <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary">
                                        مشاهده
                                    </a>
                                @endif
                                @unless($notification->is_read)
                                    <button type="button" wire:click="markAsRead({{ $notification->id }})" class="btn btn-sm btn-success">
                                        خوانده شد
                                    </button>
                                @endunless
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">اعلانی برای نمایش وجود ندارد.</div>
                @endforelse
            </div>
        </div>

        @if($notifications->hasPages())
            <div class="card-footer bg-white">
                {{ $notifications->links('layouts.admin.pagination') }}
            </div>
        @endif
    </div>
</div>
