<div>
    @push('link')
        <style>
            [x-cloak] { display: none !important; }

            /* ============ (B1) جنسیت + آواتار ============ */
            .gender-opt { display: flex; flex-direction: column; align-items: center; gap: .55rem; padding: .9rem .75rem;
                border-radius: 1rem; border: 1px solid hsl(var(--border)); background: hsl(var(--secondary) / .5);
                transition: border-color .2s, background .2s, transform .2s; cursor: pointer; }
            .gender-opt:hover { border-color: hsl(var(--primary) / .5); transform: translateY(-2px); }
            .gender-opt--boy { border-color: hsl(var(--primary)); background: hsl(var(--primary) / .10); }
            .gender-opt--girl { border-color: #ec4899; background: rgba(236, 72, 153, .10); }
            .gender-ava { width: 56px; height: 56px; border-radius: 50%; overflow: hidden; flex: none;
                display: flex; align-items: flex-end; justify-content: center; }
            .gender-ava--boy { background: hsl(var(--primary)); }
            .gender-ava--girl { background: #ec4899; }
            .gender-ava img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }

            .avatar-bubble { width: 44px; height: 44px; border-radius: 50%; overflow: hidden; flex: none; color: #fff;
                display: flex; align-items: flex-end; justify-content: center; }
            .avatar-bubble--boy { background: hsl(var(--primary)); }
            .avatar-bubble--girl { background: #ec4899; }
            .avatar-bubble img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }

            .avatar-pick { aspect-ratio: 1; border-radius: 50%; overflow: hidden; border: 3px solid transparent;
                display: flex; align-items: flex-end; justify-content: center; transition: transform .2s, border-color .2s; cursor: pointer; }
            .avatar-pick--boy { background: hsl(var(--primary)); }
            .avatar-pick--girl { background: #ec4899; }
            .avatar-pick:hover { transform: scale(1.05); }
            .avatar-pick--on { border-color: hsl(var(--foreground)); transform: scale(1.05); box-shadow: 0 8px 22px -8px rgba(0,0,0,.4); }
            .avatar-pick img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }

            .grid-figma {
                background-image:
                    linear-gradient(to right, hsl(var(--border) / 0.4) 1px, transparent 1px),
                    linear-gradient(to bottom, hsl(var(--border) / 0.4) 1px, transparent 1px),
                    linear-gradient(to right, hsl(var(--border) / 0.2) 1px, transparent 1px),
                    linear-gradient(to bottom, hsl(var(--border) / 0.2) 1px, transparent 1px);
                background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
                -webkit-mask-image: radial-gradient(ellipse 100% 80% at 50% 30%, #000 30%, transparent 90%);
                mask-image: radial-gradient(ellipse 100% 80% at 50% 30%, #000 30%, transparent 90%);
            }
            .glass-card {
                background: hsl(var(--background) / 0.6);
                backdrop-filter: blur(18px) saturate(140%);
                -webkit-backdrop-filter: blur(18px) saturate(140%);
                border: 1px solid hsl(var(--border) / 0.6);
            }
            .glass-input {
                background: hsl(var(--secondary) / 0.6);
                border: 1px solid hsl(var(--border));
                transition: all 0.2s ease;
                color: hsl(var(--foreground));
            }
            .glass-input:focus {
                background: hsl(var(--secondary));
                border-color: hsl(var(--primary));
                box-shadow: 0 0 0 3px hsl(var(--primary) / 0.15);
                outline: none;
            }
            .glass-input::placeholder { color: hsl(var(--muted) / 0.7); }

            .train-border { position: relative; border-radius: 1.5rem; --bw: 3px; --speed: 9s; }
            .train-border::before {
                content: '';
                position: absolute; inset: 0; border-radius: inherit;
                padding: var(--bw);
                background: conic-gradient(from var(--angle, 0deg),
                transparent 0deg, transparent 200deg,
                hsl(var(--primary) / 0.45) 270deg, #3b82f6 318deg,
                #93c5fd 340deg, #ffffff 351deg, #93c5fd 360deg);
                -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                animation: rotate-border var(--speed) linear infinite;
                pointer-events: none; z-index: 3;
            }
            .train-border > * { position: relative; z-index: 1; }
            @property --angle { syntax: '<angle>'; initial-value: 0deg; inherits: false; }
            @keyframes rotate-border { to { --angle: 360deg; } }
            @supports not (background: conic-gradient(from 0deg, red, blue)) { .train-border::before { display: none; } }

            .btn-press {
                position: relative; transform: translateY(0);
                box-shadow: 0 4px 0 0 hsl(var(--primary) / 0.4), 0 6px 12px hsl(var(--primary) / 0.25);
                transition: transform 0.08s ease, box-shadow 0.08s ease;
                background: hsl(var(--primary)); color: white; user-select: none;
            }
            .btn-press:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 5px 0 0 hsl(var(--primary) / 0.4), 0 8px 16px hsl(var(--primary) / 0.35); }
            .btn-press:active:not(:disabled), .btn-press.pressed { transform: translateY(3px); box-shadow: 0 1px 0 0 hsl(var(--primary) / 0.4), 0 2px 4px hsl(var(--primary) / 0.2); }
            .btn-press:disabled { opacity: 0.6; cursor: not-allowed; }

            .btn-press-secondary {
                position: relative; transform: translateY(0);
                box-shadow: 0 3px 0 0 hsl(var(--border)), 0 4px 8px hsl(var(--foreground) / 0.05);
                transition: transform 0.08s ease, box-shadow 0.08s ease;
                background: hsl(var(--secondary)); color: hsl(var(--foreground));
                border: 1px solid hsl(var(--border));
            }
            .btn-press-secondary:hover:not(:disabled) { transform: translateY(-1px); }

            .accent-emerald { --accent: 16 185 129; }
            .accent-card { background: linear-gradient(135deg, rgb(var(--accent) / 0.08), hsl(var(--secondary) / 0.6)); border: 1px solid rgb(var(--accent) / 0.25); }
            .accent-icon { background: rgb(var(--accent) / 0.12); color: rgb(var(--accent)); border: 1px solid rgb(var(--accent) / 0.25); }

            .step-fade { transition: opacity 0.22s ease; }

            @keyframes pop-in { 0% { transform: scale(0.6); opacity: 0; } 60% { transform: scale(1.08); } 100% { transform: scale(1); opacity: 1; } }
            @keyframes soft-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
            .anim-pop { animation: pop-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
            .anim-float { animation: soft-float 3s ease-in-out infinite; }

            .progress-dot { width: 8px; height: 8px; border-radius: 999px; background: hsl(var(--border)); transition: all 0.3s ease; }
            .progress-dot.active { width: 28px; background: hsl(var(--primary)); }
            .progress-dot.completed { background: hsl(var(--primary) / 0.5); }

            @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
            .shake { animation: shake 0.4s ease; }

            @keyframes float-orb { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, -25px); } }
            .float-orb { animation: float-orb 9s ease-in-out infinite; }

            /* ═══ LOGO — bigger, centered, mobile + desktop ═══ */
            .brand-logo {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 180px;
                max-width: 80%;
                height: auto;
            }
            @media (min-width: 768px) { .brand-logo { width: 260px; } }

            .password-wrapper { position: relative; }
            .password-wrapper input { padding-left: 2.5rem; }
            .eye-btn {
                position: absolute; left: 0.625rem; top: 50%;
                transform: translateY(-50%); color: hsl(var(--muted));
                background: none; border: none; padding: 4px; cursor: pointer;
                display: flex; align-items: center; justify-content: center;
                border-radius: 4px; transition: color 0.15s ease;
            }
            .eye-btn:hover { color: hsl(var(--foreground)); }

            @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
            .skeleton { background: linear-gradient(90deg, hsl(var(--secondary)) 25%, hsl(var(--border) / 0.5) 50%, hsl(var(--secondary)) 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 0.5rem; }

            @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
        </style>
    @endpush

    @php
        $gradeLabels = ['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم','graduate'=>'فارغ‌التحصیل'];
        $fieldLabels = ['math'=>'ریاضی','experimental'=>'تجربی','human'=>'انسانی'];
        $gradeOptions = [];
        foreach ($gradeLabels as $v => $l) { $gradeOptions[] = ['id' => (string) $v, 'name' => $l]; }
        $fieldOptions = [];
        foreach ($fieldLabels as $v => $l) { $fieldOptions[] = ['id' => $v, 'name' => $l]; }
        $stateOptions = collect($states)->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->all();
        $cityOptions  = collect($cities)->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()->all();

        $features = [
            ['t' => 'برنامه‌ی هفتگی اختصاصی', 'd' => 'مشاور متخصص برای تو برنامه می‌نویسه', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
            ['t' => 'گزارش لحظه‌ای پیشرفت',   'd' => 'هر روز عملکرد خودتو رصد می‌کنی',     'icon' => '<line x1="3" y1="3" x2="3" y2="21"/><line x1="3" y1="21" x2="21" y2="21"/><polyline points="7 16 11 12 15 16 21 10"/>'],
            ['t' => 'تایمر هوشمند مطالعه',   'd' => 'ساعت مفید مطالعه‌ی هر درس رو ثبت می کنی', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            ['t' => 'آزمون‌های آنلاین اختصاصی',       'd' => 'آزمون های تشریحی و تستی متناسب با سطح تو',       'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
            ['t' => 'اتاق مشاوره',           'd' => 'ارتباط مستقیم با مشاور تخصصی',         'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
            ['t' => 'کارنامه و آزمون',       'd' => 'کارنامه‌ی ماهانه با نمودار پیشرفت',     'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'],
        ];

        $svgWelcome = '<img src="/client/assets/images/theme/intro/header.png" alt="SDFR" class="brand-logo anim-float" />';
        $svgOtp     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full text-primary anim-float"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>';
        $svgSuccess = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full text-emerald-500 anim-pop"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></svg>';

        // (B1) آواتارهای قابلِ انتخاب — همان تصاویرِ بخشِ «ستارگانِ SDFR»، تفکیک‌شده بر اساسِ جنسیت.
        $boyAvatars = [
            '/client/assets/images/avatars/star-boy-1.webp',
            '/client/assets/images/avatars/star-boy-2.webp',
            '/client/assets/images/avatars/star-boy-3.png',
        ];
        $girlAvatars = [
            '/client/assets/images/avatars/star-girl-1.webp',
            '/client/assets/images/avatars/star-girl-2.webp',
            '/client/assets/images/avatars/star-girl-3.png',
        ];
    @endphp

    <div class="relative min-h-screen overflow-hidden bg-background text-foreground" dir="rtl" x-data="onboardingFlow()">

        <div class="absolute inset-0 grid-figma pointer-events-none"></div>
        <div class="absolute top-20 -right-20 w-72 h-72 bg-primary/15 rounded-full blur-3xl float-orb pointer-events-none"></div>
        <div class="absolute bottom-20 -left-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl float-orb pointer-events-none" style="animation-delay: -3s"></div>

        {{-- ═══════════════ 🧑‍🚀 مودالِ انتخابِ آواتار (B1) ═══════════════ --}}
        <div x-data="{ openAv: false }"
             x-on:open-avatar.window="openAv = true"
             x-show="openAv" x-cloak
             class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="openAv = false"></div>
            <div class="relative w-full max-w-md glass-card rounded-3xl p-6"
                 x-show="openAv" x-transition>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-lg">انتخاب آواتار</h3>
                    <button type="button" @click="openAv = false" class="w-8 h-8 rounded-lg bg-secondary/60 flex items-center justify-center hover:bg-secondary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-muted mb-5">یکی از آواتارها را برای پروفایلت انتخاب کن.</p>
                @php $avList = $gender === 'female' ? $girlAvatars : $boyAvatars; @endphp
                <div class="grid grid-cols-3 gap-4">
                    @foreach($avList as $a)
                        <button type="button"
                            wire:click="$set('avatar', '{{ $a }}')" @click="openAv = false"
                            class="avatar-pick avatar-pick--{{ $gender === 'female' ? 'girl' : 'boy' }} @if($avatar === $a) avatar-pick--on @endif">
                            <img src="{{ $a }}" alt="آواتار" loading="lazy">
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══════════════ 📱 MOBILE ═══════════════ --}}
        <div class="md:hidden relative z-10 min-h-[100dvh] flex flex-col">

            <header class="px-4 pt-5 pb-3" x-show="$wire.currentStep < {{ $totalSteps }}">
                <div class="flex items-center gap-3">
                    <div class="flex-1 flex items-center justify-center gap-2">
                        @for ($i = 2; $i <= $totalSteps; $i++)
                            <span class="progress-dot" :class="{ 'active': $wire.currentStep === {{ $i }}, 'completed': $wire.currentStep > {{ $i }} }"></span>
                        @endfor
                    </div>
                    <div class="text-xs font-mono text-muted"><span x-text="$wire.currentStep"></span>/{{ $totalSteps }}</div>
                </div>
            </header>

            @if ($generalError)
                <div class="mx-4 mb-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 px-4 py-3 text-sm">{{ $generalError }}</div>
            @endif

            <main class="flex-1 flex items-stretch justify-center px-4 py-4" @touchstart="handleTouchStart($event)" @touchend="handleTouchEnd($event)">
                <div class="w-full relative">

                    <div x-show="busy" x-cloak class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-4 min-h-[60vh]">
                        <svg class="w-12 h-12 animate-spin text-primary" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="48" stroke-linecap="round" opacity="0.4"/>
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="14 60" stroke-linecap="round"/>
                        </svg>
                        <span class="text-sm text-muted">لطفاً صبر کنید…</span>
                    </div>

                    <div class="step-fade" :class="busy ? 'opacity-0 pointer-events-none' : 'opacity-100'">


                        {{-- STEP 2 --}}
                        <section x-show="$wire.currentStep === 2">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                        <div><h2 class="font-black text-lg">اطلاعات شخصی</h2></div>
                                    </div>

                                    <form @submit.prevent="goNext()" autocomplete="on" class="space-y-4">
                                        <div class="relative" data-tour="firstName">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام</label>
                                            <input wire:model.blur="firstName" type="text" placeholder="مثلاً علی" autocomplete="given-name" class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('firstName') border-rose-500/60 shake @enderror">
                                            @error('firstName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام خانوادگی</label>
                                            <input wire:model.blur="lastName" type="text" placeholder="مثلاً محمدی" autocomplete="family-name" class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('lastName') border-rose-500/60 shake @enderror">
                                            @error('lastName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative" data-tour="codeMell">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">کد ملی</label>
                                            <input wire:model.blur="codeMell" type="tel" maxlength="10" placeholder="۱۰ رقم" inputmode="numeric" dir="ltr" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono tracking-wider @error('codeMell') border-rose-500/60 shake @enderror">
                                            @error('codeMell')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        @include('livewire.client.onboarding.partials._gender-avatar')

                                        <button type="submit" class="hidden" tabindex="-1">submit</button>
                                    </form>
                                </div>
                            </div>
                        </section>

                        {{-- STEP 3 --}}
                        <section x-show="$wire.currentStep === 3">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        </div>
                                        <div><h2 class="font-black text-lg">والدین و پایه</h2><p class="text-[11px] text-muted">برای گزارش‌گیری و ارتباط</p></div>
                                    </div>

                                    <form @submit.prevent="goNext()" class="space-y-4">
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره پدر</label>
                                            <input wire:model.blur="fatherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('fatherMobile') border-rose-500/60 shake @enderror">
                                            @error('fatherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره مادر</label>
                                            <input wire:model.blur="motherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('motherMobile') border-rose-500/60 shake @enderror">
                                            @error('motherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">پایه</label>
                                                <x-ui.select wire:model.live="grade" :options="$gradeOptions" placeholder="انتخاب پایه" />
                                            </div>
                                            @if($grade !== '9')
                                                <div class="relative" wire:key="field-m-{{ $grade }}">
                                                    <label class="block text-xs font-semibold mb-1.5 text-muted">رشته</label>
                                                    <x-ui.select wire:model="field" :options="$fieldOptions" placeholder="انتخاب رشته" />
                                                </div>
                                            @endif
                                        </div>

                                        @if($grade === 'graduate')
                                            <div class="flex items-start gap-2 rounded-xl bg-primary/5 border border-primary/20 px-3.5 py-3">
                                                <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                                <p class="text-[11px] text-muted leading-5">چون فارغ‌التحصیل هستی، برنامه‌ات بدون نیاز به برنامه‌ی کلاسی مدرسه طراحی می‌شود.</p>
                                            </div>
                                        @else
                                            <div class="relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">در حال حاضر مدرسه می‌روی؟</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <button type="button" wire:click="$set('attendsSchool', true)" class="rounded-xl px-4 py-3 text-sm font-bold border transition-colors {{ $attendsSchool ? 'bg-primary/10 border-primary text-primary' : 'glass-input border-border text-muted' }}">بله، می‌رم</button>
                                                    <button type="button" wire:click="$set('attendsSchool', false)" class="rounded-xl px-4 py-3 text-sm font-bold border transition-colors {{ !$attendsSchool ? 'bg-primary/10 border-primary text-primary' : 'glass-input border-border text-muted' }}">نه، نمی‌رم</button>
                                                </div>
                                            </div>
                                        @endif
                                        <button type="submit" class="hidden" tabindex="-1">submit</button>
                                    </form>
                                </div>
                            </div>
                        </section>

                        {{-- STEP 4 --}}
                        <section x-show="$wire.currentStep === 4">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        </div>
                                        <div><h2 class="font-black text-lg">مکان و حساب</h2><p class="text-[11px] text-muted">شماره برای ورود به سامانه</p></div>
                                    </div>

                                    <form @submit.prevent="goNext()" autocomplete="on" class="space-y-4">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">استان</label>
                                                <x-ui.select wire:model.live="stateId" :options="$stateOptions" :searchable="true" placeholder="انتخاب استان" search-placeholder="جستجوی استان..." />
                                                @error('stateId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="relative" wire:key="city-m-{{ $stateId }}">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">شهر</label>
                                                <div wire:loading wire:target="updatedStateId" class="skeleton w-full h-[42px] rounded-lg"></div>
                                                <div wire:loading.remove wire:target="updatedStateId">
                                                    <x-ui.select wire:model="cityId" :options="$cityOptions" :searchable="true" :disabled="(int) $stateId === 0" placeholder="انتخاب شهر" search-placeholder="جستجوی شهر..." />
                                                </div>
                                                @error('cityId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل (برای ورود)</label>
                                            <input wire:model.blur="mobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="username" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                            @error('mobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="relative" x-data="{ showPw: false }">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور</label>
                                            <div class="password-wrapper">
                                                <input wire:model.live.debounce.300ms="password" :type="showPw ? 'text' : 'password'" dir="ltr" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('password') border-rose-500/60 shake @enderror">
                                                <button type="button" class="eye-btn" @click="showPw = !showPw" tabindex="-1">
                                                    <svg x-show="!showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                                </button>
                                            </div>
                                            @error('password')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="relative" x-data="{ showPwc: false }">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">تکرار رمز</label>
                                            <div class="password-wrapper">
                                                <input wire:model.blur="passwordConf" :type="showPwc ? 'text' : 'password'" dir="ltr" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('passwordConf') border-rose-500/60 shake @enderror">
                                                <button type="button" class="eye-btn" @click="showPwc = !showPwc" tabindex="-1">
                                                    <svg x-show="!showPwc" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <svg x-show="showPwc" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                                </button>
                                            </div>
                                            @error('passwordConf')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <button type="submit" class="hidden" tabindex="-1">submit</button>
                                    </form>
                                </div>
                            </div>
                        </section>

                        {{-- STEP 5 — OTP --}}
                        <section x-show="$wire.currentStep === 5">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6 text-center">
                                    <div class="inline-block w-28 h-28 mb-2">{!! $svgOtp !!}</div>
                                    <h2 class="font-black text-xl mb-2">کد تأیید را وارد کنید</h2>
                                    <p class="text-sm text-muted mb-5 leading-7">کد ۶ رقمی به شماره‌ی <strong dir="ltr" class="text-primary">{{ $mobile }}</strong> ارسال شد.</p>

                                    <form @submit.prevent="$wire.verifyOtp()" autocomplete="off">
                                        <input wire:model="otpInput" type="text" maxlength="6" placeholder="------" inputmode="numeric" dir="ltr" x-init="$el.focus()" autocomplete="one-time-code" class="glass-input w-full text-center tracking-[0.6em] text-2xl font-mono rounded-2xl px-4 py-4 mb-3">

                                        @if($otpError)<div class="text-rose-500 text-xs mb-3">{{ $otpError }}</div>@endif

                                        <div class="flex items-center justify-between text-sm mb-2">
                                            @if($countdown > 0)
                                                <span class="text-muted text-xs">ارسال مجدد تا <span class="text-primary font-mono mx-1" x-text="$wire.countdown"></span> ثانیه</span>
                                            @else
                                                <button type="button" wire:click="resendOtp" class="text-primary hover:underline text-xs font-bold">ارسال مجدد کد</button>
                                            @endif

                                            <button type="submit" @mousedown="pressBtn($el)" wire:loading.attr="disabled" wire:target="verifyOtp" class="btn-press px-6 py-2.5 rounded-xl text-sm font-bold">
                                                <span wire:loading.remove wire:target="verifyOtp">تأیید کد</span>
                                                <span wire:loading wire:target="verifyOtp">در حال بررسی…</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

                        {{-- STEP 6 --}}
                        <section x-show="$wire.currentStep === 6"
                                 x-data="{ done: false, secs: 5 }"
                                 x-effect="if ($wire.currentStep === 6 && !done) { done = true; const t = setInterval(() => { if (--secs <= 0) clearInterval(t); }, 1000); setTimeout(() => $wire.startAssessments(), 5000); }">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6 text-center">
                                    <div class="inline-block w-28 h-28 mb-2">{!! $svgSuccess !!}</div>
                                    <h2 class="font-black text-2xl mb-2">🎉 تبریک! حساب شما ساخته شد</h2>
                                    <p class="text-sm text-muted leading-7">به جمعِ ستارگانِ SDFR خوش اومدی.<br>الان خودکار به آزمون شخصیت‌شناسی می‌ری.</p>
                                    <div class="mt-5 flex items-center justify-center gap-2 text-primary">
                                        <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="48" stroke-linecap="round" opacity="0.35"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="14 60" stroke-linecap="round"/></svg>
                                        <span class="text-sm font-semibold">انتقال در <span x-text="secs">۵</span> ثانیه…</span>
                                    </div>
                                    <button type="button" wire:click="startAssessments" class="btn-press mt-5 h-11 px-6 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                                        همین حالا شروع کن
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </main>

            <footer class="sticky bottom-0 px-4 pb-4 pt-2 bg-gradient-to-t from-background via-background/95 to-transparent" x-show="$wire.currentStep >= 2 && $wire.currentStep <= 4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="goPrev()" @mousedown="pressBtn($el)" x-show="$wire.currentStep > 2" :disabled="busy" class="btn-press-secondary h-12 px-5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        قبلی
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" @click="goNext()" @mousedown="pressBtn($el)" :disabled="busy" class="btn-press h-12 px-8 rounded-xl text-sm font-bold flex items-center gap-2">
                        <span x-show="!busy" class="flex items-center gap-2">
                            ادامه
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                        </span>
                        <span x-show="busy" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/></svg>
                            صبر کنید…
                        </span>
                    </button>
                </div>
            </footer>
        </div>


        {{-- ═══════════════ 🖥️ DESKTOP ═══════════════ --}}
        <div class="hidden md:block relative z-10 min-h-screen">

            @if ($generalError)
                <div class="max-w-7xl mx-auto px-8 mt-4">
                    <div class="rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 px-4 py-3 text-sm">{{ $generalError }}</div>
                </div>
            @endif

            <div x-show="$wire.currentStep >= 5" class="max-w-2xl mx-auto px-8 py-12">
                <section x-show="$wire.currentStep === 5">
                    <div class="train-border">
                        <div class="glass-card rounded-3xl p-10 text-center">
                            <div class="inline-block w-32 h-32 mb-3">{!! $svgOtp !!}</div>
                            <h2 class="font-black text-2xl mb-2">کد تأیید را وارد کنید</h2>
                            <p class="text-sm text-muted mb-6 leading-7">کد ۶ رقمی به <strong dir="ltr" class="text-primary">{{ $mobile }}</strong> ارسال شد.</p>
                            <form @submit.prevent="$wire.verifyOtp()" autocomplete="off">
                                <input wire:model="otpInput" type="text" maxlength="6" placeholder="------" inputmode="numeric" dir="ltr" x-init="$el.focus()" autocomplete="one-time-code" class="glass-input w-full text-center tracking-[0.7em] text-3xl font-mono rounded-2xl px-4 py-4 mb-3">
                                @if($otpError)<div class="text-rose-500 text-xs mb-3">{{ $otpError }}</div>@endif
                                <div class="flex items-center justify-between mt-5">
                                    @if($countdown > 0)
                                        <span class="text-muted text-sm">ارسال مجدد تا <span class="text-primary font-mono mx-1" x-text="$wire.countdown"></span> ثانیه</span>
                                    @else
                                        <button type="button" wire:click="resendOtp" class="text-primary hover:underline text-sm font-bold">ارسال مجدد کد</button>
                                    @endif
                                    <button type="submit" @mousedown="pressBtn($el)" wire:loading.attr="disabled" wire:target="verifyOtp" class="btn-press px-8 py-3 rounded-xl font-bold">
                                        <span wire:loading.remove wire:target="verifyOtp">تأیید کد</span>
                                        <span wire:loading wire:target="verifyOtp">در حال بررسی…</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <section x-show="$wire.currentStep === 6"
                         x-data="{ done: false, secs: 5 }"
                         x-effect="if ($wire.currentStep === 6 && !done) { done = true; const t = setInterval(() => { if (--secs <= 0) clearInterval(t); }, 1000); setTimeout(() => $wire.startAssessments(), 5000); }">
                    <div class="train-border">
                        <div class="glass-card rounded-3xl p-10 text-center">
                            <div class="inline-block w-32 h-32 mb-2">{!! $svgSuccess !!}</div>
                            <h2 class="font-black text-3xl mb-2">🎉 تبریک! حساب شما ساخته شد</h2>
                            <p class="text-muted leading-8">به جمعِ ستارگانِ SDFR خوش اومدی.<br>همین الان خودکار به آزمون شخصیت‌شناسی منتقل می‌شی.</p>
                            <div class="mt-6 flex items-center justify-center gap-2 text-primary">
                                <svg class="w-6 h-6 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="48" stroke-linecap="round" opacity="0.35"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="14 60" stroke-linecap="round"/></svg>
                                <span class="font-semibold">انتقال در <span x-text="secs">۵</span> ثانیه…</span>
                            </div>
                            <button type="button" wire:click="startAssessments" class="btn-press mt-6 h-12 px-8 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                                همین حالا شروع کن
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                            </button>
                        </div>
                    </div>
                </section>
            </div>

            <div x-show="$wire.currentStep < 5" class="max-w-7xl mx-auto px-8 pt-8 pb-12">
                <div class="grid grid-cols-12 gap-8 items-start">

                    <div class="col-span-5 sticky top-8 space-y-6">
                        <p class="text-muted leading-8 text-sm">پلتفرم هوشمند پایش مطالعه و مشاوره‌ی تخصصی برای دانش‌آموزان جدی. با تکمیل فرم روبه‌رو، حساب کاربری شما ساخته می‌شه و وارد یک هفته‌ی آزمایشی رایگان می‌شید.</p>

                        <div class="train-border">
                            <div class="relative glass-card rounded-3xl p-8">
                                <div class="w-full flex items-center justify-center">{!! $svgWelcome !!}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @foreach($features as $f)
                                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-secondary/50 border border-border">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 shrink-0">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $f['icon'] !!}</svg>
                                    </span>
                                    <div>
                                        <div class="font-bold text-xs">{{ $f['t'] }}</div>
                                        <div class="text-[10px] text-muted leading-5 mt-0.5">{{ $f['d'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-span-7">
                        <div class="train-border">
                            <form @submit.prevent="submitDesktopForm()" autocomplete="on" class="glass-card rounded-3xl p-8 space-y-7">

                                <div class="flex items-center gap-3 pb-5 border-b border-border">
                                    <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <div>
                                        <h2 class="font-black text-lg">فرم ثبت‌نام</h2>
                                        <p class="text-xs text-muted mt-0.5">اطلاعات زیر را تکمیل کنید تا حسابتون ساخته بشه</p>
                                    </div>
                                </div>

                                <fieldset class="space-y-4">
                                    <legend class="flex items-center gap-2 font-bold text-sm text-foreground mb-1">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-md bg-primary/10 text-primary text-[10px] font-black border border-primary/20">۱</span>
                                        اطلاعات شخصی
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative" data-tour="firstName">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام</label>
                                            <input wire:model.blur="firstName" type="text" placeholder="مثلاً علی" autocomplete="given-name" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('firstName') border-rose-500/60 shake @enderror">
                                            @error('firstName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام خانوادگی</label>
                                            <input wire:model.blur="lastName" type="text" placeholder="مثلاً محمدی" autocomplete="family-name" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('lastName') border-rose-500/60 shake @enderror">
                                            @error('lastName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="relative" data-tour="codeMell">
                                        <label class="block text-xs font-semibold mb-1.5 text-muted">کد ملی</label>
                                        <input wire:model.blur="codeMell" type="text" maxlength="10" placeholder="۱۰ رقم" inputmode="numeric" dir="ltr" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono tracking-wider @error('codeMell') border-rose-500/60 shake @enderror">
                                        @error('codeMell')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                    </div>

                                    @include('livewire.client.onboarding.partials._gender-avatar')
                                </fieldset>

                                <fieldset class="space-y-4 pt-5 border-border">
                                    <legend class="flex items-center gap-2 font-bold text-sm text-foreground mb-1">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-md bg-primary/10 text-primary text-[10px] font-black border border-primary/20">۲</span>
                                        والدین و پایه‌ی تحصیلی
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره پدر</label>
                                            <input wire:model.blur="fatherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('fatherMobile') border-rose-500/60 shake @enderror">
                                            @error('fatherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره مادر</label>
                                            <input wire:model.blur="motherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('motherMobile') border-rose-500/60 shake @enderror">
                                            @error('motherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">پایه</label>
                                            <x-ui.select wire:model.live="grade" :options="$gradeOptions" placeholder="انتخاب پایه" />
                                        </div>
                                        @if($grade !== '9')
                                            <div class="relative" wire:key="field-d-{{ $grade }}">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">رشته</label>
                                                <x-ui.select wire:model="field" :options="$fieldOptions" placeholder="انتخاب رشته" />
                                            </div>
                                        @endif

                                        @if($grade === 'graduate')
                                            <div class="col-span-2 flex items-start gap-2 rounded-xl bg-primary/5 border border-primary/20 px-3.5 py-3">
                                                <svg class="w-4 h-4 text-primary shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                                <p class="text-[11px] text-muted leading-5">چون فارغ‌التحصیل هستی، برنامه‌ات بدون نیاز به برنامه‌ی کلاسی مدرسه طراحی می‌شود.</p>
                                            </div>
                                        @else
                                            <div class="col-span-2 relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">در حال حاضر مدرسه می‌روی؟</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <button type="button" wire:click="$set('attendsSchool', true)" class="rounded-xl px-4 py-2.5 text-sm font-bold border transition-colors {{ $attendsSchool ? 'bg-primary/10 border-primary text-primary' : 'glass-input border-border text-muted' }}">بله، می‌رم</button>
                                                    <button type="button" wire:click="$set('attendsSchool', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold border transition-colors {{ !$attendsSchool ? 'bg-primary/10 border-primary text-primary' : 'glass-input border-border text-muted' }}">نه، نمی‌رم</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </fieldset>

                                <fieldset class="space-y-4 pt-5 border-border">
                                    <legend class="flex items-center gap-2 font-bold text-sm text-foreground mb-1">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-md bg-primary/10 text-primary text-[10px] font-black border border-primary/20">۳</span>
                                        مکان و رمز عبور
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">استان</label>
                                            <x-ui.select wire:model.live="stateId" :options="$stateOptions" :searchable="true" placeholder="انتخاب استان" search-placeholder="جستجوی استان..." />
                                            @error('stateId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative" wire:key="city-d-{{ $stateId }}">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شهر</label>
                                            <div wire:loading wire:target="updatedStateId" class="skeleton w-full h-[42px] rounded-lg"></div>
                                            <div wire:loading.remove wire:target="updatedStateId">
                                                <x-ui.select wire:model="cityId" :options="$cityOptions" :searchable="true" :disabled="(int) $stateId === 0" placeholder="انتخاب شهر" search-placeholder="جستجوی شهر..." />
                                            </div>
                                            @error('cityId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-span-2 relative" data-tour="mobile">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل (برای ورود)</label>
                                            <input wire:model.blur="mobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="username" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                            @error('mobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="relative" x-data="{ showPw: false }" data-tour="password">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور</label>
                                            <div class="password-wrapper">
                                                <input wire:model.live.debounce.300ms="password" :type="showPw ? 'text' : 'password'" dir="ltr" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('password') border-rose-500/60 shake @enderror">
                                                <button type="button" class="eye-btn" @click="showPw = !showPw" tabindex="-1">
                                                    <svg x-show="!showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                                </button>
                                            </div>
                                            @error('password')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="relative" x-data="{ showPwc: false }">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">تکرار رمز</label>
                                            <div class="password-wrapper">
                                                <input wire:model.blur="passwordConf" :type="showPwc ? 'text' : 'password'" dir="ltr" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('passwordConf') border-rose-500/60 shake @enderror">
                                                <button type="button" class="eye-btn" @click="showPwc = !showPwc" tabindex="-1">
                                                    <svg x-show="!showPwc" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    <svg x-show="showPwc" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                                </button>
                                            </div>
                                            @error('passwordConf')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="flex items-center justify-between gap-3 pt-5 border-border">
                                    <p class="text-[11px] text-muted leading-5 max-w-[50%]">با ارسال این فرم، یک کد تأیید روی شماره‌ی موبایلتون ارسال می‌شه.</p>
                                    <button type="submit" @mousedown="pressBtn($el)" :disabled="busy" class="btn-press h-12 px-8 rounded-xl font-bold text-sm flex items-center gap-2">
                                        <span x-show="!busy" class="flex items-center gap-2">
                                            ساخت حساب و دریافت کد
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                                        </span>
                                        <span x-show="busy" class="flex items-center gap-2">
                                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/></svg>
                                            صبر کنید…
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('script')
        <script>
            window.onboardingFlow = function () {
                return {
                    busy: false,
                    countdownTimer: null,
                    busyWatchdog: null,
                    touchStartX: 0,
                    touchEndX: 0,
                    tourShown: false,
                    livewireHookHandle: null,
                    boundNavigated: null,

                    init() {
                        this.busy = false;

                        this.startCountdownIfNeeded();

                        if (window.Livewire) {
                            try {
                                Livewire.on('start-countdown', () => this.startCountdownIfNeeded());
                                Livewire.on('step-validation-failed', () => { this.clearBusy(); });
                                Livewire.on('step-changed', () => {
                                    this.endTransition();
                                    this.$nextTick(() => this.maybeShowTour());
                                });

                                if (Livewire.hook) {
                                    this.livewireHookHandle = Livewire.hook('commit', ({ succeed, fail }) => {
                                        succeed(() => { setTimeout(() => this.clearBusy(), 250); });
                                        fail(() => { this.clearBusy(); });
                                    });
                                }
                            } catch (e) { console.warn('[onboarding] Livewire hook setup failed', e); }
                        }

                        this.boundNavigated = () => { this.clearBusy(); };
                        document.addEventListener('livewire:navigated', this.boundNavigated);

                        this.$nextTick(() => this.maybeShowTour());
                    },

                    destroy() {
                        if (this.countdownTimer) clearInterval(this.countdownTimer);
                        if (this.busyWatchdog) clearTimeout(this.busyWatchdog);
                        if (this.boundNavigated) document.removeEventListener('livewire:navigated', this.boundNavigated);
                    },

                    clearBusy() {
                        this.busy = false;
                        if (this.busyWatchdog) { clearTimeout(this.busyWatchdog); this.busyWatchdog = null; }
                    },

                    setBusy() {
                        this.busy = true;
                        if (this.busyWatchdog) clearTimeout(this.busyWatchdog);
                        this.busyWatchdog = setTimeout(() => {
                            console.warn('[onboarding] watchdog reset busy after 8s');
                            this.clearBusy();
                        }, 8000);
                    },

                    endTransition() {
                        setTimeout(() => { this.clearBusy(); }, 280);
                    },

                    pressBtn(el) {
                        if (!el) return;
                        el.classList.add('pressed');
                        setTimeout(() => el.classList.remove('pressed'), 120);
                        if (navigator.vibrate) navigator.vibrate(10);
                    },

                    maybeShowTour() {
                        if (this.tourShown) return;
                        if (localStorage.getItem('sdfr_onboarding_tour_done')) return;
                        if (typeof window.driver === 'undefined') return;

                        const isDesktop = window.matchMedia('(min-width: 768px)').matches;
                        if (!isDesktop && this.$wire.currentStep !== 2) return;
                        if (isDesktop && this.$wire.currentStep > 4) return;
                        if (!document.querySelector('[data-tour="firstName"]')) return;

                        this.tourShown = true;
                        const driver = window.driver.js.driver;
                        const tour = driver({
                            showProgress: true, allowClose: true,
                            nextBtnText: 'بعدی', prevBtnText: 'قبلی', doneBtnText: 'فهمیدم',
                            steps: [
                                { element: '[data-tour="firstName"]', popover: { title: 'اطلاعات اولیه', description: 'این اطلاعات روی کارنامه و گزارش‌ها درج می‌شه. حتماً فارسی و کامل وارد کنید.', side: isDesktop ? 'right' : 'bottom' } },
                                { element: '[data-tour="codeMell"]', popover: { title: 'کد ملی', description: 'کد ملی برای احراز هویت در سامانه استفاده می‌شه.', side: 'bottom' } },
                            ],
                            onDestroyed: () => { localStorage.setItem('sdfr_onboarding_tour_done', '1'); }
                        });
                        setTimeout(() => tour.drive(), 500);
                    },

                    startCountdownIfNeeded() {
                        if (this.countdownTimer) clearInterval(this.countdownTimer);
                        if (this.$wire.currentStep !== 5) return;
                        if (this.$wire.countdown <= 0) return;
                        this.countdownTimer = setInterval(() => {
                            if (this.$wire.countdown > 0) {
                                this.$wire.set('countdown', this.$wire.countdown - 1, false);
                            } else {
                                clearInterval(this.countdownTimer);
                                this.$wire.countdownFinished();
                            }
                        }, 1000);
                    },

                    goNext() {
                        if (this.busy) return;
                        this.setBusy();
                        this.$wire.next();
                    },

                    goPrev() {
                        if (this.busy) return;
                        if (this.$wire.currentStep <= 2) return;
                        this.setBusy();
                        this.$wire.previous().then(() => this.endTransition());
                    },

                    submitDesktopForm() {
                        if (this.busy) return;
                        this.setBusy();
                        this.$wire.submitAll();
                    },

                    handleTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        const diff = this.touchEndX - this.touchStartX;
                        if (Math.abs(diff) < 60) return;
                        if (['INPUT','TEXTAREA','SELECT','BUTTON'].includes(e.target.tagName)) return;
                        if (diff > 0 && this.$wire.currentStep > 2 && this.$wire.currentStep <= 4) {
                            this.goPrev();
                        }
                    },
                };
            };
        </script>
    @endpush
</div>
