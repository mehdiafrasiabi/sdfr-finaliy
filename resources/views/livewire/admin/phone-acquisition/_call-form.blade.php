<style>
    @keyframes pa-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
    @keyframes pa-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.55)} 70%{box-shadow:0 0 0 24px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
    @keyframes pa-pulse-g { 0%{box-shadow:0 0 0 0 rgba(25,135,84,.55)} 70%{box-shadow:0 0 0 24px rgba(25,135,84,0)} 100%{box-shadow:0 0 0 0 rgba(25,135,84,0)} }
    .pa-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:pa-pulse 1.5s infinite; }
    .pa-call-icon i{ display:inline-block;animation:pa-ring 1s infinite; }
    .pa-call-icon--talk{ background:#198754;animation:pa-pulse-g 1.5s infinite; }
    .pa-call-icon--talk i{ animation:none; }
    [x-cloak]{ display:none !important; }
    /* تقویم شمسی یک المان سفارشی <jdp-container> است که z-index:1000 را inline می‌گیرد؛
       با سلکتور تگ + !important آن را روی مودال (z-index 1055) می‌آوریم. */
    jdp-container{ z-index:3000 !important; }
</style>
@if ($activeLeadId && $activeLead)
    @php
        $leadLabel = ($activeLead->full_name ? $activeLead->full_name . ' — ' : '') . $activeLead->mobile;
    @endphp
    <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
        <div class="modal-dialog modal-lg">
            {{-- فاز تماس از سمت سرور کنترل می‌شود (قطعی)؛ Alpine فقط تایمرها را می‌چرخاند. --}}
            <div class="modal-content"
                 wire:key="call-modal-{{ $activeLeadId }}-{{ $callPhase }}"
                 x-data="{
                    elapsed: 0, talk: 0, _r: null, _t: null,
                    startRing(){ this.stop(); this.elapsed = 0; this._r = setInterval(() => this.elapsed++, 1000); },
                    startTalk(){ this.stop(); this.talk = 0; this._t = setInterval(() => this.talk++, 1000); },
                    stop(){ if(this._r){clearInterval(this._r);this._r=null;} if(this._t){clearInterval(this._t);this._t=null;} },
                    fmt(s){ return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); },
                 }">
                <div class="modal-header">
                    <h5 class="modal-title">ثبت تماس — <span dir="ltr">{{ $activeLead->mobile }}</span></h5>
                </div>

                <div class="modal-body">
                    @if ($callPhase === 'ringing')
                        {{-- ───── در حال زنگ‌خوردن ───── --}}
                        <div class="text-center py-4" x-init="startRing()">
                            <div class="pa-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="mb-1">در حال تماس…</h5>
                            <p class="text-muted mb-3" dir="ltr">{{ $leadLabel }}</p>
                            <div class="display-4 fw-bold text-primary" dir="ltr" x-text="fmt(elapsed)"></div>
                            <p class="text-muted small mt-2">
                                اگر پاسخ داد «پاسخ کاربر» را بزنید. «عدم پاسخ» از ثانیهٔ ۲۰ به بعد فعال می‌شود.
                            </p>
                        </div>

                    @elseif ($callPhase === 'talking')
                        {{-- ───── در حال مکالمه (تایمر بالارونده) ───── --}}
                        <div class="text-center py-4" x-init="startTalk()">
                            <div class="pa-call-icon pa-call-icon--talk mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                            <h5 class="mb-1">در حال مکالمه…</h5>
                            <p class="text-muted mb-2" dir="ltr">{{ $leadLabel }}</p>
                            <div class="display-3 fw-bold text-success" dir="ltr" x-text="fmt(talk)"></div>
                            <p class="text-muted small mt-2 mb-3">پس از پایان مکالمه «اتمام مکالمه» را بزنید.</p>

                            {{-- ارسال لینک ثبت‌نام حین مکالمه --}}
                            @if ($sentLinkUrl)
                                <div class="alert alert-info py-2 small mb-0">
                                    لینک ثبت‌نام ارسال شد:
                                    <a href="{{ $sentLinkUrl }}" target="_blank" dir="ltr">{{ $sentLinkUrl }}</a>
                                </div>
                            @else
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-info btn-sm" wire:click="sendRegistrationLink('default')">
                                        <i class="fi fi-rr-paper-plane"></i> ارسال لینک عمومی
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="sendRegistrationLink('trial')">
                                        <i class="fi fi-rr-paper-plane"></i> ارسال لینک یک هفته آزمایشی
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" wire:click="sendRegistrationLink('exam')">
                                        <i class="fi fi-rr-paper-plane"></i> ارسال لینک برنامه امتحانی
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="sendTestSms">
                                        <i class="fi fi-rr-flask"></i> ارسال تست به 09940682693
                                    </button>
                                </div>
                            @endif
                        </div>

                    @elseif ($callPhase === 'answerForm')
                        {{-- ───── فرم پاسخ کاربر ───── --}}
                        @if ($talkSeconds)
                            <div class="alert alert-success py-2 d-flex align-items-center gap-2">
                                <i class="fi fi-rr-stopwatch"></i>
                                <span>مدت مکالمه: <strong dir="ltr">{{ sprintf('%02d:%02d', intdiv($talkSeconds, 60), $talkSeconds % 60) }}</strong></span>
                            </div>
                        @endif

                        {{-- ارسال لینک ثبت‌نام --}}
                        <div class="mb-3">
                            @if ($sentLinkUrl)
                                <div class="alert alert-info py-2 small mb-0">
                                    لینک ثبت‌نام ارسال شد:
                                    <a href="{{ $sentLinkUrl }}" target="_blank" dir="ltr">{{ $sentLinkUrl }}</a>
                                </div>
                            @else
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-info btn-sm" wire:click="sendRegistrationLink('default')">
                                        <i class="fi fi-rr-paper-plane"></i> ارسال لینک عمومی
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="sendRegistrationLink('trial')">
                                        <i class="fi fi-rr-paper-plane"></i> ارسال لینک یک هفته آزمایشی
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" wire:click="sendRegistrationLink('exam')">
                                        <i class="fi fi-rr-paper-plane"></i> ارسال لینک برنامه امتحانی
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="sendTestSms">
                                        <i class="fi fi-rr-flask"></i> ارسال تست به 09940682693
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                @foreach (['student' => 'خود دانش‌آموز', 'father' => 'پدر', 'mother' => 'مادر', 'other' => 'سایر'] as $key => $label)
                                    <div class="col-6 col-md-3">
                                        <label class="form-check border rounded-3 p-2 h-100 mb-0">
                                            <input type="checkbox" class="form-check-input" wire:model.live="spokeWith" value="{{ $key }}">
                                            <span class="form-check-label">{{ $label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('spokeWith')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('spokeWith.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @if (in_array('other', $spokeWith, true))
                                <div class="mt-3">
                                    <input type="text" wire:model="spokeWithOther" class="form-control" placeholder="نام شخص دیگر را بنویسید">
                                    @error('spokeWithOther')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">درصد تمایل به همکاری (۰ تا ۱۰۰) <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="100" inputmode="numeric"
                                   wire:model.live.debounce.500ms="willingness"
                                   x-data x-on:input="
                                        let v = $event.target.value.replace(/[^0-9]/g,'');
                                        if (v !== '' && +v > 100) v = '100';
                                        $event.target.value = v;
                                   "
                                   class="form-control" placeholder="مثلاً ۷۰">
                            @error('willingness')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        @if ($willingness !== '' && $willingness !== null && (int) $willingness < 50)
                            <div class="mb-3">
                                <label class="form-label text-danger">علت تمایل زیر ۵۰ درصد <span class="text-danger">*</span></label>
                                <textarea wire:model="lowWillingnessReason" rows="2" class="form-control"></textarea>
                                @error('lowWillingnessReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">نتیجهٔ تماس <span class="text-danger">*</span></label>
                            <select wire:model.live="result" class="form-select">
                                <option value="">— انتخاب کنید —</option>
                                <option value="registration_follow_up">نیاز به پیگیری مجدد ثبت نام</option>
                                <option value="follow_up">نیاز به پیگیری مجدد جذب تلفنی</option>
                                <option value="no_interest">عدم تمایل</option>
                            </select>
                            @error('result')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3" @if (! in_array($result, ['registration_follow_up', 'follow_up'], true)) style="display:none" @endif>
                            <label class="form-label">تاریخ و ساعت پیگیری <span class="text-danger">*</span></label>
                            <div wire:ignore>
                                <input type="text" id="jdp-followup" data-jdp
                                       class="form-control" placeholder="انتخاب تاریخ و ساعت شمسی"
                                       autocomplete="off" readonly>
                                <input type="hidden" id="followup_hidden" wire:model="followUpAt">
                            </div>
                            @error('followUpAt')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">خلاصه و نتیجهٔ گفتگو</label>
                            <textarea wire:model="summary" rows="3" class="form-control"
                                      placeholder="خلاصهٔ گفتگو را اینجا بنویسید…"></textarea>
                            @error('summary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                    @elseif ($callPhase === 'noAnswerForm')
                        {{-- ───── فرم عدم پاسخ ───── --}}
                        <div class="mb-3">
                            <label class="form-label">علت عدم برقراری تماس <span class="text-danger">*</span></label>
                            <select wire:model="failReason" class="form-select">
                                <option value="no_answer">عدم پاسخ</option>
                                <option value="off">خاموش</option>
                                <option value="rejected">رد تماس</option>
                                <option value="wrong">شماره اشتباه (خاکستری)</option>
                            </select>
                            @error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <p class="text-muted small mt-2">
                                «عدم پاسخ»، «خاموش» و «رد تماس» شماره را برای تماس مجدد نگه می‌دارند.
                                «شماره اشتباه» شماره را خاکستری (غیرقابل تماس) می‌کند.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    @if ($callPhase === 'ringing')
                        {{-- لغو فقط قبل از ثانیهٔ ۲۵ و قبل از انتخاب پاسخ/عدم‌پاسخ --}}
                        <button type="button" class="btn btn-light" x-show="elapsed < 25" x-cloak
                                @click="stop(); $wire.cancelCall()">لغو</button>
                        <button type="button" class="btn btn-success btn-lg" wire:click="markCallAnswered">
                            <i class="fi fi-rr-phone-call"></i> پاسخ کاربر
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-lg" x-show="elapsed >= 20" x-cloak
                                @click="stop(); $wire.markNoAnswer()">
                            <i class="fi fi-rr-phone-slash"></i> عدم پاسخ
                        </button>
                    @elseif ($callPhase === 'talking')
                        <button type="button" class="btn btn-danger btn-lg w-100"
                                @click="stop(); $wire.endConversation(Number(talk || 0))">
                            <i class="fi fi-rr-phone-slash"></i> اتمام مکالمه
                        </button>
                    @else
                        {{-- فرم پاسخ/عدم‌پاسخ: حتماً باید ثبت شود (بدون لغو) --}}
                        <button type="button" class="btn btn-primary btn-lg w-100" wire:click="logCall">
                            <i class="fi fi-rr-disk"></i> ثبت تماس
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@if ($showCallConfirmModal)
    <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.55)" wire:click.self="cancelCallPrompt">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">تأیید شروع تماس</h5>
                    <button type="button" class="btn-close" wire:click="cancelCallPrompt"></button>
                </div>
                <div class="modal-body py-5 text-center">
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark px-3 py-2">مطمئنی؟</span>
                    </div>
                    <h5 class="mb-2">می‌خوای تماس با {{ $pendingCallLeadLabel }} شروع بشه؟</h5>
                    <p class="text-muted mb-0">با ادامه، فرم ثبت تماس باز می‌شود.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="cancelCallPrompt">لغو</button>
                    <button type="button" class="btn btn-primary" wire:click="continueCallPrompt">
                        <i class="fi fi-rr-phone-call"></i> ادامه تماس
                    </button>
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
                if (gDayNo >= 36525) {
                    gDayNo--;
                    gy += 100 * Math.floor(gDayNo / 36524);
                    gDayNo = gDayNo % 36524;
                    if (gDayNo >= 365) gDayNo++; else leap = false;
                }
                gy += 4 * Math.floor(gDayNo / 1461);
                gDayNo %= 1461;
                if (gDayNo >= 366) {
                    leap = false; gDayNo--;
                    gy += Math.floor(gDayNo / 365);
                    gDayNo = gDayNo % 365;
                }
                var gMonths = [31, leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                var gm = 0;
                for (var i = 0; gDayNo >= gMonths[i]; i++) gDayNo -= gMonths[i];
                gm = i + 1;
                var gd = gDayNo + 1;
                return [gy, gm, gd];
            }

            function syncFollowupToLivewire(pickerValue) {
                var hidden = document.getElementById('followup_hidden');
                if (!hidden) return;
                if (!pickerValue) {
                    hidden.value = '';
                    hidden.dispatchEvent(new Event('input', {bubbles: true}));
                    return;
                }
                var parts = pickerValue.split(' ');
                var dp = (parts[0] || '').split('/');
                var timePart = parts[1] || '00:00';
                if (dp.length !== 3) return;
                var greg = jalaliToGregorian(dp[0], dp[1], dp[2]);
                var gregorianDate = greg[0] + '-'
                    + String(greg[1]).padStart(2, '0') + '-'
                    + String(greg[2]).padStart(2, '0');
                hidden.value = gregorianDate + ' ' + timePart;
                hidden.dispatchEvent(new Event('input', {bubbles: true}));
            }

            function initFollowupPicker() {
                if (typeof jalaliDatepicker === 'undefined') return;
                var input = document.getElementById('jdp-followup');
                if (!input || input.dataset.jdpBound === '1') return;

                jalaliDatepicker.startWatch({
                    time: true,
                    hasSecond: false,
                    hideAfterChange: true,
                    showTodayBtn: true,
                    showEmptyBtn: true,
                    zIndex: 3000, // بالاتر از مودال (۱۰۵۵) تا تقویم زیر مودال نرود
                    selector: '#jdp-followup',
                });

                input.addEventListener('jdp:change', function () {
                    syncFollowupToLivewire(this.value);
                });
                input.dataset.jdpBound = '1';
            }

            document.addEventListener('livewire:navigated', initFollowupPicker);
            document.addEventListener('livewire:init', function () {
                Livewire.on('phone-call-form-opened', function () {
                    setTimeout(initFollowupPicker, 80);
                });
            });
            if (document.readyState !== 'loading') initFollowupPicker();
        })();
    </script>
@endpush
