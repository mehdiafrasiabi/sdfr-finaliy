<div class="pa-page pa-followup-page pa-dashboard-page">
    @include('livewire.admin.phone-acquisition._styles')

    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    <section class="pa-dashboard-hero mb-3">
        <div>
            <div class="pa-eyebrow"><i class="fi fi-rr-chart-histogram"></i> مرکز کنترل جذب تلفنی</div>
            <h3>سلام؛ امروز روی مهم‌ترین تماس‌ها تمرکز کنید</h3>
            <p>آمار عملکرد، صف‌های کاری و اهداف ثبت‌نام شما در یک نمای یکپارچه قرار گرفته است.</p>
        </div>
        <div class="pa-dashboard-hero-actions">
            <span class="pa-dashboard-date">
                <i class="fi fi-rr-calendar"></i>
                {{ jalali($now)->format('%A، %d %B %Y') }}
            </span>
            <a href="{{ route('admin.phone-acquisition.queue') }}" class="btn btn-primary">
                <i class="fi fi-rr-phone-call"></i> رفتن به صف تماس
            </a>
        </div>
    </section>

    @php
        $performanceStats = [
            ['label' => 'تماس برقرار نشده', 'value' => number_format($notCalledCount), 'desc' => 'شماره‌های بدون سابقه تماس', 'icon' => 'fi-rr-phone-slash', 'tone' => 'primary'],
            ['label' => 'دارای موعد پیگیری', 'value' => number_format($needsFollowUpCount), 'desc' => 'همه تماس‌های زمان‌بندی‌شده', 'icon' => 'fi-rr-calendar-clock', 'tone' => 'warning'],
            ['label' => 'دانش‌آموزان من', 'value' => number_format($myStudentsCount), 'desc' => 'برنامه ساخته و آماده شروع', 'icon' => 'fi-rr-graduation-cap', 'tone' => 'success'],
            ['label' => 'ثبت‌نام موفق', 'value' => number_format($registrationStats['total']), 'desc' => 'تبدیل‌های موفق از لینک شما', 'icon' => 'fi-rr-user-add', 'tone' => 'success'],
            ['label' => 'هفته آزمایشی', 'value' => number_format($registrationStats['trial']), 'desc' => 'برنامه آزمایشی آماده شروع', 'icon' => 'fi-rr-rocket-lunch', 'tone' => 'info'],
            ['label' => 'برنامه امتحانی', 'value' => number_format($registrationStats['exam']), 'desc' => 'بازه امتحانات آماده شروع', 'icon' => 'fi-rr-test', 'tone' => 'success'],
            ['label' => 'کل تماس‌های من', 'value' => number_format($myCallsCount), 'desc' => 'تمام تماس‌های ثبت‌شده', 'icon' => 'fi-rr-headset', 'tone' => 'primary'],
            ['label' => 'زمان کل مکالمه', 'value' => $totalTalkTimeLabel, 'desc' => 'مجموع مکالمه‌های شما', 'icon' => 'fi-rr-stopwatch', 'tone' => 'secondary'],
        ];

        $workQueues = [
            ['title' => 'پیگیری مجدد جذب', 'count' => $dueCount, 'desc' => 'موعد تماس مجدد تلفنی رسیده است', 'route' => route('admin.phone-acquisition.needs-follow-up-acquisition'), 'icon' => 'fi-rr-refresh', 'tone' => 'primary'],
            ['title' => 'پیگیری ثبت‌نام', 'count' => $pendingRegistrationCount, 'desc' => 'لینک دارند و در چرخه ثبت‌نام هستند', 'route' => route('admin.phone-acquisition.needs-follow-up-registration'), 'icon' => 'fi-rr-user-time', 'tone' => 'info'],
            ['title' => 'عدم تمایل موقت', 'count' => $temporaryDisinterestCount, 'desc' => 'امکان پیگیری در موعد مشخص وجود دارد', 'route' => route('admin.phone-acquisition.disinterest-temporary'), 'icon' => 'fi-rr-time-quarter-past', 'tone' => 'warning'],
            ['title' => 'عدم تمایل قطعی', 'count' => $definitiveDisinterestCount, 'desc' => 'از چرخه تماس مجدد خارج شده‌اند', 'route' => route('admin.phone-acquisition.disinterest-definitive'), 'icon' => 'fi-rr-ban', 'tone' => 'secondary'],
        ];

        $colorFa = ['primary' => 'آبی', 'success' => 'سبز', 'warning' => 'زرد', 'danger' => 'قرمز', 'secondary' => 'خاکستری'];
    @endphp

    <section class="mb-4">
        <div class="pa-dashboard-section-head">
            <div>
                <span>نمای کلی عملکرد</span>
                <h4>آمار فعالیت شما</h4>
            </div>
        </div>
        <div class="row g-3">
            @foreach($performanceStats as $stat)
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card pa-metric-card h-100">
                        <div class="card-body">
                            <span class="pa-metric-icon bg-{{ $stat['tone'] }}-subtle text-{{ $stat['tone'] }}">
                                <i class="fi {{ $stat['icon'] }}"></i>
                            </span>
                            <div class="pa-metric-content">
                                <span>{{ $stat['label'] }}</span>
                                <strong>{{ $stat['value'] }}</strong>
                                <small>{{ $stat['desc'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-4">
        <div class="pa-dashboard-section-head">
            <div>
                <span>اقدام‌های ضروری</span>
                <h4>صف‌های کاری</h4>
            </div>
            <a href="{{ route('admin.phone-acquisition.queue') }}">مشاهده صف اصلی <i class="fi fi-rr-arrow-left"></i></a>
        </div>
        <div class="row g-3">
            @foreach($workQueues as $queue)
                <div class="col-12 col-sm-6 col-xl-3">
                    <a href="{{ $queue['route'] }}" class="card pa-queue-card h-100 text-decoration-none">
                        <div class="card-body">
                            <div class="pa-queue-card-head">
                                <span class="pa-metric-icon bg-{{ $queue['tone'] }}-subtle text-{{ $queue['tone'] }}">
                                    <i class="fi {{ $queue['icon'] }}"></i>
                                </span>
                                <strong>{{ number_format($queue['count']) }}</strong>
                            </div>
                            <h5>{{ $queue['title'] }}</h5>
                            <p>{{ $queue['desc'] }}</p>
                            <span class="pa-queue-link">ورود به این بخش <i class="fi fi-rr-arrow-left"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="card pa-insight-card h-100">
                <div class="card-body">
                    <div class="pa-insight-head">
                        <div>
                            <span>وضعیت شماره‌ها</span>
                            <h5>رنگ‌بندی تلاش‌های تماس</h5>
                        </div>
                        <span class="badge bg-light text-dark border">{{ number_format(array_sum($colorCounts)) }} شماره</span>
                    </div>
                    <div class="pa-color-breakdown">
                        @foreach ($colorFa as $key => $label)
                            @php
                                $count = $colorCounts[$key] ?? 0;
                                $total = max(1, array_sum($colorCounts));
                                $percent = round($count / $total * 100);
                            @endphp
                            <div class="pa-color-row">
                                <span><i class="bg-{{ $key }}"></i> {{ $label }}</span>
                                <div class="progress"><div class="progress-bar bg-{{ $key }}" style="width:{{ $percent }}%"></div></div>
                                <strong>{{ number_format($count) }}</strong>
                            </div>
                        @endforeach
                    </div>
                    <p class="pa-insight-note">خاکستری نشان‌دهنده شماره غیرفعال است؛ سایر رنگ‌ها تعداد تلاش‌های تماس را نشان می‌دهند.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card pa-insight-card h-100">
                <div class="card-body">
                    <div class="pa-insight-head">
                        <div>
                            <span>تحلیل پاسخ‌گویی</span>
                            <h5>بهترین ساعت برای تماس</h5>
                        </div>
                        @if($bestHour)
                            <span class="badge bg-success">{{ $bestHour['answer_rate'] }}٪ پاسخ‌گویی</span>
                        @endif
                    </div>

                    @if($bestHour)
                        <div class="pa-best-hour">
                            <span><i class="fi fi-rr-clock-three"></i></span>
                            <div>
                                <strong>{{ $bestHour['label'] }}</strong>
                                <small>
                                    {{ number_format($bestHour['answered']) }} پاسخ از {{ number_format($bestHour['total']) }} تماس
                                </small>
                            </div>
                        </div>
                        @if(!empty($bestHourCandidates))
                            <div class="pa-hour-candidates">
                                @foreach($bestHourCandidates as $hour)
                                    <span>
                                        <strong>{{ $hour['label'] }}</strong>
                                        {{ $hour['answer_rate'] }}٪ · {{ number_format($hour['answered']) }}/{{ number_format($hour['total']) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="pa-dashboard-empty">
                            <i class="fi fi-rr-chart-pie-alt"></i>
                            <span>هنوز داده کافی برای پیشنهاد بهترین ساعت تماس وجود ندارد.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($goals->isNotEmpty())
        <section class="mb-3">
            <div class="pa-dashboard-section-head">
                <div>
                    <span>برنامه رشد</span>
                    <h4>اهداف فعال ثبت‌نام</h4>
                </div>
            </div>
            <div class="row g-3">
                @foreach($goals as $goal)
                    @php
                        $pct = $goal->target_count > 0
                            ? min(100, round($goal->achieved / $goal->target_count * 100))
                            : 0;
                    @endphp
                    <div class="col-12 col-lg-6">
                        <div class="card pa-goal-card h-100">
                            <div class="card-body">
                                <div class="pa-goal-head">
                                    <div>
                                        <span>{{ $goal->isTeamGoal() ? 'هدف تیمی' : 'هدف شخصی شما' }}</span>
                                        <h5>{{ number_format($goal->target_count) }} ثبت‌نام موفق</h5>
                                    </div>
                                    <span class="badge bg-light text-dark border">تا {{ jalali($goal->goal_date)->format('%d %B') }}</span>
                                </div>
                                @if($goal->description)
                                    <p>{{ $goal->description }}</p>
                                @endif
                                <div class="pa-goal-progress-label">
                                    <span>پیشرفت فعلی</span>
                                    <strong>{{ number_format($goal->achieved) }} از {{ number_format($goal->target_count) }} · {{ $pct }}٪</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $pct >= 100 ? 'bg-success' : 'bg-primary' }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @include('livewire.admin.phone-acquisition._call-form')
</div>
