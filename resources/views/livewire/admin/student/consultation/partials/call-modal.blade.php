<style>
    @keyframes cm-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
    @keyframes cm-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.55)} 70%{box-shadow:0 0 0 24px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
    @keyframes cm-pulse-g { 0%{box-shadow:0 0 0 0 rgba(25,135,84,.55)} 70%{box-shadow:0 0 0 24px rgba(25,135,84,0)} 100%{box-shadow:0 0 0 0 rgba(25,135,84,0)} }
    .cm-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:cm-pulse 1.5s infinite; }
    .cm-call-icon i{ display:inline-block;animation:cm-ring 1s infinite; }
    .cm-call-icon--talk{ background:#198754;animation:cm-pulse-g 1.5s infinite; }
    .cm-call-icon--talk i{ animation:none; }
    [x-cloak]{ display:none !important; }
</style>

@if ($activeStudentId && $activeStudent)
    @php
        $studentName = $activeStudent->user?->personalInformation?->name
            ?? $activeStudent->user?->name ?? 'دانش‌آموز';
    @endphp
    <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                 wire:key="cm-modal-{{ $activeStudentId }}"
                 x-data="{
                    elapsed: 0, talk: 0, _r: null, _t: null,
                    startRing(){ this.stop(); this.elapsed = 0; this._r = setInterval(() => {
                        this.elapsed++;
                        if (this.elapsed >= 25) { this.stop(); $wire.autoNoAnswer(); }
                    }, 1000); },
                    startTalk(){ this.stop(); this.talk = 0; this._t = setInterval(() => this.talk++, 1000); },
                    stop(){ if(this._r){clearInterval(this._r);this._r=null;} if(this._t){clearInterval(this._t);this._t=null;} },
                    fmt(s){ return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); },
                 }">
                <div class="modal-header">
                    <h5 class="modal-title">تماس با {{ $studentName }}</h5>
                    @if ($callPhase === 'select')
                        <button type="button" class="btn-close" wire:click="closeCall"></button>
                    @endif
                </div>

                <div class="modal-body">
                    @if ($callPhase === 'select')
                        {{-- ───── انتخابِ پاسخگو پیش از تماس ───── --}}
                        <div class="mb-3">
                            <label class="form-label">با چه کسانی صحبت شد؟ <span class="text-danger">*</span></label>
                            <select wire:model="respondents" class="form-select" multiple size="3">
                                <option value="father">پدر</option>
                                <option value="mother">مادر</option>
                                <option value="student">دانش‌آموز</option>
                            </select>
                            <div class="form-text">هر مورد انتخاب‌شده یعنی در همین تماس با آن شخص صحبت شده است.</div>
                        </div>

                    @elseif ($callPhase === 'ringing')
                        <div class="text-center py-4" x-init="startRing()">
                            <div class="cm-call-icon mb-3"><i class="ri-phone-line"></i></div>
                            <h5 class="mb-1">در حال تماس…</h5>
                            <p class="text-muted mb-3">{{ $studentName }}</p>
                            <div class="display-4 fw-bold text-primary" dir="ltr" x-text="fmt(elapsed)"></div>
                            <p class="text-muted small mt-2">در صورتِ پاسخ، «پاسخ داده شد» را بزنید. اگر تا ۲۵ ثانیه پاسخ داده نشود، «عدم پاسخ» خودکار ثبت می‌شود.</p>
                        </div>

                    @elseif ($callPhase === 'talking')
                        <div class="text-center py-4" x-init="startTalk()">
                            <div class="cm-call-icon cm-call-icon--talk mb-3"><i class="ri-chat-1-line"></i></div>
                            <h5 class="mb-1">در حال مکالمه…</h5>
                            <p class="text-muted mb-2">{{ $studentName }}</p>
                            <div class="display-3 fw-bold text-success" dir="ltr" x-text="fmt(talk)"></div>
                            <p class="text-muted small mt-2 mb-0">پس از پایان مکالمه «اتمام مکالمه» را بزنید.</p>
                        </div>

                    @elseif ($callPhase === 'answerForm')
                        @if ($talkSeconds)
                            <div class="alert alert-success py-2 d-flex align-items-center gap-2">
                                <i class="ri-timer-line"></i>
                                <span>مدت مکالمه: <strong dir="ltr">{{ sprintf('%02d:%02d', intdiv($talkSeconds, 60), $talkSeconds % 60) }}</strong></span>
                            </div>
                        @endif
                        <div class="mb-2">
                            <span class="small text-muted">پاسخگو:</span>
                            @foreach($respondents as $selectedRespondent)
                                <span class="badge bg-light text-dark border">{{ \App\Models\ContactDocumentation::RESPONDENT_MULTI[$selectedRespondent] ?? $selectedRespondent }}</span>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحاتِ تماس (اختیاری)</label>
                            <textarea wire:model="callSummary" rows="3" class="form-control" placeholder="خلاصه‌ی گفتگو…"></textarea>
                        </div>

                    @elseif ($callPhase === 'noAnswerForm')
                        <div class="mb-3">
                            <label class="form-label">علتِ عدم برقراری تماس <span class="text-danger">*</span></label>
                            <select wire:model="failReason" class="form-select">
                                <option value="no_answer">عدم پاسخ</option>
                                <option value="off">خاموش</option>
                                <option value="rejected">رد تماس</option>
                            </select>
                            @error('failReason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    @if ($callPhase === 'select')
                        <button type="button" class="btn btn-light" wire:click="closeCall">بستن</button>
                        <button type="button" class="btn btn-primary" wire:click="startRinging">
                            <i class="ri-phone-line"></i> شروع تماس
                        </button>
                    @elseif ($callPhase === 'ringing')
                        <button type="button" class="btn btn-light" x-show="elapsed < 25" x-cloak
                                @click="stop(); $wire.closeCall()">لغو</button>
                        <button type="button" class="btn btn-success btn-lg" @click="stop(); $wire.markAnswered()">
                            <i class="ri-phone-line"></i> پاسخ داده شد
                        </button>
                        <button type="button" class="btn btn-outline-danger" @click="stop(); $wire.markNoAnswer()">
                            <i class="ri-phone-off-line"></i> عدم پاسخ
                        </button>
                    @elseif ($callPhase === 'talking')
                        <button type="button" class="btn btn-danger btn-lg w-100" @click="stop(); $wire.endConversation(talk)">
                            <i class="ri-phone-off-line"></i> اتمام مکالمه
                        </button>
                    @elseif ($callPhase === 'answerForm')
                        <button type="button" class="btn btn-primary btn-lg w-100" wire:click="saveAnsweredCall">
                            <i class="ri-save-line"></i> ثبتِ تماس
                        </button>
                    @elseif ($callPhase === 'noAnswerForm')
                        <button type="button" class="btn btn-light" wire:click="closeCall">انصراف</button>
                        <button type="button" class="btn btn-primary" wire:click="saveNoAnswer">
                            <i class="ri-save-line"></i> ثبتِ عدم پاسخ
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
