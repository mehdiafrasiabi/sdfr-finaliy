<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">گفتگو با دانش‌آموزان</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6"><h4 class="mb-0">گفتگو با دانش‌آموزان</h4></div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="جستجو: نام یا موبایل دانش‌آموز..."
                           wire:model.live.debounce.400ms="search">
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="list-group">
                @forelse ($students as $student)
                    @php
                        $conv    = $student->conversation;
                        $unread  = $conv ? $conv->unreadCountFor('advisor') : 0;
                        $online  = $conv ? $conv->isOnline('student') : false;
                        $last    = $conv?->messages->last();
                        if ($last) {
                            $preview = $last->deleted_at ? 'پیام حذف شد'
                                : ($last->body ?: ($last->image_path ? '🖼 تصویر' : ''));
                        } else {
                            $preview = 'هنوز پیامی رد و بدل نشده است';
                        }
                    @endphp
                    <a href="{{ route('admin.consultant.chat.show', $student->id) }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                        <div class="position-relative">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold"
                                 style="width:46px;height:46px;">
                                {{ mb_substr($student->user->name ?? 'د', 0, 1) }}
                            </div>
                            @if($online)
                                <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                      style="width:12px;height:12px;"></span>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-truncate">{{ $student->user->name ?? 'دانش‌آموز' }}</span>
                                @if($last)
                                    <small class="text-muted">{{ jalali($last->created_at)->format('%H:%M') }}</small>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted text-truncate" style="max-width:90%;">{{ \Illuminate\Support\Str::limit($preview, 60) }}</small>
                                @if($unread > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unread > 9 ? '9+' : $unread }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-muted py-5">
                        هنوز دانش‌آموزی به شما به‌عنوان مشاور تخصیص نیافته است.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
