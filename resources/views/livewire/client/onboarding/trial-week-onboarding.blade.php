<div dir="rtl" class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 py-8 px-4"
     x-data="{ isMobile: window.innerWidth < 768 }"
     x-init="window.addEventListener('resize', () => isMobile = window.innerWidth < 768)">

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden">

            {{-- Progress / Stepper --}}
            <div class="bg-gradient-to-l from-blue-500 to-indigo-600 px-6 py-5">
                <div class="flex items-center justify-between gap-2">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex items-center flex-1">
                            <button type="button" wire:click="jumpTo({{ $i }})"
                                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all
                                    {{ $currentStep === $i ? 'bg-white text-indigo-600 scale-110' : ($currentStep > $i ? 'bg-green-400 text-white' : 'bg-white/20 text-white') }}">
                                @if ($currentStep > $i) ✓ @else {{ $i }} @endif
                            </button>
                            @if ($i < $totalSteps)
                                <div class="flex-1 h-1 mx-1 {{ $currentStep > $i ? 'bg-green-400' : 'bg-white/20' }} rounded"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                <p class="text-white/90 text-sm mt-3 font-medium">
                    @switch($currentStep)
                        @case(1) خوش آمدید @break
                        @case(2) ورود با شماره موبایل @break
                        @case(3) اطلاعات شخصی @break
                        @case(4) شروع هفته آزمایشی @break
                    @endswitch
                </p>
            </div>

            <div class="p-6 md:p-10">

                {{-- DESKTOP: همه مراحل پشت سر هم. MOBILE: فقط مرحله فعلی --}}

                {{-- Step 1: Welcome --}}
                <section x-show="!isMobile || {{ $currentStep === 1 ? 'true' : 'false' }}"
                         class="{{ $currentStep === 1 ? '' : 'md:opacity-100 md:pointer-events-auto' }} mb-12">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 dark:text-white mb-4">
                        به <span class="text-indigo-600">SDFR</span> خوش آمدی! 🎉
                    </h1>
                    <p class="text-slate-600 dark:text-slate-300 leading-loose">
                        تنها مسیر ثبت‌نام در سامانه از طریق <strong>یک هفته آزمایشی رایگان</strong> است.
                        یک پشتیبان جذب اختصاصی به تو معرفی می‌شود، برنامه مطالعاتی شخصی برایت ساخته می‌شود
                        و در پایان هفته تصمیم می‌گیری که ادامه دهی یا نه.
                    </p>
                    <ul class="mt-6 space-y-3 text-slate-700 dark:text-slate-200">
                        <li class="flex items-center gap-2">✅ پشتیبان اختصاصی</li>
                        <li class="flex items-center gap-2">✅ برنامه مطالعاتی هوشمند</li>
                        <li class="flex items-center gap-2">✅ گزارش‌گیری روزانه</li>
                        <li class="flex items-center gap-2">✅ آزمون‌های تخصصی</li>
                    </ul>
                </section>

                {{-- Step 2: Mobile + OTP --}}
                <section x-show="!isMobile || {{ $currentStep === 2 ? 'true' : 'false' }}" class="mb-12">
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">ورود/ثبت‌نام با موبایل</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">شماره موبایل</label>
                            <input type="tel" wire:model.live="mobile"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none"
                                   placeholder="09xxxxxxxxx" {{ $otpSent ? 'disabled' : '' }}>
                            @error('mobile') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if ($otpSent)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">کد تایید ۶ رقمی</label>
                                <input type="text" inputmode="numeric" maxlength="6" wire:model.live="userInputCode"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-center text-xl tracking-widest focus:ring-2 focus:ring-indigo-500 outline-none"
                                       placeholder="------">
                                @if ($codeErrorMessage) <p class="text-red-500 text-sm mt-1">{{ $codeErrorMessage }}</p> @endif
                                <button type="button" wire:click="resendOtp" class="text-sm text-indigo-600 mt-2 hover:underline">ارسال مجدد کد</button>
                            </div>
                        @endif

                        @if ($sendSmsError)
                            <p class="text-red-500 text-sm">{{ $sendSmsError }}</p>
                        @endif
                    </div>
                </section>

                {{-- Step 3: Registration Info --}}
                <section x-show="!isMobile || {{ $currentStep === 3 ? 'true' : 'false' }}" class="mb-12">
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">اطلاعات شخصی</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">نام</label>
                            <input type="text" wire:model="firstName" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('firstName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">نام خانوادگی</label>
                            <input type="text" wire:model="lastName" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('lastName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">کد ملی</label>
                            <input type="text" inputmode="numeric" maxlength="10" wire:model.live="nationalCode" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('nationalCode') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">شماره موبایل پدر</label>
                            <input type="tel" wire:model.live="fatherMobile" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="09xxxxxxxxx">
                            @error('fatherMobile') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">شماره موبایل مادر</label>
                            <input type="tel" wire:model.live="motherMobile" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="09xxxxxxxxx">
                            @error('motherMobile') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">پایه</label>
                            <select wire:model.live="grade" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">انتخاب کنید</option>
                                <option value="9">نهم</option>
                                <option value="10">دهم</option>
                                <option value="11">یازدهم</option>
                                <option value="12">دوازدهم</option>
                            </select>
                            @error('grade') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">رشته</label>
                            <select wire:model="field" {{ $grade == 9 ? 'disabled' : '' }} class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                <option value="">انتخاب کنید</option>
                                <option value="math">ریاضی</option>
                                <option value="experimental">تجربی</option>
                                <option value="human">انسانی</option>
                            </select>
                            @error('field') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">استان</label>
                            <select wire:model.live="stateId" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">انتخاب کنید</option>
                                @foreach ($states as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('stateId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">شهر</label>
                            <select wire:model="cityId" class="w-full px-4 py-3 rounded-xl border dark:bg-slate-800 dark:border-slate-700 outline-none focus:ring-2 focus:ring-indigo-500" {{ !$stateId ? 'disabled' : '' }}>
                                <option value="">انتخاب کنید</option>
                                @foreach ($cities as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('cityId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                {{-- Step 4: Trial decision --}}
                <section x-show="!isMobile || {{ $currentStep === 4 ? 'true' : 'false' }}" class="mb-12">
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">یک هفته آزمایشی</h2>
                    <p class="text-slate-600 dark:text-slate-300 mb-6 leading-loose">
                        می‌خواهی در دوره <strong>یک هفته آزمایشی رایگان</strong> شرکت کنی؟
                        در این هفته یک پشتیبان به تو اختصاص داده می‌شود و می‌توانی بدون پرداخت با سیستم آشنا شوی.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-start gap-3 p-5 rounded-2xl border-2 cursor-pointer transition-all
                                {{ $trialDecision === 'accepted' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-slate-200 dark:border-slate-700' }}">
                            <input type="radio" wire:model.live="trialDecision" value="accepted" class="mt-1">
                            <div>
                                <div class="font-bold text-slate-800 dark:text-white">بله، شرکت می‌کنم</div>
                                <div class="text-sm text-slate-500 mt-1">شروع هفته آزمایشی رایگان</div>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 p-5 rounded-2xl border-2 cursor-pointer transition-all
                                {{ $trialDecision === 'declined' ? 'border-slate-500 bg-slate-100 dark:bg-slate-800' : 'border-slate-200 dark:border-slate-700' }}">
                            <input type="radio" wire:model.live="trialDecision" value="declined" class="mt-1">
                            <div>
                                <div class="font-bold text-slate-800 dark:text-white">خیر، مستقیم خرید می‌کنم</div>
                                <div class="text-sm text-slate-500 mt-1">به صفحه پرداخت بروم</div>
                            </div>
                        </label>
                    </div>
                    @error('trialDecision') <p class="text-red-500 text-sm mt-3">{{ $message }}</p> @enderror
                </section>

                {{-- Navigation --}}
                <div class="flex items-center justify-between gap-3 mt-8 md:mt-0">
                    <button type="button" wire:click="prev"
                            x-show="isMobile && {{ $currentStep > 1 ? 'true' : 'false' }}"
                            class="px-5 py-3 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-white hover:bg-slate-300 transition-all">
                        قبلی
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" wire:click="next" wire:loading.attr="disabled"
                            class="px-7 py-3 rounded-xl bg-gradient-to-l from-indigo-600 to-blue-600 text-white font-bold hover:shadow-lg disabled:opacity-50 transition-all">
                        <span wire:loading.remove wire:target="next">
                            @if ($currentStep < $totalSteps)
                                {{ $currentStep === 2 && !$otpSent ? 'ارسال کد تایید' : 'بعدی' }}
                            @else
                                @if ($trialDecision === 'declined')
                                    رفتن به پرداخت
                                @else
                                    شروع هفته آزمایشی
                                @endif
                            @endif
                        </span>
                        <span wire:loading wire:target="next">در حال پردازش...</span>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            قبلاً ثبت‌نام کرده‌ای؟ <a href="{{ route('client.auth.login') }}" class="text-indigo-600 font-bold hover:underline">ورود</a>
        </p>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('start-countdown', () => {
                // could implement a timer if needed
            });
        });
    </script>
</div>
