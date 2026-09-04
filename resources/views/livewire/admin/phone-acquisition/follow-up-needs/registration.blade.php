<div class="pa-page pa-followup-page">
    @include('livewire.admin.phone-acquisition._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.phone-acquisition.dashboard') }}">جذب تلفنی</a></li>
                <li class="breadcrumb-item active">نیاز پیگیری مجدد ثبت نام</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center g-3 pa-toolbar">
                <div class="col-md-7">
                    <div class="pa-eyebrow"><i class="fi fi-rr-user-add"></i> صف پیگیری ثبت‌نام</div>
                    <h4 class="mb-1">پیگیری افرادی که لینک ثبت‌نام دارند</h4>
                    <p class="small text-muted mb-0">تمام اطلاعات لازم برای تماس و وضعیت لینک، به‌صورت دسته‌بندی‌شده نمایش داده می‌شود.</p>
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
                    <span class="pa-summary-icon bg-primary-subtle text-primary"><i class="fi fi-rr-list"></i></span>
                    <span><strong>{{ number_format($totalCount) }}</strong><small>مورد در صف ثبت‌نام</small></span>
                </div>
                <div class="pa-summary-item">
                    <span class="pa-summary-icon bg-success-subtle text-success"><i class="fi fi-rr-check-circle"></i></span>
                    <span><strong>{{ number_format($successfulTodayCount) }}</strong><small>ثبت‌نام موفق امروز</small></span>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($links as $link)
                    @php $lead = $link->lead; @endphp
                    @continue(! $lead)
                    @php
                        $isRegistered = (bool) $link->registered_user_id;
                        $isRegistrationFollowUp = $lead->last_outcome === \App\Models\PhoneCall::RESULT_REGISTRATION_FOLLOW_UP;
                        $color = $lead->color;
                        $cardColor = $isRegistered ? 'success' : $color;
                        $sentAt = $link->sent_at ?? $link->created_at;
                        $elapsedDays = $sentAt ? $sentAt->copy()->startOfDay()->diffInDays($now->copy()->startOfDay()) : 0;
                        $elapsedLabel = $elapsedDays > 0
                            ? number_format($elapsedDays) . ' روز گذشته'
                            : 'امروز ارسال شده';
                    @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="card pa-followup-card h-100 border-{{ $cardColor }}" style="border-right-width:5px;">
                            <div class="card-body">
                                <div class="pa-card-head">
                                    <div>
                                        <span class="pa-card-kicker">شماره تماس</span>
                                        <h5 class="mb-0 pa-mobile" dir="ltr">{{ $lead->mobile }}</h5>
                                    </div>
                                    @if($isRegistered)
                                        <span class="pa-status-pill is-success"><i class="fi fi-rr-check"></i> ثبت‌نام شد</span>
                                    @elseif($isRegistrationFollowUp)
                                        <span class="pa-status-pill bg-primary text-white"><i class="fi fi-rr-refresh"></i> پیگیری مجدد ثبت‌نام</span>
                                    @else
                                        <span class="pa-status-pill is-warning"><i class="fi fi-rr-hourglass-end"></i> در انتظار ثبت‌نام</span>
                                    @endif
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

                                @if ($lead->next_call_at)
                                    <div class="pa-reminder-box {{ $lead->next_call_at->lte($now) ? 'is-due' : '' }}">
                                        <span><i class="fi fi-rr-calendar-clock"></i> موعد تماس بعدی</span>
                                        <strong dir="ltr">{{ jalali($lead->next_call_at)->format('Y/m/d H:i') }}</strong>
                                    </div>
                                @endif

                                <div class="pa-link-section">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                        <span class="pa-card-kicker mb-0">اطلاعات لینک ثبت‌نام</span>
                                        <span class="badge bg-info">{{ $elapsedLabel }}</span>
                                    </div>
                                    <div class="pa-link-meta">
                                        <span>ارسال لینک</span>
                                        <strong>{{ $sentAt ? jalali($sentAt)->format('%d %B، %H:%M') : '—' }}</strong>
                                    </div>
                                    @if($isRegistered && $link->used_at)
                                        <div class="pa-link-meta text-success">
                                            <span>زمان ثبت‌نام</span>
                                            <strong>{{ jalali($link->used_at)->format('%d %B، %H:%M') }}</strong>
                                        </div>
                                    @endif
                                    <a href="{{ $link->url }}" target="_blank" class="pa-registration-link" dir="ltr" title="باز کردن لینک ثبت‌نام">
                                        <span class="text-truncate">{{ $link->url }}</span>
                                        <i class="fi fi-rr-arrow-up-right-from-square"></i>
                                    </a>
                                </div>

                                @if($isRegistered)
                                    <span class="btn btn-sm btn-outline-success disabled w-100">
                                        ثبت نام شد
                                    </span>
                                @else
                                    <button wire:click="promptCall({{ $lead->id }})"
                                            class="btn btn-sm btn-{{ $color }} w-100">
                                        <i class="fi fi-rr-phone-call"></i> تماس پیگیری ثبت‌نام
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">موردی برای پیگیری مجدد ثبت نام نیست.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $links->links() }}</div>
        </div>
    </div>

    @include('livewire.admin.phone-acquisition._call-form')
</div>
