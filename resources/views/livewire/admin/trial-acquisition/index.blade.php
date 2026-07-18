<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">{{ $pageBreadcrumb ?? 'جذب یک هفته آزمایشی' }}</li>
            </ol>
        </nav>
    </div>

    <style>
        @keyframes ta-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
        @keyframes ta-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.55)} 70%{box-shadow:0 0 0 24px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
        .ta-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:ta-pulse 1.5s infinite; }
        .ta-call-icon i{ display:inline-block;animation:ta-ring 1s infinite; }
        .ta-call-modal-content{ background:#1f1f1f; border:0; color:#fff; overflow:hidden; }
        .ta-call-modal-header,
        .ta-call-modal-footer{ background:#1f1f1f; border-color:rgba(255,255,255,.08); }
        .ta-call-modal-body{ background:#1f1f1f; }
        .ta-call-muted{ color:rgba(255,255,255,.55); }
        [x-cloak]{ display:none !important; }
    </style>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <h4 class="mb-0">{{ $pageTitle ?? 'دانش‌آموزان جذب آزمایشی من' }}</h4>
                    <p class="small text-muted mb-0">{{ $pageSubtitle ?? 'مراحل تماس بر اساس روزهای هفتهٔ آزمایشی: روز اول، روز سوم، روز هفتم.' }}</p>
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filter" class="form-select">
                        <option value="">همه</option>
                        <option value="not_called">تماس‌گرفته‌نشده</option>
                        <option value="reminders">دارای یادآور</option>
                        <option value="confirmed">ثبت‌نام قطعی</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($trials as $trial)
                    @php
                        $calls = $trial->trialAcquisitionCalls;
                        $byStage = $calls->groupBy('stage');
                        $hasMonitorAccess = $calls->contains(fn ($call) => (bool) $call->answered && $call->stage !== \App\Models\TrialAcquisitionCall::STAGE_EXTRA);
                        $hasExamProgram = $trial->student?->examSchedules?->isNotEmpty() ?? false;
                        $stageMeta = $this->stageMetaFor($trial, $now);
                        $reminderDue = $trial->acq_reminder_at && $trial->acq_reminder_at->lte($now);
                        $name = $trial->user?->personalInformation?->name ?? $trial->user?->name ?? '—';
                    @endphp
                    <div class="col-md-6" wire:key="trial-card-{{ $trial->id }}">
                        <div class="card h-100 border {{ $reminderDue ? 'border-danger' : '' }}" style="{{ $reminderDue ? 'border-right-width:5px;' : '' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-0">{{ $name }} {{$trial->user?->personalInformation?->name_full}}</h5>
                                        <span class="text-muted small" dir="ltr">{{ $trial->user?->mobile ?? '—' }}</span>
                                    </div>
                                    <div class="text-start">
                                        @if($hasExamProgram)
                                            <span class="badge bg-warning text-dark">برنامه امتحانی</span>
                                        @endif
                                        @if($trial->acq_confirmed)
                                            <span class="badge bg-success">ثبت‌نام قطعی</span>
                                        @endif
                                        <span class="badge bg-light text-dark border">
                                            {{ $trial->isExpired() ? 'منقضی شده' : $trial->days_remaining . ' روز مانده تا پایان دسترسی' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-muted small mb-2 d-flex flex-wrap gap-2">
                                    <span>{{ $trial->grade_label }} / {{ $trial->field_label }}</span>
                                    <span>پدر: <span dir="ltr">{{ $trial->father_mobile ?? '—' }}</span></span>
                                    <span>مادر: <span dir="ltr">{{ $trial->mother_mobile ?? '—' }}</span></span>
                                </div>

                                {{-- مراحل تماس --}}
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach($stageMeta as $stage => $meta)
                                        @php
                                            $sCalls = $byStage->get($stage, collect());
                                            $done = $sCalls->where('answered', true)->isNotEmpty();
                                            $attempts = $sCalls->where('answered', false)->count();
                                        @endphp
                                        @if($done)
                                            <button class="btn btn-sm btn-success" disabled>
                                                <i class="fi fi-rr-phone-call"></i> {{ $meta['label'] }} ✓
                                            </button>
                                        @elseif($meta['due'])
                                            <button wire:click="promptCall('{{ $trial->id }}', '{{ $stage }}')" class="btn btn-sm btn-primary">
                                                <i class="fi fi-rr-phone-call"></i> {{ $meta['label'] }}
                                                @if($attempts > 0)<span class="badge bg-light text-dark ms-1">تلاش {{ $attempts }}</span>@endif
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>{{ $meta['label'] }} (قفل)</button>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Other info sections --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-center text-muted py-4">{{ $emptyMessage ?? 'دانش‌آموزی به شما تخصیص نیافته است.' }}</p></div>
                @endforelse
            </div>

            <div class="mt-3">{{ $trials->links() }}</div>
        </div>
    </div>

    {{-- Pre-call Modal for Day 1 --}}
    @if ($showPreCallModal)
    <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.6)">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">راهنمای تماس روز اول</h5>
                    <button type="button" class="btn-close" wire:click="cancelCallPrompt"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">لطفاً در این تماس موارد زیر را به اطلاع دانش‌آموز و والدین برسانید:</p>
                    <ul>
                        <li>خوش‌آمدگویی و معرفی خود به عنوان مشاور پشتیبان در هفته آزمایشی.</li>
                        <li>شرح روند کلی هفته آزمایشی و هدف آن.</li>
                        <li>توضیح نحوه ارسال گزارش کار روزانه و اهمیت آن.</li>
                        <li>اعلام زمان‌بندی تماس‌های بعدی (روز سوم و هفتم).</li>
                        <li>پاسخ به سوالات اولیه دانش‌آموز و والدین.</li>
                    </ul>
                    <p>پس از مطالعه، برای شروع فرآیند تماس، دکمه زیر را بزنید.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelCallPrompt">انصراف</button>
                    <button type="button" class="btn btn-primary" wire:click="continueFromPreCall">ادامه و شروع تماس</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Call Confirmation Modal --}}
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
                        <h5 class="text-white mb-2">آیا برای شروع تماس آماده‌اید؟</h5>
                        <p class="ta-call-muted mb-0">با ادامه، فرآیند ثبت تماس برای این دانش‌آموز آغاز می‌شود.</p>
                    </div>
                    <div class="modal-footer ta-call-modal-footer border-0">
                        <button type="button" class="btn btn-light" wire:click="cancelCallPrompt">لغو</button>
                        <button type="button" class="btn btn-success btn-lg" wire:click="continueCallPrompt">
                            <i class="fi fi-rr-phone-call"></i> بله، تماس را شروع کن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Call Modal --}}
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
                        init() {
                            this.$watch('phase', (value) => this.handlePhaseChange(value));
                            this.handlePhaseChange(this.phase);
                        },
                        stopTimer() {
                            if (this.timer) clearInterval(this.timer);
                            this.timer = null;
                        },
                        handlePhaseChange(currentPhase) {
                            this.stopTimer();
                            if (currentPhase === 'ringing') {
                                this.secondsLeft = 15;
                                this.timer = setInterval(() => {
                                    if (this.secondsLeft > 0) this.secondsLeft--;
                                    else this.stopTimer();
                                }, 1000);
                            } else if (currentPhase === 'talking') {
                                this.talkSeconds = 0;
                                this.timer = setInterval(() => { this.talkSeconds++; }, 1000);
                            }
                        },
                        formatTime(s) { return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0'); },
                        endTalkAndSync() {
                            this.stopTimer();
                            $wire.endConversation(this.talkSeconds);
                        }
                     }"
                     x-init="init()"
                >
                    <div class="modal-header ta-call-modal-header text-end">
                        <div>
                            <h5 class="modal-title text-white mb-0">موضوع تماس: {{ $stageLabel }}</h5>
                            <div class="small ta-call-muted" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }} ({{ $activeTrial->user?->name ?? '' }})</div>
                        </div>
                    </div>
                    <div class="modal-body ta-call-modal-body">
                        
                        <div x-show="phase === 'ringing'" x-cloak class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="mb-1 text-white">در حال تماس…</h5>
                            <div class="display-4 fw-bold text-primary" dir="ltr" x-text="secondsLeft"></div>
                        </div>

                        <div x-show="phase === 'talking'" x-cloak class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                            <h5 class="mb-1 text-white">در حال مکالمه…</h5>
                            <div class="display-3 fw-bold text-success" dir="ltr" x-text="formatTime(talkSeconds)"></div>
                        </div>

                        <div x-show="phase !== 'ringing' && phase !== 'talking'" x-cloak>
                            @if($answered)
                                <div class="bg-body text-dark rounded-4 p-3">
                                    <div class="mb-3">
                                        <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
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
                                    </div>

                                    @if ($activeStage === 'day1')
                                        <div class="mb-3">
                                            <label class="form-label">خلاصه تماس خوش آمد گویی <span class="text-danger">*</span></label>
                                            <textarea wire:model="welcomeCallSummary" rows="4" class="form-control"></textarea>
                                            @error('welcomeCallSummary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    @endif
                                    
                                    @if($activeStage !== 'day1')
                                    <div class="mb-2">
                                        <label class="form-label">توضیحات / خلاصهٔ گفتگو (اختیاری)</label>
                                        <textarea wire:model="notes" rows="2" class="form-control"></textarea>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-body text-dark rounded-4 p-3">
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
                            <button class="btn btn-outline-light" wire:click="closeCallForm">لغو</button>
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
</div>
