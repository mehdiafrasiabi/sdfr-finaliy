<div class="em-page ad-page" dir="rtl">
    @include('livewire.admin.educational-manager._styles')
    @php
        $isPhone = $channel === 'phone';
        $channelLabel = $isPhone ? 'جذب تلفنی' : 'جذب یک‌هفته آزمایشی';
        $periodLabels = ['all' => 'از ابتدا', 'today' => 'امروز', '7' => '۷ روز اخیر', '30' => '۳۰ روز اخیر'];
    @endphp
    @push('link')
        <style>
            .ad-page{--ad-surface:var(--bs-body-bg);--ad-soft:var(--bs-tertiary-bg);--ad-border:var(--bs-border-color);--ad-muted:var(--bs-secondary-color);color:var(--bs-body-color)}
            .ad-hero,.ad-filters,.ad-table-card{background:var(--ad-surface);border:1px solid var(--ad-border);border-radius:14px;box-shadow:0 10px 30px rgba(15,23,42,.06)}
            .ad-hero{padding:20px;display:flex;align-items:center;justify-content:space-between;gap:15px;background:linear-gradient(135deg,color-mix(in srgb,var(--bs-primary) 8%,var(--ad-surface)),var(--ad-surface))}.ad-hero h3{margin:0 0 5px}.ad-hero p{margin:0;color:var(--ad-muted)}
            .ad-tabs{display:flex;gap:7px;overflow-x:auto;padding:3px 0 9px}.ad-tabs a{white-space:nowrap;border:1px solid var(--ad-border);background:var(--ad-surface);color:var(--bs-body-color);padding:8px 12px;border-radius:9px;text-decoration:none;font-size:.78rem;font-weight:700}.ad-tabs a.active{color:#fff;background:var(--bs-primary);border-color:var(--bs-primary)}
            .ad-filters{padding:15px}.ad-table-card{overflow:hidden}.ad-table-head{padding:14px 16px;background:var(--ad-soft);border-bottom:1px solid var(--ad-border);display:flex;align-items:center;justify-content:space-between}.ad-table{--bs-table-bg:transparent;--bs-table-color:var(--bs-body-color);--bs-table-border-color:var(--ad-border);min-width:1450px;margin:0}.ad-table th{background:var(--ad-soft);font-size:.75rem;white-space:nowrap}.ad-table td{vertical-align:middle;font-size:.79rem}.ad-name{min-width:150px}.ad-mobile{direction:ltr;text-align:right;white-space:nowrap}.ad-badge{display:inline-flex;padding:5px 8px;border-radius:999px;font-size:.7rem;font-weight:700;white-space:nowrap}.ad-reason{max-width:210px;white-space:normal;color:var(--ad-muted)}
            .ad-call-details summary{cursor:pointer;color:var(--bs-primary);white-space:nowrap}.ad-call-pop{position:absolute;z-index:15;width:min(420px,90vw);left:18px;background:var(--ad-surface);border:1px solid var(--ad-border);border-radius:10px;padding:10px;box-shadow:0 16px 38px rgba(0,0,0,.16)}.ad-call-item{padding:8px;border-bottom:1px solid var(--ad-border)}.ad-call-item:last-child{border:0}
            .ad-empty{text-align:center;padding:40px!important;color:var(--ad-muted)}.ad-pagination{padding:14px 16px;border-top:1px solid var(--ad-border)}
            @media(max-width:767.98px){.ad-hero{align-items:stretch;flex-direction:column}.ad-hero .btn{width:100%}.ad-filters .btn{width:100%}}
        </style>
    @endpush

    <div class="app-page-head mb-3"><nav aria-label="breadcrumb"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li><li class="breadcrumb-item"><a href="{{ route('admin.educational-manager.phone-acquisition.dashboard') }}">داشبورد جامع جذب</a></li><li class="breadcrumb-item active">{{ $channelLabel }}</li></ol></nav></div>

    <section class="ad-hero mb-3">
        <div><span class="small fw-bold text-primary">گزارش فقط‌خواندنی مدیر آموزشی</span><h3>{{ $channelLabel }} — {{ $segmentLabel }}</h3><p>اطلاعات پرونده، مشاور مسئول، منبع ورود، تماس‌ها و نتیجه نهایی در یک جدول.</p></div>
        <a class="btn btn-outline-primary" href="{{ route('admin.educational-manager.phone-acquisition.dashboard', ['period' => $period]) }}"><i class="fi fi-rr-arrow-right"></i> بازگشت به داشبورد</a>
    </section>

    <nav class="ad-tabs mb-2">
        @foreach($segments as $key => $label)
            <a class="{{ $segment === $key ? 'active' : '' }}" href="{{ route('admin.educational-manager.acquisition.details', ['channel'=>$channel,'segment'=>$key,'period'=>$period]) }}">{{ $label }}</a>
        @endforeach
    </nav>

    <section class="ad-filters mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4"><label class="form-label small fw-bold">جست‌وجوی نام یا موبایل</label><input class="form-control" type="search" wire:model.live.debounce.400ms="search" placeholder="نام یا شماره موبایل..."></div>
            <div class="col-6 col-md-3"><label class="form-label small fw-bold">مشاور جذب</label><select class="form-select" wire:model.live="consultant"><option value="">همه مشاوران</option>@foreach($consultants as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></div>
            <div class="col-6 col-md-2"><label class="form-label small fw-bold">بازه ورود</label><select class="form-select" wire:model.live="period">@foreach($periodLabels as $key=>$label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select></div>
            @if(!$isPhone)<div class="col-6 col-md-2"><label class="form-label small fw-bold">منبع ورود</label><select class="form-select" wire:model.live="source"><option value="">همه منابع</option><option value="site">ورود مستقیم سایت</option><option value="phone">لینک مشاور تلفنی</option></select></div>@endif
            <div class="col-6 col-md"><button class="btn btn-light border" wire:click="clearFilters"><i class="fi fi-rr-eraser"></i> پاک‌کردن</button></div>
        </div>
    </section>

    <section class="ad-table-card">
        <div class="ad-table-head"><strong>{{ $segmentLabel }}</strong><span class="badge bg-primary-subtle text-primary">{{ number_format($rows->total()) }} پرونده</span></div>
        <div class="table-responsive">
            @if($isPhone)
                <table class="table table-hover ad-table">
                    <thead><tr><th>#</th><th>نام و موبایل</th><th>پایه / رشته</th><th>استان / شهر</th><th>مشاور فعلی</th><th>وضعیت لید</th><th>عدم تمایل</th><th>تماس‌ها</th><th>آخرین نتیجه</th><th>لینک ثبت‌نام</th><th>دانش‌آموز ثبت‌شده</th><th>آخرین فعالیت</th><th>جزئیات</th></tr></thead>
                    <tbody>
                    @forelse($rows as $lead)
                        @php $latestCall=$lead->calls->last(); $link=$lead->latestRegistrationLink; @endphp
                        <tr>
                            <td>{{ $rows->firstItem()+$loop->index }}</td>
                            <td class="ad-name"><strong>{{ $lead->full_name ?: 'بدون نام' }}</strong><div class="ad-mobile text-muted">{{ $lead->mobile }}</div></td>
                            <td>{{ $lead->grade_label }}<div class="text-muted">{{ $lead->field_label }}</div></td>
                            <td>{{ $lead->state?->name ?? '—' }}<div class="text-muted">{{ $lead->city?->name ?? '—' }}</div></td>
                            <td>{{ $lead->activeAssignment?->consultant?->name ?? 'بدون تخصیص' }}</td>
                            <td><span class="ad-badge bg-{{ $lead->status_color ?? ($lead->status==='active'?'info':'secondary') }}-subtle text-{{ $lead->status==='active'?'info':'secondary' }}">{{ $lead->status_label }}</span></td>
                            <td><span class="ad-badge {{ $lead->disinterest_status==='definitive'?'bg-danger-subtle text-danger':($lead->disinterest_status==='temporary'?'bg-warning-subtle text-warning':'bg-light text-muted') }}">{{ $lead->disinterest_status_label }}</span><div class="ad-reason">{{ $lead->disinterest_reason }}</div></td>
                            <td>{{ number_format($lead->calls_count) }}<div class="text-muted">{{ $latestCall?->talk_duration_label }}</div></td>
                            <td>{{ $latestCall?->result_label ?? $lead->last_outcome_label }}<div class="text-muted">{{ $latestCall?->admin?->name }}</div></td>
                            <td>@if($link)<span class="ad-badge {{ $link->registered_user_id?'bg-success-subtle text-success':'bg-warning-subtle text-warning' }}">{{ $link->registered_user_id?'استفاده شده':'ارسال شده' }}</span><div class="text-muted">{{ $link->consultant?->name }}</div>@else — @endif</td>
                            <td>@if($link?->user)<strong>{{ $link->user->name }}</strong><div class="ad-mobile text-muted">{{ $link->user->mobile }}</div>@else — @endif</td>
                            <td>{{ $latestCall?->called_at ? jalali($latestCall->called_at)->format('%Y/%m/%d %H:i') : jalali($lead->created_at)->format('%Y/%m/%d') }}</td>
                            <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.educational-manager.phone-acquisition.history.show',$lead) }}">مشاهده تاریخچه</a></td>
                        </tr>
                    @empty<tr><td colspan="13" class="ad-empty">پرونده‌ای مطابق این فیلترها پیدا نشد.</td></tr>@endforelse
                    </tbody>
                </table>
            @else
                <table class="table table-hover ad-table">
                    <thead><tr><th>#</th><th>دانش‌آموز</th><th>موبایل‌ها</th><th>پایه / رشته</th><th>مشاور جذب آزمایشی</th><th>منبع ورود</th><th>مشاور مبدا تلفنی</th><th>مرحله فعلی</th><th>عدم تمایل</th><th>احتمال ثبت‌نام</th><th>تماس‌ها</th><th>آخرین تماس</th><th>تاریخ ورود</th><th>ریز تماس‌ها</th></tr></thead>
                    <tbody>
                    @forelse($rows as $trial)
                        @php $link=$sourceMap->get($trial->user_id); $latestCall=$trial->trialAcquisitionCalls->last(); @endphp
                        <tr>
                            <td>{{ $rows->firstItem()+$loop->index }}</td>
                            <td class="ad-name"><strong>{{ $trial->user?->name ?? 'بدون نام' }}</strong><div class="ad-mobile text-muted">{{ $trial->user?->mobile }}</div></td>
                            <td><span class="ad-mobile d-block">پدر: {{ $trial->father_mobile ?: '—' }}</span><span class="ad-mobile d-block">مادر: {{ $trial->mother_mobile ?: '—' }}</span></td>
                            <td>{{ $trial->grade_label }}<div class="text-muted">{{ $trial->field_label }}</div></td>
                            <td>{{ $trial->acquisitionSupporter?->name ?? 'بدون تخصیص' }}</td>
                            <td><span class="ad-badge {{ $link?'bg-success-subtle text-success':'bg-primary-subtle text-primary' }}">{{ $link?'لینک مشاور تلفنی':'ورود مستقیم سایت' }}</span></td>
                            <td>{{ $link?->consultant?->name ?? '—' }}</td>
                            <td><span class="ad-badge bg-{{ $trial->status_color }}-subtle text-{{ $trial->status_color }}">{{ $trial->status_label }}</span>@if($trial->program_built_at)<div class="text-success mt-1">ثبت‌نام تکمیل شده</div>@endif</td>
                            <td><span class="ad-badge {{ $trial->acq_disinterest_status==='definitive'?'bg-danger-subtle text-danger':($trial->acq_disinterest_status==='temporary'?'bg-warning-subtle text-warning':'bg-light text-muted') }}">{{ $trial->acq_disinterest_label }}</span><div class="ad-reason">{{ $trial->acq_disinterest_reason }}</div></td>
                            <td>{{ $trial->acq_probability !== null ? $trial->acq_probability.'٪' : '—' }}@if($trial->acq_confirmed)<div class="text-success">تأیید قطعی</div>@endif</td>
                            <td>{{ number_format($trial->trial_acquisition_calls_count) }}<div class="text-muted">{{ $trial->trialAcquisitionCalls->where('answered',true)->count() }} پاسخ</div></td>
                            <td>{{ $latestCall?->stage_label ?? '—' }}<div class="text-muted">{{ $latestCall?->called_at ? jalali($latestCall->called_at)->format('%Y/%m/%d %H:i') : '' }}</div></td>
                            <td>{{ jalali($trial->created_at)->format('%Y/%m/%d %H:i') }}</td>
                            <td class="position-relative">
                                <details class="ad-call-details"><summary>نمایش {{ $trial->trialAcquisitionCalls->count() }} تماس</summary><div class="ad-call-pop">
                                    @forelse($trial->trialAcquisitionCalls->sortByDesc('called_at')->take(8) as $call)<div class="ad-call-item"><strong>{{ $call->stage_label }} · {{ $call->answered?'پاسخ داده':'بی‌پاسخ' }}</strong><div>{{ $call->admin?->name ?? '—' }} — {{ $call->called_at ? jalali($call->called_at)->format('%Y/%m/%d %H:i') : '—' }}</div><small class="text-muted">{{ $call->answered ? ($call->call_subject ?: $call->spoke_with_label) : $call->fail_label }}</small></div>@empty<div class="text-muted">تماسی ثبت نشده است.</div>@endforelse
                                </div></details>
                            </td>
                        </tr>
                    @empty<tr><td colspan="14" class="ad-empty">پرونده‌ای مطابق این فیلترها پیدا نشد.</td></tr>@endforelse
                    </tbody>
                </table>
            @endif
        </div>
        @if($rows->hasPages())<div class="ad-pagination">{{ $rows->links('layouts.admin.pagination') }}</div>@endif
    </section>
</div>
