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
    <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
        <div class="modal-dialog modal-lg">
            {{-- فلوی تماس: ۲۵ ثانیه انتظار → پاسخ/عدم‌پاسخ → تایمر مکالمه → فرم نتیجه --}}
            <div class="modal-content"
                 wire:key="call-modal-{{ $activeLeadId }}"
                 x-data="{
                    phase: 'calling',          // calling | talking | form
                    formMode: 'success',       // success | fail
                    secondsLeft: 25,
                    talkSeconds: 0,
                    _cd: null,
                    _tt: null,
                    init() {
                        this._cd = setInterval(() => {
                            if (this.secondsLeft > 0) this.secondsLeft--;
                            else clearInterval(this._cd);
                        }, 1000);
                    },
                    answer() {
                        if (this.phase !== 'calling') return;
                        clearInterval(this._cd);
                        this.formMode = 'success';
                        this.phase = 'talking';
                        $wire.markCallAnswered();
                        this._tt = setInterval(() => this.talkSeconds++, 1000);
                    },
                    endCall() {
                        clearInterval(this._tt);
                        $wire.endConversation(this.talkSeconds);
                        this.phase = 'form';
                    },
                    noAnswer() {
                        clearInterval(this._cd);
                        this.formMode = 'fail';
                        this.phase = 'form';
                        $wire.markNoAnswer();
                    },
                    fmt(s) {
                        return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
                    },
                 }">
                <div class="modal-header">
                    <h5 class="modal-title">ثبت تماس — <span dir="ltr">{{ $activeLead->mobile }}</span></h5>
                    {{-- بستن فقط در فاز فرم؛ در حین تماس/مکالمه مودال بسته نمی‌شود --}}
                    <button type="button" class="btn-close" wire:click="closeCallForm" x-show="phase==='form'" x-cloak></button>
                </div>
                <div class="modal-body">
                    {{-- ───── فاز «در حال تماس» (۲۵ ثانیه) ───── --}}
                    <div x-show="phase==='calling'" class="text-center py-4">
                        <div class="pa-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                        <h5 class="mb-1">در حال تماس…</h5>
                        <p class="text-muted mb-3" dir="ltr">{{ $activeLead->full_name ? $activeLead->full_name . ' — ' : '' }}{{ $activeLead->mobile }}</p>
                        <div class="display-4 fw-bold text-primary" x-text="secondsLeft"></div>
                        <p class="text-muted small mt-2">
                            اگر مخاطب پاسخ داد «پاسخ کاربر» را بزنید.
                            <span x-show="secondsLeft>0">دکمهٔ «عدم پاسخ» پس از پایان شمارش فعال می‌شود.</span>
                        </p>
                    </div>

                    {{-- ───── فاز «در حال مکالمه» (تایمر بالارونده) ───── --}}
                    <div x-show="phase==='talking'" x-cloak class="text-center py-4">
                        <div class="pa-call-icon pa-call-icon--talk mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                        <h5 class="mb-1">در حال مکالمه…</h5>
                        <p class="text-muted mb-3" dir="ltr">{{ $activeLead->full_name ? $activeLead->full_name . ' — ' : '' }}{{ $activeLead->mobile }}</p>
                        <div class="display-3 fw-bold text-success" dir="ltr" x-text="fmt(talkSeconds)"></div>
                        <p class="text-muted small mt-2">پس از پایان مکالمه «اتمام مکالمه» را بزنید تا مدت آن ثبت شود.</p>
                    </div>

                    {{-- ───── فاز «فرم نتیجه» ───── --}}
                    <div x-show="phase==='form'" x-cloak>
                        {{-- نمایش مدت مکالمهٔ ثبت‌شده در شاخهٔ پاسخ --}}
                        <div class="alert alert-success py-2 d-flex align-items-center gap-2" x-show="formMode==='success' && talkSeconds>0" x-cloak>
                            <i class="fi fi-rr-stopwatch"></i>
                            <span>مدت مکالمه: <strong dir="ltr" x-text="fmt(talkSeconds)"></strong></span>
                        </div>

                        {{-- ===== فرم پاسخ کاربر (موفق) ===== --}}
                        <div x-show="formMode==='success'">
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

                            <div class="mb-3">
                                <label class="form-label">درصد تمایل به همکاری (۰ تا ۱۰۰) <span class="text-danger">*</span></label>
                                <input type="number" min="0" max="100" inputmode="numeric"
                                       wire:model.live.debounce.500ms="willingness"
                                       x-on:input="
                                            let v = $event.target.value.replace(/[^0-9]/g,'');
                                            if (v !== '' && +v > 100) v = '100';
                                            $event.target.value = v;
                                       "
                                       class="form-control" placeholder="مثلاً ۷۰">
                                @error('willingness')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            @if ($willingness !== '' && $willingness !== null && (int) $willingness < 50)
                                <div class="mb-3">
                                    <label class="form-label text-danger">
                                       علت تمایل زیر 50 درصد<span class="text-danger">*</span>
                                    </label>
                                    <textarea wire:model="lowWillingnessReason" rows="2" class="form-control"></textarea>
                                    @error('lowWillingnessReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">نتیجهٔ تماس <span class="text-danger">*</span></label>
                                <select wire:model.live="result" class="form-select">
                                    <option value="">— انتخاب کنید —</option>
                                    <option value="registered">ثبت‌نام</option>
                                    <option value="follow_up">نیاز به پیگیری مجدد جذب تلفنی</option>
                                    <option value="no_interest">عدم تمایل</option>
                                </select>
                                @error('result')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            {{-- تاریخ و ساعت پیگیری (تقویم شمسی) — همیشه در DOM، فقط هنگام «پیگیری» نمایش --}}
                            <div class="mb-3" @if ($result !== 'follow_up') style="display:none" @endif>
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
                        </div>

                        {{-- ===== فرم عدم پاسخ کاربر (ناموفق) ===== --}}
                        <div x-show="formMode==='fail'" x-cloak>
                            <div class="mb-3">
                                <label class="form-label">علت عدم برقراری تماس <span class="text-danger">*</span></label>
                                <select wire:model="failReason" class="form-select">
                                    <option value="">— انتخاب کنید —</option>
                                    <option value="no_answer">عدم پاسخ</option>
                                    <option value="off">خاموش</option>
                                    <option value="rejected">رد تماس</option>
                                    <option value="wrong">شماره اشتباه (خاکستری)</option>
                                </select>
                                @error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                <p class="text-muted small mt-2">
                                    «عدم پاسخ»، «خاموش» و «رد تماس» شماره را برای تماس مجدد در صف نگه می‌دارند.
                                    «شماره اشتباه» شماره را خاکستری (غیرقابل تماس) می‌کند.
                                </p>
                            </div>
                        </div>
                    </div>{{-- پایان فاز فرم --}}
                </div>
                <div class="modal-footer">
                    {{-- فاز تماس: «پاسخ کاربر» همیشه، «عدم پاسخ» پس از ۲۵ ثانیه --}}
                    <div class="d-flex gap-2 w-100 justify-content-center" x-show="phase==='calling'">
                        <button type="button" class="btn btn-success btn-lg" @click="answer()">
                            <i class="fi fi-rr-phone-call"></i> پاسخ کاربر
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-lg" x-show="secondsLeft<=0" x-cloak @click="noAnswer()">
                            <i class="fi fi-rr-phone-slash"></i> عدم پاسخ
                        </button>
                    </div>

                    {{-- فاز مکالمه: «اتمام مکالمه» --}}
                    <button type="button" class="btn btn-danger btn-lg w-100" x-show="phase==='talking'" x-cloak @click="endCall()">
                        <i class="fi fi-rr-phone-slash"></i> اتمام مکالمه
                    </button>

                    {{-- فاز فرم: انصراف / ثبت --}}
                    <div class="d-flex gap-2 justify-content-end w-100" x-show="phase==='form'" x-cloak>
                        <button class="btn btn-secondary" wire:click="closeCallForm">انصراف</button>
                        <button class="btn btn-primary" wire:click="logCall">ثبت تماس</button>
                    </div>
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
