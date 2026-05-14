<div class="min-h-screen bg-background" x-data="{
    isMobile: window.innerWidth < 1024,
    init() {
        window.addEventListener('resize', () => { this.isMobile = window.innerWidth < 1024; });
    }
}">

    @push('link')
    <style>
        .onboarding-hero {
            background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(59,130,246,0.1)),
                        linear-gradient(180deg, #0b0f1a, #0e1726);
        }
        .step-dot { transition: all 0.3s ease; }
        .step-dot.active { transform: scale(1.3); }
        .slide-enter { animation: slideIn .35s ease forwards; }
        @keyframes slideIn { from { opacity:0; transform: translateX(-20px); } to { opacity:1; transform: translateX(0); } }
    </style>
    @endpush

    @php
        $gradeLabels = ['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم'];
        $fieldLabels = ['math'=>'ریاضی','experimental'=>'تجربی','human'=>'انسانی'];
    @endphp

    {{-- ─────────────────────────────────────────── --}}
    {{-- DESKTOP: نمایش همه بخش‌ها به صورت عادی --}}
    {{-- ─────────────────────────────────────────── --}}
    <div class="hidden lg:block">

        {{-- Hero Section --}}
        <div class="onboarding-hero py-24 text-center">
            <div class="max-w-4xl mx-auto px-6">
                <div class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    هفته آزمایشی رایگان
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">
                    مسیر تحصیلی هوشمند<br>
                    <span class="text-emerald-400">با SDFR شروع کن</span>
                </h1>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                    با یک هفته آزمایشی رایگان، برنامه مطالعاتی شخصی بساز، طبقه‌بندی مباحث داشته باش و قدم اول رو محکم برو.
                </p>
            </div>
        </div>

        {{-- Features --}}
        <div class="max-w-5xl mx-auto px-6 py-16">
            <div class="grid grid-cols-3 gap-8 mb-16">
                @foreach([
                    ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','title'=>'برنامه شخصی','desc'=>'بر اساس نقاط ضعف و قوتت برنامه مطالعاتی دقیق بساز'],
                    ['icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','title'=>'طبقه‌بندی مباحث','desc'=>'وضعیت هر مبحث رو از D تا A+ بسنج و ضعیف‌ها رو بشناس'],
                    ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','title'=>'پشتیبان اختصاصی','desc'=>'پشتیبان جذب اختصاصی در کنارته تا بهترین مسیر رو انتخاب کنی'],
                ] as $f)
                <div class="bg-card border border-border rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-foreground mb-2">{{ $f['title'] }}</h3>
                    <p class="text-sm text-muted">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- فرم ثبت‌نام دسکتاپ --}}
            @if(!$registered)
            <div class="max-w-2xl mx-auto bg-card border border-border rounded-3xl p-8">
                <h2 class="text-2xl font-black text-foreground mb-8 text-center">ثبت‌نام در هفته آزمایشی</h2>

                @if($generalError)
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-sm text-center">{{ $generalError }}</div>
                @endif

                @if($currentStep < 7)
                {{-- نام و نام خانوادگی --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">نام *</label>
                        <input wire:model="firstName" type="text" placeholder="مثال: علی"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('firstName') border-red-500 @enderror">
                        @error('firstName')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">نام خانوادگی *</label>
                        <input wire:model="lastName" type="text" placeholder="مثال: محمدی"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('lastName') border-red-500 @enderror">
                        @error('lastName')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- کد ملی --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-muted mb-1">کد ملی *</label>
                    <input wire:model="codeMell" type="text" maxlength="10" placeholder="۱۰ رقم"
                           class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('codeMell') border-red-500 @enderror">
                    @error('codeMell')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- شماره والدین --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شماره پدر *</label>
                        <input wire:model="fatherMobile" type="text" placeholder="09..."
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('fatherMobile') border-red-500 @enderror">
                        @error('fatherMobile')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شماره مادر *</label>
                        <input wire:model="motherMobile" type="text" placeholder="09..."
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('motherMobile') border-red-500 @enderror">
                        @error('motherMobile')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- پایه و رشته --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">پایه *</label>
                        <select wire:model.live="grade" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500">
                            @foreach(['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم'] as $v=>$l)
                            <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($grade !== '9')
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">رشته *</label>
                        <select wire:model="field" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500">
                            <option value="math">ریاضی</option>
                            <option value="experimental">تجربی</option>
                            <option value="human">انسانی</option>
                        </select>
                    </div>
                    @endif
                </div>

                {{-- استان و شهر --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">استان *</label>
                        <select wire:model.live="stateId" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('stateId') border-red-500 @enderror">
                            <option value="0">انتخاب استان</option>
                            @foreach($states as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('stateId')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شهر *</label>
                        <select wire:model="cityId" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('cityId') border-red-500 @enderror">
                            <option value="0">انتخاب شهر</option>
                            @foreach($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('cityId')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- موبایل و رمز --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-muted mb-1">شماره موبایل *</label>
                    <input wire:model="mobile" type="text" placeholder="09..."
                           class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('mobile') border-red-500 @enderror">
                    @error('mobile')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">رمز عبور *</label>
                        <input wire:model.live="password" type="password"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('password') border-red-500 @enderror">
                        @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">تکرار رمز *</label>
                        <input wire:model="passwordConf" type="password"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('passwordConf') border-red-500 @enderror">
                        @error('passwordConf')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button wire:click="next" wire:loading.attr="disabled"
                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                    <svg wire:loading wire:target="next" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span wire:loading.remove wire:target="next">ارسال کد تأیید</span>
                    <span wire:loading wire:target="next">در حال ارسال...</span>
                </button>

                <p class="text-center text-sm text-muted mt-4">
                    قبلاً ثبت‌نام کردی؟
                    <a href="{{ route('client.auth.login') }}" class="text-emerald-400 hover:underline">وارد شو</a>
                </p>
                @elseif($currentStep === 7)
                {{-- OTP --}}
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-foreground mb-2">تأیید شماره موبایل</h3>
                    <p class="text-muted text-sm mb-6">کد ۶ رقمی ارسال‌شده به <span class="text-emerald-400 font-mono">{{ $mobile }}</span> را وارد کن</p>

                    @if($otpError)
                    <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-sm">{{ $otpError }}</div>
                    @endif

                    <input wire:model="otpInput" type="text" maxlength="6" placeholder="------"
                           class="w-48 text-center text-2xl font-mono bg-secondary border-2 border-border focus:border-emerald-500 rounded-xl px-4 py-3 text-foreground focus:outline-none mx-auto block mb-4 tracking-widest">

                    <button wire:click="verifyOtp" wire:loading.attr="disabled"
                            class="w-full max-w-xs mx-auto py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="verifyOtp" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        تأیید و ادامه
                    </button>

                    <div class="mt-4 text-sm text-muted">
                        @if($countdown > 0)
                        <span x-data="{ t: @entangle('countdown'), interval: null }"
                              x-init="interval = setInterval(() => { if(t>0) t--; else { clearInterval(interval); $wire.countdownFinished(); } }, 1000)">
                            ارسال مجدد تا <span x-text="t" class="text-emerald-400 font-mono"></span> ثانیه
                        </span>
                        @else
                        <button wire:click="resendOtp" class="text-emerald-400 hover:underline">ارسال مجدد کد</button>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @else
            {{-- پس از ثبت‌نام → تأیید هفته آزمایشی --}}
            <div class="max-w-lg mx-auto bg-card border border-border rounded-3xl p-8 text-center">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-foreground mb-3">ثبت‌نام کامل شد!</h2>
                <p class="text-muted text-sm leading-relaxed mb-8">
                    حالا می‌تونی هفته آزمایشی رایگانت رو شروع کنی یا مستقیم دوره بخری.
                </p>
                <div class="flex flex-col gap-3">
                    <button wire:click="openTrialConfirm"
                            class="py-4 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors">
                        بله، می‌خوام در هفته آزمایشی شرکت کنم
                    </button>
                    <button wire:click="declineTrial"
                            class="py-3 bg-secondary hover:bg-border text-muted rounded-xl font-medium transition-colors text-sm">
                        نه، ترجیح می‌دم مستقیم دوره بخرم
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ─────────────────────────────────────────── --}}
    {{-- MOBILE: مرحله به مرحله --}}
    {{-- ─────────────────────────────────────────── --}}
    <div class="block lg:hidden min-h-screen flex flex-col">

        {{-- نوار پیشرفت --}}
        @if(!$registered)
        <div class="px-4 pt-6 pb-2">
            <div class="flex items-center justify-center gap-1.5">
                @for($i = 1; $i <= $totalSteps; $i++)
                <div class="step-dot h-1.5 rounded-full transition-all duration-300
                    {{ $currentStep === $i ? 'w-8 bg-emerald-400 active' : ($currentStep > $i ? 'w-4 bg-emerald-600' : 'w-4 bg-border') }}">
                </div>
                @endfor
            </div>
            <p class="text-center text-xs text-muted mt-2">مرحله {{ $currentStep }} از {{ $totalSteps }}</p>
        </div>
        @endif

        {{-- محتوای هر مرحله --}}
        <div class="flex-1 flex flex-col px-4 py-6 slide-enter" wire:key="step-{{ $currentStep }}">

            @if($currentStep === 1)
            {{-- خوش‌آمدگویی ۱ --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg shadow-emerald-500/30">
                    <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-black text-foreground mb-4 leading-tight">
                    به SDFR<br><span class="text-emerald-400">خوش اومدی!</span>
                </h1>
                <p class="text-muted leading-relaxed">
                    با یک هفته آزمایشی رایگان، ببین این سیستم چطور می‌تونه تحصیلت رو متحول کنه.
                </p>
            </div>

            @elseif($currentStep === 2)
            {{-- خوش‌آمدگویی ۲ --}}
            <div class="flex-1 flex flex-col justify-center">
                <h2 class="text-2xl font-black text-foreground mb-8 text-center">این هفته چی داری؟</h2>
                <div class="space-y-4">
                    @foreach([
                        ['bg'=>'bg-blue-500/10','icon_color'=>'text-blue-400','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','title'=>'برنامه مطالعاتی هوشمند','desc'=>'برنامه شخصی بر اساس پایه و رشته‌ات'],
                        ['bg'=>'bg-purple-500/10','icon_color'=>'text-purple-400','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2','title'=>'طبقه‌بندی مباحث','desc'=>'وضعیت دقیق هر مبحث رو بسنج'],
                        ['bg'=>'bg-emerald-500/10','icon_color'=>'text-emerald-400','icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','title'=>'پشتیبان اختصاصی','desc'=>'راهنمایی و پیگیری توسط پشتیبان'],
                    ] as $f)
                    <div class="flex items-center gap-4 {{ $f['bg'] }} rounded-2xl p-4">
                        <div class="w-10 h-10 bg-background/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 {{ $f['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-foreground text-sm">{{ $f['title'] }}</h3>
                            <p class="text-xs text-muted">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @elseif($currentStep === 3)
            {{-- خوش‌آمدگویی ۳ --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-amber-500/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-foreground mb-3">کاملاً رایگانه!</h2>
                <p class="text-muted leading-relaxed mb-6">یه هفته آزمایشی کامل بدون هیچ هزینه‌ای. اگه دوست داشتی، بعدش ادامه بده.</p>
                <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-4 text-right">
                    <p class="text-sm text-emerald-300 leading-relaxed">
                        ✓ بدون نیاز به کارت بانکی<br>
                        ✓ دسترسی کامل به امکانات<br>
                        ✓ پشتیبان اختصاصی
                    </p>
                </div>
            </div>

            @elseif($currentStep === 4)
            {{-- اطلاعات شخصی --}}
            <div>
                <h2 class="text-xl font-black text-foreground mb-1">اطلاعات شخصی</h2>
                <p class="text-sm text-muted mb-6">برای ثبت‌نام به این اطلاعات نیاز داریم</p>

                @if($generalError)
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-sm">{{ $generalError }}</div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">نام *</label>
                        <input wire:model="firstName" type="text" placeholder="مثال: علی"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('firstName') border-red-500 @enderror">
                        @error('firstName')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">نام خانوادگی *</label>
                        <input wire:model="lastName" type="text" placeholder="مثال: محمدی"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('lastName') border-red-500 @enderror">
                        @error('lastName')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">کد ملی *</label>
                        <input wire:model="codeMell" type="text" maxlength="10" placeholder="۱۰ رقم"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('codeMell') border-red-500 @enderror">
                        @error('codeMell')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            @elseif($currentStep === 5)
            {{-- والدین + پایه + رشته --}}
            <div>
                <h2 class="text-xl font-black text-foreground mb-1">اطلاعات تحصیلی</h2>
                <p class="text-sm text-muted mb-6">شماره والدین و پایه تحصیلیت رو وارد کن</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شماره پدر *</label>
                        <input wire:model="fatherMobile" type="tel" placeholder="09..."
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('fatherMobile') border-red-500 @enderror">
                        @error('fatherMobile')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شماره مادر *</label>
                        <input wire:model="motherMobile" type="tel" placeholder="09..."
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('motherMobile') border-red-500 @enderror">
                        @error('motherMobile')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">پایه *</label>
                        <select wire:model.live="grade" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500">
                            <option value="9">نهم</option>
                            <option value="10">دهم</option>
                            <option value="11">یازدهم</option>
                            <option value="12">دوازدهم</option>
                        </select>
                    </div>
                    @if($grade !== '9')
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">رشته *</label>
                        <select wire:model="field" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500">
                            <option value="math">ریاضی</option>
                            <option value="experimental">تجربی</option>
                            <option value="human">انسانی</option>
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            @elseif($currentStep === 6)
            {{-- مکان + اطلاعات حساب --}}
            <div>
                <h2 class="text-xl font-black text-foreground mb-1">اطلاعات حساب</h2>
                <p class="text-sm text-muted mb-6">استان، شهر و اطلاعات ورود رو وارد کن</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">استان *</label>
                        <select wire:model.live="stateId" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('stateId') border-red-500 @enderror">
                            <option value="0">انتخاب استان</option>
                            @foreach($states as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('stateId')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شهر *</label>
                        <select wire:model="cityId" class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('cityId') border-red-500 @enderror">
                            <option value="0">انتخاب شهر</option>
                            @foreach($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('cityId')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">شماره موبایل *</label>
                        <input wire:model="mobile" type="tel" placeholder="09..."
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('mobile') border-red-500 @enderror">
                        @error('mobile')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">رمز عبور *</label>
                        <input wire:model.live="password" type="password"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('password') border-red-500 @enderror">
                        <div class="flex gap-2 mt-2">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $passwordStrength['length'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-secondary text-muted' }}">۸ کاراکتر</span>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $passwordStrength['letter'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-secondary text-muted' }}">حرف</span>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $passwordStrength['number'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-secondary text-muted' }}">عدد</span>
                        </div>
                        @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted mb-1">تکرار رمز *</label>
                        <input wire:model="passwordConf" type="password"
                               class="w-full bg-secondary border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-emerald-500 @error('passwordConf') border-red-500 @enderror">
                        @error('passwordConf')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            @elseif($currentStep === 7)
            {{-- OTP --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-foreground mb-2">تأیید موبایل</h3>
                <p class="text-muted text-sm mb-6">کد ارسال‌شده به <span class="text-emerald-400 font-mono">{{ $mobile }}</span> را وارد کن</p>

                @if($otpError)
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-sm w-full">{{ $otpError }}</div>
                @endif

                <input wire:model="otpInput" type="text" maxlength="6" placeholder="------"
                       class="w-48 text-center text-2xl font-mono bg-secondary border-2 border-border focus:border-emerald-500 rounded-xl px-4 py-3 text-foreground focus:outline-none block mb-4 tracking-widest">

                <button wire:click="verifyOtp" wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2 mb-4">
                    <svg wire:loading wire:target="verifyOtp" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    تأیید و ادامه
                </button>

                <div class="text-sm text-muted">
                    @if($countdown > 0)
                    <span x-data="{ t: @entangle('countdown'), interval: null }"
                          x-init="interval = setInterval(() => { if(t>0) t--; else { clearInterval(interval); $wire.countdownFinished(); } }, 1000)">
                        ارسال مجدد تا <span x-text="t" class="text-emerald-400 font-mono"></span> ثانیه
                    </span>
                    @else
                    <button wire:click="resendOtp" class="text-emerald-400 hover:underline">ارسال مجدد کد</button>
                    @endif
                </div>
            </div>

            @elseif($currentStep === 8 && $registered)
            {{-- تأیید نهایی --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-foreground mb-3">ثبت‌نام کامل شد!</h2>
                <p class="text-muted text-sm leading-relaxed mb-8">
                    حالا می‌تونی هفته آزمایشی رایگانت رو شروع کنی یا مستقیم دوره بخری.
                </p>
                <div class="w-full space-y-3">
                    <button wire:click="openTrialConfirm"
                            class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors">
                        بله، می‌خوام شرکت کنم
                    </button>
                    <button wire:click="declineTrial"
                            class="w-full py-3 bg-secondary hover:bg-border text-muted rounded-xl font-medium transition-colors text-sm">
                        نه، مستقیم دوره می‌خرم
                    </button>
                </div>
            </div>
            @endif

        </div>

        {{-- دکمه‌های ناوبری موبایل --}}
        @if(!$registered && $currentStep !== 7)
        <div class="px-4 pb-8 flex gap-3">
            @if($currentStep > 1)
            <button wire:click="previous"
                    class="flex-1 py-3.5 bg-secondary hover:bg-border text-foreground rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                قبلی
            </button>
            @endif
            @if($currentStep < 6)
            <button wire:click="next" wire:loading.attr="disabled"
                    class="flex-1 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                بعدی
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            @elseif($currentStep === 6)
            <button wire:click="next" wire:loading.attr="disabled"
                    class="flex-1 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                <svg wire:loading wire:target="next" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span wire:loading.remove wire:target="next">ارسال کد تأیید</span>
                <span wire:loading wire:target="next">در حال ارسال...</span>
            </button>
            @endif
        </div>
        @endif

        {{-- لینک ورود --}}
        @if($currentStep <= 3)
        <div class="pb-6 text-center text-sm text-muted">
            قبلاً ثبت‌نام کردی؟
            <a href="{{ route('client.auth.login') }}" class="text-emerald-400 hover:underline">وارد شو</a>
        </div>
        @endif

    </div>

    {{-- ─── مودال تأیید هفته آزمایشی ──────────────────────────────────────────── --}}
    @if($showTrialConfirm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="$wire.closeTrialConfirm()">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeTrialConfirm"></div>
        <div class="relative z-10 w-full max-w-sm bg-card border border-border rounded-3xl shadow-2xl p-8 text-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="text-lg font-black text-foreground mb-2">شروع هفته آزمایشی</h3>
            <p class="text-sm text-muted leading-relaxed mb-6">
                با تأیید، هفته آزمایشی رایگانت شروع می‌شه و پشتیبان اختصاصی بهت اختصاص داده می‌شه.
                آیا مطمئنی؟
            </p>
            <div class="flex gap-3">
                <button wire:click="confirmTrial" wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors">
                    بله، شروع کن!
                </button>
                <button wire:click="closeTrialConfirm"
                        class="flex-1 py-3 bg-secondary hover:bg-border text-foreground rounded-xl font-bold transition-colors">
                    انصراف
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
