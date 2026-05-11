<div class="max-w-7xl space-y-8 px-4 mx-auto">

    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        {{-- Sidebar --}}
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        {{-- محتوای اصلی --}}
        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-6">

                {{-- عنوان --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground text-lg">هفته آزمایشی</div>
                    </div>

                    {{-- تایمر انقضا --}}
                    @if($trialWeek && $trialWeek->expires_at)
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full
                            {{ $trialWeek->isExpired() ? 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-bold">
                                @if($trialWeek->isExpired())
                                    منقضی شده
                                @else
                                    {{ $trialWeek->daysRemaining }} روز مانده
                                @endif
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Progress Bar --}}
                @if($trialWeek)
                <div class="bg-secondary border border-border rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-bold text-foreground">پیشرفت شما</span>
                        <span class="text-sm text-muted">{{ $trialWeek->statusLabel }}</span>
                    </div>

                    <div class="relative">
                        <div class="w-full bg-border rounded-full h-3">
                            <div class="bg-gradient-to-l from-emerald-400 to-teal-500 h-3 rounded-full transition-all duration-700"
                                 style="width: {{ ($trialWeek->step / 4) * 100 }}%"></div>
                        </div>

                        {{-- نقاط پیشرفت --}}
                        <div class="flex justify-between mt-4">
                            @php
                                $steps = [
                                    ['label' => 'ثبت‌نام', 'icon' => '✓', 'done' => $trialWeek->step >= 0],
                                    ['label' => 'پشتیبان', 'icon' => '👤', 'done' => $trialWeek->step >= 1],
                                    ['label' => 'طبقه‌بندی', 'icon' => '📊', 'done' => $trialWeek->step >= 2],
                                    ['label' => 'پیش‌جلسه', 'icon' => '📋', 'done' => $trialWeek->step >= 3],
                                    ['label' => 'برنامه', 'icon' => '🗓', 'done' => $trialWeek->step >= 4],
                                ];
                            @endphp
                            @foreach($steps as $step)
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all
                                        {{ $step['done'] ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-secondary border-border text-muted' }}">
                                        {{ $step['icon'] }}
                                    </div>
                                    <span class="text-[10px] text-muted hidden md:block">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- مراحل راهنما --}}
                <div class="space-y-4">

                    {{-- مرحله ۱: انتظار برای پشتیبان --}}
                    <div class="relative bg-secondary border rounded-2xl p-6 transition-all
                        {{ $trialWeek->status === 'pending' ? 'border-amber-400 dark:border-amber-500/50 shadow-lg shadow-amber-500/10' : 'border-border' }}">

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                                {{ $trialWeek->step >= 1 ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-amber-100 dark:bg-amber-900/30' }}">
                                @if($trialWeek->step >= 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h3 class="font-black text-foreground mb-1">
                                    مرحله ۱: تخصیص پشتیبان آزمایشی
                                </h3>
                                @if($trialWeek->step >= 1)
                                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-semibold">
                                        ✓ پشتیبان شما تخصیص یافته است
                                        @if($trialWeek->supporter)
                                            — {{ $trialWeek->supporter->name }}
                                        @endif
                                    </p>
                                @else
                                    <p class="text-sm text-muted leading-relaxed">
                                        درخواست شما ثبت شد. تیم ما در حال بررسی و تخصیص پشتیبان برای شما هستند.
                                        این فرایند معمولاً کمتر از ۲۴ ساعت طول می‌کشد.
                                    </p>
                                    <div class="mt-3 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">در انتظار تخصیص پشتیبان...</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- مرحله ۲: طبقه‌بندی --}}
                    <div class="relative bg-secondary border rounded-2xl p-6 transition-all
                        {{ $trialWeek->status === 'supporter_assigned' && !$trialWeek->isExpired() ? 'border-primary shadow-lg shadow-primary/10' : 'border-border' }}
                        {{ $trialWeek->step < 1 ? 'opacity-50' : '' }}">

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                                {{ $trialWeek->step >= 2 ? 'bg-emerald-100 dark:bg-emerald-900/30' : ($trialWeek->step >= 1 ? 'bg-primary/10' : 'bg-secondary') }}">
                                @if($trialWeek->step >= 2)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="font-black text-foreground">
                                        مرحله ۲: پر کردن طبقه‌بندی دروس
                                    </h3>

                                    @if($trialWeek->step >= 2)
                                        <span class="text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded-full font-bold">
                                            قفل شده ✓
                                        </span>
                                    @elseif($trialWeek->step === 1)
                                        <div class="flex items-center gap-2">
                                            @if($activeProject)
                                                <a wire:navigate
                                                   href="{{ route('client.profile.classification.projects') }}"
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl text-sm font-bold transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    رفتن به طبقه‌بندی
                                                </a>
                                                @if($this->classificationDone)
                                                    <button wire:click="openLockConfirm"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl text-sm font-bold transition-colors">
                                                        تایید و قفل کردن
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-xs text-amber-600 dark:text-amber-400">پروژه‌ای برای طبقه‌بندی موجود نیست</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                @if($trialWeek->step >= 2)
                                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                                        ✓ طبقه‌بندی تکمیل و قفل شده است
                                    </p>
                                @else
                                    <p class="text-sm text-muted leading-relaxed mt-2">
                                        در این مرحله باید وضعیت خود را در هر درس مشخص کنید.
                                        پس از تکمیل، برای قفل کردن باید تایید کنید — <strong>قابل ویرایش نخواهد بود.</strong>
                                    </p>
                                    @if($trialWeek->step === 1 && $this->classificationDone)
                                        <div class="mt-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40 rounded-xl">
                                            <p class="text-sm text-emerald-700 dark:text-emerald-300 font-semibold">
                                                طبقه‌بندی تکمیل شده! روی "تایید و قفل کردن" کلیک کنید.
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- مرحله ۳: پیش‌جلسه --}}
                    <div class="relative bg-secondary border rounded-2xl p-6 transition-all
                        {{ $trialWeek->status === 'classification_done' ? 'border-primary shadow-lg shadow-primary/10' : 'border-border' }}
                        {{ $trialWeek->step < 2 ? 'opacity-50' : '' }}">

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                                {{ $trialWeek->step >= 3 ? 'bg-emerald-100 dark:bg-emerald-900/30' : ($trialWeek->step >= 2 ? 'bg-primary/10' : 'bg-secondary') }}">
                                @if($trialWeek->step >= 3)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="font-black text-foreground">
                                        مرحله ۳: پر کردن پیش‌جلسه
                                    </h3>

                                    @if($trialWeek->step >= 3)
                                        <span class="text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded-full font-bold">
                                            تکمیل شده ✓
                                        </span>
                                    @elseif($trialWeek->step === 2 && $trialWeek->advisingSession)
                                        <a wire:navigate
                                           href="{{ route('client.profile.consultation.pre-session', $trialWeek->advising_session_id) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl text-sm font-bold transition-colors">
                                            رفتن به پیش‌جلسه
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                                <p class="text-sm text-muted leading-relaxed mt-2">
                                    پیش‌جلسه شامل اطلاعاتی درباره وضعیت مطالعاتی شما است که به پشتیبان کمک می‌کند برنامه بهتری بسازد.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- مرحله ۴: ورود به جلسه و ساخت برنامه --}}
                    <div class="relative bg-secondary border rounded-2xl p-6 transition-all
                        {{ $trialWeek->status === 'pre_session_done' ? 'border-primary shadow-lg shadow-primary/10' : 'border-border' }}
                        {{ $trialWeek->step < 3 ? 'opacity-50' : '' }}">

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                                {{ $trialWeek->step >= 4 ? 'bg-emerald-100 dark:bg-emerald-900/30' : ($trialWeek->step >= 3 ? 'bg-primary/10' : 'bg-secondary') }}">
                                @if($trialWeek->step >= 4)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="font-black text-foreground">
                                        مرحله ۴: ورود به جلسه و ساخت برنامه
                                    </h3>

                                    @if($trialWeek->step >= 4)
                                        <span class="text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded-full font-bold">
                                            کامل شد ✓
                                        </span>
                                    @elseif($trialWeek->step === 3)
                                        <div class="flex items-center gap-2">
                                            <a wire:navigate
                                               href="{{ route('client.profile.trial.session-analysis') }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl text-sm font-bold transition-colors">
                                                ورود به جلسه
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-sm text-muted leading-relaxed mt-2">
                                    در این مرحله وضعیت شما تحلیل می‌شود و یک برنامه مطالعاتی شخصی بر اساس طبقه‌بندی و پیش‌جلسه برای شما ساخته می‌شود.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- نتیجه نهایی: دسترسی کامل --}}
                    @if($trialWeek->step >= 4)
                    <div class="bg-gradient-to-l from-emerald-500/10 to-teal-500/10 border border-emerald-500/30 rounded-2xl p-6 text-center">
                        <div class="text-4xl mb-3">🎉</div>
                        <h3 class="font-black text-foreground text-xl mb-2">تبریک! هفته آزمایشی کامل شد</h3>
                        <p class="text-muted text-sm mb-5">
                            برنامه مطالعاتی شما آماده است. در طول {{ $trialWeek->daysRemaining }} روز باقی‌مانده از تمام امکانات استفاده کنید.
                        </p>
                        <div class="flex justify-center gap-3 flex-wrap">
                            <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                               class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-bold transition-colors">
                                مشاهده جلسات
                            </a>
                            <a wire:navigate href="{{ route('client.profile.classification.projects') }}"
                               class="inline-flex items-center gap-2 px-5 py-3 bg-secondary hover:bg-border border border-border text-foreground rounded-xl font-bold transition-colors">
                                مشاهده طبقه‌بندی
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- مودال تایید قفل طبقه‌بندی --}}
    @if($showLockConfirm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="$wire.closeLockConfirm()">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeLockConfirm"></div>
        <div class="relative z-10 w-full max-w-md bg-background border border-border rounded-3xl shadow-2xl p-8 text-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-center w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h2 class="text-xl font-black text-foreground mb-3">تایید قفل طبقه‌بندی</h2>
            <p class="text-muted text-sm leading-relaxed mb-6">
                پس از تایید، <strong class="text-foreground">امکان ویرایش طبقه‌بندی وجود نخواهد داشت.</strong>
                آیا از صحت اطلاعات وارد شده مطمئن هستید؟
            </p>

            @error('lock') <p class="text-sm text-red-500 mb-3">{{ $message }}</p> @enderror

            <div class="flex gap-3">
                <button wire:click="lockClassification"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-400 disabled:opacity-50 text-white rounded-xl font-bold transition-colors">
                    <svg wire:loading wire:target="lockClassification" class="animate-spin w-4 h-4 inline ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    بله، قفل کن
                </button>
                <button wire:click="closeLockConfirm"
                        class="flex-1 py-3 bg-secondary hover:bg-border text-foreground rounded-xl font-bold transition-colors">
                    بازبینی می‌کنم
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
