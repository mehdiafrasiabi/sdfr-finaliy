<div class="tai-page" dir="rtl">
    @push('link')
        <style>
            @keyframes tai-ring{0%,100%{transform:rotate(0)}20%{transform:rotate(14deg)}40%{transform:rotate(-14deg)}60%{transform:rotate(9deg)}80%{transform:rotate(-9deg)}}
            @keyframes tai-pulse{0%{box-shadow:0 0 0 0 rgba(13,110,253,.5)}70%{box-shadow:0 0 0 22px rgba(13,110,253,0)}100%{box-shadow:0 0 0 0 rgba(13,110,253,0)}}
            .tai-page{
                --tai-surface:var(--bs-body-bg);--tai-soft:var(--bs-tertiary-bg);--tai-border:var(--bs-border-color);
                --tai-text:var(--bs-body-color);--tai-muted:var(--bs-secondary-color);--tai-shadow:0 14px 38px rgba(15,23,42,.08);
                color:var(--tai-text);direction:rtl;text-align:right;overflow-x:hidden;
            }
            [data-bs-theme="dark"] .tai-page,html.dark .tai-page{
                --tai-surface:#171d2d;--tai-soft:rgba(255,255,255,.055);--tai-border:rgba(255,255,255,.14);
                --tai-text:#f8fafc;--tai-muted:#e2e8f0;--tai-shadow:0 18px 48px rgba(0,0,0,.28);
            }
            .tai-page .breadcrumb{margin-bottom:0;color:var(--tai-muted)}.tai-page .breadcrumb a{color:var(--bs-primary);text-decoration:none}
            .tai-hero,.tai-shell{background:var(--tai-surface);border:1px solid var(--tai-border);border-radius:10px;box-shadow:var(--tai-shadow)}
            .tai-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;margin:14px 0;padding:20px}
            .tai-hero-main{display:flex;align-items:center;gap:13px;min-width:0}.tai-hero-icon{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;flex:0 0 48px;color:#fff;background:linear-gradient(135deg,#0d6efd,#6f42c1);border-radius:10px;font-size:1.2rem}
            .tai-hero h3{margin:0 0 4px;color:var(--tai-text);font-size:1.18rem}.tai-hero p{margin:0;color:var(--tai-muted);font-size:.77rem;line-height:1.8}
            .tai-total{display:flex;align-items:center;gap:8px;flex:0 0 auto;padding:9px 12px;background:var(--tai-soft);border:1px solid var(--tai-border);border-radius:9px}.tai-total strong{color:var(--tai-text);font-size:1.15rem}.tai-total span{color:var(--tai-muted);font-size:.7rem}
            .tai-shell{overflow:hidden}.tai-toolbar{display:grid;grid-template-columns:minmax(0,1fr) minmax(250px,390px) minmax(170px,220px);align-items:center;gap:12px;padding:14px 16px;background:var(--tai-soft);border-bottom:1px solid var(--tai-border)}
            .tai-toolbar-title{color:var(--tai-text);font-size:.82rem;font-weight:800}.tai-search{position:relative}.tai-search i{position:absolute;inset-inline-start:13px;top:50%;z-index:2;color:var(--tai-muted);transform:translateY(-50%)}
            .tai-search .form-control{padding-inline-start:40px}.tai-page .form-control,.tai-page .form-select{min-height:42px;color:var(--tai-text);background-color:var(--tai-surface);border-color:var(--tai-border);text-align:right}.tai-page .form-control::placeholder{color:var(--tai-muted);opacity:.8}
            .tai-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;padding:14px;background:var(--tai-soft)}
            .tai-card{position:relative;display:flex;flex-direction:column;min-width:0;padding:15px;background:var(--tai-surface);border:1px solid var(--tai-border);border-radius:10px;box-shadow:0 8px 24px rgba(15,23,42,.055);overflow:hidden}
            .tai-card.is-due{border-color:rgba(var(--bs-danger-rgb),.55)}.tai-card.is-due::before{content:"";position:absolute;inset-block:0;inset-inline-start:0;width:4px;background:var(--bs-danger)}
            .tai-card-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding-bottom:12px;border-bottom:1px solid var(--tai-border)}.tai-student{display:flex;align-items:center;gap:10px;min-width:0}
            .tai-avatar{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;flex:0 0 40px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.12);border-radius:10px;font-weight:900}.tai-name{color:var(--tai-text);font-size:.85rem;font-weight:850;overflow-wrap:anywhere}.tai-mobile{margin-top:3px;color:var(--tai-muted);font-size:.69rem;direction:ltr;unicode-bidi:isolate;text-align:right}
            .tai-badges{display:flex;align-items:center;justify-content:flex-end;flex-wrap:wrap;gap:5px;max-width:55%}.tai-badge{display:inline-flex;align-items:center;padding:4px 7px;border-radius:999px;font-size:.62rem;font-weight:800;white-space:normal;text-align:center}
            .tai-info{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin:12px 0}.tai-info-item{padding:8px 9px;background:var(--tai-soft);border:1px solid var(--tai-border);border-radius:8px;min-width:0}.tai-info-label{display:block;margin-bottom:3px;color:var(--tai-muted);font-size:.61rem}.tai-info-value{display:block;color:var(--tai-text);font-size:.7rem;font-weight:750;overflow-wrap:anywhere}.tai-info-value[dir="ltr"]{text-align:right;unicode-bidi:isolate}
            .tai-reminder{display:flex;align-items:center;gap:7px;margin-bottom:12px;padding:8px 10px;color:#92400e;background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;font-size:.68rem;font-weight:800}.tai-reminder time{direction:ltr;unicode-bidi:isolate}
            [data-bs-theme="dark"] .tai-reminder,html.dark .tai-reminder{color:#fde68a;background:rgba(245,158,11,.14);border-color:rgba(245,158,11,.4)}
            .tai-stage-title{display:flex;align-items:center;gap:6px;margin:0 0 8px;color:var(--tai-text);font-size:.7rem;font-weight:850}.tai-stages{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:7px;margin-bottom:12px}.tai-stages .btn{min-height:39px;padding:6px 7px;font-size:.67rem}.tai-stages .badge{font-size:.58rem}
            .tai-card-footer{display:flex;align-items:center;margin-top:auto;padding-top:11px;border-top:1px solid var(--tai-border)}.tai-card-footer .btn{width:100%;min-height:40px}
            .tai-page .btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;white-space:normal;text-align:center}.tai-page .btn:disabled{opacity:.68}
            .tai-empty{grid-column:1/-1;display:flex;flex-direction:column;align-items:center;gap:10px;min-height:260px;padding:45px 20px;color:var(--tai-muted);background:var(--tai-surface);border:1px dashed var(--tai-border);border-radius:10px;text-align:center}.tai-empty i{display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.1);border-radius:50%;font-size:1.35rem}
            .tai-pagination{padding:12px 16px;background:var(--tai-surface);border-top:1px solid var(--tai-border)}
            .tai-modal{direction:rtl;text-align:right}.tai-modal .modal-content{color:var(--tai-text);background:var(--tai-surface);border:1px solid var(--tai-border);border-radius:12px;box-shadow:0 24px 70px rgba(0,0,0,.28);overflow:hidden}.tai-modal .modal-header,.tai-modal .modal-footer{background:var(--tai-soft);border-color:var(--tai-border)}.tai-modal .modal-title{color:var(--tai-text)}.tai-modal .modal-body{color:var(--tai-text)}.tai-guide{padding-inline-start:1.2rem;color:var(--tai-muted);font-size:.78rem;line-height:2}.tai-guide li::marker{color:var(--bs-primary)}
            .ta-call-modal-content{direction:rtl;color:#fff!important;background:#151a27!important;border:1px solid rgba(255,255,255,.12)!important;border-radius:12px;overflow:hidden}.ta-call-modal-header,.ta-call-modal-footer,.ta-call-modal-body{background:#151a27!important;border-color:rgba(255,255,255,.1)!important}.ta-call-muted{color:#cbd5e1}.ta-call-icon{display:flex;align-items:center;justify-content:center;width:82px;height:82px;margin-inline:auto;color:#fff;background:#0d6efd;border-radius:50%;font-size:31px;animation:tai-pulse 1.5s infinite}.ta-call-icon i{display:inline-block;animation:tai-ring 1s infinite}.ta-call-icon.is-talking{background:#198754}
            .tai-form-panel{padding:15px;color:var(--tai-text);background:var(--tai-surface);border:1px solid var(--tai-border);border-radius:10px}.tai-form-panel .form-label,.tai-form-panel .form-check-label{color:var(--tai-text)}.tai-form-panel .form-check{display:flex;align-items:center;gap:8px;padding:9px!important;background:var(--tai-soft);border:1px solid var(--tai-border);border-radius:8px}.tai-form-panel .form-check-input{float:none;margin:0!important}.tai-modal-actions{display:flex;align-items:center;flex-wrap:wrap;gap:8px;width:100%}.tai-call-modal .modal-footer>div{width:100%}.tai-call-modal .modal-footer>div:not([style*="display: none"]){display:flex;align-items:center;justify-content:flex-end;flex-wrap:wrap;gap:8px}
            [data-bs-theme="dark"] .tai-page .btn-outline-primary,html.dark .tai-page .btn-outline-primary{color:#93c5fd;border-color:#60a5fa}[data-bs-theme="dark"] .tai-page .btn-outline-secondary,html.dark .tai-page .btn-outline-secondary{color:#e2e8f0;border-color:#64748b}[x-cloak]{display:none!important}jdp-container{z-index:99999!important}
            @media(max-width:991.98px){.tai-toolbar{grid-template-columns:1fr 1fr}.tai-toolbar-title{grid-column:1/-1}.tai-grid{grid-template-columns:1fr}.tai-badges{max-width:50%}}
            @media(max-width:767.98px){
                .tai-page{margin-inline:-5px}.tai-page .app-page-head{padding-inline:5px}.tai-hero{align-items:stretch;flex-direction:column;padding:15px}.tai-total{justify-content:center;width:100%}.tai-toolbar{display:flex;align-items:stretch;flex-direction:column}.tai-grid{padding:9px}.tai-card{padding:13px}.tai-card-head{align-items:stretch;flex-direction:column}.tai-badges{justify-content:flex-start;max-width:none}.tai-stages{grid-template-columns:1fr}.tai-stages .btn{width:100%;min-height:42px}.tai-info{grid-template-columns:1fr 1fr}
                .tai-modal .modal-dialog,.tai-call-modal .modal-dialog{width:auto;max-width:100%;margin:10px}.tai-modal .modal-header,.tai-call-modal .modal-header{align-items:flex-start}.tai-modal .modal-title,.tai-call-modal .modal-title{font-size:1rem;line-height:1.7}.tai-modal .modal-footer,.tai-call-modal .modal-footer{display:flex;flex-direction:column;align-items:stretch}.tai-modal .modal-footer .btn,.tai-call-modal .modal-footer .btn{width:100%;min-height:42px}.tai-call-modal .modal-footer>div:not([style*="display: none"]){display:grid;grid-template-columns:1fr;width:100%}.tai-form-panel{padding:12px}.ta-call-icon{width:70px;height:70px;font-size:27px}
            }
            @media(max-width:420px){.tai-hero-main{align-items:flex-start}.tai-hero-icon{width:42px;height:42px;flex-basis:42px}.tai-hero h3{font-size:1.02rem}.tai-info{grid-template-columns:1fr}.tai-grid{padding-inline:5px}}
        </style>
    @endpush

    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">{{ $pageBreadcrumb ?? 'جذب یک هفته آزمایشی' }}</li>
            </ol>
        </nav>
    </div>

    <section class="tai-hero">
        <div class="tai-hero-main">
            <span class="tai-hero-icon"><i class="fi fi-rr-phone-call"></i></span>
            <div>
                <h3>{{ $pageTitle ?? 'دانش‌آموزان جذب آزمایشی من' }}</h3>
                <p>{{ $pageSubtitle ?? 'مراحل تماس بر اساس روزهای هفته آزمایشی: روز اول، روز سوم و روز هفتم.' }}</p>
            </div>
        </div>
        <div class="tai-total"><strong>{{ number_format($trials->total()) }}</strong><span>دانش‌آموز</span></div>
    </section>

    <section class="tai-shell">
        <div class="tai-toolbar">
            <span class="tai-toolbar-title">فهرست پیگیری تماس‌ها</span>
            <label class="tai-search">
                <i class="fi fi-rr-search"></i>
                <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="جست‌وجوی نام یا شماره موبایل…" autocomplete="off">
            </label>
            <select wire:model.live="filter" class="form-select" aria-label="فیلتر دانش‌آموزان">
                <option value="">همه دانش‌آموزان</option>
                <option value="not_called">تماس‌گرفته‌نشده</option>
                <option value="reminders">دارای یادآور</option>
                <option value="confirmed">ثبت‌نام قطعی</option>
            </select>
        </div>

        <div class="tai-grid" wire:loading.class="opacity-50">
            @forelse($trials as $trial)
                @php
                    $calls = $trial->trialAcquisitionCalls;
                    $byStage = $calls->groupBy('stage');
                    $hasMonitorAccess = $calls->contains(fn ($call) => (bool) $call->answered && $call->stage !== \App\Models\TrialAcquisitionCall::STAGE_EXTRA);
                    $hasExamProgram = $trial->student?->examSchedules?->isNotEmpty() ?? false;
                    $examStage = $this->examProgramStage($trial);
                    $stageMeta = $this->stageMetaFor($trial, $now);
                    $reminderDue = $trial->acq_reminder_at && $trial->acq_reminder_at->lte($now);
                    $name = $this->trialStudentFullName($trial);
                @endphp
                <article class="tai-card {{ $reminderDue ? 'is-due' : '' }}" wire:key="trial-card-{{ $trial->id }}">
                    <div class="tai-card-head">
                        <div class="tai-student">
                            <span class="tai-avatar">{{ mb_substr($name, 0, 1) }}</span>
                            <div>
                                <div class="tai-name">{{ $name }}</div>
                                <div class="tai-mobile">{{ $trial->user?->mobile ?? 'شماره ثبت نشده' }}</div>
                            </div>
                        </div>
                        <div class="tai-badges">
                            @if($hasExamProgram)<span class="tai-badge bg-warning text-dark">برنامه امتحانی</span>@endif
                            @if($examStage)<span class="tai-badge bg-{{ $examStage['color'] }} text-white">{{ $examStage['label'] }}</span>@endif
                            @if($trial->acq_confirmed)<span class="tai-badge bg-success text-white">ثبت‌نام قطعی</span>@endif
                            <span class="tai-badge {{ $trial->isExpired() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                {{ $trial->isExpired() ? 'دسترسی منقضی شده' : number_format($trial->days_remaining) . ' روز دسترسی' }}
                            </span>
                        </div>
                    </div>

                    <div class="tai-info">
                        <div class="tai-info-item"><span class="tai-info-label">پایه و رشته</span><span class="tai-info-value">{{ $trial->grade_label }} / {{ $trial->field_label }}</span></div>
                        <div class="tai-info-item"><span class="tai-info-label">شماره پدر</span><span class="tai-info-value" dir="ltr">{{ $trial->father_mobile ?? '—' }}</span></div>
                        <div class="tai-info-item"><span class="tai-info-label">شماره مادر</span><span class="tai-info-value" dir="ltr">{{ $trial->mother_mobile ?? '—' }}</span></div>
                        <div class="tai-info-item"><span class="tai-info-label">تعداد تماس‌ها</span><span class="tai-info-value">{{ number_format($calls->count()) }} تماس ثبت‌شده</span></div>
                    </div>

                    @if($trial->acq_reminder_at)
                        <div class="tai-reminder"><i class="fi fi-rr-alarm-clock"></i><span>{{ $reminderDue ? 'یادآور سررسیدشده:' : 'یادآور:' }}</span><time>{{ jdate($trial->acq_reminder_at)->format('Y/m/d H:i') }}</time></div>
                    @endif

                    <h4 class="tai-stage-title"><i class="fi fi-rr-phone-call"></i> مراحل تماس</h4>
                    <div class="tai-stages">
                        @foreach($stageMeta as $stage => $meta)
                            @php
                                $stageCalls = $byStage->get($stage, collect());
                                $done = $stageCalls->where('answered', true)->isNotEmpty();
                                $attempts = $stageCalls->where('answered', false)->count();
                            @endphp
                            @if($done)
                                <button type="button" class="btn btn-sm btn-success" disabled><i class="fi fi-rr-check"></i>{{ $meta['label'] }}</button>
                            @elseif($meta['due'])
                                <button type="button" wire:click="promptCall({{ $trial->id }}, '{{ $stage }}')" wire:loading.attr="disabled" class="btn btn-sm btn-primary">
                                    <i class="fi fi-rr-phone-call"></i>{{ $meta['label'] }}
                                    @if($attempts > 0)<span class="badge bg-light text-dark">تلاش {{ number_format($attempts) }}</span>@endif
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fi fi-rr-lock"></i>{{ $meta['label'] }}</button>
                            @endif
                        @endforeach
                    </div>

                    <div class="tai-card-footer">
                        @if($hasMonitorAccess)
                            <a href="{{ route($this->monitorRouteName(), $trial->id) }}" class="btn btn-outline-primary btn-sm"><i class="fi fi-rr-chart-histogram"></i> مشاهده رصد دانش‌آموز</a>
                        @else
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled><i class="fi fi-rr-lock"></i> رصد پس از اولین تماس موفق فعال می‌شود</button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="tai-empty"><i class="fi fi-rr-users"></i><strong>{{ $emptyMessage ?? 'دانش‌آموزی به شما تخصیص نیافته است.' }}</strong><span>در صورت تخصیص دانش‌آموز جدید، اطلاعات او در این قسمت نمایش داده می‌شود.</span></div>
            @endforelse
        </div>

        @if($trials->hasPages())
            <div class="tai-pagination">{{ $trials->links('layouts.admin.pagination') }}</div>
        @endif
    </section>

    @if($showPreCallModal)
        <div class="modal d-block tai-modal" tabindex="-1" style="background:rgba(0,0,0,.62)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fi fi-rr-info ms-1"></i> راهنمای تماس روز اول</h5>
                        <button type="button" class="btn-close" wire:click="cancelCallPrompt" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <p class="fw-bold mb-2">لطفاً در این تماس موارد زیر را به اطلاع دانش‌آموز و والدین برسانید:</p>
                        <ul class="tai-guide">
                            <li>خوش‌آمدگویی و معرفی خود به‌عنوان مشاور پشتیبان هفته آزمایشی.</li>
                            <li>شرح روند کلی هفته آزمایشی و هدف آن.</li>
                            <li>توضیح نحوه ارسال گزارش کار روزانه و اهمیت آن.</li>
                            <li>اعلام زمان‌بندی تماس‌های بعدی در روز سوم و هفتم.</li>
                            <li>پاسخ به پرسش‌های اولیه دانش‌آموز و والدین.</li>
                        </ul>
                        <p class="mb-0">پس از مطالعه، برای شروع فرایند تماس دکمه ادامه را بزنید.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelCallPrompt">انصراف</button>
                        <button type="button" class="btn btn-primary" wire:click="continueFromPreCall"><i class="fi fi-rr-phone-call"></i> ادامه و شروع تماس</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showCallConfirmModal)
        <div class="modal d-block tai-call-modal" tabindex="-1" style="background:rgba(0,0,0,.65)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ta-call-modal-content">
                    <div class="modal-header ta-call-modal-header border-0">
                        <h5 class="modal-title text-white">تأیید شروع تماس</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cancelCallPrompt" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body ta-call-modal-body py-5 text-center">
                        <div class="ta-call-icon mb-4"><i class="fi fi-rr-phone-call"></i></div>
                        <h5 class="text-white mb-2">آیا برای شروع تماس آماده هستید؟</h5>
                        <p class="ta-call-muted mb-0">با ادامه، فرایند ثبت تماس برای این دانش‌آموز آغاز می‌شود.</p>
                    </div>
                    <div class="modal-footer ta-call-modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" wire:click="cancelCallPrompt">لغو</button>
                        <button type="button" class="btn btn-success" wire:click="continueCallPrompt"><i class="fi fi-rr-phone-call"></i> شروع تماس</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($activeTrialId && $activeTrial)
        <div class="modal d-block tai-call-modal" tabindex="-1" style="background:rgba(0,0,0,.65)" wire:ignore.self>
            @php $stageLabel = $this->stageLabel($activeStage); @endphp
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content ta-call-modal-content"
                     x-data="{
                        phase: @entangle('callPhase'), secondsLeft: 15, talkSeconds: 0, timer: null,
                        init(){this.$watch('phase',(value)=>this.handlePhaseChange(value));this.handlePhaseChange(this.phase)},
                        stopTimer(){if(this.timer)clearInterval(this.timer);this.timer=null},
                        handlePhaseChange(currentPhase){this.stopTimer();if(currentPhase==='ringing'){this.secondsLeft=15;this.timer=setInterval(()=>{if(this.secondsLeft>0)this.secondsLeft--;else this.stopTimer()},1000)}else if(currentPhase==='talking'){this.talkSeconds=0;this.timer=setInterval(()=>{this.talkSeconds++},1000)}},
                        formatTime(s){return String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0')},
                        endTalkAndSync(){this.stopTimer();$wire.endConversation(this.talkSeconds)}
                     }" x-init="init()">
                    <div class="modal-header ta-call-modal-header">
                        <div>
                            <h5 class="modal-title text-white mb-1">موضوع تماس: {{ $stageLabel }}</h5>
                            <div class="small ta-call-muted" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }} ({{ $this->trialStudentFullName($activeTrial) }})</div>
                        </div>
                    </div>
                    <div class="modal-body ta-call-modal-body">
                        <div x-show="phase === 'ringing'" x-cloak class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div><h5 class="text-white mb-1">در حال تماس…</h5><div class="display-4 fw-bold text-primary" dir="ltr" x-text="secondsLeft"></div>
                        </div>
                        <div x-show="phase === 'talking'" x-cloak class="text-center py-4">
                            <div class="ta-call-icon is-talking mb-3"><i class="fi fi-rr-comment-alt"></i></div><h5 class="text-white mb-1">در حال مکالمه…</h5><div class="display-3 fw-bold text-success" dir="ltr" x-text="formatTime(talkSeconds)"></div>
                        </div>
                        <div x-show="phase !== 'ringing' && phase !== 'talking'" x-cloak>
                            @if($answered)
                                <div class="tai-form-panel">
                                    <div class="mb-3">
                                        <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
                                        <div class="row g-2">
                                            @foreach(['father'=>'پدر','mother'=>'مادر','student'=>'دانش‌آموز','other'=>'سایر'] as $key=>$label)
                                                <div class="col-6 col-md-3"><label class="form-check h-100 mb-0"><input type="checkbox" class="form-check-input" wire:model.live="spokeWith" value="{{ $key }}"><span class="form-check-label">{{ $label }}</span></label></div>
                                            @endforeach
                                        </div>
                                        @error('spokeWith')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        @if(in_array('other',$spokeWith,true))
                                            <div class="mt-3"><input type="text" wire:model="spokeWithOther" class="form-control" placeholder="نام شخص دیگر را بنویسید">@error('spokeWithOther')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                                        @endif
                                    </div>
                                    @if($activeStage === 'day1')<div class="alert alert-info small">این تماس خوش‌آمدگویی روز اول است؛ نتیجه تماس را در فرم زیر ثبت کنید.</div>@endif
                                    <div class="mb-3">
                                        <label class="form-label">یادآور</label>
                                        <div wire:ignore x-data="{}" x-init="$el.querySelector('input').addEventListener('jdp:change',(e)=>{$wire.set('reminderAt',e.target.value)})">
                                            <input type="text" data-jdp class="form-control" placeholder="انتخاب تاریخ و ساعت شمسی" autocomplete="off" readonly data-jdp-gregorian-format="Y-m-d H:i:s">
                                        </div>
                                        @error('reminderAt')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" id="taiHasDisinterest" wire:model.live="hasDisinterest"><label class="form-check-label" for="taiHasDisinterest">عدم تمایل</label></div>
                                    @if($hasDisinterest)
                                        <div class="mb-3"><label class="form-label">نوع عدم تمایل <span class="text-danger">*</span></label><select wire:model.live="disinterestStatus" class="form-select"><option value="">— انتخاب کنید —</option><option value="{{ \App\Models\TrialWeek::ACQ_DISINTEREST_TEMPORARY }}">عدم تمایل موقت</option><option value="{{ \App\Models\TrialWeek::ACQ_DISINTEREST_DEFINITIVE }}">عدم تمایل قطعی</option></select>@error('disinterestStatus')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                                        <div class="mb-3"><label class="form-label">علت عدم تمایل <span class="text-danger">*</span></label><textarea wire:model="disinterestReason" rows="3" class="form-control"></textarea>@error('disinterestReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                                    @else
                                        <div class="mb-3"><label class="form-label">خلاصه گفتگو <span class="text-danger">*</span></label><textarea wire:model="callSummary" rows="3" class="form-control"></textarea>@error('callSummary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                                    @endif
                                </div>
                            @else
                                <div class="tai-form-panel">
                                    <div class="mb-3"><label class="form-label">علت عدم پاسخ <span class="text-danger">*</span></label><select wire:model.live="failReason" class="form-select"><option value="">— انتخاب کنید —</option><option value="no_answer">عدم پاسخ</option><option value="off">خاموش</option><option value="rejected">رد تماس</option><option value="unavailable">عدم دسترس</option><option value="other">سایر</option></select>@error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                                    @if($failReason === 'other')<div class="mb-3"><label class="form-label">توضیح علت <span class="text-danger">*</span></label><input type="text" wire:model="otherFailReason" class="form-control" maxlength="100">@error('otherFailReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>@endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer ta-call-modal-footer">
                        <div x-show="phase === 'ringing'" x-cloak class="tai-modal-actions"><button class="btn btn-success" wire:click="markCallAnswered">پاسخ داد</button><button class="btn btn-outline-danger" wire:click="markNoAnswer">پاسخ نداد</button><button class="btn btn-outline-light" wire:click="closeCallForm">لغو</button></div>
                        <div x-show="phase === 'talking'" x-cloak class="tai-modal-actions"><button type="button" class="btn btn-danger btn-lg w-100" @click="endTalkAndSync()">اتمام مکالمه</button></div>
                        <div x-show="phase !== 'ringing' && phase !== 'talking'" x-cloak class="tai-modal-actions"><button class="btn btn-primary" wire:click="logCall">ثبت نتیجه</button><button class="btn btn-secondary" wire:click="closeCallForm">انصراف</button></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('script')
        <script>
            (function(){
                if(window.__taJdpBooted)return;
                window.__taJdpBooted=true;
                function bootJdp(){if(typeof jalaliDatepicker==='undefined')return;jalaliDatepicker.startWatch({date:true,time:true,hasSecond:false,autoHide:true,hideAfterChangeWithTime:true})}
                document.addEventListener('DOMContentLoaded',bootJdp);
                document.addEventListener('livewire:navigated',bootJdp);
                document.addEventListener('trial-call-form-opened',bootJdp);
            })();
        </script>
    @endpush
</div>
