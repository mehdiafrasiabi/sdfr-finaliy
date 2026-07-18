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
                        $day1SubjectOptions = \App\Livewire\Admin\TrialAcquisition\Index::callSubjectOptions();
                        $reminderDue = $trial->acq_reminder_at && $trial->acq_reminder_at->lte($now);
                        $name = $trial->user?->personalInformation?->name ?? $trial->user?->name ?? '—';
                    @endphp
                    <div class="col-md-6">
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
                                            $attempts = $sCalls->count();
                                        @endphp
                                        @if($this->requiresCallSubject($stage))
                                            @foreach($day1SubjectOptions as $subject => $subjectLabel)
                                                @php
                                                    $subjectCalls = $sCalls->where('call_subject', $subject);
                                                    $subjectDone = $subjectCalls->where('answered', true)->isNotEmpty();
                                                    $subjectAttempts = $subjectCalls->count();
                                                @endphp
                                                @if($subjectDone)
                                                    <button wire:click="promptCall({{ $trial->id }}, '{{ $stage }}', '{{ $subject }}')" class="btn btn-sm btn-success">
                                                        <i class="fi fi-rr-phone-call"></i> {{ $subjectLabel }} ✓
                                                    </button>
                                                @elseif($meta['due'])
                                                    <button wire:click="promptCall({{ $trial->id }}, '{{ $stage }}', '{{ $subject }}')" class="btn btn-sm btn-primary">
                                                        <i class="fi fi-rr-phone-call"></i> {{ $subjectLabel }}
                                                        @if($subjectAttempts > 0)<span class="badge bg-light text-dark ms-1">تلاش {{ $subjectAttempts }}</span>@endif
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled>{{ $subjectLabel }} (قفل)</button>
                                                @endif
                                            @endforeach
                                        @elseif($done)
                                            <button wire:click="promptCall({{ $trial->id }}, '{{ $stage }}')" class="btn btn-sm btn-success">
                                                <i class="fi fi-rr-phone-call"></i> {{ $meta['label'] }} ✓
                                            </button>
                                        @elseif($meta['due'])
                                            <button wire:click="promptCall({{ $trial->id }}, '{{ $stage }}')" class="btn btn-sm btn-primary">
                                                <i class="fi fi-rr-phone-call"></i> {{ $meta['label'] }}
                                                @if($attempts > 0)<span class="badge bg-light text-dark ms-1">تلاش {{ $attempts }}</span>@endif
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled>{{ $meta['label'] }} (قفل)</button>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- احتمال ثبت‌نام --}}
                                @if($trial->acq_probability !== null)
                                    @php $p = $trial->acq_probability; $pc = $p >= 60 ? 'success' : ($p >= 30 ? 'warning' : 'danger'); @endphp
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>احتمال ثبت‌نام</span>
                                            <span class="fw-bold text-{{ $pc }}">{{ $p }}٪</span>
                                        </div>
                                        <div class="progress" style="height:8px">
                                            <div class="progress-bar bg-{{ $pc }}" style="width: {{ $p }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                {{-- یادآور --}}
                                @if($trial->acq_reminder_at)
                                    <div class="small mb-2 {{ $reminderDue ? 'text-danger fw-bold' : 'text-muted' }}">
                                        <i class="fi fi-rr-bell"></i> یادآور: {{ jalali($trial->acq_reminder_at)->format('%d %B %Y، ساعت %H:%M') }}
                                        @if($reminderDue) (سررسید شد) @endif
                                    </div>
                                @endif

                                <div class="d-flex gap-2">
                                    @if($hasMonitorAccess)
                                        <a href="{{ route('admin.trial-acquisition.monitor', $trial->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fi fi-rr-chart-histogram"></i> رصد
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-fill" disabled>
                                            <i class="fi fi-rr-lock"></i> رصد
                                        </button>
                                    @endif
                                </div>
                                @unless($hasMonitorAccess)
                                    <div class="small text-danger mt-2 fw-semibold">برای رصد باید اول شما تماس را بگیری.</div>
                                @endunless
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

    {{-- ───── مودال ثبت تماس (انیمیشن ۱۵ ثانیه‌ای) ───── --}}
    @if ($activeTrialId && $activeTrial)
        @php $stageLabel = $this->stageLabel($activeStage); @endphp
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.6)">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content ta-call-modal-content"
                     wire:key="call-modal-{{ $activeTrialId }}-{{ $callPhase }}"
                     x-data="{
                        phase: '{{ $callPhase }}',
                        secondsLeft: 15,
                        talkSeconds: 0,
                        _t: null,
                        start(){ if (this.phase === 'ringing') { this._t = setInterval(() => { if (this.secondsLeft > 0) { this.secondsLeft--; } if (this.secondsLeft <= 0) { this.stop(); } }, 1000); } if (this.phase === 'talking') { this.startTalk(); } },
                        startTalk(){ this.stop(); this.talkSeconds = 0; this._t = setInterval(() => this.talkSeconds++, 1000); },
                        stop(){ if (this._t) { clearInterval(this._t); this._t = null; } },
                        answerNow(){ this.stop(); $wire.markCallAnswered(); },
                        noAnswerNow(){ this.stop(); $wire.markNoAnswer(); },
                        endTalk(){ this.stop(); $wire.endConversation(this.talkSeconds); },
                        fmt(s){ return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0'); },
                     }"
                     x-init="start()">
                    <div class="modal-header ta-call-modal-header border-0">
                        <div class="text-end">
                            <h5 class="modal-title text-white mb-0">{{ $stageLabel }} — {{ $activeTrial->user?->name ?? '' }}</h5>
                            <div class="small ta-call-muted" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }}</div>
                        </div>
                    </div>
                    <div class="modal-body ta-call-modal-body">
                        @if($callPhase === 'ringing')
                        <div class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="mb-1 text-white">در حال تماس…</h5>
                            <p class="ta-call-muted mb-3" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }}</p>
                            <div class="display-4 fw-bold text-primary" dir="ltr" x-text="String(secondsLeft).padStart(2, '0')"></div>
                            <p class="ta-call-muted small mt-2">اگر پاسخ داد، پاسخ کاربر را بزنید. عدم پاسخ را هم از همین‌جا ثبت کنید.</p>
                        </div>
                        @elseif($callPhase === 'talking')
                        <div class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                            <h5 class="mb-1 text-white">در حال مکالمه…</h5>
                            <p class="ta-call-muted mb-3" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }}</p>
                            <div class="display-3 fw-bold text-success" dir="ltr" x-text="fmt(talkSeconds)"></div>
                            <p class="ta-call-muted small mt-2">پس از پایان مکالمه، «اتمام مکالمه» را بزنید تا وارد فرم پاسخ شوید.</p>
                        </div>
                        @else
                        <div>
                            @if($answered)
                                <div class="bg-body text-dark rounded-4 p-3">
                                    <div class="alert alert-primary py-2 mb-3">
                                        مدت تماس ثبت شد: <strong dir="ltr">{{ sprintf('%02d:%02d', intdiv((int) ($talkSeconds ?? 0), 60), (int) ($talkSeconds ?? 0) % 60) }}</strong>
                                    </div>
                                    @if ($activeStage !== 'emergency')
                                        <div class="mb-3">
                                            <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
                                            <div class="row g-2">
                                                @foreach (['father' => 'پدر', 'mother' => 'مادر', 'student' => 'دانش‌آموز', 'other' => 'سایر'] as $key => $label)
                                                    <div class="col-6 col-md-3">
                                                        <label class="form-check border rounded-3 p-2 h-100 mb-0">
                                                            <input type="checkbox" class="form-check-input" wire:model.live="spokeWith" value="{{ $key }}">
                                                            <span class="form-check-label">{{ $label }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('spokeWith')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            @error('spokeWithOther')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            @if (in_array('other', $spokeWith, true))
                                                <div class="mt-3">
                                                    <input type="text" wire:model="spokeWithOther" class="form-control" placeholder="نام شخص دیگر را بنویسید">
                                                </div>
                                            @endif
                                        </div>
                                        @if ($this->requiresCallSubject($activeStage))
                                            <div class="mb-3">
                                                <label class="form-label">موضوع تماس</label>
                                                <div class="border rounded-3 p-2 bg-light fw-semibold">
                                                    {{ \App\Livewire\Admin\TrialAcquisition\Index::callSubjectOptions()[$callSubject] ?? '—' }}
                                                </div>
                                                @error('callSubject')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        @endif
                                        @if ($this->requiresProbability($activeStage))
                                            <div class="mb-3">
                                                <label class="form-label">درصد احتمال ثبت‌نام (۰ تا ۱۰۰) <span class="text-danger">*</span></label>
                                                <input type="number" min="0" max="100" wire:model="probability" class="form-control" placeholder="مثلاً ۷۰">
                                                @error('probability')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">توضیحات احتمال ثبت‌نام <span class="text-danger">*</span></label>
                                                <textarea wire:model="probabilityNote" rows="2" class="form-control"></textarea>
                                                @error('probabilityNote')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        @endif
                                        @if ($this->requiresDefinitiveConfirmation($activeStage))
                                            <div class="mb-3 p-2 rounded border border-success-subtle bg-success-subtle">
                                                <label class="form-check">
                                                    <input type="checkbox" class="form-check-input" wire:model="isDefinitive" value="1">
                                                    <span class="form-check-label">با همین درصد و توضیحات، مطمئنم که ثبت‌نام می‌کند (تأیید قطعی).</span>
                                                </label>
                                                @error('isDefinitive')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        @endif
                                    @else
                                        <div class="mb-3">
                                            <label class="form-label">علت تماس اضطراری <span class="text-danger">*</span></label>
                                            <textarea wire:model="emergencyReason" rows="3" class="form-control" placeholder="مثلاً عدم پیشروی طبق برنامه، انجام‌ندادن ساعت مطالعه، عدم ارسال گزارش…"></textarea>
                                            @error('emergencyReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    @endif
                                    <div class="mb-2">
                                        <label class="form-label">توضیحات / خلاصهٔ گفتگو</label>
                                        <textarea wire:model="notes" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                            @else
                                <div class="bg-body text-dark rounded-4 p-3">
                                    <div class="mb-3">
                                        <label class="form-label">علت عدم پاسخ <span class="text-danger">*</span></label>
                                        <select wire:model="failReason" class="form-select">
                                            <option value="">— انتخاب کنید —</option>
                                            <option value="no_answer">عدم پاسخ</option>
                                            <option value="off">خاموش</option>
                                            <option value="rejected">رد تماس</option>
                                        </select>
                                        @error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer ta-call-modal-footer">
                        @if($callPhase === 'ringing')
                        <span class="ta-call-muted small">لطفاً تا پایان تماس صبر کنید…</span>
                        <button class="btn btn-success" @click="answerNow()">
                            <i class="fi fi-rr-phone-call"></i> پاسخ کاربر
                        </button>
                        <button class="btn btn-outline-danger" @click="noAnswerNow()">
                            <i class="fi fi-rr-phone-slash"></i> عدم پاسخ
                        </button>
                        <button class="btn btn-outline-light" wire:click="closeCallForm">لغو</button>
                        @elseif($callPhase === 'talking')
                        <button type="button" class="btn btn-danger btn-lg w-100" @click="endTalk()">
                            <i class="fi fi-rr-phone-slash"></i> اتمام مکالمه
                        </button>
                        @else
                        @if ($answered === true)
                            <button class="btn btn-primary" wire:click="logCall">ثبت تماس</button>
                            <button class="btn btn-secondary" wire:click="closeCallForm">انصراف</button>
                        @elseif($answered === false)
                            <button class="btn btn-primary" wire:click="logCall">ثبت عدم پاسخ</button>
                            <button class="btn btn-secondary" wire:click="closeCallForm">انصراف</button>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showCallConfirmModal)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.6)">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content ta-call-modal-content">
                    <div class="modal-header ta-call-modal-header border-0">
                        <h5 class="modal-title text-white">تأیید شروع تماس</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cancelCallPrompt"></button>
                    </div>
                    <div class="modal-body ta-call-modal-body py-5">
                        <div class="text-center">
                            <div class="ta-call-icon mb-4"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="text-white mb-2">مطمئنی می‌خوای الان تماس بگیری؟</h5>
                            @if($this->requiresCallSubject($pendingCallStage) && $pendingCallSubject)
                                <div class="bg-body text-dark rounded-4 p-3 mt-3 text-end">
                                    <div class="fw-bold mb-2">
                                        موضوع تماس: {{ \App\Livewire\Admin\TrialAcquisition\Index::callSubjectOptions()[$pendingCallSubject] ?? '—' }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $callSubjectInstructions[$pendingCallSubject] ?? '' }}
                                    </div>
                                </div>
                            @else
                                <p class="ta-call-muted mb-0">با ادامه، فرم ثبت تماس همین دانش‌آموز باز می‌شود.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer ta-call-modal-footer border-0 justify-content-start gap-2">
                        <button type="button" class="btn btn-light" wire:click="cancelCallPrompt">لغو</button>
                        <button type="button" class="btn btn-success btn-lg" wire:click="continueCallPrompt">
                            <i class="fi fi-rr-phone-call"></i> ادامه تماس
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
