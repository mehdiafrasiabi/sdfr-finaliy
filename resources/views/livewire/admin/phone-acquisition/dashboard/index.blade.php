<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    @php
        $colorFa = ['primary'=>'آبی','success'=>'سبز','warning'=>'زرد','danger'=>'قرمز','secondary'=>'خاکستری'];
    @endphp

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">تماس برقرار نشده</div>
                    <h3 class="mb-1">{{ number_format($notCalledCount) }}</h3>
                    <div class="small text-muted">شماره‌هایی که هنوز ۰ تماس دارند.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">تماس‌های نیاز به پیگیری</div>
                    <h3 class="mb-1">{{ number_format($needsFollowUpCount) }}</h3>
                    <div class="small text-muted">شماره‌های فعالی که برایشان زمان تماس بعدی ثبت شده است.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">دانش‌آموزان من</div>
                    <h3 class="mb-1">{{ number_format($myStudentsCount) }}</h3>
                    <div class="small text-muted">ثبت‌نام‌های انجام‌شده از طریق لینک اختصاصی شما.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">ثبت‌نام‌های من</div>
                    <h3 class="mb-1">{{ number_format($registrationStats['total']) }}</h3>
                    <div class="small text-muted">اولین تبدیل واقعی: هفته آزمایشی یا خرید مستقیم.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="text-muted small mb-1">ثبت‌نام آزمایشی</div>
                    <h3 class="mb-1 text-info">{{ number_format($registrationStats['trial']) }}</h3>
                    <div class="small text-muted">کاربرانی که اولین اقدامشان شروع هفته آزمایشی بوده است.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="text-muted small mb-1">خرید مستقیم</div>
                    <h3 class="mb-1 text-success">{{ number_format($registrationStats['purchase']) }}</h3>
                    <div class="small text-muted">کاربرانی که اولین اقدامشان خرید مستقیم دوره بوده است.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">تعداد تماس‌های من تا الان</div>
                    <h3 class="mb-1">{{ number_format($myCallsCount) }}</h3>
                    <div class="small text-muted">تمام تماس‌هایی که با اکانت شما ثبت شده است.</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">زمان کل تماس‌ها</div>
                    <h3 class="mb-1">{{ $totalTalkTimeLabel }}</h3>
                    <div class="small text-muted">جمع مدت مکالمه‌های ثبت‌شده شما.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <a href="{{ route('admin.phone-acquisition.needs-follow-up-acquisition') }}" class="card border-0 shadow-sm h-100 text-decoration-none text-body">
                <div class="card-body">
                    <div class="text-muted small mb-1">نیاز پیگیری مجدد جذب تلفنی</div>
                    <h3 class="mb-1">{{ number_format($dueCount) }}</h3>
                    <div class="small text-muted">شماره‌هایی که آخرین نتیجه‌شان پیگیری بوده و هنوز باید دوباره تماس بخورند.</div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('admin.phone-acquisition.needs-follow-up-registration') }}" class="card border-0 shadow-sm h-100 text-decoration-none text-body">
                <div class="card-body">
                    <div class="text-muted small mb-1">نیاز پیگیری مجدد ثبت نام</div>
                    <h3 class="mb-1">{{ number_format($pendingRegistrationCount) }}</h3>
                    <div class="small text-muted">شماره‌هایی که در نتیجه‌ی تماس، پیگیری مجدد ثبت‌نام برایشان ثبت شده و موعدشان رسیده است.</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">رنگ‌بندی شماره‌ها</h5>
                        <span class="badge bg-light text-dark border">{{ array_sum($colorCounts) }} شماره</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($colorFa as $key => $label)
                            <span class="badge bg-{{ $key }} fs-6">
                                {{ $label }}: {{ number_format($colorCounts[$key] ?? 0) }}
                            </span>
                        @endforeach
                    </div>
                    <div class="small text-muted mt-3">خاکستری یعنی شماره مرده/غیرفعال، بقیه رنگ‌ها بر اساس تعداد تلاش‌های تماس هستند.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">بهترین ساعت برای تماس</h5>
                        @if ($bestHour)
                            <span class="badge bg-success">{{ $bestHour['answer_rate'] }}٪ پاسخ‌گویی</span>
                        @endif
                    </div>

                    @if ($bestHour)
                        <h4 class="mb-1">{{ $bestHour['label'] }}</h4>
                        <div class="small text-muted mb-3">
                            از {{ number_format($bestHour['total']) }} تماس در این بازه،
                            {{ number_format($bestHour['answered']) }} تماس پاسخ داده شده است.
                        </div>

                        @if (!empty($bestHourCandidates))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($bestHourCandidates as $hour)
                                    <span class="badge bg-light text-dark border">
                                        {{ $hour['label'] }} - {{ $hour['answer_rate'] }}٪
                                        ({{ number_format($hour['answered']) }}/{{ number_format($hour['total']) }})
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-muted">هنوز داده‌ی کافی برای پیشنهاد ساعت تماس ثبت نشده است.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ───── اهداف ثبت‌نام (تعیین‌شده توسط مدیر آموزشی) ───── --}}
    @if ($goals->isNotEmpty())
        <div class="row g-3 mb-3">
            @foreach ($goals as $goal)
                @php $pct = $goal->target_count > 0 ? min(100, round($goal->achieved / $goal->target_count * 100)) : 0; @endphp
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0">
                                    <i class="fi fi-rr-target text-primary"></i>
                                    هدف {{ $goal->isTeamGoal() ? 'تیمی' : 'شما' }}: {{ number_format($goal->target_count) }} ثبت‌نام
                                </h6>
                                <span class="badge bg-light text-dark border">تا {{ jalali($goal->goal_date)->format('%d %B') }}</span>
                            </div>
                            @if ($goal->description)
                                <p class="text-muted small mb-2">{{ $goal->description }}</p>
                            @endif
                            <div class="progress" style="height:18px">
                                <div class="progress-bar {{ $pct >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $pct }}%">
                                    {{ number_format($goal->achieved) }} / {{ number_format($goal->target_count) }} ({{ $pct }}٪)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    {{-- مودال ثبت تماس (مشترک) --}}
    @include('livewire.admin.phone-acquisition._call-form')
</div>
