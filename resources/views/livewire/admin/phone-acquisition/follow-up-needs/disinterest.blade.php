<div class="pa-page pa-followup-page">
    @include('livewire.admin.phone-acquisition._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.phone-acquisition.dashboard') }}">جذب تلفنی</a></li>
                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center g-3 pa-toolbar">
                <div class="col-md-7">
                    <div class="pa-eyebrow">
                        <i class="fi {{ $isTemporary ? 'fi-rr-time-quarter-past' : 'fi-rr-ban' }}"></i>
                        مدیریت وضعیت عدم تمایل
                    </div>
                    <h4 class="mb-1">{{ $pageTitle }}</h4>
                    <p class="small text-muted mb-0">
                        {{ $isTemporary ? 'موعد پیگیری، علت عدم تمایل و سوابق تماس هر مخاطب را یکجا مشاهده کنید.' : 'این مخاطبان عدم تمایل قطعی اعلام کرده‌اند و تماس مجدد برای آن‌ها غیرفعال است.' }}
                    </p>
                </div>
                <div class="col-md-5">
                    <div class="pa-search-wrap">
                        <i class="fi fi-rr-search"></i>
                        <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="جستجو بر اساس نام یا شماره موبایل…">
                    </div>
                </div>
            </div>
            <div class="pa-summary-row">
                <div class="pa-summary-item">
                    <span class="pa-summary-icon {{ $isTemporary ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' }}">
                        <i class="fi fi-rr-users"></i>
                    </span>
                    <span><strong>{{ number_format($totalCount) }}</strong><small>کل مخاطبان این دسته</small></span>
                </div>
                @if($isTemporary)
                    <div class="pa-summary-item">
                        <span class="pa-summary-icon bg-danger-subtle text-danger"><i class="fi fi-rr-alarm-clock"></i></span>
                        <span><strong>{{ number_format($dueCount) }}</strong><small>موعد پیگیری رسیده</small></span>
                    </div>
                    <div class="pa-summary-item">
                        <span class="pa-summary-icon bg-info-subtle text-info"><i class="fi fi-rr-lock"></i></span>
                        <span><strong>{{ number_format($lockedCount) }}</strong><small>در انتظار موعد</small></span>
                    </div>
                @endif
            </div>
            <div class="pa-section-tabs" aria-label="نوع عدم تمایل">
                <a href="{{ route('admin.phone-acquisition.disinterest-temporary') }}"
                   class="{{ $isTemporary ? 'active' : '' }}">
                    <i class="fi fi-rr-time-quarter-past"></i> عدم تمایل موقت
                </a>
                <a href="{{ route('admin.phone-acquisition.disinterest-definitive') }}"
                   class="{{ ! $isTemporary ? 'active' : '' }}">
                    <i class="fi fi-rr-ban"></i> عدم تمایل قطعی
                </a>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($leads as $lead)
                    @php
                        $color = $lead->color;
                        $lastCall = $lead->calls->first();
                        $isLockedUntilReminder = $isTemporary && $lead->next_call_at && $lead->next_call_at->isFuture();
                    @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="card pa-followup-card h-100 border-{{ $isTemporary ? 'warning' : 'secondary' }}" style="border-right-width:5px;">
                            <div class="card-body">
                                <div class="pa-card-head">
                                    <div>
                                        <span class="pa-card-kicker">شماره تماس</span>
                                        <h5 class="mb-0 pa-mobile" dir="ltr">{{ $lead->mobile }}</h5>
                                    </div>
                                    <span class="pa-status-pill {{ $isTemporary ? 'is-warning' : 'bg-secondary text-white' }}">
                                        <i class="fi {{ $isTemporary ? 'fi-rr-time-quarter-past' : 'fi-rr-ban' }}"></i>
                                        {{ $lead->disinterest_status_label }}
                                    </span>
                                </div>

                                <div class="pa-info-grid">
                                    <div class="pa-info-row">
                                        <span><i class="fi fi-rr-user"></i> نام</span>
                                        <strong>{{ $lead->full_name ?: 'بدون نام' }}</strong>
                                    </div>
                                    <div class="pa-info-row">
                                        <span><i class="fi fi-rr-graduation-cap"></i> تحصیلات</span>
                                        <strong>{{ $lead->grade_label }} / {{ $lead->field_label }}</strong>
                                    </div>
                                    <div class="pa-info-row">
                                        <span><i class="fi fi-rr-marker"></i> محل سکونت</span>
                                        <strong>{{ $lead->state?->name ?? '—' }}{{ $lead->city ? '، ' . $lead->city->name : '' }}</strong>
                                    </div>
                                    <div class="pa-info-row">
                                        <span><i class="fi fi-rr-phone-call"></i> تعداد تماس</span>
                                        <strong>{{ number_format($lead->attempts_count) }} مرتبه</strong>
                                    </div>
                                </div>

                                @if($lead->disinterest_at)
                                    <div class="pa-last-result">
                                        <span><i class="fi fi-rr-calendar"></i> زمان ثبت عدم تمایل</span>
                                        <strong>{{ jalali($lead->disinterest_at)->format('%d %B، %H:%M') }}</strong>
                                    </div>
                                @endif

                                @if($isTemporary && $lead->next_call_at)
                                    <div class="pa-reminder-box {{ $lead->next_call_at->lte($now) ? 'is-due' : '' }}">
                                        <span><i class="fi fi-rr-calendar-clock"></i> موعد تماس بعدی</span>
                                        <strong dir="ltr">{{ jalali($lead->next_call_at)->format('Y/m/d H:i') }}</strong>
                                    </div>
                                @endif

                                @if($lead->disinterest_reason)
                                    <div class="pa-reason-box {{ $isTemporary ? 'is-temporary' : '' }}">
                                        <span><i class="fi fi-rr-comment-alt"></i> علت عدم تمایل</span>
                                        <p>{{ $lead->disinterest_reason }}</p>
                                    </div>
                                @endif

                                @if($lastCall)
                                    <div class="pa-call-meta">
                                        <span>آخرین تماس</span>
                                        <strong>{{ jalali($lastCall->called_at)->format('%d %B، %H:%M') }}</strong>
                                        @if($lastCall->admin?->name)
                                            <span>توسط {{ $lastCall->admin->name }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if($isTemporary)
                                    @if($isLockedUntilReminder)
                                        <div class="d-grid gap-2">
                                            <span class="pa-lock-notice">
                                                <i class="fi fi-rr-lock"></i>
                                                تا موعد یادآور قفل است
                                            </span>
                                            <button wire:click="promptCall({{ $lead->id }}, true)"
                                                    class="btn btn-sm btn-warning w-100">
                                                <i class="fi fi-rr-phone-call"></i> تماس زودهنگام
                                            </button>
                                        </div>
                                    @else
                                        <button wire:click="promptCall({{ $lead->id }})"
                                                class="btn btn-sm btn-{{ $color }} w-100">
                                            <i class="fi fi-rr-phone-call"></i> تماس و پیگیری
                                        </button>
                                    @endif
                                @else
                                    <span class="btn btn-sm btn-outline-secondary disabled w-100">عدم تمایل قطعی ثبت شده - تماس ممنوع</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">موردی برای {{ $pageTitle }} وجود ندارد.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $leads->links('layouts.admin.pagination') }}</div>
        </div>
    </div>

    @include('livewire.admin.phone-acquisition._call-form')
</div>
