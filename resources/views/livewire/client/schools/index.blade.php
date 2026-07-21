<div>
    @assets
    <style>
        /* ---------- Smooth scroll ---------- */
        html {
            scroll-behavior: smooth;
        }

        /* ---------- Custom scrollbar ---------- */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: hsl(var(--background));
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, hsl(var(--primary) / 0.5), hsl(var(--primary) / 0.2));
            border-radius: 999px;
            border: 2px solid hsl(var(--background));
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, hsl(var(--primary) / 0.8), hsl(var(--primary) / 0.4));
        }

        /* ---------- Grid background pattern ---------- */
        .grid-bg {
            background-image: linear-gradient(to right, hsl(var(--border) / 0.4) 1px, transparent 1px),
            linear-gradient(to bottom, hsl(var(--border) / 0.4) 1px, transparent 1px);
            background-size: 48px 48px;
            -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 30%, #000 40%, transparent 100%);
            mask-image: radial-gradient(ellipse 80% 60% at 50% 30%, #000 40%, transparent 100%);
        }

        .grid-bg-sm {
            background-image: linear-gradient(to right, hsl(var(--border) / 0.35) 1px, transparent 1px),
            linear-gradient(to bottom, hsl(var(--border) / 0.35) 1px, transparent 1px);
            background-size: 28px 28px;
            -webkit-mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, #000 30%, transparent 100%);
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, #000 30%, transparent 100%);
        }

        /* ---------- Glass cards ---------- */
        .glass {
            background: hsl(var(--background) / 0.55);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
            border: 1px solid hsl(var(--border) / 0.6);
        }

        .glass-strong {
            background: hsl(var(--background) / 0.75);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid hsl(var(--border) / 0.7);
        }

        /* ---------- Floating blobs ---------- */
        @keyframes float-slow {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -30px) scale(1.05); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-25px, 20px) scale(1.08); }
        }

        .blob-1 { animation: float-slow 12s ease-in-out infinite; }
        .blob-2 { animation: float-reverse 14s ease-in-out infinite; }

        /* ---------- Shimmer ---------- */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .shimmer-text {
            background: linear-gradient(90deg, hsl(var(--foreground)) 0%, hsl(var(--primary)) 50%, hsl(var(--foreground)) 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 4s linear infinite;
        }

        /* ---------- Floaty ---------- */
        @keyframes floaty {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .floaty { animation: floaty 5s ease-in-out infinite; }

        /* ---------- Glow on hover ---------- */
        .glow-on-hover { position: relative; transition: transform 0.3s ease, border-color 0.3s ease; }
        .glow-on-hover::after {
            content: ''; position: absolute; inset: -1px; border-radius: inherit;
            background: linear-gradient(135deg, hsl(var(--primary) / 0.5), transparent 60%);
            opacity: 0; transition: opacity 0.3s ease; pointer-events: none; z-index: -1;
        }
        .glow-on-hover:hover { transform: translateY(-3px); }
        .glow-on-hover:hover::after { opacity: 1; }

        /* ---------- Grow bar ---------- */
        @keyframes grow-bar { from { width: 0; } }
        .grow-bar { animation: grow-bar 1.5s ease-out forwards; }

        /* =====================================================
           NEW: Orbiting blue orb under sections
           ===================================================== */
        .orbit-wrap { position: relative; }
        .orbit-wrap .orb-track {
            position: absolute; bottom: -32px; left: 0; right: 0; height: 24px; pointer-events: none; z-index: 5;
        }
        .orbit-wrap .orb-track::before {
            content: ''; position: absolute; top: 50%; left: 8%; right: 8%; height: 1px;
            background: linear-gradient(to left, transparent, hsl(217 91% 60% / 0.5), transparent);
            transform: translateY(-50%);
        }
        .orbit-wrap .orb {
            position: absolute; top: 50%; right: 0; width: 14px; height: 14px; margin-top: -7px; border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #93c5fd, #3b82f6 55%, #1d4ed8);
            box-shadow: 0 0 12px rgba(96, 165, 250, 0.9), 0 0 24px rgba(59, 130, 246, 0.6), 0 0 40px rgba(37, 99, 235, 0.4);
            animation: orbit-rtl 6s linear infinite, glow-pulse 2.5s ease-in-out infinite;
        }
        .orbit-wrap .orb-trail {
            position: absolute; top: 50%; right: 0; width: 7px; height: 7px; margin-top: -3.5px; border-radius: 999px;
            background: #93c5fd; box-shadow: 0 0 8px rgba(147, 197, 253, 0.8); opacity: 0.5;
            animation: orbit-rtl 6s linear infinite; animation-delay: -0.35s;
        }

        @keyframes orbit-rtl {
            0% { right: 0; transform: translateY(0) scale(1); }
            25% { right: 50%; transform: translateY(-8px) scale(1.2); }
            50% { right: calc(100% - 14px); transform: translateY(0) scale(1); }
            75% { right: 50%; transform: translateY(8px) scale(0.8); }
            100% { right: 0; transform: translateY(0) scale(1); }
        }

        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 12px rgba(96, 165, 250, 0.9), 0 0 24px rgba(59, 130, 246, 0.6); }
            50% { box-shadow: 0 0 20px rgba(96, 165, 250, 1), 0 0 40px rgba(59, 130, 246, 0.8), 0 0 60px rgba(37, 99, 235, 0.5); }
        }

        /* =====================================================
           NEW: Side floating decorations
           ===================================================== */
        .side-deco { position: fixed; z-index: 1; pointer-events: none; opacity: 0.2; }
        .side-deco-right { right: 24px; top: 25%; animation: side-float-1 7s ease-in-out infinite; }
        .side-deco-left { left: 24px; top: 60%; animation: side-float-2 9s ease-in-out infinite; }
        .side-deco-right-2 { right: 40px; top: 75%; animation: side-float-1 11s ease-in-out infinite; }

        @keyframes side-float-1 { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-40px) rotate(8deg); } }
        @keyframes side-float-2 { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(30px) rotate(-10deg); } }

        @media (max-width: 1024px) { .side-deco { display: none; } }

        /* =====================================================
           NEW: Reveal on scroll
           ===================================================== */
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1); will-change: opacity, transform; }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }
        .reveal-right { opacity: 0; transform: translateX(40px); transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-right.is-visible { opacity: 1; transform: translateX(0); }
        .reveal-left { opacity: 0; transform: translateX(-40px); transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-left.is-visible { opacity: 1; transform: translateX(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* Reduce motion accessibility */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; scroll-behavior: auto !important; }
        }
    </style>
    @endassets

    {{-- ===== Side floating decorations (fixed, RTL aware) ===== --}}
    <svg class="side-deco side-deco-right w-12 h-12 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
    </svg>
    <svg class="side-deco side-deco-left w-10 h-10 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 6v6l4 2"/>
    </svg>
    <svg class="side-deco side-deco-right-2 w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
    </svg>

    {{-- ✅ این Wrapper باعث می‌شود هیچکدام از انیمیشن‌ها در موبایل اسکرول افقی ایجاد نکنند --}}
    <div class="overflow-x-hidden w-full pb-16">
        <div dir="rtl" class="max-w-7xl mx-auto px-4 space-y-16 md:space-y-24 mt-8">

            {{-- =========================== HERO =========================== --}}
            <section class="relative rounded-3xl glass orbit-wrap reveal">
                <div class="overflow-hidden rounded-3xl relative">
                    <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                    <div class="absolute inset-0 pointer-events-none overflow-hidden">
                        <div class="blob-1 absolute -top-32 -right-32 w-96 h-96 bg-primary/30 rounded-full blur-3xl"></div>
                        <div class="blob-2 absolute -bottom-32 -left-20 w-[28rem] h-[28rem] bg-primary/15 rounded-full blur-3xl"></div>
                    </div>
                    <svg class="absolute top-6 left-6 w-24 h-24 text-primary/20 pointer-events-none" viewBox="0 0 100 100" fill="currentColor">
                        @for ($i = 0; $i < 5; $i++)
                            @for ($j = 0; $j < 5; $j++)
                                <circle cx="{{ 10 + $i * 20 }}" cy="{{ 10 + $j * 20 }}" r="1.5"/>
                            @endfor
                        @endfor
                    </svg>

                    <div class="relative grid md:grid-cols-12 gap-8 md:gap-10 p-6 md:p-14">
                        <div class="md:col-span-7 space-y-7 reveal-right">
                            <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-2">
                            <span class="relative flex w-2 h-2">
                                <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                <span class="relative inline-flex w-2 h-2 bg-primary rounded-full"></span>
                            </span>
                                <span class="font-semibold text-xs text-foreground">طرح ویژه‌ی همکاری با مدارس</span>
                            </div>

                            <h1 class="font-black text-3xl md:text-5xl text-foreground " style="line-height: 1.5">
                                همراه مدرسه‌ی شما برای
                                <span class="shimmer-text ">ارتقای کیفیت آموزشی</span>
                                دانش‌آموزان
                            </h1>

                            <p class="font-medium text-sm md:text-base text-muted leading-8 max-w-2xl">
                                پلتفرم <span class="font-black text-foreground">SDFR</span>
                                با ارائه‌ی زیرساخت یکپارچه‌ی پایش مطالعه، برنامه‌ریزی هفتگی،
                                مشاوره‌ی تخصصی و گزارش‌های لحظه‌ای، مدیران مدارس را در رصد دقیق
                                عملکرد دانش‌آموزان و ارتقای پیشرفت تحصیلی همراهی می‌کند.
                            </p>

                            <div class="flex flex-wrap items-center gap-5 pt-4">
                                <div class="flex -space-x-2 space-x-reverse">
                                    @foreach (['#6366f1','#8b5cf6','#ec4899','#f59e0b'] as $c)
                                        <div class="w-8 h-8 rounded-full border-2 border-background" style="background: {{ $c }}"></div>
                                    @endforeach
                                </div>
                                <div class="text-xs">
                                    <div class="font-bold text-foreground">+۲۰۰ مدرسه</div>
                                    <div class="text-muted">به ما اعتماد کرده‌اند</div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-5 flex items-center justify-center reveal-left">
                            <div class="relative w-full max-w-sm aspect-square floaty">
                                <div class="absolute inset-0 bg-gradient-to-tr from-primary/40 via-primary/10 to-transparent rounded-3xl blur-2xl"></div>
                                <div class="absolute inset-4 glass-strong rounded-2xl flex flex-col items-center justify-center text-center p-6 space-y-4">
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-primary/20 rounded-2xl blur-xl"></div>
                                        <div class="relative flex items-center justify-center w-20 h-20 bg-primary/10 rounded-2xl border border-primary/20">
                                            <svg class="w-10 h-10 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="font-black text-foreground text-lg">قرارداد رسمی مدارس</h3>
                                    <p class="font-medium text-xs text-muted leading-6">
                                        دسترسی اختصاصی مدیران به داشبورد گزارش‌گیری دانش‌آموزان مدرسه و پشتیبانی تخصصی در طول مدت قرارداد.
                                    </p>
                                    <div class="grid grid-cols-2 gap-3 w-full pt-2 border-t border-border">
                                        <div class="text-center">
                                            <div class="font-black text-primary text-lg">+۹۸٪</div>
                                            <div class="text-[10px] text-muted">رضایت</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-black text-primary text-lg">۲۴/۷</div>
                                            <div class="text-[10px] text-muted">پشتیبانی</div>
                                        </div>
                                    </div>
                                </div>
                                <svg class="absolute -top-4 -left-4 w-16 h-16 text-primary/40" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="50" cy="50" r="40" stroke-dasharray="4 4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="orb-track">
                    <span class="orb"></span>
                    <span class="orb-trail"></span>
                </div>
            </section>

            {{-- ===================== BRAND / SDFR ===================== --}}
            <section class="relative space-y-10 reveal">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>

                <div class="text-center space-y-3">
                    <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                        <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>
                        </svg>
                        <span class="font-semibold text-xs text-foreground">معنای SDFR</span>
                    </div>
                    <h2 class="font-black text-2xl md:text-3xl text-foreground ">
                        چهار ستون اصلی <span class="shimmer-text">الگوی آموزشی</span> ما
                    </h2>
                    <p class="font-medium text-sm text-muted max-w-2xl mx-auto leading-7">
                        نام برند ما از چهار اصل بنیادین که هویت آموزشی SDFR را شکل می‌دهد گرفته شده است.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-5" dir="ltr">
                    @php
                        $pillars = [
                            ['letter' => 'S', 'en' => 'Specific',   'fa' => 'منحصر به فرد',   'desc' => 'برنامه‌ریزی اختصاصی بر اساس وضعیت هر دانش‌آموز.'],
                            ['letter' => 'D', 'en' => 'Discussion', 'fa' => 'مبحثی',          'desc' => 'تمرکز بر مباحث درسی و یادگیری عمیق و گام‌به‌گام.'],
                            ['letter' => 'F', 'en' => 'Flexible',   'fa' => 'انعطاف‌پذیر',     'desc' => 'انطباق برنامه با شرایط واقعی دانش‌آموز و مدرسه.'],
                            ['letter' => 'R', 'en' => 'Reportage',  'fa' => 'گزارش‌محور',      'desc' => 'گزارش‌های دقیق و دوره‌ای برای مدیران و اولیا.'],
                        ];
                    @endphp
                    @foreach($pillars as $i => $p)
                        <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 relative overflow-hidden reveal reveal-delay-{{ $i + 1 }}" dir="rtl">
                            <span class="absolute -bottom-2 font-black text-[10rem] leading-none text-primary/5 select-none pointer-events-none" style="left: 1.75rem">{{ $p['letter'] }}</span>
                            <div class="relative flex items-center justify-between">
                                <span class="font-medium text-[10px] text-muted uppercase tracking-wider">{{ $p['en'] }}</span>
                            </div>
                            <div class="relative font-black text-foreground text-[21px] max-w-[60%]">{{ $p['fa'] }}</div>
                            <p class="relative font-medium text-xs text-muted leading-6 max-w-[60%]">{{ $p['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ========================= STATS ========================= --}}
            <section class="relative rounded-3xl glass orbit-wrap reveal">
                <div class="overflow-hidden rounded-3xl relative p-4 md:p-8">
                    <div class="absolute inset-0 grid-bg-sm pointer-events-none"></div>
                    <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-40 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        @php
                            $stats = [
                                ['v' => '+۱۰٬۰۰۰', 'l' => 'دانش‌آموز فعال', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                                ['v' => '+۲۰۰',    'l' => 'مدرسه‌ی همکار',  'icon' => '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>'],
                                ['v' => '+۱۵۰',    'l' => 'مشاور تخصصی',    'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                                ['v' => '۹۸٪',     'l' => 'رضایت مدیران',   'icon' => '<polyline points="20 6 9 17 4 12"/>'],
                            ];
                        @endphp
                        @foreach($stats as $i => $s)
                            <div class="relative text-center space-y-2 p-2 sm:p-4 rounded-2xl reveal reveal-delay-{{ $i + 1 }}">
                                <div class="inline-flex items-center justify-center w-10 h-10 bg-primary/10 text-primary rounded-xl mb-2">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $s['icon'] !!}
                                    </svg>
                                </div>
                                <div class="font-black text-foreground text-xl md:text-3xl">{{ $s['v'] }}</div>
                                <div class="font-medium text-[11px] sm:text-xs text-muted">{{ $s['l'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="orb-track">
                    <span class="orb"></span>
                    <span class="orb-trail"></span>
                </div>
            </section>
            {{-- ====================== FEATURES ====================== --}}
            <section id="features" class="relative space-y-10 scroll-mt-24 reveal">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
                <div class="text-center space-y-3 max-w-3xl mx-auto">
                    <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                        <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
                        </svg>
                        <span class="font-semibold text-xs text-foreground">امکانات حرفه‌ای</span>
                    </div>
                    <h2 class="font-black text-2xl md:text-3xl text-foreground">امکانات <span class="shimmer-text">پلتفرم</span> برای <span class="shimmer-text">دانش‌آموزان </span>مدرسه</h2>
                    <p class="font-medium text-sm text-muted leading-7">
                        مجموعه‌ای از ابزارهای حرفه‌ای که در داشبورد اختصاصی هر دانش‌آموز در دسترس قرار می‌گیرد و مدیران مدرسه می‌توانند نتایج آن را به‌صورت گزارش‌های منسجم دریافت کنند.
                    </p>
                </div>

                @php
                    $features = [
                        ['title' => 'داشبورد هوشمند', 'desc' => 'نمایش وضعیت لحظه‌ای ساعت مطالعه، گزارش‌های روزانه، برنامه‌ی امروز و اطلاعات پشتیبان.', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>'],
                        ['title' => 'ثبت ساعت مطالعه', 'desc' => 'تایمر هوشمند ثبت ساعت مطالعه برای هر بخش از برنامه‌ی هفتگی، با امکان رصد دقیق زمان مفید.', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                        ['title' => 'برنامه‌ی هفتگی اختصاصی', 'desc' => 'تدوین برنامه‌ی مطالعاتی هفتگی توسط مشاور، با جزئیات کامل ساعت، آزمون و درس.', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                        ['title' => 'گزارش‌های روزانه‌ی مطالعه', 'desc' => 'ثبت گزارش روزانه‌ی دانش‌آموز شامل دروس خوانده‌شده، آزمون‌ها و جلسات جبرانی.', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>'],

                        // ✅ در این بخش لینک فایل اضافه شده است (می‌توانید مسیر فایل را در file_link تغییر دهید)
                        ['title' => 'کارنامه و گزارش پیشرفت', 'desc' => 'کارنامه‌ی ماهانه و نمودارهای پیشرفت تحصیلی برای شناسایی نقاط ضعف و قوت.', 'icon' => '<line x1="3" y1="3" x2="3" y2="21"/><line x1="3" y1="21" x2="21" y2="21"/><polyline points="7 16 11 12 15 16 21 10"/>', 'file_link' => asset('/client/کارنامه هوشمند سید محمدرضا رضوی نژاد — اردیبهشت 1405.pdf')],

                        ['title' => 'اتاق مشاوره‌ی تخصصی', 'desc' => 'برگزاری جلسات مشاوره‌ی هدفمند، ثبت برنامه‌ی هفتگی و بارگذاری برنامه‌ی کلاسی.', 'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                        ['title' => 'آزمون‌های آنلاین', 'desc' => 'برگزاری آزمون‌های استاندارد همراه با ارائه‌ی نتیجه و بازخورد تخصصی.', 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                        ['title' => 'طبقه‌بندی دروس', 'desc' => 'دسته‌بندی پروژه‌ها و مباحث هر درس برای یادگیری سازمان‌یافته و قابل‌پیگیری.', 'icon' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'],
                        ['title' => 'تایمر پومودرو حرفه‌ای', 'desc' => 'ابزار تمرکز با چرخه‌های ۲۵ دقیقه‌ای، استراحت کوتاه و طولانی و آمار جلسات.', 'icon' => '<circle cx="12" cy="13" r="8"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="9" y1="2" x2="15" y2="2"/>'],
                        ['title' => 'سامانه‌ی تیکتینگ', 'desc' => 'ارتباط با تیم پشتیبانی برای پیگیری مسائل آموزشی و فنی به‌صورت ساختارمند.', 'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
                        ['title' => 'مرکز اطلاع‌رسانی', 'desc' => 'دریافت پیام‌ها و اطلاعیه‌های مهم مدرسه و مشاوران به‌صورت دسته‌بندی‌شده.', 'icon' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'],
                        ['title' => 'مدیریت مالی', 'desc' => 'کیف پول دیجیتال، پیگیری اقساط شهریه و سیستم امتیاز و پاداش.', 'icon' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>'],
                    ];
                @endphp

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                    @foreach($features as $i => $f)
                        <div class="glow-on-hover glass rounded-2xl p-5 flex flex-col group reveal reveal-delay-{{ ($i % 3) + 1 }}">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="relative shrink-0">
                                    <div class="absolute inset-0 bg-primary/20 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="relative inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                {!! $f['icon'] !!}
                            </svg>
                        </span>
                                </div>
                                <h3 class="font-black text-foreground text-[17px] sm:text-lg leading-tight">{{ $f['title'] }}</h3>
                            </div>

                            {{-- flex-grow باعث میشه متن فضا رو پر کنه و دکمه در صورت وجود بره پایین کارت --}}
                            <p class="font-medium text-xs text-muted leading-6 flex-grow">{{ $f['desc'] }}</p>

                            {{-- ✅ شرط نمایش دکمه در صورت وجود لینک فایل --}}
                            @if(isset($f['file_link']))
                                <div class="mt-4 pt-4 border-t border-border">
                                    <a href="{{ $f['file_link'] }}" target="_blank" download class="inline-flex items-center justify-center w-full gap-2 px-4 py-2.5 bg-primary/10 hover:bg-primary text-primary hover:text-white border border-primary/20 hover:border-primary transition-all rounded-xl text-xs font-bold shadow-sm">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        دانلود نمونه
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ====================== FOR MANAGERS ====================== --}}
            <section class="relative rounded-3xl glass orbit-wrap reveal">
                <div class="overflow-hidden rounded-3xl relative">
                    <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                    <div class="absolute -top-10 -left-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl blob-1"></div>
                    <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-primary/15 rounded-full blur-3xl blob-2"></div>

                    <div class="relative grid md:grid-cols-12 gap-8 items-center p-6 md:p-12">
                        <div class="md:col-span-7 space-y-5 reveal-right">
                            <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                                <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4z"/>
                                </svg>
                                <span class="font-semibold text-xs text-foreground">ویژه‌ی مدیران مدارس</span>
                            </div>
                            <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                                تصمیم‌گیری <span class="shimmer-text">مبتنی بر داده</span> برای مدیران
                            </h2>
                            <p class="font-medium text-sm text-muted leading-8">
                                با پنل مدیریتی SDFR، دیگر برای ارزیابی عملکرد دانش‌آموزان به حدس و گمان نیاز ندارید. گزارش‌های دقیق، نمودارهای پیشرفت و خروجی‌های قابل ارائه به اولیا را در یک مکان متمرکز در اختیار خواهید داشت.
                            </p>
                            <ul class="grid sm:grid-cols-2 gap-3 pt-2">
                                @foreach([
                                    'گزارش‌گیری دقیق دوره‌ای',
                                    'پایش ساعت مطالعه‌ی کل مدرسه',
                                    'ارتباط با مشاوران تخصصی',
                                    'پشتیبانی اختصاصی قرارداد',
                                ] as $item)
                                    <li class="flex items-start gap-2.5">
                                    <span class="flex items-center justify-center w-6 h-6 bg-primary/15 text-primary border border-primary/20 rounded-md mt-0.5 shrink-0">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                        <span class="font-semibold text-sm text-foreground leading-6">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="md:col-span-5 reveal-left">
                            <div class="glass-strong rounded-2xl p-5 space-y-4 shadow-xl shadow-primary/10">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                        </svg>
                                        <span class="font-bold text-xs text-muted">نمونه‌ی گزارش هفتگی</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-primary glass rounded-full px-2 py-0.5">
                                        <span class="relative flex w-1.5 h-1.5"><span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span><span class="relative inline-flex w-1.5 h-1.5 bg-primary rounded-full"></span></span>زنده
                                    </span>
                                </div>
                                <div class="space-y-3">
                                    @foreach([
                                        ['name' => 'پایه‌ی دهم - ریاضی', 'val' => '۸۲٪', 'w' => '82%'],
                                        ['name' => 'پایه‌ی یازدهم - تجربی', 'val' => '۷۵٪', 'w' => '75%'],
                                        ['name' => 'پایه‌ی دوازدهم - انسانی', 'val' => '۹۱٪', 'w' => '91%'],
                                    ] as $row)
                                        <div class="space-y-1.5">
                                            <div class="flex items-center justify-between">
                                                <span class="font-semibold text-xs text-foreground">{{ $row['name'] }}</span>
                                                <span class="font-black text-xs text-primary">{{ $row['val'] }}</span>
                                            </div>
                                            <div class="h-2 bg-secondary/50 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-l from-primary to-primary/70 rounded-full grow-bar" style="width: {{ $row['w'] }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="pt-3 border-t border-border grid grid-cols-3 gap-3 text-center">
                                    <div><div class="font-black text-foreground text-lg">۱٬۲۴۰</div><div class="font-medium text-[10px] text-muted">ساعت مطالعه</div></div>
                                    <div><div class="font-black text-foreground text-lg">۳۲۰</div><div class="font-medium text-[10px] text-muted">گزارش روزانه</div></div>
                                    <div><div class="font-black text-foreground text-lg">۸۵</div><div class="font-medium text-[10px] text-muted">آزمون</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="orb-track">
                    <span class="orb"></span>
                    <span class="orb-trail"></span>
                </div>
            </section>

            {{-- ===================== NEW & UPCOMING FEATURES ===================== --}}
            <section class="relative space-y-10 scroll-mt-24 reveal">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
                <div class="text-center space-y-3 max-w-3xl mx-auto">
                    <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                        <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/>
                        </svg>
                        <span class="font-semibold text-xs text-foreground">بروزرسانی‌های مستمر</span>
                    </div>
                    <h2 class="font-black text-2xl md:text-3xl text-foreground">
                        امکانات جدید و <span class="shimmer-text">شگفت‌انگیز</span>
                    </h2>
                    <p class="font-medium text-sm text-muted leading-7">
                        ما همواره در حال توسعه‌ی ابزارهای جدید هستیم. با جدیدترین ویژگی‌های اضافه‌شده و نقشه راه پیش‌رو آشنا شوید.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 reveal" style="background: linear-gradient(135deg, hsl(var(--primary)/0.1), transparent); border: 1px dashed hsl(var(--primary)/0.4);">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                            </span>
                            <h3 class="font-black text-foreground text-[17px] sm:text-lg">برنامه امتحانات (جدید)</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">دریافت برنامه‌ی جامع و شخصی‌سازی‌شده ویژه ایام امتحانات، با در نظر گرفتن ضرایب دروس و نقاط ضعف دانش‌آموز.</p>
                    </div>

                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 reveal reveal-delay-1" style="background: linear-gradient(135deg, hsl(var(--primary)/0.1), transparent); border: 1px dashed hsl(var(--primary)/0.4);">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            </span>
                            <h3 class="font-black text-foreground text-[17px] sm:text-lg">نمونه سوالات امتحانی</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">دسترسی به بانک غنی از نمونه سوالات پرتکرار امتحانی به‌صورت دسته‌بندی شده برای تسلط کامل بر مباحث.</p>
                    </div>

                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 reveal reveal-delay-2" style="background: linear-gradient(135deg, hsl(var(--primary)/0.1), transparent); border: 1px dashed hsl(var(--primary)/0.4);">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 6.1H3"/><path d="M21 12.1H3"/><path d="M15.1 18H3"/></svg>
                            </span>
                            <h3 class="font-black text-foreground text-[17px] sm:text-lg">ارتباط ۲۴ ساعته مشاور</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">ارتباط مستقیم، بدون استرس و چت‌محور بین دانش‌آموز و مشاور برای رفع اشکال و راهنمایی در هر ساعت از شبانه‌روز.</p>
                    </div>

                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 reveal" style="background: linear-gradient(135deg, hsl(var(--primary)/0.1), transparent); border: 1px dashed hsl(var(--primary)/0.4);">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><polyline points="20 8 22 10 20 12"/><polyline points="16 8 14 10 16 12"/></svg>
                            </span>
                            <h3 class="font-black text-foreground text-[17px] sm:text-lg">امکان جابجایی مشاور</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">در صورت عدم هماهنگی، دانش‌آموزان حق درخواست جابجایی مشاور را دارند تا بهترین بازدهی آموزشی شکل بگیرد.</p>
                    </div>

                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 reveal reveal-delay-1" style="background: linear-gradient(135deg, hsl(var(--primary)/0.1), transparent); border: 1px dashed hsl(var(--primary)/0.4);">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </span>
                            <h3 class="font-black text-foreground text-[17px] sm:text-lg">مدیریت اتفاقات یهویی</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">بخش اختصاصی برای ثبت رویدادهای پیش‌بینی‌نشده جهت جابجایی خودکار برنامه‌ی مطالعاتی.</p>
                    </div>

                    <div class="glow-on-hover rounded-2xl p-5 space-y-3 reveal reveal-delay-2 relative overflow-hidden" style="background: linear-gradient(135deg, hsl(var(--primary)/0.1), transparent); border: 1px dashed hsl(var(--primary)/0.4);">
                        <div class="absolute top-0 right-0 bg-primary/20 px-3 py-1 rounded-bl-xl text-[10px] font-bold text-primary">تا پایان سال</div>
                        <div class="flex items-center gap-3 pt-2">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/20 text-primary rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg>
                            </span>
                            <h3 class="font-black text-foreground text-[17px] sm:text-lg">چت‌بات هوشمند & اپلیکیشن</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">راه‌اندازی چت‌بات اختصاصی هوش مصنوعی برای پاسخگویی به سوالات درسی، همراه با نسخه‌های بومی.</p>
                    </div>
                </div>
            </section>

            {{-- ===================== MOBILE MOCKUP SHOWCASE ===================== --}}
            <section class="relative space-y-10 reveal">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
                <div class="grid md:grid-cols-2 gap-10 items-center">
                    <div class="space-y-6 reveal-right">
                        <h2 class="font-black text-3xl text-foreground leading-tight">
                            نگاهی به <span class="shimmer-text">محیط کاربری</span> نرم‌افزار
                        </h2>
                        <p class="font-medium text-sm text-muted leading-8">
                            تجربه‌ی کاربری نرم‌افزار با تمرکز بر سادگی و کارایی طراحی شده است. در این بخش می‌توانید دموی کوتاهی از محیط اپلیکیشن و نحوه‌ی تعامل با بخش‌های مختلف را مشاهده کنید.
                        </p>
                    </div>
                    <div class="flex justify-center reveal-left px-4">
                        {{-- Mobile Frame (Responsive width added) --}}
                        <div class="relative w-full max-w-[280px] h-[580px] mx-auto rounded-[3rem] border-[12px] border-foreground/10 glass-strong shadow-2xl floaty overflow-hidden">
                            <div class="absolute top-0 inset-x-0 h-6 bg-foreground/10 rounded-b-3xl w-32 mx-auto z-20"></div>
                            <div class="absolute inset-0 bg-background flex items-center justify-center">
                                <!-- جایگذاری تگ img (برای گیف) یا video -->
                                <img src="{{ asset('path/to/your/panel-demo.gif') }}" alt="دموی محیط پنل" class="w-full h-full object-cover">
                                {{-- <span class="text-sm text-muted font-bold">محل قرارگیری ویدیو/گیف</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ======================= HOW IT WORKS ======================= --}}
            <section class="relative space-y-10 reveal">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
                <div class="text-center space-y-3">
                    <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                        <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        <span class="font-semibold text-xs text-foreground">فرآیند ساده</span>
                    </div>
                    <h2 class="font-black text-2xl md:text-3xl text-foreground">
                        فرآیند <span class="shimmer-text">همکاری</span> در چهار گام
                    </h2>
                    <p class="font-medium text-sm text-muted max-w-2xl mx-auto leading-7">
                        از ثبت درخواست تا فعال‌سازی کامل خدمات، تنها چهار قدم با ما فاصله دارید.
                    </p>
                </div>
                @php
                    $steps = [
                        ['n' => '۰۱', 't' => 'ثبت درخواست', 'd' => 'فرم همکاری را در پایین این صفحه تکمیل و ارسال می‌کنید.'],
                        ['n' => '۰۲', 't' => 'تماس کارشناس', 'd' => 'کارشناسان ما با شما تماس گرفته و جلسه‌ی مشاوره‌ی تنظیم می‌شود.'],
                        ['n' => '۰۳', 't' => 'عقد قرارداد', 'd' => 'قرارداد رسمی متناسب با تعداد دانش‌آموزان منعقد می‌گردد.'],
                        ['n' => '۰۴', 't' => 'فعال‌سازی پنل', 'd' => 'حساب دانش‌آموزان و دسترسی مدیران فعال شده و همکاری آغاز می‌شود.'],
                    ];
                @endphp
                <div class="relative grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="hidden lg:block absolute top-12 right-[12.5%] left-[12.5%] h-px bg-gradient-to-l from-transparent via-primary/30 to-transparent"></div>
                    @foreach($steps as $i => $s)
                        <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 relative reveal reveal-delay-{{ $i + 1 }}">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-4xl text-primary/20">{{ $s['n'] }}</span>
                                <span class="relative flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 12H5"/><path d="m12 5-7 7 7 7"/>
                                    </svg>
                                </span>
                            </div>
                            <h3 class="font-black text-foreground text-base">{{ $s['t'] }}</h3>
                            <p class="font-medium text-xs text-muted leading-6">{{ $s['d'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ===================== 1-WEEK FREE TRIAL (Fixed Orbit) ===================== --}}
            <section class="relative rounded-3xl orbit-wrap reveal">
                <div class="overflow-hidden rounded-3xl relative bg-gradient-to-r from-primary/20 via-primary/5 to-transparent border border-primary/20 p-6 md:p-12">
                    <div class="absolute inset-0 grid-bg pointer-events-none opacity-50"></div>
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/30 rounded-full blur-3xl blob-1"></div>

                    <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-right">
                        <div class="space-y-4 md:w-2/3">
                            <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5 mx-auto md:mx-0">
                                <span class="relative flex w-2 h-2">
                                    <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                    <span class="relative inline-flex w-2 h-2 bg-primary rounded-full"></span>
                                </span>
                                <span class="font-semibold text-xs text-foreground">تضمین کیفیت خدمات</span>
                            </div>
                            <h2 class="font-black text-2xl md:text-3xl text-foreground">
                                <span class="shimmer-text">۱ هفته استفاده‌ی کاملاً رایگان</span> و آزمایشی
                            </h2>
                            <p class="font-medium text-sm text-muted leading-8">
                                ما به کیفیت زیرساخت آموزشی خود اطمینان داریم. شما می‌توانید به مدت ۷ روز، بدون پرداخت هیچ هزینه‌ای و بدون نیاز به ثبت قرارداد، پنل مدرسه، سیستم برنامه‌ریزی و تمامی امکانات را برای تعدادی از دانش‌آموزان خود تست کنید.
                            </p>
                        </div>
                        <div class="w-full md:w-1/3 flex justify-center md:justify-end">
                            <a href="#contract-form" class="w-full sm:w-auto group relative inline-flex items-center justify-center h-14 bg-foreground hover:bg-foreground/90 transition-all rounded-2xl text-background font-bold text-base px-6 shadow-2xl hover:scale-[1.02]">
                                <span>شروع تست رایگان مدرسه</span>
                                <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="orb-track">
                    <span class="orb"></span>
                    <span class="orb-trail"></span>
                </div>
            </section>

            {{-- ======================= CALCULATOR ======================= --}}
            @php
                $tiersJs = collect($pricingTiers)->map(fn($t) => [
                    'max'   => $t['max'] > 1000000 ? null : $t['max'],
                    'price' => $t['price'],
                    'label' => $t['label'],
                ])->values();
            @endphp
            <section id="calculator" class="scroll-mt-24 reveal">
                <div class="relative rounded-3xl glass orbit-wrap">
                    <div class="overflow-hidden rounded-3xl relative p-6 md:p-10" x-data="{
                            count: '',
                            tiers: @js($tiersJs),
                            basePrice: @js($basePrice),
                            get n() {
                                let v = parseInt(String(this.count).replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[^0-9]/g, ''));
                                return isNaN(v) || v < 0 ? 0 : v;
                            },
                            get unitPrice() {
                                for (const t of this.tiers) { if (t.max === null || this.n <= t.max) return t.price; }
                                return this.tiers[this.tiers.length - 1].price;
                            },
                            get payable() { return this.n * this.unitPrice; },
                            get discountPerStudent() { return this.basePrice - this.unitPrice; },
                            get discount() { return this.n * this.discountPerStudent; },
                            get pkg() {
                                for (const t of this.tiers) { if (t.max === null || this.n <= t.max) return t.label; }
                                return this.tiers[this.tiers.length - 1].label;
                            },
                            fmt(v) { return new Intl.NumberFormat('fa-IR').format(v); }
                        }">
                        <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                        <div class="absolute -top-20 left-1/4 w-80 h-80 bg-primary/20 rounded-full blur-3xl blob-1"></div>
                        <div class="absolute -bottom-20 right-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl blob-2"></div>

                        <div class="relative space-y-8">
                            <div class="text-center space-y-3 max-w-2xl mx-auto">
                                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="8" y2="10"/><line x1="12" y1="10" x2="12" y2="10"/><line x1="16" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="8" y2="14"/><line x1="12" y1="14" x2="12" y2="14"/><line x1="16" y1="14" x2="16" y2="18"/><line x1="8" y1="18" x2="12" y2="18"/>
                                    </svg>
                                    <span class="font-semibold text-xs text-foreground">محاسبه‌گر هزینه</span>
                                </div>
                                <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                                    <span class="shimmer-text">هزینه و سود</span> همکاری را همین حالا برآورد کنید
                                </h2>
                                <p class="font-medium text-sm text-muted leading-7">
                                    تعداد دانش‌آموزان مدرسه‌ی خود را وارد کنید تا پک متناسب، هزینه‌ی قابل پرداخت و میزان سود به‌صورت لحظه‌ای محاسبه شود.
                                </p>
                            </div>

                            <div class="grid md:grid-cols-12 gap-6 md:gap-8 items-stretch">
                                <div class="md:col-span-5">
                                    <div class="glass-strong rounded-2xl p-6 h-full flex flex-col justify-center space-y-5">
                                        <label class="font-semibold text-sm text-foreground flex items-center gap-2">
                                            <svg class="w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                            تعداد دانش‌آموزان
                                        </label>
                                        <div class="flex items-center gap-2" x-data="{ inc() { let v = parseInt($refs.calcInput.value) || 0; count = String(v + 1); }, dec() { let v = parseInt($refs.calcInput.value) || 0; if (v > 1) count = String(v - 1); } }">
                                            <button type="button" @click="dec()" class="flex-shrink-0 w-12 h-14 rounded-xl flex items-center justify-center font-bold text-xl transition-all duration-150 hover:scale-105 active:scale-95 select-none" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5);" onmouseenter="this.style.borderColor='rgba(255,255,255,0.2)';this.style.color='rgba(255,255,255,0.8)'" onmouseleave="this.style.borderColor='rgba(255,255,255,0.1)';this.style.color='rgba(255,255,255,0.5)'">−</button>
                                            <input type="number" min="1" id="calc_count" x-ref="calcInput" x-model="count" inputmode="numeric" placeholder="مثلاً ۵۰" class="flex-1 min-w-0 w-full h-14 rounded-xl text-lg font-bold text-center transition-all focus:outline-none" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);caret-color:white;" onfocus="this.style.borderColor='rgba(59,130,246,0.6)';this.style.background='rgba(59,130,246,0.07)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)';this.style.background='rgba(255,255,255,0.05)'">
                                            <button type="button" @click="inc()" class="flex-shrink-0 w-12 h-14 rounded-xl flex items-center justify-center font-bold text-xl transition-all duration-150 hover:scale-105 active:scale-95 select-none" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.35);color:#60a5fa;" onmouseenter="this.style.background='rgba(59,130,246,0.25)'" onmouseleave="this.style.background='rgba(59,130,246,0.15)'">+</button>
                                        </div>
                                        <p class="font-medium text-[11px] text-muted leading-5 pt-1">
                                            کافی است تعداد دانش‌آموزان را وارد کنید؛ هزینه‌ی قابل پرداخت و میزان تخفیف به‌صورت لحظه‌ای نمایش داده می‌شود.
                                        </p>
                                    </div>
                                </div>
                                <div class="md:col-span-7">
                                    <div class="glass-strong rounded-2xl p-6 h-full shadow-xl shadow-primary/5 space-y-4">
                                        <template x-if="n === 0">
                                            <div class="flex flex-col items-center justify-center text-center h-full py-10 space-y-3">
                                                <svg class="w-12 h-12 text-primary/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M9 11H3v10h6V11zM21 3h-6v18h6V3zM15 7H9v14h6V7z"/>
                                                </svg>
                                                <p class="font-medium text-sm text-muted">برای مشاهده‌ی نتیجه، تعداد دانش‌آموزان را وارد کنید.</p>
                                            </div>
                                        </template>
                                        <template x-if="n > 0">
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between glass rounded-xl p-4">
                                                    <div class="flex items-center gap-3">
                                                        <span class="flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>
                                                            </svg>
                                                        </span>
                                                        <div>
                                                            <div class="font-medium text-[11px] text-muted">پک پیشنهادی</div>
                                                            <div class="font-black text-foreground text-lg">پک <span class="text-primary" x-text="pkg"></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="font-medium text-[11px] text-muted">تعداد دانش‌آموز</div>
                                                        <div class="font-black text-foreground text-lg" x-text="fmt(n) + ' نفر'"></div>
                                                    </div>
                                                </div>
                                                <div class="rounded-xl p-4 bg-primary/10 border border-primary/20">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <svg class="w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
                                                        </svg>
                                                        <span class="font-semibold text-xs text-muted">هزینه‌ای که باید پرداخت کنید</span>
                                                    </div>
                                                    <div class="font-black text-primary text-2xl md:text-3xl">
                                                        <span x-text="fmt(payable)"></span> <span class="text-base font-bold text-muted">تومان</span>
                                                    </div>
                                                </div>
                                                <div class="rounded-xl p-4 bg-emerald-500/10 border border-emerald-500/20">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <svg class="w-4 h-4 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                        </svg>
                                                        <span class="font-semibold text-xs text-muted">سود و تخفیف شما</span>
                                                    </div>
                                                    <div class="font-black text-emerald-500 text-2xl md:text-3xl">
                                                        <span x-text="fmt(discount)"></span> <span class="text-base font-bold text-muted">تومان</span>
                                                    </div>
                                                    <p class="font-medium text-[11px] text-muted leading-5 mt-1" x-show="discount === 0">
                                                        قیمت هر دانش‌آموز در این تعداد <span x-text="fmt(unitPrice)"></span> تومان است. با افزایش تعداد، قیمت هر نفر کاهش می‌یابد.
                                                    </p>
                                                    <p class="font-medium text-[11px] text-muted leading-5 mt-1" x-show="discount > 0">
                                                        قیمت هر دانش‌آموز <span x-text="fmt(discountPerStudent)"></span> تومان کاهش یافته است (از <span x-text="fmt(basePrice)"></span> به <span x-text="fmt(unitPrice)"></span> تومان).
                                                    </p>
                                                </div>
                                                <a href="#contract-form" class="group w-full inline-flex items-center justify-center h-12 bg-primary hover:bg-primary/90 transition-all rounded-full text-white font-bold text-sm px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.01]">
                                                    <span>ثبت درخواست با این تعداد</span>
                                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                                                </a>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orb-track">
                        <span class="orb"></span>
                        <span class="orb-trail"></span>
                    </div>
                </div>
            </section>

            {{-- ======================= INSTALLMENTS ======================= --}}
            <section id="installments" class="scroll-mt-24 reveal">
                <div class="relative rounded-3xl glass orbit-wrap">
                    <div class="overflow-hidden rounded-3xl relative p-6 md:p-10">
                        <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                        <div class="absolute -top-20 left-1/4 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl blob-1"></div>
                        <div class="absolute -bottom-20 right-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl blob-2"></div>

                        <div class="relative grid md:grid-cols-12 gap-8 items-center">
                            <div class="md:col-span-7 space-y-5 reveal-right">
                                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
                                    </svg>
                                    <span class="font-semibold text-xs text-foreground">پرداخت اقساطی</span>
                                </div>
                                <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                                    امکان همکاری به‌صورت <span class="shimmer-text">اقساطی</span>
                                </h2>
                                <p class="font-medium text-sm text-muted leading-8">
                                    لازم نیست همه‌ی هزینه را یکجا پرداخت کنید. برای راحتی مدارس، طرح پرداخت اقساطی هم در نظر گرفته‌ایم.
                                </p>
                                <ul class="space-y-3 pt-1">
                                    @foreach([
                                        'پرداخت ۳۰٪ مبلغ قرارداد به‌صورت نقد در ابتدای همکاری',
                                        'تقسیط مابقی مبلغ در چند قسط ماهانه و بدون سود',
                                        'تنظیم برنامه‌ی اقساط متناسب با شرایط مدرسه',
                                    ] as $item)
                                        <li class="flex items-start gap-2.5">
                                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-500/15 text-emerald-500 border border-emerald-500/20 rounded-md mt-0.5 shrink-0">
                                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                            </span>
                                            <span class="font-semibold text-sm text-foreground leading-6">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="md:col-span-5 reveal-left">
                                <div class="glass-strong rounded-2xl p-6 space-y-4 shadow-xl shadow-emerald-500/10">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-muted">نمونه‌ی طرح پرداخت</span>
                                        <span class="font-semibold text-[10px] text-emerald-500 glass rounded-full px-2 py-0.5">اقساطی</span>
                                    </div>
                                    <div class="rounded-xl p-4 bg-emerald-500/10 border border-emerald-500/20">
                                        <div class="font-medium text-[11px] text-muted">پیش‌پرداخت نقدی</div>
                                        <div class="font-black text-emerald-500 text-2xl">۳۰٪</div>
                                    </div>
                                    <div class="rounded-xl p-4 bg-primary/10 border border-primary/20">
                                        <div class="font-medium text-[11px] text-muted">مابقی مبلغ</div>
                                        <div class="font-black text-primary text-2xl">۷۰٪</div>
                                        <div class="font-medium text-[11px] text-muted mt-0.5">تقسیط در چند قسط ماهانه</div>
                                    </div>
                                    <div class="h-2.5 rounded-full overflow-hidden flex" style="background:rgba(255,255,255,0.06);">
                                        <div class="h-full bg-emerald-500" style="width:30%"></div>
                                        <div class="h-full bg-gradient-to-l from-primary to-primary/70" style="width:70%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] font-semibold">
                                        <span class="text-emerald-500">نقد ۳۰٪</span>
                                        <span class="text-primary">اقساط ۷۰٪</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orb-track">
                        <span class="orb"></span>
                        <span class="orb-trail"></span>
                    </div>
                </div>
            </section>

            {{-- ========================== FORM (FIXED RESPONSIVE FOOTER) ========================== --}}
            <section id="contract-form" class="scroll-mt-24 reveal">
                <div class="relative rounded-3xl glass orbit-wrap">
                    <div class="overflow-hidden rounded-3xl relative p-6 md:p-10">
                        <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                        <div class="absolute -top-20 right-1/4 w-80 h-80 bg-primary/20 rounded-full blur-3xl blob-1"></div>
                        <div class="absolute -bottom-20 left-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl blob-2"></div>

                        <div class="relative grid md:grid-cols-12 gap-6 md:gap-10 items-stretch">
                            <div class="md:col-span-5 space-y-6 reveal-right">
                                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                                    <span class="relative flex w-1.5 h-1.5">
                                        <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                        <span class="relative inline-flex w-1.5 h-1.5 bg-primary rounded-full"></span>
                                    </span>
                                    <span class="font-semibold text-xs text-foreground">فرم درخواست همکاری</span>
                                </div>
                                <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                                    آماده‌ی شروع همکاری با <span class="shimmer-text">SDFR</span> هستید؟
                                </h2>
                                <p class="font-medium text-sm text-muted leading-8">
                                    اطلاعات تماس مدرسه‌ی خود را وارد کنید تا کارشناسان ما جزئیات قرارداد را بررسی کنند.
                                </p>
                                <ul class="space-y-3 pt-2">
                                    @foreach([
                                        ['t' => 'بدون پیش‌پرداخت',                'svg' => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'],
                                        ['t' => 'مشاوره‌ی اولیه‌ی رایگان',         'svg' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                                        ['t' => 'پشتیبانی اختصاصی در طول قرارداد', 'svg' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>'],
                                    ] as $item)
                                        <li class="flex items-center gap-3 glass rounded-xl p-3">
                                    <span class="flex items-center justify-center w-9 h-9 bg-primary/15 text-primary border border-primary/20 rounded-lg shrink-0">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $item['svg'] !!}</svg>
                                    </span>
                                            <span class="font-semibold text-sm text-foreground">{{ $item['t'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="md:col-span-7 reveal-left">
                                <div class="glass-strong rounded-2xl p-6 shadow-xl shadow-primary/5">
                                    @if($submitted)
                                        <div class="flex flex-col items-center text-center py-8 space-y-4">
                                            <div class="relative w-16 h-16 mx-auto">
                                                <div class="absolute inset-0 rounded-full animate-ping" style="background:rgba(34,197,94,0.15);"></div>
                                                <div class="relative w-16 h-16 rounded-full flex items-center justify-center" style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);">
                                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-black text-foreground mb-2">درخواست شما ثبت شد! 🎉</h3>
                                                <p class="text-sm text-muted leading-7">کارشناسان ما در اولین فرصت با شما تماس خواهند گرفت.</p>
                                            </div>
                                            <button type="button" wire:click="$set('submitted', false)" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-foreground transition-all hover:scale-[1.02]" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                                                ثبت درخواست جدید
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3 pb-5 mb-5 border-b border-border">
                                            <span class="flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            </span>
                                            <div>
                                                <div class="font-black text-foreground">اطلاعات قرارداد مدرسه</div>
                                                <p class="font-medium text-[11px] text-muted leading-5 mt-0.5">تمامی فیلدها الزامی است.</p>
                                            </div>
                                        </div>

                                        <form wire:submit.prevent="submit" class="space-y-4">
                                            <div class="space-y-1.5">
                                                <label for="full_name" class="font-semibold text-xs text-muted flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>نام و نام خانوادگی</label>
                                                <input type="text" id="full_name" wire:model="full_name" placeholder="مهدی آبان" class="w-full h-12 rounded-xl text-sm px-4 transition-all focus:outline-none" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);caret-color:white;" onfocus="this.style.borderColor='rgba(59,130,246,0.6)';this.style.background='rgba(59,130,246,0.06)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)';this.style.background='rgba(255,255,255,0.05)'">
                                                @error('full_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="space-y-1.5">
                                                <label for="mobile" class="font-semibold text-xs text-muted flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>شماره تلفن همراه</label>
                                                <input type="tel" dir="ltr" id="mobile" wire:model="mobile" placeholder="09xxxxxxxxx" class="w-full h-12 rounded-xl text-sm px-4 transition-all focus:outline-none text-left" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);caret-color:white;" onfocus="this.style.borderColor='rgba(59,130,246,0.6)';this.style.background='rgba(59,130,246,0.06)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)';this.style.background='rgba(255,255,255,0.05)'">
                                                @error('mobile') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="grid sm:grid-cols-2 gap-3">
                                                <div class="space-y-1.5">
                                                    <label class="font-semibold text-xs text-muted flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>استان</label>
                                                    <div wire:key="state-select">
                                                        <x-ui.select wire:model.live="state_id" :options="collect($states)->toArray()" placeholder="انتخاب استان" :searchable="true" name="state_id" />
                                                    </div>
                                                    @error('state_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="space-y-1.5">
                                                    <label class="font-semibold text-xs text-muted flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>شهر</label>
                                                    <div wire:key="city-select-{{ $state_id ?: 'none' }}">
                                                        <x-ui.select wire:model="city_id" :options="collect($cities)->toArray()" :disabled="empty($state_id)" :searchable="true" :placeholder="empty($state_id) ? 'ابتدا استان را انتخاب کنید' : 'انتخاب شهر'" name="city_id" />
                                                    </div>
                                                    @error('city_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>

                                            {{-- ✅ حل مشکل ریسپانسیو دکمه در اینجا (flex-col-reverse) --}}
                                            <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-4 border-t border-border">
                                                <p class="font-medium text-[11px] text-muted leading-5 text-center sm:text-right w-full sm:max-w-[55%]">
                                                    با ارسال، با <a href="{{ route('client.terms') }}" class="text-primary hover:underline font-bold">قوانین و مقررات</a> موافقت می‌نمایید.
                                                </p>
                                                <button type="submit" class="w-full sm:w-auto group h-12 inline-flex items-center justify-center bg-primary hover:bg-primary/90 transition-all rounded-full text-white px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02] disabled:opacity-60 disabled:hover:scale-100" wire:loading.attr="disabled" wire:target="submit">
                                                    <span class="font-semibold text-sm flex items-center" wire:loading.remove wire:target="submit">
                                                        ثبت درخواست <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                                                    </span>
                                                    <span wire:loading wire:target="submit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="28px" height="28px" style="shape-rendering:auto;display:block;background:transparent;">
                                                            <g><path stroke="none" fill="#ffffff" d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50"><animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1" repeatCount="indefinite" dur="0.81s" type="rotate" attributeName="transform"/></path></g>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orb-track">
                        <span class="orb"></span>
                        <span class="orb-trail"></span>
                    </div>
                </div>
            </section>
        </div>
    </div> <!-- پایان نگهدارنده‌ی اصلی -->

    @script
    <script>
        (function () {
            const SEL = '.reveal, .reveal-right, .reveal-left';
            const revealed = new WeakSet();
            let observer = null;

            function makeVisible(el, animate) {
                if (!animate) {
                    const prev = el.style.transition;
                    el.style.transition = 'none';
                    el.classList.add('is-visible');
                    void el.offsetWidth;
                    el.style.transition = prev;
                } else {
                    el.classList.add('is-visible');
                }
                revealed.add(el);
            }

            function initObserver() {
                if (observer) observer.disconnect();
                observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            makeVisible(entry.target, true);
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                document.querySelectorAll(SEL).forEach(el => {
                    if (revealed.has(el) || el.classList.contains('is-visible')) {
                        makeVisible(el, false);
                    } else {
                        observer.observe(el);
                    }
                });
            }

            let raf = null;
            function reapply() {
                document.querySelectorAll(SEL).forEach(el => {
                    if (revealed.has(el)) makeVisible(el, false);
                    else if (observer && !el.classList.contains('is-visible')) observer.observe(el);
                });
            }
            function scheduleReapply() {
                cancelAnimationFrame(raf);
                raf = requestAnimationFrame(reapply);
            }

            document.addEventListener('DOMContentLoaded', initObserver);
            document.addEventListener('livewire:navigated', initObserver);
            document.addEventListener('livewire:init', () => {
                if (window.Livewire && Livewire.hook) {
                    Livewire.hook('morph.updated', scheduleReapply);
                    Livewire.hook('morphed', scheduleReapply);
                }
            });
        })();
    </script>
    @endscript
</div>
