<div>

    @assets
        <link rel="stylesheet" href="/client/assets/css/jalalidatepicker.min.css">
        <script src="/client/assets/js/jalalidatepicker.min.js" defer></script>
        <style>
            [x-cloak] { display: none !important; }
            input[data-jdp] { direction: ltr; text-align: center; letter-spacing: 0.04em; }
            .jdp-container { font-family: inherit !important; z-index: 70 !important; }

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
            .otp-stage-mode {
                background: #030303 !important;
                color: #f8fafc;
            }
            .otp-stage-mode .grid-figma {
                background-image:
                    linear-gradient(to right, rgba(255,255,255,.055) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255,255,255,.055) 1px, transparent 1px),
                    linear-gradient(to right, rgba(255,255,255,.035) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255,255,255,.035) 1px, transparent 1px);
                background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
                -webkit-mask-image: none;
                mask-image: none;
            }
            .otp-stage-mode .float-orb {
                display: none;
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
            @media (max-width: 768px) {
                .train-border::before {
                    display: none !important; /* خاموش کردن افکت سنگین در موبایل */
                }
                .train-border {
                    border: 1px solid hsl(var(--border) / 0.5); /* یک حاشیه ساده جایگزین */
                }
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
            .float-orb {
                animation: float-orb 9s ease-in-out infinite;
                will-change: transform; /* این خط معجزه می‌کند */
            }

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

            .otp-panel {
                max-width: 38rem;
                margin-inline: auto;
                position: relative;
                overflow: hidden;
                background: #0b0b0c;
                border: 1px solid rgba(148, 163, 184, 0.12);
                box-shadow: -2px 2px 0 rgba(37, 99, 235, 0.38), 0 30px 70px -45px rgba(37, 99, 235, 0.65);
            }
            .otp-panel::before {
                content: '';
                position: absolute;
                inset: -1px auto auto -1px;
                width: 54%;
                height: 2px;
                background: linear-gradient(90deg, rgb(37 99 235), rgb(147 197 253));
                pointer-events: none;
            }
            .otp-panel::after {
                content: '';
                position: absolute;
                inset: -1px auto auto -1px;
                width: 2px;
                height: 100%;
                background: linear-gradient(180deg, rgb(37 99 235), rgba(37, 99, 235, 0));
                pointer-events: none;
            }
            .otp-head-icon {
                width: 72px;
                height: 72px;
                border-radius: 999px;
                margin-inline: auto;
                background: rgba(59, 130, 246, 0.18);
                display: flex;
                align-items: center;
                justify-content: center;
                color: rgb(37 99 235);
            }
            .otp-head-phone {
                width: 32px;
                height: 40px;
                border: 4px solid currentColor;
                border-radius: 8px;
                position: relative;
            }
            .otp-head-phone::after {
                content: '';
                position: absolute;
                bottom: 5px;
                left: 50%;
                width: 5px;
                height: 5px;
                transform: translateX(-50%);
                border-radius: 999px;
                background: currentColor;
            }
            .otp-box-grid {
                direction: ltr;
                display: grid;
                grid-template-columns: repeat(6, minmax(0, 3.25rem));
                justify-content: center;
                gap: 0.55rem;
            }
            .otp-digit-box {
                width: 100%;
                height: 3.25rem;
                border: 1px solid hsl(var(--border));
                background: hsl(var(--secondary));
                color: #f8fafc;
                border-radius: 0.72rem;
                text-align: center;
                font-size: 1.35rem;
                font-weight: 900;
                letter-spacing: 0;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease, color .15s ease;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            }
            .otp-digit-box:focus {
                border-color: rgb(59 130 246);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.22);
            }
            .otp-digit-box--filled {
                background: rgb(37 99 235);
                border-color: rgb(37 99 235);
                color: #fff;
                box-shadow: 0 12px 24px -18px rgba(59, 130, 246, 0.9);
            }
            .otp-digit-box--error {
                color: rgb(248 113 113);
            }
            .otp-edit-link {
                color: #f8fafc;
                font-weight: 800;
            }
            .otp-note {
                color: #f8fafc;
                line-height: 1.9;
                font-weight: 700;
            }
            .otp-countdown {
                color: #f8fafc;
                font-weight: 800;
            }
            .otp-countdown button {
                color: #f8fafc;
                font-weight: 800;
            }
            .otp-secondary-btn {
                height: 3.4rem;
                padding-inline: 0.75rem;
                color: #f8fafc;
                background: transparent;
                font-weight: 900;
                white-space: nowrap;
            }
            @media (min-width: 768px) {
                .otp-panel { max-width: 38rem; }
                .otp-box-grid {
                    grid-template-columns: repeat(6, minmax(0, 3.75rem));
                    gap: 0.65rem;
                }
                .otp-digit-box {
                    height: 3.75rem;
                    font-size: 1.55rem;
                }
            }
            @media (max-width: 380px) {
                .otp-box-grid {
                    grid-template-columns: repeat(6, minmax(0, 2.6rem));
                    gap: 0.4rem;
                }
                .otp-digit-box {
                    height: 2.8rem;
                    border-radius: 0.6rem;
                }
            }

            @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
        </style>
    @endassets

    @php
        $gradeLabels = ['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم','graduate'=>'فارغ‌التحصیل'];
        $fieldLabels = ['math'=>'ریاضی','experimental'=>'تجربی','human'=>'انسانی'];
        $gradeOptions = [];
        foreach ($gradeLabels as $v => $l) { $gradeOptions[] = ['id' => (string) $v, 'name' => $l]; }
        $fieldOptions = [];
        foreach ($fieldLabels as $v => $l) { $fieldOptions[] = ['id' => $v, 'name' => $l]; }

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
        $boyAvatars = $maleAvatarOptions;
        $girlAvatars = $femaleAvatarOptions;
    @endphp

    <div class="relative min-h-screen overflow-hidden bg-background text-foreground" dir="rtl" x-data="onboardingFlow()" :class="$wire.currentStep === 5 ? 'otp-stage-mode' : ''">

        <div class="absolute inset-0 grid-figma pointer-events-none"></div>
        <div class="absolute top-20 -right-20 w-72 h-72 bg-primary/15 rounded-full blur-3xl float-orb pointer-events-none"></div>
        <div class="absolute bottom-20 -left-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl float-orb pointer-events-none" style="animation-delay: -3s"></div>

        {{-- ═══════════════ 🧑‍🚀 مودالِ انتخابِ آواتار (B1) ═══════════════ --}}
        {{-- ═══════════════ 🧑‍🚀 مودالِ انتخابِ آواتار (B1) ═══════════════ --}}
        <div x-data="{ openAv: false }"
             x-on:open-avatar.window="openAv = true"
             x-show="openAv" x-cloak
             class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center p-0 sm:p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="openAv = false"></div>
            <div class="relative w-full max-w-md glass-card rounded-t-[2rem] sm:rounded-3xl px-5 pt-4 pb-[calc(env(safe-area-inset-bottom)+1.25rem)] sm:p-6"
                 x-show="openAv"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95">
                <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-foreground/10 sm:hidden"></div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-lg">انتخاب آواتار</h3>
                    <button type="button" @click="openAv = false" class="w-8 h-8 rounded-lg bg-secondary/60 flex items-center justify-center hover:bg-secondary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-muted mb-5">یکی از آواتارها را برای پروفایلت انتخاب کن.</p>

                {{-- نمایش آنی آواتارهای پسرانه --}}
                <div class="grid grid-cols-3 gap-4" x-show="gender === 'male'">
                    @forelse($boyAvatars as $a)
                        <button type="button"
                                @click="avatar = '{{ $a }}'; openAv = false"
                                class="avatar-pick avatar-pick--boy"
                                :class="avatar === '{{ $a }}' ? 'avatar-pick--on' : ''">
                            <img src="{{ $a }}" alt="آواتار" loading="lazy">
                        </button>
                    @empty
                        <div class="col-span-3 rounded-2xl border border-dashed border-border px-4 py-6 text-center text-xs text-muted">
                            هنوز آواتار پسرانه‌ای تعریف نشده است.
                        </div>
                    @endforelse
                </div>

                {{-- نمایش آنی آواتارهای دخترانه --}}
                <div class="grid grid-cols-3 gap-4" x-show="gender === 'female'">
                    @forelse($girlAvatars as $a)
                        <button type="button"
                                @click="avatar = '{{ $a }}'; openAv = false"
                                class="avatar-pick avatar-pick--girl"
                                :class="avatar === '{{ $a }}' ? 'avatar-pick--on' : ''">
                            <img src="{{ $a }}" alt="آواتار" loading="lazy">
                        </button>
                    @empty
                        <div class="col-span-3 rounded-2xl border border-dashed border-border px-4 py-6 text-center text-xs text-muted">
                            هنوز آواتار دخترانه‌ای تعریف نشده است.
                        </div>
                    @endforelse
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

                                        <div class="gap-3">
                                            <div class="relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">تاریخ تولد</label>
                                                <input wire:model.blur="birthDate" type="text" re data-jdp data-jdp-max-date="today" placeholder="۱۳۸۰/۰۱/۰۱" dir="ltr" inputmode="none"
                                                       autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm cursor-pointer @error('birthDate') border-rose-500/60 shake @enderror">
                                                @error('birthDate')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                            </div>
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
                                            <input wire:model.blur="fatherMobile" type="tel" maxlength="11" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('fatherMobile') border-rose-500/60 shake @enderror">
                                            @error('fatherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره مادر</label>
                                            <input wire:model.blur="motherMobile" type="tel" maxlength="11" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('motherMobile') border-rose-500/60 shake @enderror">
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
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg>
                                        </div>
                                        <div><h2 class="font-black text-lg">حساب کاربری</h2><p class="text-[11px] text-muted">شماره و رمز ورود به سامانه</p></div>
                                    </div>

                                    <form @submit.prevent="goNext()" autocomplete="on" class="space-y-4">
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل (برای ورود)</label>
                                            <input wire:model.blur="mobile" type="tel" maxlength="11" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="username" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                            @error('mobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="relative" x-data="{ showPw: false }">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور</label>
                                            <div class="password-wrapper">
                                                <input wire:model.live.debounce.300ms="password" :type="showPw ? 'text' : 'password'" dir="rtl" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('password') border-rose-500/60 shake @enderror">
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
                                                <input wire:model.blur="passwordConf" :type="showPwc ? 'text' : 'password'" dir="rtl" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('passwordConf') border-rose-500/60 shake @enderror">
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
                            <div>
                                <div class="otp-panel rounded-[2rem] px-6 py-10 text-center">
                                    <div class="otp-head-icon mb-5">
                                        <span class="otp-head-phone"></span>
                                    </div>
                                    <h2 class="font-black text-2xl text-white mb-4">لطفا کد ارسال شده را وارد کنید</h2>
                                    <p class="text-sm text-white/40 mb-2">کد تأیید به این شماره ارسال شد</p>
                                    <button type="button" wire:click="previous" class="otp-edit-link inline-flex text-sm mb-8 text">ویرایش شماره <span dir="ltr" class="mr-1">{{ $mobile }}</span></button>

                                    <form @submit.prevent="$wire.verifyOtp()" autocomplete="off">
                                        <div class="otp-box-grid mb-5" x-data="otpCodeBoxes(@entangle('otpInput').live, @entangle('otpError').live)" x-init="init()">
                                            <template x-for="(_, index) in digits" :key="index">
                                                <input
                                                    data-otp-digit
                                                    type="tel"
                                                    maxlength="1"
                                                    inputmode="numeric"
                                                    autocomplete="one-time-code"
                                                    class="otp-digit-box"
                                                    :class="{ 'otp-digit-box--filled': digits[index], 'otp-digit-box--error': digits[index] && errorMessage }"
                                                    x-model="digits[index]"
                                                    @input="handleInput(index, $event)"
                                                    @keydown.backspace="handleBackspace(index, $event)"
                                                    @paste.prevent="handlePaste($event)"
                                                    :aria-label="`رقم ${index + 1} کد تایید`"
                                                >
                                            </template>
                                        </div>

                                        @if($otpError)<div class="text-rose-500 text-xs mb-3">{{ $otpError }}</div>@endif

                                        <div class="otp-note text-xs mb-5 flex items-start justify-center gap-2 text-right">
                                            <svg class="w-4 h-4 mt-0.5 flex-none text-blue-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"></circle><path d="M12 8h.01"></path><path d="M11 12h1v4h1"></path></svg>
                                            <p>در صورتی که کد تأیید را دریافت نکردید، بخش اسپم پیامک‌های تلفن همراه خود را بررسی کنید.</p>
                                        </div>

                                        <div class="otp-countdown text-sm mb-8">
                                            @if($countdown > 0)
                                                <span>ارسال مجدد (<span x-text="$wire.countdown"></span>)</span>
                                            @else
                                                <button type="button" wire:click="resendOtp">ارسال مجدد کد</button>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <button type="button" wire:click="previous" class="otp-secondary-btn shrink-0">برگشت</button>
                                            <button type="submit" @mousedown="pressBtn($el)" wire:loading.attr="disabled" wire:target="verifyOtp" class="btn-press h-14 flex-1 rounded-2xl text-lg font-black">
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
                                    <h2 class="font-black text-2xl mb-2">🎉  حساب شما با موفقیت ساخته شد</h2>
                                    <p class="text-sm text-muted leading-7">شرط ورود به خانواده SDFR آنالیز فردی هست!<br>الان خودکار به آزمون Mindet منتقل میشی.</p>
                                    <div class="mt-5 flex items-center justify-center gap-2 text-primary">
                                        <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="48" stroke-linecap="round" opacity="0.35"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="14 60" stroke-linecap="round"/></svg>
                                        <span class="text-sm font-semibold">انتقال در <span x-text="secs">۵</span> ثانیه…</span>
                                    </div>
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
                    <div>
                        <div class="otp-panel rounded-[2.25rem] px-10 py-16 text-center">
                            <div class="otp-head-icon mb-6">
                                <span class="otp-head-phone"></span>
                            </div>
                            <h2 class="font-black text-3xl text-white mb-5">لطفا کد ارسال شده را وارد کنید</h2>
                            <p class="text-base text-white/40 mb-2">کد تأیید به این شماره ارسال شد</p>
                            <button type="button" wire:click="previous" class="otp-edit-link inline-flex text-base mb-9">ویرایش شماره <span dir="ltr" class="mr-1">{{ $mobile }}</span></button>
                            <form @submit.prevent="$wire.verifyOtp()" autocomplete="off">
                                <div class="otp-box-grid mb-6" x-data="otpCodeBoxes(@entangle('otpInput').live, @entangle('otpError').live)" x-init="init()">
                                    <template x-for="(_, index) in digits" :key="index">
                                        <input
                                            data-otp-digit
                                            type="tel"
                                            maxlength="1"
                                            inputmode="numeric"
                                            autocomplete="one-time-code"
                                            class="otp-digit-box"
                                            :class="{ 'otp-digit-box--filled': digits[index], 'otp-digit-box--error': digits[index] && errorMessage }"
                                            x-model="digits[index]"
                                            @input="handleInput(index, $event)"
                                            @keydown.backspace="handleBackspace(index, $event)"
                                            @paste.prevent="handlePaste($event)"
                                            :aria-label="`رقم ${index + 1} کد تایید`"
                                        >
                                    </template>
                                </div>

                                @if($otpError)<div class="text-rose-500 text-sm mb-4">{{ $otpError }}</div>@endif

                                <div class="otp-note text-sm mb-7 flex items-start justify-center gap-2 text-right max-w-md mx-auto">
                                    <svg class="w-5 h-5 mt-0.5 flex-none text-blue-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"></circle><path d="M12 8h.01"></path><path d="M11 12h1v4h1"></path></svg>
                                    <p>در صورتی که کد تأیید را دریافت نکردید، بخش اسپم پیامک‌های تلفن همراه خود را بررسی کنید.</p>
                                </div>

                                <div class="otp-countdown text-base mb-8">
                                    @if($countdown > 0)
                                        <span>ارسال مجدد (<span x-text="$wire.countdown"></span>)</span>
                                    @else
                                        <button type="button" wire:click="resendOtp">ارسال مجدد کد</button>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4">
                                    <button type="button" wire:click="previous" class="otp-secondary-btn shrink-0 text-lg">برگشت</button>
                                    <button type="submit" @mousedown="pressBtn($el)" wire:loading.attr="disabled" wire:target="verifyOtp" class="btn-press h-14 flex-1 rounded-2xl text-lg font-black">
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

                                    <div class="gap-4">
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">تاریخ تولد</label>
                                            <input wire:model.blur="birthDate" type="text" data-jdp data-jdp-max-date="today" placeholder="۱۳۸۰/۰۱/۰۱" dir="ltr" inputmode="none" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm cursor-pointer @error('birthDate') border-rose-500/60 shake @enderror">
                                            @error('birthDate')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
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
                                            <input wire:model.blur="fatherMobile" type="tel" maxlength="11" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('fatherMobile') border-rose-500/60 shake @enderror">
                                            @error('fatherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره مادر</label>
                                            <input wire:model.blur="motherMobile" type="tel" maxlength="11" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="off" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('motherMobile') border-rose-500/60 shake @enderror">
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
                                        حساب کاربری و رمز عبور
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-2 relative" data-tour="mobile">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل (برای ورود)</label>
                                            <input wire:model.blur="mobile" type="tel" maxlength="11" placeholder="09..." dir="ltr" inputmode="numeric" autocomplete="username" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                            @error('mobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="relative" x-data="{ showPw: false }" data-tour="password">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور</label>
                                            <div class="password-wrapper">
                                                <input wire:model.live.debounce.300ms="password" :type="showPw ? 'text' : 'password'" dir="rtl" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('password') border-rose-500/60 shake @enderror">
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
                                                <input wire:model.blur="passwordConf" :type="showPwc ? 'text' : 'password'" dir="rtl" autocomplete="new-password" class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('passwordConf') border-rose-500/60 shake @enderror">
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
    @script
    <script>
        window.otpCodeBoxes = function (model, errorModel) {
            return {
                otpValue: model,
                errorMessage: errorModel,
                digits: Array(6).fill(''),
                verifying: false,

                init() {
                    this.syncFromValue();
                    this.$watch('otpValue', () => this.syncFromValue());
                    this.$nextTick(() => {
                        if (this.$el.offsetParent !== null) {
                            this.focusDigit(0);
                        }
                    });
                },

                normalize(value) {
                    const persian = '۰۱۲۳۴۵۶۷۸۹';
                    const arabic = '٠١٢٣٤٥٦٧٨٩';

                    return String(value || '')
                        .replace(/[۰-۹]/g, digit => String(persian.indexOf(digit)))
                        .replace(/[٠-٩]/g, digit => String(arabic.indexOf(digit)))
                        .replace(/\D/g, '')
                        .slice(0, 6);
                },

                syncFromValue() {
                    const normalized = this.normalize(this.otpValue);
                    if (normalized === this.digits.join('')) {
                        return;
                    }

                    this.digits = Array.from({ length: 6 }, (_, index) => normalized[index] || '');
                },

                commit() {
                    this.otpValue = this.normalize(this.digits.join(''));
                    this.syncFromValue();
                },

                clearError() {
                    if (this.errorMessage) {
                        this.errorMessage = '';
                    }
                },

                focusDigit(index) {
                    const input = this.$el.querySelectorAll('[data-otp-digit]')[index];
                    if (!input) {
                        return;
                    }

                    input.focus();
                    input.select();
                },

                fillFrom(value, startIndex = 0) {
                    const chars = this.normalize(value).split('');
                    this.clearError();
                    chars.forEach((char, offset) => {
                        const targetIndex = startIndex + offset;
                        if (targetIndex < this.digits.length) {
                            this.digits[targetIndex] = char;
                        }
                    });

                    this.commit();
                    this.focusDigit(Math.min(startIndex + chars.length, this.digits.length - 1));
                    this.maybeAutoSubmit();
                },

                handleInput(index, event) {
                    const value = this.normalize(event.target.value);
                    this.clearError();

                    if (value.length > 1) {
                        this.fillFrom(value, index);
                        return;
                    }

                    this.digits[index] = value;
                    this.commit();

                    if (value && index < this.digits.length - 1) {
                        this.focusDigit(index + 1);
                    }

                    this.maybeAutoSubmit();
                },

                handleBackspace(index, event) {
                    this.clearError();

                    if (this.digits[index]) {
                        event.preventDefault();
                        this.digits[index] = '';
                        this.commit();
                        return;
                    }

                    if (index > 0) {
                        event.preventDefault();
                        this.focusDigit(index - 1);
                    }
                },

                handlePaste(event) {
                    this.digits = Array(6).fill('');
                    this.fillFrom(event.clipboardData.getData('text'), 0);
                },

                maybeAutoSubmit() {
                    if (this.verifying || this.normalize(this.digits.join('')).length !== 6) {
                        return;
                    }

                    this.verifying = true;
                    this.$nextTick(() => {
                        Promise.resolve(this.$wire.verifyOtp()).finally(() => {
                            this.verifying = false;
                        });
                    });
                },
            };
        };

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

                // 🟢 این دو خط را برای اتصال آنی لایو‌وایر و آلپاین اضافه کن:
                gender: @entangle('gender'),
                avatar: @entangle('avatar'),

                init() {
                    this.busy = false;

                    this.initDatePicker();

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

                initDatePicker() {
                    const start = () => {
                        if (typeof jalaliDatepicker !== 'undefined') {
                            try {
                                jalaliDatepicker.startWatch({
                                    persianDigits: true,
                                    showTodayBtn: false,
                                    showEmptyBtn: true,
                                    time: false,
                                    autoHide: true,
                                    zIndex: 100,
                                });
                            } catch (e) { console.warn('[jdp] init failed', e); }
                        } else {
                            setTimeout(start, 200);
                        }
                    };
                    start();
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
    @endscript
</div>
