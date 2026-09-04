<div class="pa-page pa-followup-page">
    @include('livewire.admin.phone-acquisition._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">صف تماس جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center g-3 pa-toolbar">
                <div class="col-md-7">
                    <div class="pa-eyebrow"><i class="fi fi-rr-phone-call"></i> صف کاری امروز</div>
                    <h4 class="mb-1">صف تماس‌های من</h4>
                    <p class="small text-muted mb-0">شماره‌های آماده تماس بر اساس تعداد تلاش مرتب شده‌اند؛ نتیجه هر تماس را همان لحظه ثبت کنید.</p>
                </div>
                <div class="col-md-5">
                    <div class="pa-search-wrap">
                        <i class="fi fi-rr-search"></i>
                        <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                               placeholder="جستجو بر اساس نام یا شماره موبایل…">
                    </div>
                </div>
            </div>
            <div class="pa-summary-row">
                <div class="pa-summary-item">
                    <span class="pa-summary-icon bg-primary-subtle text-primary"><i class="fi fi-rr-list-check"></i></span>
                    <span><strong>{{ number_format($leads->total()) }}</strong><small>شماره آماده تماس</small></span>
                </div>
            </div>
            <div class="pa-color-legend">
                <span>رنگ کارت براساس تعداد تماس:</span>
                <span class="badge bg-primary">تماس اول</span>
                <span class="badge bg-success">تماس دوم</span>
                <span class="badge bg-warning text-dark">تماس سوم</span>
                <span class="badge bg-danger">تماس چهارم</span>
                <span class="badge bg-secondary">شماره اشتباه</span>
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
                                    <span class="pa-status-pill bg-{{ $color }} {{ $color === 'warning' ? 'text-dark' : 'text-white' }}">
                                        تلاش {{ number_format($lead->attempts_count + 1) }}
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
                                        <span><i class="fi fi-rr-phone-call"></i> تماس‌های ثبت‌شده</span>
                                        <strong>{{ number_format($lead->calls_count) }} مرتبه</strong>
                                    </div>
                                </div>

                                @if ($lead->last_outcome)
                                    <div class="pa-last-result">
                                        <span>آخرین نتیجه تماس</span>
                                        <strong>{{ $lead->last_outcome_label }}</strong>
                                    </div>
                                @else
                                    <div class="pa-last-result">
                                        <span>وضعیت</span>
                                        <strong>هنوز تماسی ثبت نشده</strong>
                                    </div>
                                @endif

                                <button wire:click="promptCall({{ $lead->id }})"
                                        class="btn btn-sm btn-{{ $color }} w-100">
                                    <i class="fi fi-rr-phone-call"></i> شروع و ثبت تماس
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">شماره‌ای در صف شما نیست.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $leads->links('layouts.admin.pagination') }}</div>
        </div>
    </div>

    {{-- ─────── مودال ثبت تماس (مشترک) ─────── --}}
    @include('livewire.admin.phone-acquisition._call-form')
</div>
