<div class="em-page am-dashboard" dir="rtl">
    @include('livewire.admin.educational-manager._styles')
    @php
        $phone = $analytics['phone'];
        $trial = $analytics['trial'];
        $detailUrl = fn (string $channel, string $segment) => route('admin.educational-manager.acquisition.details', [
            'channel' => $channel,
            'segment' => $segment,
            'period' => $period,
        ]);
        $periodLabels = ['all' => 'از ابتدا', 'today' => 'امروز', '7' => '۷ روز اخیر', '30' => '۳۰ روز اخیر'];
        $maxTrend = max(1, $analytics['dailyTrend']->max(fn ($day) => max($day['phoneCalls'], $day['trialCalls'], $day['registrations'])));
    @endphp

    @push('link')
        <style>
            .am-dashboard{--am-surface:var(--bs-body-bg);--am-soft:var(--bs-tertiary-bg);--am-border:var(--bs-border-color);--am-muted:var(--bs-secondary-color);color:var(--bs-body-color)}
            .am-hero,.am-card,.am-panel{background:var(--am-surface);border:1px solid var(--am-border);border-radius:14px;box-shadow:0 10px 30px rgba(15,23,42,.06)}
            .am-hero{padding:22px;display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:18px;background:linear-gradient(135deg,color-mix(in srgb,var(--bs-primary) 9%,var(--am-surface)),var(--am-surface))}
            .am-hero h3{margin:0 0 6px}.am-hero p{margin:0;color:var(--am-muted)}.am-filter{min-width:170px}
            .am-switches{display:flex;gap:8px;flex-wrap:wrap}.am-switches .btn{border-radius:10px}
            .am-section-head{display:flex;align-items:end;justify-content:space-between;gap:12px;margin:24px 0 12px}.am-section-head h4{margin:2px 0 0}.am-kicker{font-size:.75rem;color:var(--bs-primary);font-weight:800}
            .am-card{height:100%;position:relative;overflow:hidden;transition:.18s}.am-card:hover{transform:translateY(-2px);border-color:color-mix(in srgb,var(--am-accent,var(--bs-primary)) 45%,var(--am-border))}.am-card:before{content:"";position:absolute;inset-block:0;inset-inline-start:0;width:4px;background:var(--am-accent,var(--bs-primary))}
            .am-card .card-body{padding:17px}.am-card-top{display:flex;align-items:center;justify-content:flex-start;gap:10px}.am-card-top>span{font-weight:700}.am-icon{width:42px;height:42px;flex:0 0 42px;border-radius:11px;display:grid;place-items:center;background:color-mix(in srgb,var(--am-accent,var(--bs-primary)) 13%,transparent);color:var(--am-accent,var(--bs-primary));font-size:1.1rem}.am-card strong{display:block;font-size:1.65rem;margin:12px 0 3px}.am-card small{color:var(--am-muted)}
            .am-card--success{--am-accent:#198754}.am-card--warning{--am-accent:#f59f00}.am-card--danger{--am-accent:#dc3545}.am-card--info{--am-accent:#0dcaf0}.am-card--purple{--am-accent:#6f42c1}.am-card--secondary{--am-accent:#64748b}
            a.am-card{text-decoration:none;color:inherit}.am-panel{overflow:hidden}.am-panel-head{padding:15px 17px;background:var(--am-soft);border-bottom:1px solid var(--am-border);display:flex;align-items:center;justify-content:space-between;gap:10px}.am-panel-head h5{margin:0}.am-panel-body{padding:17px}
            .am-source{display:grid;grid-template-columns:1fr 1fr;gap:12px}.am-source-item{padding:15px;border:1px solid var(--am-border);border-radius:11px}.am-source-item strong{font-size:1.45rem;display:block}.am-source-item span{font-size:.8rem;color:var(--am-muted)}
            .am-table{--bs-table-bg:transparent;--bs-table-color:var(--bs-body-color);--bs-table-border-color:var(--am-border);margin:0;min-width:900px}.am-table th{background:var(--am-soft);white-space:nowrap;font-size:.78rem}.am-table td{vertical-align:middle}.am-rank{width:28px;height:28px;border-radius:8px;display:inline-grid;place-items:center;background:var(--am-soft);font-weight:800}
            .am-trend{display:flex;align-items:end;gap:8px;min-height:210px;overflow-x:auto;padding-top:16px}.am-trend-day{min-width:48px;flex:1;display:flex;align-items:center;justify-content:end;flex-direction:column;gap:5px}.am-bars{height:145px;display:flex;align-items:end;gap:3px}.am-bar{width:8px;min-height:3px;border-radius:5px 5px 2px 2px}.am-bar-phone{background:#0d6efd}.am-bar-trial{background:#6f42c1}.am-bar-reg{background:#198754}.am-trend-day small{font-size:.66rem;color:var(--am-muted);white-space:nowrap}.am-legend{display:flex;gap:14px;flex-wrap:wrap;font-size:.75rem}.am-legend i{width:9px;height:9px;border-radius:3px;display:inline-block;margin-left:4px}
            .am-source-badge{display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:5px 9px;font-size:.72rem;font-weight:700}.am-empty{padding:30px;text-align:center;color:var(--am-muted)}
            @media(max-width:767.98px){.am-hero{align-items:stretch;flex-direction:column}.am-filter{width:100%}.am-section-head{align-items:stretch;flex-direction:column}.am-switches .btn{flex:1}.am-source{grid-template-columns:1fr}}
        </style>
    @endpush

    <div class="app-page-head mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جامع جذب</li>
            </ol>
        </nav>
    </div>

    <section class="am-hero">
        <div>
            <span class="am-kicker">مرکز کنترل مدیر آموزشی</span>
            <h3>جذب تلفنی و جذب یک‌هفته آزمایشی</h3>
            <p>آمار هر دو جریان با تعریف یکسان، منبع دقیق ورود دانش‌آموز و عملکرد تک‌تک مشاوران.</p>
        </div>
        <div class="am-filter">
            <label class="form-label small fw-bold">بازه ورود پرونده‌ها</label>
            <select class="form-select" wire:model.live="period">
                @foreach($periodLabels as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
            </select>
        </div>
    </section>

    <div class="am-switches mb-3">
        <a class="btn btn-primary" href="{{ $detailUrl('phone', 'all') }}"><i class="fi fi-rr-phone-call"></i> همه جزئیات جذب تلفنی</a>
        <a class="btn btn-outline-primary" href="{{ $detailUrl('trial', 'all') }}"><i class="fi fi-rr-rocket-lunch"></i> همه جزئیات جذب آزمایشی</a>
    </div>

    <div class="am-section-head">
        <div><span class="am-kicker">جریان اول</span><h4>جذب تلفنی</h4></div>
        <span class="badge bg-primary-subtle text-primary">{{ $periodLabels[$period] ?? 'از ابتدا' }}</span>
    </div>
    @php
        $phoneCards = [
            ['all','کل شماره‌ها',$phone['total'],'کل لیدهای واردشده','fi-rr-users',''],
            ['active','در جریان',$phone['active'],'پرونده‌های فعال','fi-rr-refresh','info'],
            ['no-call','بدون تماس',$phone['noCall'],'هنوز تماسی ثبت نشده','fi-rr-phone-slash','secondary'],
            ['temporary','عدم تمایل موقت',$phone['temporary'],'قابل پیگیری مجدد','fi-rr-time-quarter-past','warning'],
            ['definitive','عدم تمایل قطعی',$phone['definitive'],'خارج‌شده از چرخه','fi-rr-ban','danger'],
            ['registered','ثبت‌نام موفق',$phone['registrations'],'آزمایشی '.$phone['trialRegistrations'].' · امتحانی '.$phone['examRegistrations'],'fi-rr-user-add','success'],
        ];
    @endphp
    <div class="row g-3">
        @foreach($phoneCards as [$segment,$label,$value,$desc,$icon,$tone])
            <div class="col-6 col-lg-4 col-xl-2">
                <a href="{{ $detailUrl('phone', $segment) }}" class="card am-card am-card--{{ $tone }}">
                    <div class="card-body"><div class="am-card-top"><i class="am-icon fi {{ $icon }}"></i><span>{{ $label }}</span></div><strong>{{ number_format($value) }}</strong><small>{{ $desc }}</small></div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mt-1">
        @foreach([
            ['تعداد مشاور جذب',$phone['consultants'],'fi-rr-user-headset','purple'],
            ['کل تماس‌ها',$phone['calls'],'fi-rr-phone-call',''],
            ['نرخ پاسخ‌گویی',$phone['answerRate'].'٪','fi-rr-chart-histogram','info'],
            ['نرخ تبدیل',$phone['conversionRate'].'٪','fi-rr-stats','success'],
            ['زمان مکالمه',$phone['talkTime'],'fi-rr-stopwatch','secondary'],
        ] as [$label,$value,$icon,$tone])
            <div class="col-6 col-md-4 col-xl">
                <div class="card am-card am-card--{{ $tone }}"><div class="card-body"><div class="am-card-top"><i class="am-icon fi {{ $icon }}"></i><span>{{ $label }}</span></div><strong>{{ is_numeric($value) ? number_format($value) : $value }}</strong></div></div>
            </div>
        @endforeach
    </div>

    <div class="am-section-head">
        <div><span class="am-kicker">جریان دوم</span><h4>جذب یک‌هفته آزمایشی</h4></div>
        <span class="badge bg-purple-subtle text-primary">{{ $periodLabels[$period] ?? 'از ابتدا' }}</span>
    </div>
    @php
        $trialCards = [
            ['all','کل دانش‌آموزان',$trial['total'],'همه ورودی‌های آزمایشی','fi-rr-users',''],
            ['active','در جریان',$trial['active'],'پرونده‌های فعال','fi-rr-refresh','info'],
            ['unassigned','بدون مشاور',$trial['unassigned'],'نیازمند تخصیص','fi-rr-user-time','secondary'],
            ['temporary','عدم تمایل موقت',$trial['temporary'],'قابل پیگیری مجدد','fi-rr-time-quarter-past','warning'],
            ['definitive','عدم تمایل قطعی',$trial['definitive'],'خارج‌شده از چرخه','fi-rr-ban','danger'],
            ['registered','ثبت‌نام موفق',$trial['registrations'],'برنامه ساخته‌شده','fi-rr-user-add','success'],
        ];
    @endphp
    <div class="row g-3">
        @foreach($trialCards as [$segment,$label,$value,$desc,$icon,$tone])
            <div class="col-6 col-lg-4 col-xl-2">
                <a href="{{ $detailUrl('trial', $segment) }}" class="card am-card am-card--{{ $tone }}">
                    <div class="card-body"><div class="am-card-top"><i class="am-icon fi {{ $icon }}"></i><span>{{ $label }}</span></div><strong>{{ number_format($value) }}</strong><small>{{ $desc }}</small></div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mt-1">
        @foreach([
            ['تعداد مشاور جذب',$trial['consultants'],'fi-rr-user-headset','purple'],
            ['کل تماس‌ها',$trial['calls'],'fi-rr-phone-call',''],
            ['نرخ پاسخ‌گویی',$trial['answerRate'].'٪','fi-rr-chart-histogram','info'],
            ['نرخ تبدیل',$trial['conversionRate'].'٪','fi-rr-stats','success'],
            ['زمان مکالمه',$trial['talkTime'],'fi-rr-stopwatch','secondary'],
        ] as [$label,$value,$icon,$tone])
            <div class="col-6 col-md-4 col-xl">
                <div class="card am-card am-card--{{ $tone }}"><div class="card-body"><div class="am-card-top"><i class="am-icon fi {{ $icon }}"></i><span>{{ $label }}</span></div><strong>{{ $value }}</strong></div></div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mt-3">
        <div class="col-xl-4">
            <section class="am-panel h-100">
                <div class="am-panel-head"><h5>منبع ورود دانش‌آموز آزمایشی</h5><a href="{{ $detailUrl('trial','all') }}">جزئیات</a></div>
                <div class="am-panel-body">
                    <div class="am-source">
                        <a class="am-source-item text-decoration-none text-body" href="{{ $detailUrl('trial','all') }}&source=site"><strong>{{ number_format($trial['siteSource']) }}</strong><span>ورود مستقیم از سایت</span></a>
                        <a class="am-source-item text-decoration-none text-body" href="{{ $detailUrl('trial','all') }}&source=phone"><strong>{{ number_format($trial['phoneSource']) }}</strong><span>از لینک مشاور جذب تلفنی</span></a>
                    </div>
                    <div class="progress mt-3" style="height:10px">
                        @php $phoneSourcePercent = $trial['total'] ? round($trial['phoneSource'] / $trial['total'] * 100) : 0; @endphp
                        <div class="progress-bar bg-primary" style="width:{{ 100-$phoneSourcePercent }}%"></div><div class="progress-bar bg-success" style="width:{{ $phoneSourcePercent }}%"></div>
                    </div>
                    <p class="small text-muted mt-3 mb-0">برای ورودی تلفنی، نام مشاوری که لینک اختصاصی او استفاده شده در جزئیات ثبت شده است.</p>
                </div>
            </section>
        </div>
        <div class="col-xl-8">
            <section class="am-panel h-100">
                <div class="am-panel-head"><h5>روند ۱۴ روز اخیر</h5><div class="am-legend"><span><i class="bg-primary"></i>تماس تلفنی</span><span><i style="background:#6f42c1"></i>تماس آزمایشی</span><span><i class="bg-success"></i>ثبت‌نام</span></div></div>
                <div class="am-panel-body"><div class="am-trend">
                    @foreach($analytics['dailyTrend'] as $day)
                        <div class="am-trend-day" title="تلفنی: {{ $day['phoneCalls'] }} | آزمایشی: {{ $day['trialCalls'] }} | ثبت‌نام: {{ $day['registrations'] }}">
                            <div class="am-bars"><i class="am-bar am-bar-phone" style="height:{{ max(3,round($day['phoneCalls']/$maxTrend*140)) }}px"></i><i class="am-bar am-bar-trial" style="height:{{ max(3,round($day['trialCalls']/$maxTrend*140)) }}px"></i><i class="am-bar am-bar-reg" style="height:{{ max(3,round($day['registrations']/$maxTrend*140)) }}px"></i></div>
                            <small>{{ jalali($day['date'])->format('%d %b') }}</small>
                        </div>
                    @endforeach
                </div></div>
            </section>
        </div>
    </div>

    @foreach([['phone','عملکرد مشاوران جذب تلفنی',$analytics['phoneConsultants']],['trial','عملکرد مشاوران جذب آزمایشی',$analytics['trialConsultants']]] as [$channel,$title,$consultantRows])
        <section class="am-panel mt-3">
            <div class="am-panel-head"><h5>{{ $title }}</h5><a href="{{ $detailUrl($channel,'all') }}">مشاهده همه پرونده‌ها</a></div>
            <div class="table-responsive"><table class="table am-table">
                <thead><tr><th>#</th><th>مشاور</th><th>پرونده تخصیصی</th><th>تماس</th><th>پاسخ</th><th>عدم تمایل موقت</th><th>عدم تمایل قطعی</th><th>ثبت‌نام موفق</th>@if($channel==='trial')<th>سایت / تلفنی</th>@endif<th>نرخ تبدیل</th><th>زمان مکالمه</th></tr></thead>
                <tbody>
                @forelse($consultantRows as $index => $row)
                    <tr><td><span class="am-rank">{{ $index+1 }}</span></td><td class="fw-bold">{{ $row['name'] }}</td><td>{{ number_format($row['assigned']) }}</td><td>{{ number_format($row['calls']) }}</td><td>{{ number_format($row['answered']) }}</td><td class="text-warning">{{ number_format($row['temporary']) }}</td><td class="text-danger">{{ number_format($row['definitive']) }}</td><td><a class="badge bg-success-subtle text-success text-decoration-none" href="{{ $detailUrl($channel,'registered') }}&consultant={{ $row['id'] }}">{{ number_format($row['registrations']) }}</a></td>@if($channel==='trial')<td>{{ $row['siteSource'] }} / {{ $row['phoneSource'] }}</td>@endif<td>{{ $row['conversion'] }}٪</td><td>{{ $row['talkTime'] }}</td></tr>
                @empty<tr><td colspan="11" class="am-empty">مشاوری در این نقش ثبت نشده است.</td></tr>@endforelse
                </tbody>
            </table></div>
        </section>
    @endforeach

    <section class="am-panel mt-3">
        <div class="am-panel-head"><h5>آخرین ثبت‌نام‌های تکمیل‌شده و منبع دقیق آن‌ها</h5><a href="{{ $detailUrl('trial','registered') }}">همه ثبت‌نام‌ها</a></div>
        <div class="table-responsive"><table class="table am-table">
            <thead><tr><th>دانش‌آموز</th><th>موبایل</th><th>منبع ورود</th><th>مشاور مبدا</th><th>مشاور جذب آزمایشی</th><th>تاریخ تکمیل</th></tr></thead>
            <tbody>@forelse($analytics['recentRegistrations'] as $row)<tr><td class="fw-bold">{{ $row['name'] }}</td><td dir="ltr">{{ $row['mobile'] }}</td><td><span class="am-source-badge {{ $row['sourceConsultant'] ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }}">{{ $row['source'] }}</span></td><td>{{ $row['sourceConsultant'] ?? '—' }}</td><td>{{ $row['trialConsultant'] }}</td><td>{{ $row['date'] ? jalali($row['date'])->format('%Y/%m/%d %H:i') : '—' }}</td></tr>@empty<tr><td colspan="6" class="am-empty">ثبت‌نام تکمیل‌شده‌ای در این بازه وجود ندارد.</td></tr>@endforelse</tbody>
        </table></div>
    </section>
</div>
