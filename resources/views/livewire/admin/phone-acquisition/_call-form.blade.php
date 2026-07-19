<div>
    <style>
        @keyframes pa-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
        @keyframes pa-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.55)} 70%{box-shadow:0 0 0 24px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
        @keyframes pa-pulse-g { 0%{box-shadow:0 0 0 0 rgba(25,135,84,.55)} 70%{box-shadow:0 0 0 24px rgba(25,135,84,0)} 100%{box-shadow:0 0 0 0 rgba(25,135,84,0)} }
        .pa-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:pa-pulse 1.5s infinite; }
        .pa-call-icon i{ display:inline-block;animation:pa-ring 1s infinite; }
        .pa-call-icon--talk{ background:#198754;animation:pa-pulse-g 1.5s infinite; }
        .pa-call-icon--talk i{ animation:none; }
        [x-cloak]{ display:none !important; }
        jdp-container{ z-index:99999 !important; }
    </style>
    @if ($activeLeadId && $activeLead)
        @php
            $leadLabel = ($activeLead->full_name ? $activeLead->full_name . ' — ' : '') . $activeLead->mobile;
        @endphp
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog modal-lg">
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
                            <div class="text-center py-4" x-init="startTalk()">
                                <div class="pa-call-icon pa-call-icon--talk mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                                <h5 class="mb-1">در حال مکالمه…</h5>
                                <p class="text-muted mb-2" dir="ltr">{{ $leadLabel }}</p>
                                <div class="display-3 fw-bold text-success" dir="ltr" x-text="fmt(talk)"></div>
                                <p class="text-muted small mt-2 mb-3">پس از پایان مکالمه «اتمام مکالمه» را بزنید.</p>

                                <div class="mt-4 pt-3 border-top" wire:loading.class="opacity-50" wire:target="sendInvite">
                                    <h6 class="mb-2 fw-bold">ارسال لینک دعوت برای کاربر</h6>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="sendInvite('trial')">
                                            <i class="fi fi-rr-rocket-lunch"></i> هفته آزمایشی
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="sendInvite('exam')">
                                            <i class="fi fi-rr-test"></i> برنامه امتحانی
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="sendInvite('cash')">
                                            <i class="fi fi-rr-money"></i> پرداخت نقدی
                                        </button>
                                    </div>
                                </div>
                            </div>

                        @elseif ($callPhase === 'answerForm')
                            @if ($talkSeconds)
                                <div class="alert alert-success py-2 d-flex align-items-center gap-2">
                                    <i class="fi fi-rr-stopwatch"></i>
                                    <span>مدت مکالمه: <strong dir="ltr">{{ sprintf('%02d:%02d', intdiv($talkSeconds, 60), $talkSeconds % 60) }}</strong></span>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @foreach (['student' => 'خود دانش‌آموز', 'father' => 'پدر', 'mother' => 'مادر', 'other' => 'سایر'] as $key => $label)
                                        <div class="col-6 col-md-3">
                                            <label class="form-check rounded-3 h-100 mb-0">
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
                                <label for="willingness" class="form-label">درصد تمایل به همکاری <span class="text-danger">*</span></label>
                                <input type="range" class="form-range" min="0" max="100" step="5" wire:model.live="willingness" id="willingness">
                                <div class="text-center" x-text="($wire.willingness || 0) + '%'"></div>
                                @error('willingness')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3" x-show="$wire.willingness < 50" x-cloak>
                                <label class="form-label">علت عدم تمایل</label>
                                <textarea wire:model="lowWillingnessReason" rows="2" class="form-control"
                                          placeholder="چون تمایل زیر ۵۰٪ است، علت را بنویسید…"></textarea>
                                @error('lowWillingnessReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">نتیجهٔ تماس <span class="text-danger">*</span></label>
                                <select wire:model.live="result" class="form-select">
                                    <option value="">— انتخاب کنید —</option>
                                    <option value="reg_fu">نیاز به پیگیری مجدد ثبت نام</option>
                                    <option value="fu">نیاز به پیگیری مجدد جذب تلفنی</option>
                                    <option value="no_int">عدم تمایل</option>
                                </select>
                                @error('result')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3" @if (! in_array($result, ['reg_fu', 'fu'], true)) style="display:none" @endif>
                                <label class="form-label">تاریخ و ساعت پیگیری <span class="text-danger">*</span></label>
                                <div wire:ignore
                                     x-data="{}"
                                     x-init="$el.querySelector('input').addEventListener('jdp:change', (e) => { $wire.set('followUpAt', e.target.value) })">
                                    <input type="text"
                                           data-jdp
                                           class="form-control" placeholder="انتخاب تاریخ و ساعت شمسی"
                                           autocomplete="off" readonly
                                           data-jdp-gregorian-theme="blue"
                                           data-jdp-gregorian-format="Y-m-d H:i:s">
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
                            <div class="mb-3">
                                <label class="form-label">علت عدم برقراری تماس <span class="text-danger">*</span></label>
                                <select wire:model="failReason" class="form-select">
                                    <option value="no_answer">عدم پاسخ</option>
                                    <option value="off">خاموش</option>
                                    <option value="rejected">رد تماس</option>
                                    <option value="wrong">شماره اشتباه (خاکستری)</option>
                                </select>
                                @error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        @if ($callPhase === 'ringing')
                            <button type="button" class="btn btn-light" @click="stop(); $wire.cancelCall()">لغو</button>
                            <button type="button" class="btn btn-success btn-lg" wire:click="markCallAnswered">
                                <i class="fi fi-rr-phone-call"></i> پاسخ کاربر
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-lg" x-show="elapsed >= 20" x-cloak @click="stop(); $wire.markNoAnswer()">
                                <i class="fi fi-rr-phone-slash"></i> عدم پاسخ
                            </button>
                        @elseif ($callPhase === 'talking')
                            <button type="button" class="btn btn-danger btn-lg w-100" @click="stop(); $wire.endConversation(Number(talk || 0))">
                                <i class="fi fi-rr-phone-slash"></i> اتمام مکالمه
                            </button>
                        @else
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
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content" wire:key="call-confirm-{{ $pendingCallLeadId }}">
                    <div class="modal-header">
                        <h5 class="modal-title">شروع تماس</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            می‌خواهید با
                            <strong dir="ltr">{{ $pendingCallLeadLabel }}</strong>
                            تماس بگیرید؟
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="cancelCallPrompt">انصراف</button>
                        <button type="button" class="btn btn-primary" wire:click="continueCallPrompt">
                            <i class="fi fi-rr-phone-call"></i> شروع تماس
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif



</div>@push('script')
<script>
    (function () {
        if (window.__paJdpBooted) {
            return;
        }
        window.__paJdpBooted = true;

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

        document.addEventListener('DOMContentLoaded', () => bootJdp());
        document.addEventListener('livewire:navigated', () => bootJdp());
    })();
</script>
@endpush
