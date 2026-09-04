<div class="pa-page pa-followup-page">
    @include('livewire.admin.phone-acquisition._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.phone-acquisition.dashboard') }}">جذب تلفنی</a></li>
                <li class="breadcrumb-item active">نیاز پیگیری مجدد تلفنی</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center g-3 pa-toolbar">
                <div class="col-md-7">
                    <div class="pa-eyebrow"><i class="fi fi-rr-phone-call"></i> صف پیگیری جذب</div>
                    <h4 class="mb-1">پیگیری مجدد جذب تلفنی</h4>
                    <p class="small text-muted mb-0">مخاطبان این بخش هنوز در مرحله تصمیم‌گیری و پیگیری اولیه هستند.</p>
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
                    <span><strong>{{ number_format($totalCount) }}</strong><small>کل مخاطبان پیگیری</small></span>
                </div>
                <div class="pa-summary-item">
                    <span class="pa-summary-icon bg-danger-subtle text-danger"><i class="fi fi-rr-alarm-clock"></i></span>
                    <span><strong>{{ number_format($dueCount) }}</strong><small>موعد رسیده</small></span>
                </div>
                <label class="form-check form-switch pa-due-switch mb-0">
                    <input class="form-check-input" type="checkbox" wire:model.live="dueOnly">
                    <span class="form-check-label">فقط سررسیده‌ها</span>
                </label>
            </div>
            <div class="pa-color-legend">
                <span>تعداد تلاش تماس:</span>
                <span class="badge bg-primary">آبی: {{ number_format($colorCounts['primary'] ?? 0) }}</span>
                <span class="badge bg-success">سبز: {{ number_format($colorCounts['success'] ?? 0) }}</span>
                <span class="badge bg-warning text-dark">زرد: {{ number_format($colorCounts['warning'] ?? 0) }}</span>
                <span class="badge bg-danger">قرمز: {{ number_format($colorCounts['danger'] ?? 0) }}</span>
                <span class="badge bg-secondary">خاکستری: {{ number_format($colorCounts['secondary'] ?? 0) }}</span>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($leads as $lead)
                    @php $color = $lead->color; @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="card pa-followup-card h-100 border-{{ $color }}" style="border-right-width:5px;">
                            <div class="card-body">
                                <div class="pa-card-head">
                                    <div>
                                        <span class="pa-card-kicker">شماره تماس</span>
                                        <h5 class="mb-0 pa-mobile" dir="ltr">{{ $lead->mobile }}</h5>
                                    </div>
                                    <span class="pa-status-pill bg-{{ $color }} text-white">تماس {{ number_format($lead->attempts_count) }}</span>
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
                                </div>

                                @if ($lead->next_call_at)
                                    <div class="pa-reminder-box {{ $lead->next_call_at->lte($now) ? 'is-due' : '' }}">
                                        <span><i class="fi fi-rr-calendar-clock"></i> موعد تماس بعدی</span>
                                        <strong dir="ltr">{{ \Morilog\Jalali\Jalalian::fromDateTime($lead->next_call_at)->format('Y/m/d H:i') }}</strong>
                                    </div>
                                @endif
                                @if ($lead->calls->first())
                                    <div class="pa-last-result">
                                        <span>آخرین نتیجه تماس</span>
                                        <strong>
                                            {{ $lead->last_outcome_label }}
                                            @if($lead->disinterest_status) — {{ $lead->disinterest_status_label }} @endif
                                        </strong>
                                    </div>
                                @endif

                                <button wire:click="openCallForm({{ $lead->id }})"
                                        class="btn btn-sm btn-{{ $color }} w-100">
                                    <i class="fi fi-rr-phone-call"></i> ثبت تماس مجدد
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">موردی برای پیگیری مجدد تلفنی نیست.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $leads->links('layouts.admin.pagination') }}</div>
        </div>
    </div>

    @include('livewire.admin.phone-acquisition._call-form')
</div>
