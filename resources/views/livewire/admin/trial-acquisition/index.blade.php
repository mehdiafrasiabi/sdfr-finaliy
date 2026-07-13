<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">جذب یک هفته آزمایشی</li>
            </ol>
        </nav>
    </div>

    <style>
        @keyframes ta-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
        @keyframes ta-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.55)} 70%{box-shadow:0 0 0 24px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
        .ta-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:ta-pulse 1.5s infinite; }
        .ta-call-icon i{ display:inline-block;animation:ta-ring 1s infinite; }
        [x-cloak]{ display:none !important; }
    </style>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <h4 class="mb-0">دانش‌آموزان جذب آزمایشی من</h4>
                    <p class="small text-muted mb-0">مراحل تماس بر اساس روزهای هفتهٔ آزمایشی: روز اول، روز سوم، روز هفتم.</p>
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
                        $days = (int) \Carbon\Carbon::parse($trial->created_at)->startOfDay()->diffInDays($now->copy()->startOfDay());
                        $stageMeta = [
                            'day1' => ['label' => 'روز اول', 'due' => $days >= 0],
                            'day3' => ['label' => 'روز سوم', 'due' => $days >= 2],
                            'day7' => ['label' => 'روز هفتم', 'due' => $days >= 6],
                        ];
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
                                        @if($trial->acq_confirmed)
                                            <span class="badge bg-success">ثبت‌نام قطعی</span>
                                        @endif
                                        <span class="badge bg-light text-dark border">روز {{ $days + 1 }} از آزمایشی</span>
                                    </div>
                                </div>

                                <div class="text-muted small mb-2">
                                    {{ $trial->grade_label }} / {{ $trial->field_label }}
                                </div>

                                {{-- مراحل تماس --}}
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach($stageMeta as $stage => $meta)
                                        @php
                                            $sCalls = $byStage->get($stage, collect());
                                            $done = $sCalls->where('answered', true)->isNotEmpty();
                                            $attempts = $sCalls->count();
                                        @endphp
                                        @if($done)
                                            <button wire:click="openCallForm({{ $trial->id }}, '{{ $stage }}')" class="btn btn-sm btn-success">
                                                {{ $meta['label'] }} ✓
                                            </button>
                                        @elseif($meta['due'])
                                            <button wire:click="openCallForm({{ $trial->id }}, '{{ $stage }}')" class="btn btn-sm btn-primary">
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
                                    <a href="{{ route('admin.trial-acquisition.monitor', $trial->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fi fi-rr-chart-histogram"></i> رصد
                                    </a>
                                    <button wire:click="openEmergency({{ $trial->id }})" class="btn btn-sm btn-outline-danger flex-fill">
                                        <i class="fi fi-rr-siren-on"></i> تماس اضطراری
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-center text-muted py-4">دانش‌آموزی به شما تخصیص نیافته است.</p></div>
                @endforelse
            </div>

            <div class="mt-3">{{ $trials->links() }}</div>
        </div>
    </div>

    {{-- ───── مودال ثبت تماس (انیمیشن ۱۵ ثانیه‌ای) ───── --}}
    @if ($activeTrialId && $activeTrial)
        @php $stageLabel = \App\Models\TrialAcquisitionCall::STAGE_LABELS[$activeStage] ?? ''; @endphp
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"
                     x-data="{ calling: true, secondsLeft: 15, _t: null }"
                     x-init="_t = setInterval(() => { secondsLeft--; if (secondsLeft <= 0) { clearInterval(_t); calling = false; } }, 1000)">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $stageLabel }} — {{ $activeTrial->user?->name ?? '' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeCallForm" x-show="!calling" x-cloak></button>
                    </div>
                    <div class="modal-body">
                        {{-- فاز در حال تماس --}}
                        <div x-show="calling" class="text-center py-4">
                            <div class="ta-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="mb-1">در حال تماس…</h5>
                            <p class="text-muted mb-3" dir="ltr">{{ $activeTrial->user?->mobile ?? '' }}</p>
                            <div class="display-4 fw-bold text-primary" x-text="secondsLeft"></div>
                            <p class="text-muted small mt-2">پس از پایان شمارش، وضعیت تماس را انتخاب کنید.</p>
                        </div>

                        {{-- بعد از پایان تماس --}}
                        <div x-show="!calling" x-cloak>
                            @if ($answered === null)
                                <div class="text-center py-3">
                                    <p class="mb-3">نتیجهٔ تماس را انتخاب کنید:</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-success" wire:click="setAnswered(true)">پاسخ داد</button>
                                        <button class="btn btn-outline-danger" wire:click="setAnswered(false)">پاسخ نداد</button>
                                    </div>
                                </div>
                            @else
                                @if ($answered)
                                    @if ($activeStage !== 'emergency')
                                        <div class="mb-3">
                                            <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
                                            <select wire:model="spokeWith" class="form-select">
                                                <option value="">— انتخاب کنید —</option>
                                                <option value="student">خود دانش‌آموز</option>
                                                <option value="father">پدر</option>
                                                <option value="mother">مادر</option>
                                                <option value="other">سایر</option>
                                            </select>
                                            @error('spokeWith')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        @if ($activeStage === 'day1')
                                            <div class="mb-3">
                                                <label class="form-label">پیگیر آموزشی <span class="text-danger">*</span></label>
                                                <div class="d-flex gap-3">
                                                    <label class="form-check"><input type="radio" class="form-check-input" wire:model="followUp" value="father"> <span class="form-check-label">پدر</span></label>
                                                    <label class="form-check"><input type="radio" class="form-check-input" wire:model="followUp" value="mother"> <span class="form-check-label">مادر</span></label>
                                                </div>
                                                @error('followUp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        @endif

                                        @if (count($checklistItems) > 0)
                                            <div class="mb-3">
                                                <label class="form-label">کارهای انجام‌شده در این تماس</label>
                                                @foreach ($checklistItems as $key => $label)
                                                    <label class="form-check d-block">
                                                        <input type="checkbox" class="form-check-input" wire:model="checklist.{{ $key }}" value="1">
                                                        <span class="form-check-label">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (in_array($activeStage, ['day3','day7']))
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

                                        @if ($activeStage === 'day7')
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
                                @else
                                    <div class="alert alert-warning py-2">تماس بی‌پاسخ ثبت می‌شود. در صورت نیاز می‌توانید یادآور بگذارید.</div>
                                @endif

                                {{-- یادآور (برای حالت «میخوام فکر کنم / بعداً تماس بگیرید») --}}
                                <div class="mb-3">
                                    <label class="form-check">
                                        <input type="checkbox" class="form-check-input" wire:model.live="wantsReminder" value="1">
                                        <span class="form-check-label">دانش‌آموز می‌خواهد فکر کند / بعداً تماس بگیرید (یادآور)</span>
                                    </label>
                                </div>
                                @if ($wantsReminder)
                                    <div class="mb-3">
                                        <label class="form-label">تاریخ و ساعت یادآور <span class="text-danger">*</span></label>
                                        <div wire:ignore>
                                            <input type="text" id="jdp-reminder" data-jdp class="form-control" placeholder="انتخاب تاریخ و ساعت شمسی" autocomplete="off" readonly>
                                            <input type="hidden" id="reminder_hidden" wire:model="reminderAt">
                                        </div>
                                        @error('reminderAt')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <label class="form-label">توضیحات / خلاصهٔ گفتگو</label>
                                    <textarea wire:model="notes" rows="2" class="form-control"></textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="text-muted small" x-show="calling">لطفاً تا پایان تماس صبر کنید…</span>
                        <button class="btn btn-secondary" wire:click="closeCallForm" x-show="!calling" x-cloak>انصراف</button>
                        @if ($answered !== null)
                            <button class="btn btn-primary" wire:click="logCall" x-show="!calling" x-cloak>ثبت تماس</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('script')
        <script>
            (function () {
                'use strict';
                function jalaliToGregorian(jy, jm, jd) {
                    jy = parseInt(jy); jm = parseInt(jm); jd = parseInt(jd);
                    var jy1 = jy - 979, jm1 = jm - 1, jd1 = jd - 1;
                    var jDayNo = 365 * jy1 + Math.floor(jy1 / 33) * 8 + Math.floor((jy1 % 33 + 3) / 4);
                    var months = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                    for (var i = 0; i < jm1; i++) jDayNo += months[i];
                    jDayNo += jd1;
                    var gDayNo = jDayNo + 79;
                    var gy = 1600 + 400 * Math.floor(gDayNo / 146097);
                    gDayNo = gDayNo % 146097;
                    var leap = true;
                    if (gDayNo >= 36525) { gDayNo--; gy += 100 * Math.floor(gDayNo / 36524); gDayNo = gDayNo % 36524; if (gDayNo >= 365) gDayNo++; else leap = false; }
                    gy += 4 * Math.floor(gDayNo / 1461);
                    gDayNo %= 1461;
                    if (gDayNo >= 366) { leap = false; gDayNo--; gy += Math.floor(gDayNo / 365); gDayNo = gDayNo % 365; }
                    var gMonths = [31, leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    var gm = 0;
                    for (var i = 0; gDayNo >= gMonths[i]; i++) gDayNo -= gMonths[i];
                    gm = i + 1;
                    var gd = gDayNo + 1;
                    return [gy, gm, gd];
                }
                function syncReminder(v) {
                    var hidden = document.getElementById('reminder_hidden');
                    if (!hidden) return;
                    if (!v) { hidden.value = ''; hidden.dispatchEvent(new Event('input', {bubbles:true})); return; }
                    var parts = v.split(' ');
                    var dp = (parts[0] || '').split('/');
                    var timePart = parts[1] || '00:00';
                    if (dp.length !== 3) return;
                    var g = jalaliToGregorian(dp[0], dp[1], dp[2]);
                    hidden.value = g[0] + '-' + String(g[1]).padStart(2,'0') + '-' + String(g[2]).padStart(2,'0') + ' ' + timePart;
                    hidden.dispatchEvent(new Event('input', {bubbles:true}));
                }
                function initReminderPicker() {
                    if (typeof jalaliDatepicker === 'undefined') return;
                    var input = document.getElementById('jdp-reminder');
                    if (!input || input.dataset.jdpBound === '1') return;
                    jalaliDatepicker.startWatch({ time: true, hasSecond: false, hideAfterChange: true, showTodayBtn: true, showEmptyBtn: true, selector: '#jdp-reminder' });
                    input.addEventListener('jdp:change', function () { syncReminder(this.value); });
                    input.dataset.jdpBound = '1';
                }
                document.addEventListener('livewire:navigated', initReminderPicker);
                document.addEventListener('livewire:init', function () {
                    Livewire.on('trial-call-form-opened', function () { setTimeout(initReminderPicker, 80); });
                });
                if (document.readyState !== 'loading') initReminderPicker();
            })();
        </script>
    @endpush
</div>
