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
            <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                    <h6 class="mb-2 fw-bold">ارسال لینک ثبت‌نام برای کاربر</h6>
                                    <div class="d-flex justify-content-center flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                wire:click="sendInvite('trial')" wire:loading.attr="disabled" wire:target="sendInvite"
                                                @disabled($inviteSendLimit !== null && $inviteSendCount >= $inviteSendLimit)>
                                            <i class="fi fi-rr-rocket-lunch"></i> هفته آزمایشی
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                wire:click="sendInvite('exam')" wire:loading.attr="disabled" wire:target="sendInvite"
                                                @disabled($inviteSendLimit !== null && $inviteSendCount >= $inviteSendLimit)>
                                            <i class="fi fi-rr-test"></i> برنامه امتحانی
                                        </button>
                                    </div>
                                    @if($inviteSendLimit !== null)
                                        <div class="small text-muted mt-2">
                                            ارسال انجام‌شده: {{ number_format($inviteSendCount) }} از {{ number_format($inviteSendLimit) }}
                                            @if($inviteSendCount >= $inviteSendLimit)
                                                <span class="text-danger d-block mt-1">سقف ارسال لینک در این تماس تکمیل شده است.</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                        @elseif ($callPhase === 'answerForm')
                            @php
                                $callResultOptions = $this->phoneCallResultOptions();
                                $needsReminder = $result === \App\Models\PhoneCall::RESULT_FOLLOW_UP
                                    || ($result === \App\Models\PhoneCall::RESULT_NO_INTEREST
                                        && $disinterestStatus === \App\Models\PhoneCall::DISINTEREST_TEMPORARY);
                            @endphp
                            @if ($talkSeconds)
                                <div class="alert alert-success py-2 d-flex align-items-center gap-2">
                                    <i class="fi fi-rr-stopwatch"></i>
                                    <span>مدت مکالمه: <strong dir="ltr">{{ sprintf('%02d:%02d', intdiv($talkSeconds, 60), $talkSeconds % 60) }}</strong></span>
                                </div>
                            @endif

                            @if($collectLeadFullName)
                                <div class="alert alert-warning mb-3">
                                    <div class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fi fi-rr-user-add mt-1"></i>
                                        <div>
                                            <strong class="d-block">تکمیل مشخصات مخاطب الزامی است</strong>
                                            <span class="small">این شماره نام کامل ندارد؛ برای ثبت اولین تماس پاسخ‌داده‌شده، نام و نام خانوادگی را وارد کنید.</span>
                                        </div>
                                    </div>
                                    <label class="form-label" for="pa-lead-full-name">نام و نام خانوادگی <span class="text-danger">*</span></label>
                                    <input id="pa-lead-full-name" type="text" wire:model="leadFullName"
                                           class="form-control @error('leadFullName') is-invalid @enderror"
                                           maxlength="150" autocomplete="off"
                                           placeholder="مثال: علی احمدی">
                                    @error('leadFullName')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">با چه شخصی صحبت شد؟ <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @foreach (['student' => 'خود دانش‌آموز', 'father' => 'پدر', 'mother' => 'مادر', 'other' => 'سایر'] as $key => $label)
                                        <div class="col-6 col-md-3">
                                            <label class="form-check border rounded-3 p-3 h-100 mb-0">
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
                                <label class="form-label">نتیجهٔ تماس <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @foreach ($callResultOptions as $value => $option)
                                        <div class="col-md-{{ count($callResultOptions) === 2 ? '6' : '4' }}">
                                            <label class="form-check border rounded-3 p-3 h-100 mb-0 {{ $result === $value ? 'border-primary bg-primary-subtle' : '' }}">
                                                <input type="radio" class="form-check-input" wire:model.live="result" value="{{ $value }}">
                                                <span class="form-check-label d-block">
                                                    <span class="fw-bold d-block">{{ $option['title'] }}</span>
                                                    <span class="small text-muted">{{ $option['desc'] }}</span>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('result')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            @if($result === \App\Models\PhoneCall::RESULT_NO_INTEREST)
                                <div class="mb-3">
                                    <label class="form-label">نوع عدم تمایل <span class="text-danger">*</span></label>
                                    <select wire:model.live="disinterestStatus" class="form-select">
                                        <option value="">— انتخاب کنید —</option>
                                        <option value="{{ \App\Models\PhoneCall::DISINTEREST_TEMPORARY }}">عدم تمایل موقت</option>
                                        <option value="{{ \App\Models\PhoneCall::DISINTEREST_DEFINITIVE }}">عدم تمایل قطعی</option>
                                    </select>
                                    @error('disinterestStatus')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="mb-3" @if (! $needsReminder) style="display:none" @endif>
                                <label class="form-label">یادآور <span class="text-danger">*</span></label>
                                <div wire:ignore
                                     x-data="{}"
                                     x-init="$el.querySelector('input').addEventListener('jdp:change', (e) => { $wire.set('followUpAt', e.target.value) })">
                                    <input type="text"
                                           data-jdp
                                           data-jdp-min-date="today"
                                           class="form-control" placeholder="انتخاب تاریخ و ساعت شمسی"
                                           autocomplete="off" readonly
                                           data-jdp-gregorian-theme="blue"
                                           data-jdp-gregorian-format="Y-m-d H:i:s">
                                </div>
                                @error('followUpAt')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            @if($result === \App\Models\PhoneCall::RESULT_NO_INTEREST)
                                <div class="mb-3">
                                    <label class="form-label">علت عدم تمایل <span class="text-danger">*</span></label>
                                    <textarea wire:model="disinterestReason" rows="3" class="form-control"
                                              placeholder="علت عدم تمایل را دقیق بنویسید…"></textarea>
                                    @error('disinterestReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @elseif($result)
                                <div class="mb-3">
                                    <label class="form-label">خلاصه گفتگو <span class="text-danger">*</span></label>
                                    <textarea wire:model="summary" rows="3" class="form-control"
                                              placeholder="خلاصهٔ گفتگو را اینجا بنویسید…"></textarea>
                                    @error('summary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @endif

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
            <div class="modal-dialog modal-dialog-centered">
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
                minDate: 'attr',
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
