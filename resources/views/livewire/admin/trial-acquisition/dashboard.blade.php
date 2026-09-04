<div class="ta-dashboard" dir="rtl">
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد</li>
            </ol>
        </nav>
    </div>

    @push('link')
        <style>
            @keyframes ta-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
            @keyframes ta-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.5)} 70%{box-shadow:0 0 0 22px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
            .ta-dashboard{
                --ta-surface:var(--bs-body-bg);--ta-soft:var(--bs-tertiary-bg);--ta-border:var(--bs-border-color);
                --ta-text:var(--bs-body-color);--ta-muted:var(--bs-secondary-color);--ta-shadow:0 14px 38px rgba(15,23,42,.08);
                color:var(--ta-text);direction:rtl;text-align:right;overflow-x:hidden;
            }
            [data-bs-theme="dark"] .ta-dashboard,html.dark .ta-dashboard{--ta-surface:#171d2d;--ta-soft:rgba(255,255,255,.055);--ta-border:rgba(255,255,255,.14);--ta-text:#f8fafc;--ta-muted:#e2e8f0;--ta-shadow:0 18px 48px rgba(0,0,0,.28)}
            .ta-dashboard .breadcrumb{margin-bottom:0;color:var(--ta-muted)}
            .ta-dashboard .breadcrumb a{color:var(--bs-primary);text-decoration:none}
            .ta-dashboard h1,.ta-dashboard h2,.ta-dashboard h3,.ta-dashboard h4,.ta-dashboard h5,.ta-dashboard h6,.ta-dashboard .form-label,.ta-dashboard .form-check-label{color:var(--ta-text)}
            .ta-dashboard .text-body-secondary,.ta-dashboard .text-muted,.ta-dashboard small{color:var(--ta-muted)!important}
            .ta-dashboard-hero,.ta-panel,.ta-metric-card,.ta-stage,.ta-modal-content,.ta-call-modal-content{background:var(--ta-surface);border:1px solid var(--ta-border);box-shadow:var(--ta-shadow);color:var(--ta-text)}
            .ta-dashboard-hero,.ta-panel,.ta-metric-card,.ta-modal-content,.ta-call-modal-content{border-radius:10px;overflow:hidden}
            .ta-dashboard-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:20px;margin:14px 0}
            .ta-dashboard-hero-main{display:flex;align-items:center;gap:13px;min-width:0;text-align:right}
            .ta-dashboard-hero-icon{display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;flex:0 0 50px;color:#fff;background:linear-gradient(135deg,#6f42c1,#0d6efd);border-radius:10px;font-size:1.25rem}
            .ta-dashboard-hero h3{margin:0 0 5px;font-size:1.25rem}.ta-dashboard-hero p{margin:0;color:var(--ta-muted);font-size:.77rem}
            .ta-dashboard-hero-badge{display:inline-flex;align-items:center;gap:7px;padding:9px 12px;color:var(--ta-text);background:var(--ta-soft);border:1px solid var(--ta-border);border-radius:8px;font-size:.72rem;white-space:nowrap}
            .ta-panel-header,.ta-modal-header,.ta-call-modal-header,.ta-call-modal-footer{background:var(--ta-soft);border-color:var(--ta-border)}
            .ta-metric-card{position:relative;min-height:128px}.ta-metric-card:before{content:"";position:absolute;inset-block:0;inset-inline-start:0;width:4px;background:var(--ta-accent)}
            .ta-metric-card--primary{--ta-accent:#0d6efd}
            .ta-metric-card--info{--ta-accent:#0dcaf0}
            .ta-metric-card--warning{--ta-accent:#f59f00}
            .ta-metric-card .card-body{display:flex;align-items:center;gap:13px;padding:17px}.ta-metric-icon{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;flex:0 0 44px;color:var(--ta-accent);background:color-mix(in srgb,var(--ta-accent) 13%,transparent);border-radius:9px;font-size:1.1rem}
            .ta-metric-content{min-width:0}.ta-metric-content h3{font-size:1.45rem}.ta-metric-content>div:first-child{font-weight:700}
            .ta-panel>.card-header{padding:16px 18px}.ta-panel>.card-body{padding:18px}
            .ta-stage-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
            .ta-stage{position:relative;border-radius:9px;padding:14px;min-height:160px;display:grid;grid-template-rows:auto 1fr auto;gap:12px;transition:transform .18s ease,border-color .18s ease}
            .ta-stage:hover{transform:translateY(-3px);border-color:rgba(var(--bs-primary-rgb),.42)}
            .ta-stage-circle{width:76px;height:76px;border-radius:18px;display:flex;align-items:center;justify-content:center;justify-self:center;color:#fff;font-size:1.55rem;font-weight:800;box-shadow:0 12px 28px rgba(15,23,42,.18)}
            .ta-stage-number{width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:var(--ta-soft);border:1px solid var(--ta-border);font-weight:800;flex:0 0 28px}
            .ta-stage .btn{justify-self:stretch}.ta-stage strong{color:var(--ta-text);line-height:1.55;text-align:right}
            .ta-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:ta-pulse 1.5s infinite; }
            .ta-call-icon i{ display:inline-block;animation:ta-ring 1s infinite; }
            .ta-call-icon--talk{background:#198754}
            .ta-dashboard .modal{padding:10px;text-align:right}.ta-dashboard .modal-dialog{margin:0 auto}.ta-dashboard .modal-content{max-height:calc(100dvh - 20px);direction:rtl}.ta-dashboard .modal-body{overflow-y:auto;text-align:right}
            .ta-call-modal-body{background:var(--ta-surface)}.ta-call-modal-content .text-white{color:var(--ta-text)!important}.ta-call-muted{color:var(--ta-muted)}
            .ta-form-panel{background:var(--ta-soft);color:var(--ta-text);border:1px solid var(--ta-border);border-radius:8px;padding:1rem}
            .ta-dashboard .form-control,.ta-dashboard .form-select{min-height:42px;color:var(--ta-text);background:var(--ta-surface);border-color:var(--ta-border)}
            .ta-dashboard .form-control::placeholder{color:var(--ta-muted)}
            .ta-dashboard .form-check{padding:10px 12px;background:var(--ta-surface);border:1px solid var(--ta-border);border-radius:8px}
            .ta-dashboard .ta-form-panel .form-check{display:flex;align-items:center;gap:8px}.ta-dashboard .ta-form-panel .form-check-input{float:none;flex:0 0 auto;margin:0!important}
            .ta-dashboard .btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;white-space:normal;text-align:center}
            .ta-table{--bs-table-bg:transparent;--bs-table-color:var(--ta-text);--bs-table-border-color:var(--ta-border)}
            .ta-table-head th{background:var(--ta-soft);color:var(--ta-text);border-color:var(--ta-border)}
            .ta-table{min-width:880px}.ta-table td,.ta-table th{padding:12px;vertical-align:middle}
            .ta-source-badge{display:inline-flex;align-items:center;justify-content:center;min-width:142px;padding:.45rem .65rem;border-radius:999px;border:1px solid currentColor;font-weight:700;font-size:.78rem}
            [data-bs-theme="dark"] .ta-dashboard .badge.bg-warning,html.dark .ta-dashboard .badge.bg-warning{color:#fff!important;background:#854d0e!important}
            [data-bs-theme="dark"] .ta-dashboard .bg-body-tertiary,html.dark .ta-dashboard .bg-body-tertiary{color:#fff!important;background:var(--ta-soft)!important}
            [data-bs-theme="dark"] .ta-call-modal-header .btn-close,[data-bs-theme="dark"] .ta-modal-header .btn-close,html.dark .ta-call-modal-header .btn-close,html.dark .ta-modal-header .btn-close{filter:invert(1) grayscale(100%) brightness(200%)}
            [x-cloak]{ display:none !important; }
            jdp-container{ z-index:99999 !important; }
            @media(max-width:991.98px){.ta-stage-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
            @media(max-width:767.98px){
                .ta-dashboard{margin-inline:-5px}.ta-dashboard .app-page-head{padding-inline:5px}.ta-dashboard-hero{align-items:stretch;flex-direction:column;padding:15px}.ta-dashboard-hero-badge{justify-content:center;width:100%}
                .ta-metric-card{min-height:112px}.ta-metric-card .card-body{align-items:flex-start;flex-direction:column;padding:14px}.ta-metric-icon{width:38px;height:38px;flex-basis:38px}
                .ta-panel>.card-header,.ta-panel>.card-body{padding:14px}.ta-panel-header>.d-flex{align-items:flex-start!important;flex-direction:column}.ta-stage-grid{grid-template-columns:1fr}.ta-stage{display:flex;align-items:center;flex-direction:row;flex-wrap:wrap;min-height:0;text-align:right}.ta-stage>div{order:1;flex:1 1 calc(100% - 78px);justify-content:flex-start!important}.ta-stage-circle{order:2;width:64px;height:64px;border-radius:15px;flex:0 0 64px}.ta-stage>.btn{order:3;width:100%;min-height:40px}
                .ta-dashboard .modal{padding:6px}.ta-dashboard .modal-dialog{width:100%;max-width:100%}.ta-dashboard .modal-content{max-height:calc(100dvh - 12px)}.ta-dashboard .modal-header,.ta-dashboard .modal-footer{padding:12px}.ta-dashboard .modal-header{align-items:flex-start}.ta-dashboard .modal-header>div{min-width:0}.ta-dashboard .modal-title{font-size:.96rem;line-height:1.65}.ta-dashboard .modal-footer>div{display:grid;width:100%;gap:7px}.ta-dashboard .modal-footer .btn{width:100%;min-height:42px;margin:0}.ta-call-icon{width:74px;height:74px;font-size:29px}
                .ta-dashboard .table-responsive{direction:rtl}.ta-dashboard .ta-table{margin-right:0}.ta-dashboard .ta-form-panel{padding:12px}.ta-dashboard .ta-form-panel .row{--bs-gutter-x:.5rem}
            }
            @media(max-width:420px){.ta-dashboard-hero-main{align-items:flex-start}.ta-dashboard-hero-icon{width:42px;height:42px;flex-basis:42px}.ta-dashboard-hero h3{font-size:1.05rem}.ta-stage{gap:9px}.ta-stage>div{flex-basis:calc(100% - 66px)}.ta-stage-circle{width:56px;height:56px;flex-basis:56px;font-size:1.2rem}}
        </style>
    @endpush

    <section class="ta-dashboard-hero">
        <div class="ta-dashboard-hero-main">
            <span class="ta-dashboard-hero-icon"><i class="fi fi-rr-rocket-lunch"></i></span>
            <div>
                <h3>داشبورد جذب دوره آزمایشی</h3>
                <p>وضعیت ثبت‌نام‌ها و مرحله فعلی هر دانش‌آموز را بررسی کنید و پیگیری‌ها را از همین صفحه انجام دهید.</p>
            </div>
        </div>
        <span class="ta-dashboard-hero-badge"><i class="fi fi-rr-users"></i> {{ number_format($stages->sum('count')) }} دانش‌آموز نیازمند پیگیری</span>
    </section>


    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="ta-metric-card ta-metric-card--primary h-100">
                <div class="card-body">
                    <span class="ta-metric-icon"><i class="fi fi-rr-user-add"></i></span>
                    <div class="ta-metric-content">
                        <div class="text-body-secondary small mb-1">ثبت‌نام تکمیل‌شده با لینک من</div>
                        <h3 class="mb-1 text-primary">{{ number_format($uniqueLinkRegistrationStats['total']) }}</h3>
                        <div class="small text-body-secondary">برنامه ساخته‌شده و آماده شروع</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ta-metric-card ta-metric-card--info h-100">
                <div class="card-body">
                    <span class="ta-metric-icon"><i class="fi fi-rr-calendar-star"></i></span>
                    <div class="ta-metric-content">
                        <div class="text-body-secondary small mb-1">یک هفته آزمایشی</div>
                        <h3 class="mb-1 text-info">{{ number_format($uniqueLinkRegistrationStats['trial']) }}</h3>
                        <div class="small text-body-secondary">هفته آزمایشی آماده شروع</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ta-metric-card ta-metric-card--warning h-100">
                <div class="card-body">
                    <span class="ta-metric-icon"><i class="fi fi-rr-test"></i></span>
                    <div class="ta-metric-content">
                        <div class="text-body-secondary small mb-1">برنامه امتحانات</div>
                        <h3 class="mb-1 text-warning">{{ number_format($uniqueLinkRegistrationStats['exam']) }}</h3>
                        <div class="small text-body-secondary">برنامه امتحانی آماده شروع</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ta-panel">
        <div class="card-header ta-panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="mb-1">مراحل پیگیری دانش‌آموزان</h5>
                    <div class="small text-body-secondary">هر دانش‌آموز فقط در مرحله فعلی خود قرار دارد؛ برای مشاهده جزئیات روی مرحله بزنید.</div>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">۶ مرحله پیگیری</span>
            </div>
        </div>
        <div class="card-body">
            <div class="ta-stage-grid">
                @foreach($stages as $stage)
                    <div class="ta-stage" wire:key="dashboard-stage-{{ $stage['key'] }}">
                        <div class="d-flex align-items-center justify-content-center gap-2">

                            <strong class="small">{{ $stage['label'] }}</strong>
                        </div>
                        <button type="button"
                                class="ta-stage-circle border-0"
                                style="background:{{ $stage['color'] }}"
                                wire:click="showStageStudents('{{ $stage['key'] }}')"
                                title="مشاهده دانش‌آموزان">

                           <span class="small">{{ number_format($stage['count']) }} نفر </span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="showStageStudents('{{ $stage['key'] }}')">
                            <i class="fi fi-rr-eye"></i>
                            مشاهده
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if($showStageStudentsModal && $selectedStage)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.55)">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content ta-modal-content">
                    <div class="modal-header ta-modal-header">
                        <div>
                            <h5 class="modal-title mb-1">{{ $selectedStage['label'] }}</h5>
                            <div class="small text-body-secondary">لیست دانش‌آموزانی که الان نیاز به تماس پیگیری همین بخش دارند</div>
                        </div>
                        <button type="button" class="btn-close" wire:click="closeStageStudentsModal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 ta-table">
                                <thead class="ta-table-head">
                                <tr>
                                    <th>نام و نام خانوادگی</th>
                                    <th>پایه و رشته</th>
                                    <th>زمان ثبت نام</th>
                                    <th>موضوع</th>
                                    <th class="text-center">منبع ثبت‌نام</th>
                                    <th class="text-center">پیگیری</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($selectedStudents as $trial)
                                        @php
                                            $registrationSource = $registrationSources[$trial->id] ?? [
                                                'label' => 'مراجعه به سایت',
                                                'class' => 'bg-body-tertiary text-body border',
                                            ];
                                        @endphp
                                        <tr wire:key="stage-student-{{ $trial->id }}">
                                            <td>
                                                <div class="fw-semibold">{{ $this->trialStudentFullName($trial) }}</div>
                                                <div class="small text-body-secondary" dir="ltr">{{ $trial->user?->mobile ?? '—' }}</div>
                                            </td>
                                            <td>{{ $trial->grade_label }} / {{ $trial->field_label }}</td>
                                            <td>{{ jdate($trial->created_at)->format('Y/m/d H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $this->subjectLabel($trial) === 'بازه امتحانات' ? 'bg-warning text-dark' : 'bg-primary' }}">
                                                    {{ $this->subjectLabel($trial) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="ta-source-badge {{ $registrationSource['class'] }}">
                                                    {{ $registrationSource['label'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-sm btn-success"
                                                        wire:click="promptRegistrationFollowUp({{ $trial->id }}, '{{ $selectedDashboardStage }}')">
                                                    <i class="fi fi-rr-phone-call"></i>
                                                    {{ $selectedStage['label'] }}
                                                </button>
                                            </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-body-secondary py-5">دانش‌آموزی در این مرحله وجود ندارد.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer ta-modal-header">
                        <button type="button" class="btn btn-light" wire:click="closeStageStudentsModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showCallConfirmModal)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.6)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ta-call-modal-content">
                    <div class="modal-header ta-call-modal-header border-0">
                        <h5 class="modal-title text-white">تأیید شروع تماس</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cancelCallPrompt"></button>
                    </div>
                    <div class="modal-body ta-call-modal-body py-5 text-center">
                        <div class="ta-call-icon mb-4"><i class="fi fi-rr-phone-call"></i></div>
                        <h5 class="text-white mb-2">آیا مطمئنی می‌خواهی تماس بگیری؟</h5>
                        <p class="ta-call-muted mb-0">با تأیید، مودال ثبت نتیجه تماس باز می‌شود.</p>
                    </div>
                    <div class="modal-footer ta-call-modal-footer border-0">
                        <button type="button" class="btn btn-light" wire:click="cancelCallPrompt">لغو</button>
                        <button type="button" class="btn btn-success btn-lg" wire:click="continueCallPrompt">
                            <i class="fi fi-rr-phone-call"></i> بله
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($activeTrialId && $activeTrial)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.6)" wire:ignore.self>
            @php $stageLabel = $this->stageLabel($activeStage); @endphp
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content ta-call-modal-content"
                     x-data="{
                        phase: @entangle('callPhase'),
                        secondsLeft: 15,
                        talkSeconds: 0,
                        timer: null,
                        init() { this.$watch('phase', (value) => this.handlePhaseChange(value)); this.handlePhaseChange(this.phase); },
                        stopTimer() { if (this.timer) clearInterval(this.timer); this.timer = null; },
                        handlePhaseChange(currentPhase) {
                            this.stopTimer();
                            if (currentPhase === 'ringing') {
                                this.secondsLeft = 15;
                                this.timer = setInterval(() => { if (this.secondsLeft > 0) this.secondsLeft--; else this.stopTimer(); }, 1000);
                            } else if (currentPhase === 'talking') {
                                this.talkSeconds = 0;
                                this.timer = setInterval(() => { this.talkSeconds++; }, 1000);
                            }
                        },
                        formatTime(s) { return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0'); },
                        endTalkAndSync() { this.stopTimer(); $wire.endConversation(this.talkSeconds); }
                     }"
                     x-init="init()">
                    <div class="modal-header ta-call-modal-header text-end">
                        <div>
                            <h5 class="modal-title text-white mb-0">موضوع تماس: {{ $dashboardCallTitle ?: $stageLabel }}</h5>
                            <div class="small ta-call-muted" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }} ({{ $this->trialStudentFullName($activeTrial) }})</div>
                        </div>
                    </div>
                    <div class="modal-body ta-call-modal-body">
                        <div x-show="phase === 'ringing'" x-cloak class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="mb-1 text-white">در حال تماس…</h5>
                            <div class="display-4 fw-bold text-primary" dir="ltr" x-text="secondsLeft"></div>
                        </div>

                        <div x-show="phase === 'talking'" x-cloak class="text-center py-4">
                            <div class="ta-call-icon ta-call-icon--talk mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                            <h5 class="mb-1 text-white">در حال مکالمه…</h5>
                            <div class="display-3 fw-bold text-success" dir="ltr" x-text="formatTime(talkSeconds)"></div>
                        </div>

                        <div x-show="phase !== 'ringing' && phase !== 'talking'" x-cloak>
                            @if($answered)
                                <div class="ta-form-panel">
                                    <div class="mb-3">
                                        <label class="form-label">با چه کسی صحبت شد؟ <span class="text-danger">*</span></label>
                                        <div class="row g-2">
                                            @foreach (['father' => 'پدر', 'mother' => 'مادر', 'student' => 'دانش‌آموز', 'other' => 'سایر'] as $key => $label)
                                                <div class="col-6 col-md-3">
                                                    <label class="form-check p-2 h-100 mb-0">
                                                        <input type="checkbox" class="form-check-input" wire:model.live="spokeWith" value="{{ $key }}">
                                                        <span class="form-check-label">{{ $label }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('spokeWith')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        @if (in_array('other', $spokeWith, true))
                                            <div class="mt-3">
                                                <input type="text" wire:model="spokeWithOther" class="form-control" placeholder="نام شخص دیگر را بنویسید">
                                                @error('spokeWithOther')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">یادآور</label>
                                        <div wire:ignore
                                             x-data="{}"
                                             x-init="$el.querySelector('input').addEventListener('jdp:change', (e) => { $wire.set('reminderAt', e.target.value) })">
                                            <input type="text"
                                                   data-jdp
                                                   class="form-control"
                                                   placeholder="انتخاب تاریخ و ساعت شمسی"
                                                   autocomplete="off"
                                                   readonly
                                                   data-jdp-gregorian-format="Y-m-d H:i:s">
                                        </div>
                                        @error('reminderAt')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="taDashHasDisinterest" wire:model.live="hasDisinterest">
                                        <label class="form-check-label" for="taDashHasDisinterest">عدم تمایل</label>
                                    </div>

                                    @if($hasDisinterest)
                                        <div class="mb-3">
                                            <label class="form-label">نوع عدم تمایل <span class="text-danger">*</span></label>
                                            <select wire:model.live="disinterestStatus" class="form-select">
                                                <option value="">— انتخاب کنید —</option>
                                                <option value="{{ \App\Models\TrialWeek::ACQ_DISINTEREST_TEMPORARY }}">عدم تمایل موقت</option>
                                                <option value="{{ \App\Models\TrialWeek::ACQ_DISINTEREST_DEFINITIVE }}">عدم تمایل قطعی</option>
                                            </select>
                                            @error('disinterestStatus')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">علت عدم تمایل <span class="text-danger">*</span></label>
                                            <textarea wire:model="disinterestReason" rows="3" class="form-control"></textarea>
                                            @error('disinterestReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <label class="form-label">خلاصه گفتگو <span class="text-danger">*</span></label>
                                            <textarea wire:model="callSummary" rows="3" class="form-control"></textarea>
                                            @error('callSummary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="ta-form-panel">
                                    <div class="mb-3">
                                        <label class="form-label">علت عدم پاسخ <span class="text-danger">*</span></label>
                                        <select wire:model.live="failReason" class="form-select">
                                            <option value="">— انتخاب کنید —</option>
                                            <option value="no_answer">عدم پاسخ</option>
                                            <option value="off">خاموش</option>
                                            <option value="rejected">رد تماس</option>
                                            <option value="unavailable">عدم دسترس</option>
                                            <option value="other">سایر</option>
                                        </select>
                                        @error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    @if($failReason === 'other')
                                        <div class="mb-3">
                                            <label class="form-label">توضیح علت <span class="text-danger">*</span></label>
                                            <input type="text" wire:model="otherFailReason" class="form-control" maxlength="100">
                                            @error('otherFailReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer ta-call-modal-footer">
                        <div x-show="phase === 'ringing'" x-cloak>
                            <button class="btn btn-success" wire:click="markCallAnswered()">پاسخ داد</button>
                            <button class="btn btn-outline-danger" wire:click="markNoAnswer()">پاسخ نداد</button>
                            <button class="btn btn-outline-secondary" wire:click="closeCallForm">لغو</button>
                        </div>
                        <div x-show="phase === 'talking'" x-cloak>
                            <button type="button" class="btn btn-danger btn-lg w-100" @click="endTalkAndSync()">اتمام مکالمه</button>
                        </div>
                        <div x-show="phase !== 'ringing' && phase !== 'talking'" x-cloak>
                            <button class="btn btn-primary" wire:click="logCall">ثبت</button>
                            <button class="btn btn-secondary" wire:click="closeCallForm">انصراف</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @push('script')
        <script>
            (function () {
                if (window.__taDashboardJdpBooted) {
                    return;
                }
                window.__taDashboardJdpBooted = true;

                function bootJdp() {
                    if (typeof jalaliDatepicker === 'undefined') {
                        return;
                    }

                    jalaliDatepicker.startWatch({
                        date: true,
                        time: true,
                        hasSecond: false,
                        autoHide: true,
                        hideAfterChangeWithTime: true,
                    });
                }

                document.addEventListener('DOMContentLoaded', bootJdp);
                document.addEventListener('livewire:navigated', bootJdp);
                document.addEventListener('trial-call-form-opened', bootJdp);
            })();
        </script>
    @endpush
</div>
