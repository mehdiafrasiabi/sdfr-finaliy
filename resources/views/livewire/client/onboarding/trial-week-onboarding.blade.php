<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100"
     x-data="onboardingFlow({
        currentStep: @entangle('currentStep').live,
        registered:  @entangle('registered'),
        showTrialConfirm: @entangle('showTrialConfirm'),
        countdown:   @entangle('countdown').live,
     })"
     x-init="init()">

    @push('styles')
        <style>
            [x-cloak] { display: none !important; }
            .step-pane {
                transition: transform .35s ease, opacity .35s ease;
            }
            .step-fade-enter { opacity: 0; transform: translateX(24px); }
            .step-fade-leave { opacity: 0; transform: translateX(-24px); }
        </style>
    @endpush

    @php
        $gradeLabels = ['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم'];
        $fieldLabels = ['math'=>'ریاضی','experimental'=>'تجربی','human'=>'انسانی'];
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-6 md:py-10" x-cloak>

        {{-- ─── progress bar ─── --}}
        <div class="flex items-center justify-between mb-8">
            <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">SDFR</div>
            <div class="flex-1 mx-4 h-1.5 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                <div class="h-full bg-emerald-500 transition-all duration-300"
                     :style="`width: ${Math.round(($wire.currentStep / {{ $totalSteps }}) * 100)}%`"></div>
            </div>
            <div class="text-xs text-slate-500 dark:text-slate-400 font-mono"
                 x-text="`${$wire.currentStep} / {{ $totalSteps }}`"></div>
        </div>

        @if ($generalError)
            <div class="mb-4 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-200 px-4 py-3 border border-rose-200 dark:border-rose-800">
                {{ $generalError }}
            </div>
        @endif

        <form @submit.prevent="goNext()"
              autocomplete="off"
              class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm p-5 md:p-8">

            {{-- ───── Step 1: خوش‌آمدگویی ───── --}}
            <section x-show="$wire.currentStep === 1"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="step-pane transition-all duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-x-6">
                <div class="text-center py-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 text-3xl mb-4">👋</div>
                    <h2 class="text-2xl font-extrabold mb-2">به SDFR خوش آمدید</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        با چند مرحلهٔ ساده، حساب کاربری شما ساخته می‌شود و مسیر آموزشی شخصی‌سازی‌شدهٔ خود را آغاز می‌کنید.
                    </p>
                </div>
            </section>

            {{-- ───── Step 2: معرفی متد ───── --}}
            <section x-show="$wire.currentStep === 2"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="step-pane transition-all duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-x-6">
                <div class="text-center py-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-3xl mb-4">🎯</div>
                    <h2 class="text-2xl font-extrabold mb-2">متد اختصاصی SDFR</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        برنامهٔ مطالعاتی، مشاورهٔ تخصصی و آزمون‌های هدفمند — همه در یک پلتفرم.
                    </p>
                </div>
            </section>

            {{-- ───── Step 3: شروع ───── --}}
            <section x-show="$wire.currentStep === 3"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="step-pane transition-all duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-x-6">
                <div class="text-center py-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-3xl mb-4">🚀</div>
                    <h2 class="text-2xl font-extrabold mb-2">آماده‌اید شروع کنیم؟</h2>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        در ادامه چند اطلاعات از شما می‌پرسیم تا حساب شما را بسازیم.
                    </p>
                </div>
            </section>

            {{-- ───── Step 4: اطلاعات شخصی ───── --}}
            <section x-show="$wire.currentStep === 4"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold mb-4">اطلاعات شخصی</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">نام <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="firstName" type="text" placeholder="مثال: علی"
                               autocomplete="given-name"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('firstName') border-rose-500 @enderror">
                        @error('firstName')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">نام خانوادگی <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="lastName" type="text" placeholder="مثال: محمدی"
                               autocomplete="family-name"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('lastName') border-rose-500 @enderror">
                        @error('lastName')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">کد ملی <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="codeMell" type="text" maxlength="10" placeholder="۱۰ رقم"
                               autocomplete="off" inputmode="numeric"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('codeMell') border-rose-500 @enderror" dir="ltr">
                        @error('codeMell')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            {{-- ───── Step 5: والدین + پایه ───── --}}
            <section x-show="$wire.currentStep === 5"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-data="{ pf: '', pm: '' }">
                <h2 class="text-xl font-bold mb-4">شماره والدین و پایهٔ تحصیلی</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">شمارهٔ پدر <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="fatherMobile" x-model="pf" type="tel" placeholder="09..."
                               autocomplete="off" inputmode="numeric"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('fatherMobile') border-rose-500 @enderror" dir="ltr">
                        @error('fatherMobile')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">شمارهٔ مادر <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="motherMobile" x-model="pm" type="tel" placeholder="09..."
                               autocomplete="off" inputmode="numeric"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('motherMobile') border-rose-500 @enderror" dir="ltr">
                        @error('motherMobile')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                        <div x-show="pf && pm && pf === pm" class="text-xs text-rose-600 dark:text-rose-400 mt-1">
                            شمارهٔ پدر و مادر نباید یکسان باشد.
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">پایه <span class="text-rose-500">*</span></label>
                        <select wire:model.live="grade"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @foreach($gradeLabels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('grade')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    @if($grade !== '9')
                        <div>
                            <label class="block text-sm font-semibold mb-1">رشته <span class="text-rose-500">*</span></label>
                            <select wire:model="field"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @foreach($fieldLabels as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('field')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                        </div>
                    @endif
                </div>
            </section>

            {{-- ───── Step 6: مکان + رمز ───── --}}
            <section x-show="$wire.currentStep === 6"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold mb-4">محل سکونت و رمز عبور</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">استان <span class="text-rose-500">*</span></label>
                        <select wire:model.live="stateId"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('stateId') border-rose-500 @enderror">
                            <option value="0">— انتخاب کنید —</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                        @error('stateId')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">شهر <span class="text-rose-500">*</span></label>
                        <select wire:model="cityId"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('cityId') border-rose-500 @enderror">
                            <option value="0">— انتخاب کنید —</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('cityId')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">شمارهٔ موبایل (برای ورود) <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="mobile" type="tel" placeholder="09..."
                               autocomplete="off" inputmode="numeric"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('mobile') border-rose-500 @enderror" dir="ltr">
                        @error('mobile')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">رمز عبور <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="password" type="password"
                               autocomplete="new-password" data-lpignore="true" data-1p-ignore="true"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('password') border-rose-500 @enderror" dir="ltr">
                        @error('password')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">تکرار رمز <span class="text-rose-500">*</span></label>
                        <input wire:model.blur="passwordConf" type="password"
                               autocomplete="new-password" data-lpignore="true" data-1p-ignore="true"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('passwordConf') border-rose-500 @enderror" dir="ltr">
                        @error('passwordConf')<div class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            {{-- ───── Step 7: OTP ───── --}}
            <section x-show="$wire.currentStep === 7"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-xl font-bold mb-2">تأیید کد پیامک‌شده</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                    کد ۶ رقمی به شمارهٔ <strong dir="ltr">{{ $mobile }}</strong> ارسال شد.
                </p>
                <input wire:model="otpInput" type="text" maxlength="6" placeholder="------"
                       inputmode="numeric"
                       class="w-full text-center tracking-[0.5em] text-2xl font-mono rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500" dir="ltr">

                @if($otpError)
                    <div class="text-rose-600 dark:text-rose-400 text-xs mt-2 text-center">{{ $otpError }}</div>
                @endif

                <div class="flex items-center justify-between mt-5 text-sm">
                    @if($countdown > 0)
                        <span class="text-slate-500 dark:text-slate-400">
                            ارسال مجدد تا
                            <span class="text-emerald-600 dark:text-emerald-400 font-mono mx-1"
                                  x-text="$wire.countdown"></span>
                            ثانیه
                        </span>
                    @else
                        <button type="button" wire:click="resendOtp"
                                class="text-emerald-600 dark:text-emerald-400 hover:underline">
                            ارسال مجدد کد
                        </button>
                    @endif

                    <button type="button" wire:click="verifyOtp"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white text-sm font-semibold">
                        تأیید کد
                    </button>
                </div>
            </section>

            {{-- ───── Step 8: انتخاب نهایی ───── --}}
            <section x-show="$wire.currentStep === 8"
                     x-transition:enter="step-pane transition-all duration-300"
                     x-transition:enter-start="opacity-0 translate-x-6"
                     x-transition:enter-end="opacity-100 translate-x-0">
                <div class="text-center py-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 text-2xl mb-3">✓</div>
                    <h2 class="text-2xl font-extrabold mb-2">حساب شما ساخته شد</h2>
                    <p class="text-slate-600 dark:text-slate-400 mb-6">برای ادامه یکی از گزینه‌های زیر را انتخاب کنید.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="button" wire:click="openTrialConfirm"
                            class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 p-5 text-start hover:shadow-md transition">
                        <div class="text-sm text-emerald-700 dark:text-emerald-300 font-semibold mb-1">رایگان</div>
                        <div class="font-bold text-lg text-slate-900 dark:text-slate-100 mb-1">۱ هفتهٔ آزمایشی</div>
                        <div class="text-xs text-slate-600 dark:text-slate-400">تجربهٔ کامل سامانه به‌مدت یک هفته</div>
                    </button>

                    <button type="button" wire:click="goToPurchase"
                            class="rounded-2xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/30 p-5 text-start hover:shadow-md transition">
                        <div class="text-sm text-indigo-700 dark:text-indigo-300 font-semibold mb-1">کامل</div>
                        <div class="font-bold text-lg text-slate-900 dark:text-slate-100 mb-1">خرید دوره</div>
                        <div class="text-xs text-slate-600 dark:text-slate-400">دسترسی به همهٔ امکانات با قیمت پلکانی</div>
                    </button>
                </div>

                <div class="text-center mt-6">
                    <button type="button" wire:click="declineTrial"
                            class="text-xs text-slate-500 dark:text-slate-400 hover:underline">
                        فعلاً نه — بازگشت به صفحه اصلی
                    </button>
                </div>
            </section>

            {{-- ─── ناوبری پایین (steps 1..6) ─── --}}
            <div class="mt-8 flex items-center justify-between gap-3"
                 x-show="$wire.currentStep >= 1 && $wire.currentStep <= 6">
                <button type="button" @click="goPrev()"
                        x-show="$wire.currentStep > 1"
                        class="px-5 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold">
                    قبلی
                </button>
                <div></div>
                <button type="submit"
                        :disabled="busy"
                        class="px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white text-sm font-bold">
                    <span x-show="!busy">بعدی</span>
                    <span x-show="busy">لطفاً صبر کنید…</span>
                </button>
            </div>
        </form>

        {{-- ─── مودال تأیید آزمایشی ─── --}}
        <div x-show="$wire.showTrialConfirm"
             x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm"
                 @click="if (!busy) $wire.closeTrialConfirm()"></div>

            <div class="relative w-full max-w-sm rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl p-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 text-2xl mb-3">✨</div>
                <h3 class="font-extrabold text-lg mb-2">شروع هفتهٔ آزمایشی</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">
                    با شروع آزمایشی، یک پشتیبان جذب با شما تماس می‌گیرد و فرایند را آغاز می‌کند.
                </p>
                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="confirmTrialAction()"
                            :disabled="busy"
                            class="flex-1 px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white text-sm font-bold">
                        <span x-show="!busy">بله، شروع می‌کنم</span>
                        <span x-show="busy">در حال ارسال…</span>
                    </button>
                    <button type="button"
                            @click="if (!busy) $wire.closeTrialConfirm()"
                            class="px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm">
                        انصراف
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function onboardingFlow(initial) {
                return {
                    busy: false,
                    countdownTimer: null,

                    init() {
                        // تایمر OTP
                        this.startCountdownIfNeeded();

                        Livewire.on('start-countdown', () => this.startCountdownIfNeeded());

                        // پایان loading در صورت برگشت validation error
                        Livewire.on('step-validation-failed', () => { this.busy = false; });
                        Livewire.on('step-changed',         () => { this.busy = false; });
                    },

                    startCountdownIfNeeded() {
                        if (this.countdownTimer) clearInterval(this.countdownTimer);
                        if ($wire.countdown <= 0) return;

                        this.countdownTimer = setInterval(() => {
                            if ($wire.countdown > 0) {
                                $wire.set('countdown', $wire.countdown - 1, false);
                            } else {
                                clearInterval(this.countdownTimer);
                                $wire.countdownFinished();
                            }
                        }, 1000);
                    },

                    goNext() {
                        if (this.busy) return;

                        // در stepهای welcome (1..3) سرور لازم نیست؛ فقط Alpine
                        if ($wire.currentStep >= 1 && $wire.currentStep <= 3) {
                            $wire.set('currentStep', $wire.currentStep + 1);
                            return;
                        }

                        this.busy = true;
                        $wire.next();
                    },

                    goPrev() {
                        if (this.busy) return;
                        if ($wire.currentStep <= 1) return;
                        $wire.previous();
                    },

                    confirmTrialAction() {
                        if (this.busy) return;
                        this.busy = true;
                        $wire.confirmTrial();
                    },
                };
            }
        </script>
    @endpush
</div>
