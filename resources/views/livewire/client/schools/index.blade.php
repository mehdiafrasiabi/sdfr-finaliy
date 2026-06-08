<div>

    @push('link')
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
                0%, 100% {
                    transform: translate(0, 0) scale(1);
                }
                50% {
                    transform: translate(20px, -30px) scale(1.05);
                }
            }

            @keyframes float-reverse {
                0%, 100% {
                    transform: translate(0, 0) scale(1);
                }
                50% {
                    transform: translate(-25px, 20px) scale(1.08);
                }
            }

            .blob-1 {
                animation: float-slow 12s ease-in-out infinite;
            }

            .blob-2 {
                animation: float-reverse 14s ease-in-out infinite;
            }

            /* ---------- Shimmer ---------- */
            @keyframes shimmer {
                0% {
                    background-position: -200% 0;
                }
                100% {
                    background-position: 200% 0;
                }
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
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
            }

            .floaty {
                animation: floaty 5s ease-in-out infinite;
            }

            /* ---------- Glow on hover ---------- */
            .glow-on-hover {
                position: relative;
                transition: transform 0.3s ease, border-color 0.3s ease;
            }

            .glow-on-hover::after {
                content: '';
                position: absolute;
                inset: -1px;
                border-radius: inherit;
                background: linear-gradient(135deg, hsl(var(--primary) / 0.5), transparent 60%);
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
                z-index: -1;
            }

            .glow-on-hover:hover {
                transform: translateY(-3px);
            }

            .glow-on-hover:hover::after {
                opacity: 1;
            }

            /* ---------- Grow bar ---------- */
            @keyframes grow-bar {
                from {
                    width: 0;
                }
            }

            .grow-bar {
                animation: grow-bar 1.5s ease-out forwards;
            }

            /* =====================================================
               NEW: Orbiting blue orb under sections
               ===================================================== */
            .orbit-wrap {
                position: relative;
            }

            .orbit-wrap .orb-track {
                position: absolute;
                bottom: -32px;
                left: 0;
                right: 0;
                height: 24px;
                pointer-events: none;
                z-index: 5;
            }

            /* خط درخشش زیر کارت */
            .orbit-wrap .orb-track::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 8%;
                right: 8%;
                height: 1px;
                background: linear-gradient(to left, transparent, hsl(217 91% 60% / 0.5), transparent);
                transform: translateY(-50%);
            }

            /* گوی اصلی آبی */
            .orbit-wrap .orb {
                position: absolute;
                top: 50%;
                right: 0;
                width: 14px;
                height: 14px;
                margin-top: -7px;
                border-radius: 999px;
                background: radial-gradient(circle at 30% 30%, #93c5fd, #3b82f6 55%, #1d4ed8);
                box-shadow: 0 0 12px rgba(96, 165, 250, 0.9),
                0 0 24px rgba(59, 130, 246, 0.6),
                0 0 40px rgba(37, 99, 235, 0.4);
                animation: orbit-rtl 6s linear infinite, glow-pulse 2.5s ease-in-out infinite;
            }

            /* دنباله‌ی کوچک‌تر */
            .orbit-wrap .orb-trail {
                position: absolute;
                top: 50%;
                right: 0;
                width: 7px;
                height: 7px;
                margin-top: -3.5px;
                border-radius: 999px;
                background: #93c5fd;
                box-shadow: 0 0 8px rgba(147, 197, 253, 0.8);
                opacity: 0.5;
                animation: orbit-rtl 6s linear infinite;
                animation-delay: -0.35s;
            }

            @keyframes orbit-rtl {
                0% {
                    right: 0;
                    transform: translateY(0) scale(1);
                }
                25% {
                    right: 50%;
                    transform: translateY(-8px) scale(1.2);
                }
                50% {
                    right: calc(100% - 14px);
                    transform: translateY(0) scale(1);
                }
                75% {
                    right: 50%;
                    transform: translateY(8px) scale(0.8);
                }
                100% {
                    right: 0;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes glow-pulse {
                0%, 100% {
                    box-shadow: 0 0 12px rgba(96, 165, 250, 0.9),
                    0 0 24px rgba(59, 130, 246, 0.6);
                }
                50% {
                    box-shadow: 0 0 20px rgba(96, 165, 250, 1),
                    0 0 40px rgba(59, 130, 246, 0.8),
                    0 0 60px rgba(37, 99, 235, 0.5);
                }
            }

            /* =====================================================
               NEW: Side floating decorations
               ===================================================== */
            .side-deco {
                position: fixed;
                z-index: 1;
                pointer-events: none;
                opacity: 0.2;
            }

            .side-deco-right {
                right: 24px;
                top: 25%;
                animation: side-float-1 7s ease-in-out infinite;
            }

            .side-deco-left {
                left: 24px;
                top: 60%;
                animation: side-float-2 9s ease-in-out infinite;
            }

            .side-deco-right-2 {
                right: 40px;
                top: 75%;
                animation: side-float-1 11s ease-in-out infinite;
            }

            @keyframes side-float-1 {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-40px) rotate(8deg);
                }
            }

            @keyframes side-float-2 {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(30px) rotate(-10deg);
                }
            }

            @media (max-width: 1024px) {
                .side-deco {
                    display: none;
                }
            }

            /* =====================================================
               NEW: Reveal on scroll
               ===================================================== */
            .reveal {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
                will-change: opacity, transform;
            }

            .reveal.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .reveal-right {
                opacity: 0;
                transform: translateX(40px);
                transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .reveal-right.is-visible {
                opacity: 1;
                transform: translateX(0);
            }

            .reveal-left {
                opacity: 0;
                transform: translateX(-40px);
                transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .reveal-left.is-visible {
                opacity: 1;
                transform: translateX(0);
            }

            .reveal-delay-1 {
                transition-delay: 0.1s;
            }

            .reveal-delay-2 {
                transition-delay: 0.2s;
            }

            .reveal-delay-3 {
                transition-delay: 0.3s;
            }

            .reveal-delay-4 {
                transition-delay: 0.4s;
            }

            /* Reduce motion accessibility */
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                    scroll-behavior: auto !important;
                }
            }
        </style>
    @endpush


    {{-- ===== Side floating decorations (fixed, RTL aware) ===== --}}
    <svg class="side-deco side-deco-right w-12 h-12 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <polygon
            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
    </svg>
    <svg class="side-deco side-deco-left w-10 h-10 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 6v6l4 2"/>
    </svg>
    <svg class="side-deco side-deco-right-2 w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path
            d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
    </svg>

    <div dir="rtl" class="max-w-7xl mx-auto px-4 space-y-16 md:space-y-24">

        {{-- =========================== HERO =========================== --}}
        <section class="relative rounded-3xl glass orbit-wrap reveal">
            <div class="overflow-hidden rounded-3xl relative">
                {{-- Grid pattern background --}}
                <div class="absolute inset-0 grid-bg pointer-events-none"></div>

                {{-- Floating blobs --}}
                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div class="blob-1 absolute -top-32 -right-32 w-96 h-96 bg-primary/30 rounded-full blur-3xl"></div>
                    <div
                        class="blob-2 absolute -bottom-32 -left-20 w-[28rem] h-[28rem] bg-primary/15 rounded-full blur-3xl"></div>
                </div>

                {{-- Decorative SVG dots --}}
                <svg class="absolute top-6 left-6 w-24 h-24 text-primary/20 pointer-events-none" viewBox="0 0 100 100"
                     fill="currentColor">
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
                            <span
                                class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
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

                        <div class="flex flex-wrap gap-3 pt-2">
                            <a href="#features"
                               class="inline-flex items-center justify-center h-12 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-sm px-8">
                                مشاهده‌ی امکانات
                            </a>
                            <a href="#contract-form"
                               class="group relative inline-flex items-center justify-center h-12 bg-primary hover:bg-primary/90 transition-all rounded-full text-white font-bold text-sm px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02]">
                                <span>ثبت درخواست همکاری</span>
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M19 12H5"/>
                                    <path d="m12 19-7-7 7-7"/>
                                </svg>
                            </a>
                        </div>

                        <div class="flex flex-wrap items-center gap-5 pt-4">
                            <div class="flex -space-x-2 space-x-reverse">
                                @foreach (['#6366f1','#8b5cf6','#ec4899','#f59e0b'] as $c)
                                    <div class="w-8 h-8 rounded-full border-2 border-background"
                                         style="background: {{ $c }}"></div>
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
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-primary/40 via-primary/10 to-transparent rounded-3xl blur-2xl"></div>
                            <div
                                class="absolute inset-4 glass-strong rounded-2xl flex flex-col items-center justify-center text-center p-6 space-y-4">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-primary/20 rounded-2xl blur-xl"></div>
                                    <div
                                        class="relative flex items-center justify-center w-20 h-20 bg-primary/10 rounded-2xl border border-primary/20">
                                        <svg class="w-10 h-10 text-primary" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="font-black text-foreground text-lg">قرارداد رسمی مدارس</h3>
                                <p class="font-medium text-xs text-muted leading-6">
                                    دسترسی اختصاصی مدیران به داشبورد گزارش‌گیری دانش‌آموزان مدرسه
                                    و پشتیبانی تخصصی در طول مدت قرارداد.
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
                            <svg class="absolute -top-4 -left-4 w-16 h-16 text-primary/40" viewBox="0 0 100 100"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="50" cy="50" r="40" stroke-dasharray="4 4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ★ گوی چرخان زیر باکس --}}
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
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M12 2 2 7l10 5 10-5-10-5z"/>
                        <path d="m2 17 10 5 10-5"/>
                        <path d="m2 12 10 5 10-5"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">معنای SDFR</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground ">
                    چهار ستون اصلی
                    <span class="shimmer-text">الگوی آموزشی</span>
                    ما</h2>
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
                    <div
                        class="glow-on-hover glass rounded-2xl p-5 space-y-3 relative overflow-hidden reveal reveal-delay-{{ $i + 1 }}"
                        dir="rtl">
                        <span
                            class="absolute -bottom-2 font-black text-[10rem] leading-none text-primary/5 select-none pointer-events-none"
                            style="left: 1.75rem">{{ $p['letter'] }}</span>
                        <div class="relative flex items-center justify-between">
                            <span
                                class="font-medium text-[10px] text-muted uppercase tracking-wider">{{ $p['en'] }}</span>
                        </div>
                        <div class="relative font-black text-foreground text-[21px] max-w-[60%]">{{ $p['fa'] }}</div>
                        <p class="relative font-medium text-xs text-muted leading-6 max-w-[60%]">{{ $p['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- ========================= STATS ========================= --}}
        <section class="relative rounded-3xl glass orbit-wrap reveal">
            <div class="overflow-hidden rounded-3xl relative p-6 md:p-8">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none"></div>
                <div
                    class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-40 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>

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
                        <div class="relative text-center space-y-2 p-4 rounded-2xl reveal reveal-delay-{{ $i + 1 }}">
                            <div
                                class="inline-flex items-center justify-center w-10 h-10 bg-primary/10 text-primary rounded-xl mb-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    {!! $s['icon'] !!}
                                </svg>
                            </div>
                            <div class="font-black text-foreground text-2xl md:text-3xl">{{ $s['v'] }}</div>
                            <div class="font-medium text-xs text-muted">{{ $s['l'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ★ گوی چرخان --}}
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
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path
                            d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">امکانات حرفه‌ای</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">امکانات
                    <span class="shimmer-text">پلتفرم</span>

                    برای    <span class="shimmer-text">دانش‌آموزان </span>مدرسه</h2>
                <p class="font-medium text-sm text-muted leading-7">
                    مجموعه‌ای از ابزارهای حرفه‌ای که در داشبورد اختصاصی هر دانش‌آموز
                    در دسترس قرار می‌گیرد و مدیران مدرسه می‌توانند نتایج آن را به‌صورت
                    گزارش‌های منسجم دریافت کنند.
                </p>
            </div>

            @php
                $features = [
                    ['title' => 'داشبورد هوشمند دانش‌آموز', 'desc' => 'نمایش وضعیت لحظه‌ای ساعت مطالعه، گزارش‌های روزانه، برنامه‌ی امروز و اطلاعات مشاور و پشتیبان دانش‌آموز.', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>'],
                    ['title' => 'ثبت ساعت مطالعه', 'desc' => 'تایمر هوشمند ثبت ساعت مطالعه برای هر بخش از برنامه‌ی هفتگی، با امکان رصد دقیق میزان زمان مفید.', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                    ['title' => 'برنامه‌ی هفتگی اختصاصی', 'desc' => 'تدوین برنامه‌ی مطالعاتی هفتگی برای هر دانش‌آموز توسط مشاور، با جزئیات کامل ساعت، آزمون و درس.', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                    ['title' => 'گزارش‌های روزانه‌ی مطالعه', 'desc' => 'ثبت گزارش روزانه‌ی دانش‌آموز شامل دروس خوانده‌شده، آزمون‌ها و جلسات جبرانی برای پایش پیوسته.', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>'],
                    ['title' => 'کارنامه و گزارش پیشرفت', 'desc' => 'کارنامه‌ی ماهانه و نمودارهای پیشرفت تحصیلی برای مقایسه‌ی روند و شناسایی نقاط ضعف و قوت.', 'icon' => '<line x1="3" y1="3" x2="3" y2="21"/><line x1="3" y1="21" x2="21" y2="21"/><polyline points="7 16 11 12 15 16 21 10"/>'],
                    ['title' => 'اتاق مشاوره‌ی تخصصی', 'desc' => 'برگزاری جلسات مشاوره‌ی هدفمند، ثبت برنامه‌ی هفتگی و بارگذاری برنامه‌ی کلاسی مدرسه.', 'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                    ['title' => 'آزمون‌های آنلاین', 'desc' => 'برگزاری آزمون‌های تایپی استاندارد همراه با ارائه‌ی نتیجه و بازخورد تخصصی به دانش‌آموز.', 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                    ['title' => 'طبقه‌بندی دروس و مباحث', 'desc' => 'دسته‌بندی پروژه‌ها و مباحث هر درس برای یادگیری سازمان‌یافته و قابل‌پیگیری.', 'icon' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'],
                    ['title' => 'تایمر پومودرو حرفه‌ای', 'desc' => 'ابزار تمرکز پومودرو با چرخه‌های ۲۵ دقیقه‌ای، استراحت کوتاه و طولانی و آمار جلسات تمرکز.', 'icon' => '<circle cx="12" cy="13" r="8"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="9" y1="2" x2="15" y2="2"/>'],
                    ['title' => 'سامانه‌ی تیکتینگ', 'desc' => 'ارتباط مستقیم دانش‌آموز با تیم پشتیبانی برای پیگیری مسائل آموزشی و فنی به‌صورت ساختارمند.', 'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
                    ['title' => 'مرکز اطلاع‌رسانی', 'desc' => 'دریافت پیام‌ها و اطلاعیه‌های مهم مدرسه و مشاوران به‌صورت دسته‌بندی‌شده و قابل پیگیری.', 'icon' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'],
                    ['title' => 'مدیریت مالی و کیف پول', 'desc' => 'کیف پول دیجیتال، پیگیری اقساط شهریه، تاریخچه‌ی تراکنش‌ها و سیستم امتیاز و پاداش.', 'icon' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>'],
                ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                @foreach($features as $i => $f)
                    <div
                        class="glow-on-hover glass rounded-2xl p-5 space-y-3 group reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        {{-- آیکون + عنوان در یک ردیف --}}
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                <div
                                    class="absolute inset-0 bg-primary/20 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span
                                    class="relative inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            {!! $f['icon'] !!}
                        </svg>
                    </span>
                            </div>
                            <h3 class="font-black text-foreground  text-xl leading-tight">{{ $f['title'] }}</h3>
                        </div>

                        <p class="font-medium text-xs text-muted leading-6">{{ $f['desc'] }}</p>
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
                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4z"/>
                            </svg>
                            <span class="font-semibold text-xs text-foreground">ویژه‌ی مدیران مدارس</span>
                        </div>
                        <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                            تصمیم‌گیری <span class="shimmer-text">مبتنی بر داده</span> برای مدیران
                        </h2>
                        <p class="font-medium text-sm text-muted leading-8">
                            با پنل مدیریتی SDFR، دیگر برای ارزیابی عملکرد دانش‌آموزان به حدس و گمان نیاز ندارید.
                            گزارش‌های دقیق، نمودارهای پیشرفت و خروجی‌های قابل ارائه به اولیا را
                            در یک مکان متمرکز در اختیار خواهید داشت.
                        </p>
                        <ul class="grid sm:grid-cols-2 gap-3 pt-2">
                            @foreach([
                                'گزارش‌گیری دقیق و دوره‌ای از هر دانش‌آموز',
                                'پایش لحظه‌ای ساعت مطالعه‌ی کل مدرسه',
                                'ارتباط مستقیم با مشاوران تخصصی',
                                'پشتیبانی اختصاصی در طول دوره‌ی قرارداد',
                            ] as $item)
                                <li class="flex items-start gap-2.5">
                                <span
                                    class="flex items-center justify-center w-6 h-6 bg-primary/15 text-primary border border-primary/20 rounded-md mt-0.5 shrink-0">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
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
                                    <svg class="w-4 h-4 text-muted" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="20" x2="18" y2="10"/>
                                        <line x1="12" y1="20" x2="12" y2="4"/>
                                        <line x1="6" y1="20" x2="6" y2="14"/>
                                    </svg>
                                    <span class="font-bold text-xs text-muted">نمونه‌ی گزارش هفتگی</span>
                                </div>
                                <span
                                    class="inline-flex items-center gap-1 font-semibold text-[10px] text-primary glass rounded-full px-2 py-0.5">
                                <span class="relative flex w-1.5 h-1.5">
                                    <span
                                        class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                    <span class="relative inline-flex w-1.5 h-1.5 bg-primary rounded-full"></span>
                                </span>
                                زنده
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
                                            <span
                                                class="font-semibold text-xs text-foreground">{{ $row['name'] }}</span>
                                            <span class="font-black text-xs text-primary">{{ $row['val'] }}</span>
                                        </div>
                                        <div class="h-2 bg-secondary/50 rounded-full overflow-hidden">
                                            <div
                                                class="h-full bg-gradient-to-l from-primary to-primary/70 rounded-full grow-bar"
                                                style="width: {{ $row['w'] }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-3 border-t border-border grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <div class="font-black text-foreground text-lg">۱٬۲۴۰</div>
                                    <div class="font-medium text-[10px] text-muted">ساعت مطالعه</div>
                                </div>
                                <div>
                                    <div class="font-black text-foreground text-lg">۳۲۰</div>
                                    <div class="font-medium text-[10px] text-muted">گزارش روزانه</div>
                                </div>
                                <div>
                                    <div class="font-black text-foreground text-lg">۸۵</div>
                                    <div class="font-medium text-[10px] text-muted">آزمون</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ★ گوی چرخان --}}
            <div class="orb-track">
                <span class="orb"></span>
                <span class="orb-trail"></span>
            </div>
        </section>


        {{-- ======================= HOW IT WORKS ======================= --}}
        <section class="relative space-y-10 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>

            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                         stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">فرآیند ساده</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">
                    فرآیند
                    <span class="shimmer-text">همکاری</span>


                    در چهار گام</h2>
                <p class="font-medium text-sm text-muted max-w-2xl mx-auto leading-7">
                    از ثبت درخواست تا فعال‌سازی کامل خدمات، تنها چهار قدم با ما فاصله دارید.
                </p>
            </div>

            @php
                $steps = [
                    ['n' => '۰۱', 't' => 'ثبت درخواست',           'd' => 'فرم همکاری را در پایین این صفحه تکمیل و ارسال می‌کنید.'],
                    ['n' => '۰۲', 't' => 'تماس کارشناس',          'd' => 'کارشناسان ما با شما تماس گرفته و جلسه‌ی مشاوره‌ی رایگان تنظیم می‌شود.'],
                    ['n' => '۰۳', 't' => 'عقد قرارداد',            'd' => 'قرارداد رسمی متناسب با تعداد دانش‌آموزان و نیاز مدرسه منعقد می‌گردد.'],
                    ['n' => '۰۴', 't' => 'فعال‌سازی پنل',          'd' => 'حساب دانش‌آموزان و دسترسی مدیران فعال شده و دوره‌ی همکاری آغاز می‌شود.'],
                ];
            @endphp

            <div class="relative grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="hidden lg:block absolute top-12 right-[12.5%] left-[12.5%] h-px bg-gradient-to-l from-transparent via-primary/30 to-transparent"></div>

                @foreach($steps as $i => $s)
                    <div
                        class="glow-on-hover glass rounded-2xl p-5 space-y-3 relative reveal reveal-delay-{{ $i + 1 }}">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-4xl text-primary/20">{{ $s['n'] }}</span>
                            <span
                                class="relative flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                 stroke-linejoin="round">
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
                <div class="overflow-hidden rounded-3xl relative p-6 md:p-10"
                     x-data="{
                        count: '',
                        tiers: @js($tiersJs),
                        fee: @js($managerFee),
                        threshold: @js($discountAfter),
                        get n() {
                            let v = parseInt(String(this.count).replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[^0-9]/g, ''));
                            return isNaN(v) || v < 0 ? 0 : v;
                        },
                        get payable() {
                            let n = this.n, total = 0, remaining = n, prev = 0;
                            for (const t of this.tiers) {
                                const max = t.max === null ? Infinity : t.max;
                                const take = Math.min(remaining, max - prev);
                                total += take * t.price;
                                remaining -= take;
                                prev = max;
                                if (remaining <= 0) break;
                            }
                            return total;
                        },
                        get discount() { return this.n > this.threshold ? this.n * this.fee : 0; },
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
                                <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="2" width="16" height="20" rx="2"/>
                                    <line x1="8" y1="6" x2="16" y2="6"/>
                                    <line x1="8" y1="10" x2="8" y2="10"/>
                                    <line x1="12" y1="10" x2="12" y2="10"/>
                                    <line x1="16" y1="10" x2="16" y2="10"/>
                                    <line x1="8" y1="14" x2="8" y2="14"/>
                                    <line x1="12" y1="14" x2="12" y2="14"/>
                                    <line x1="16" y1="14" x2="16" y2="18"/>
                                    <line x1="8" y1="18" x2="12" y2="18"/>
                                </svg>
                                <span class="font-semibold text-xs text-foreground">محاسبه‌گر هزینه</span>
                            </div>
                            <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                                <span class="shimmer-text">هزینه و سود</span> همکاری را همین حالا برآورد کنید
                            </h2>
                            <p class="font-medium text-sm text-muted leading-7">
                                تعداد دانش‌آموزان مدرسه‌ی خود را وارد کنید تا پک متناسب، هزینه‌ی قابل پرداخت
                                و میزان سود و تخفیف شما به‌صورت لحظه‌ای محاسبه شود.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-12 gap-6 md:gap-8 items-stretch">
                            {{-- ورودی تعداد دانش‌آموز --}}
                            <div class="md:col-span-5">
                                <div class="glass-strong rounded-2xl p-6 h-full flex flex-col justify-center space-y-5">
                                    <label for="calc_count"
                                           class="font-semibold text-sm text-foreground flex items-center gap-2">
                                        <svg class="w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                        تعداد دانش‌آموزان
                                    </label>
                                    <input type="number" min="1" id="calc_count" x-model="count" inputmode="numeric"
                                           placeholder="مثلاً ۵۰"
                                           class="form-input w-full h-14 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-lg font-bold text-foreground px-5 text-center">

                                    {{-- جدول پک‌ها --}}
                                    <div class="space-y-2 pt-2">
                                        <div class="font-semibold text-[11px] text-muted">قیمت هر دانش‌آموز در هر پک:</div>
                                        @foreach($pricingTiers as $i => $tier)
                                            @php
                                                $prevMax = $i === 0 ? 0 : $pricingTiers[$i - 1]['max'];
                                                $range = $tier['max'] > 1000000
                                                    ? ($prevMax + 1) . '+'
                                                    : ($prevMax + 1) . ' تا ' . $tier['max'];
                                            @endphp
                                            <div class="flex items-center justify-between glass rounded-lg px-3 py-2 text-xs">
                                                <span class="font-bold text-foreground">{{ $tier['label'] }}
                                                    <span class="font-medium text-muted">({{ $range }} نفر)</span>
                                                </span>
                                                <span class="font-semibold text-primary">
                                                    {{ number_format($tier['price']) }} تومان
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- نتیجه‌ی محاسبه --}}
                            <div class="md:col-span-7">
                                <div class="glass-strong rounded-2xl p-6 h-full shadow-xl shadow-primary/5 space-y-4">
                                    {{-- حالت خالی --}}
                                    <template x-if="n === 0">
                                        <div class="flex flex-col items-center justify-center text-center h-full py-10 space-y-3">
                                            <svg class="w-12 h-12 text-primary/40" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 11H3v10h6V11zM21 3h-6v18h6V3zM15 7H9v14h6V7z"/>
                                            </svg>
                                            <p class="font-medium text-sm text-muted">
                                                برای مشاهده‌ی نتیجه، تعداد دانش‌آموزان را وارد کنید.
                                            </p>
                                        </div>
                                    </template>

                                    {{-- نتیجه --}}
                                    <template x-if="n > 0">
                                        <div class="space-y-4">
                                            {{-- پک پیشنهادی --}}
                                            <div class="flex items-center justify-between glass rounded-xl p-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M12 2 2 7l10 5 10-5-10-5z"/>
                                                            <path d="m2 17 10 5 10-5"/>
                                                            <path d="m2 12 10 5 10-5"/>
                                                        </svg>
                                                    </span>
                                                    <div>
                                                        <div class="font-medium text-[11px] text-muted">پک پیشنهادی برای شما</div>
                                                        <div class="font-black text-foreground text-lg">
                                                            پک <span class="text-primary" x-text="pkg"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-left">
                                                    <div class="font-medium text-[11px] text-muted">تعداد دانش‌آموز</div>
                                                    <div class="font-black text-foreground text-lg" x-text="fmt(n) + ' نفر'"></div>
                                                </div>
                                            </div>

                                            {{-- هزینه‌ی قابل پرداخت --}}
                                            <div class="rounded-xl p-4 bg-primary/10 border border-primary/20">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="2" y="6" width="20" height="12" rx="2"/>
                                                        <circle cx="12" cy="12" r="2"/>
                                                        <path d="M6 12h.01M18 12h.01"/>
                                                    </svg>
                                                    <span class="font-semibold text-xs text-muted">هزینه‌ای که باید پرداخت کنید</span>
                                                </div>
                                                <div class="font-black text-primary text-2xl md:text-3xl">
                                                    <span x-text="fmt(payable)"></span>
                                                    <span class="text-base font-bold text-muted">تومان</span>
                                                </div>
                                            </div>

                                            {{-- سود و تخفیف --}}
                                            <div class="rounded-xl p-4 bg-emerald-500/10 border border-emerald-500/20">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-4 h-4 text-emerald-500" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="12" y1="1" x2="12" y2="23"/>
                                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                    </svg>
                                                    <span class="font-semibold text-xs text-muted">سود و تخفیف شما</span>
                                                </div>
                                                <div class="font-black text-emerald-500 text-2xl md:text-3xl">
                                                    <span x-text="fmt(discount)"></span>
                                                    <span class="text-base font-bold text-muted">تومان</span>
                                                </div>
                                                <p class="font-medium text-[11px] text-muted leading-5 mt-1"
                                                   x-show="discount === 0">
                                                    تخفیف از <span x-text="fmt(threshold)"></span> دانش‌آموز به بعد فعال می‌شود
                                                    (به ازای هر دانش‌آموز <span x-text="fmt(fee)"></span> تومان).
                                                </p>
                                                <p class="font-medium text-[11px] text-muted leading-5 mt-1"
                                                   x-show="discount > 0">
                                                    به ازای هر دانش‌آموز <span x-text="fmt(fee)"></span> تومان سود برای مدیر مدرسه.
                                                </p>
                                            </div>

                                            <a href="#contract-form"
                                               class="group w-full inline-flex items-center justify-center h-12 bg-primary hover:bg-primary/90 transition-all rounded-full text-white font-bold text-sm px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.01]">
                                                <span>ثبت درخواست با این تعداد</span>
                                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1"
                                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ★ گوی چرخان --}}
                <div class="orb-track">
                    <span class="orb"></span>
                    <span class="orb-trail"></span>
                </div>
            </div>
        </section>

        {{-- ========================== FORM ========================== --}}
        <section id="contract-form" class="scroll-mt-24 reveal">
            <div class="relative rounded-3xl glass orbit-wrap">
                <div class="overflow-hidden rounded-3xl relative p-6 md:p-10">
                    <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                    <div class="absolute -top-20 right-1/4 w-80 h-80 bg-primary/20 rounded-full blur-3xl blob-1"></div>
                    <div
                        class="absolute -bottom-20 left-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl blob-2"></div>

                    <div class="relative grid md:grid-cols-12 gap-6 md:gap-10 items-stretch">

                        <div class="md:col-span-5 space-y-6 reveal-right">
                            <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                            <span class="relative flex w-1.5 h-1.5">
                                <span
                                    class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                <span class="relative inline-flex w-1.5 h-1.5 bg-primary rounded-full"></span>
                            </span>
                                <span class="font-semibold text-xs text-foreground">فرم درخواست همکاری</span>
                            </div>
                            <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                                آماده‌ی شروع همکاری با <span class="shimmer-text">SDFR</span> هستید؟
                            </h2>
                            <p class="font-medium text-sm text-muted leading-8">
                                اطلاعات تماس مدرسه‌ی خود را در فرم روبه‌رو وارد کنید.
                                کارشناسان ما در اسرع وقت با شما تماس گرفته و جزئیات قرارداد را
                                در یک جلسه‌ی تخصصی رایگان بررسی خواهند کرد.
                            </p>

                            <ul class="space-y-3 pt-2">
                                @foreach([
                                    ['t' => 'بدون پیش‌پرداخت',                'svg' => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'],
                                    ['t' => 'مشاوره‌ی اولیه‌ی رایگان',         'svg' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                                    ['t' => 'پشتیبانی اختصاصی در طول قرارداد', 'svg' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>'],
                                ] as $item)
                                    <li class="flex items-center gap-3 glass rounded-xl p-3">
                                    <span
                                        class="flex items-center justify-center w-9 h-9 bg-primary/15 text-primary border border-primary/20 rounded-lg shrink-0">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            {!! $item['svg'] !!}
                                        </svg>
                                    </span>
                                        <span class="font-semibold text-sm text-foreground">{{ $item['t'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="md:col-span-7 reveal-left">
                            <div class="glass-strong rounded-2xl p-6 shadow-xl shadow-primary/5">
                                <div class="flex items-center gap-3 pb-5 mb-5 border-b border-border">
                                <span
                                    class="flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline
                                            points="14 2 14 8 20 8"/>
                                    </svg>
                                </span>
                                    <div>
                                        <div class="font-black text-foreground">اطلاعات قرارداد مدرسه</div>
                                        <p class="font-medium text-[11px] text-muted leading-5 mt-0.5">
                                            تمامی فیلدها الزامی است. اطلاعات شما کاملاً محرمانه خواهد بود.
                                        </p>
                                    </div>
                                </div>

                                <form wire:submit.prevent="submit" class="space-y-5">
                                    <div class="space-y-1.5">
                                        <label for="full_name"
                                               class="font-semibold text-xs text-muted flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            نام و نام خانوادگی
                                        </label>
                                        <input type="text" id="full_name" wire:model="full_name"
                                               placeholder="نام کامل مدیر یا نماینده مدرسه"
                                               class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-sm text-foreground px-5">
                                        @error('full_name')
                                        <div class="font-medium text-xs text-red-500 mr-2 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="school_name"
                                               class="font-semibold text-xs text-muted flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 21h18"/>
                                                <path d="M5 21V7l8-4v18"/>
                                                <path d="M19 21V11l-6-4"/>
                                            </svg>
                                            نام مدرسه
                                        </label>
                                        <input type="text" id="school_name" wire:model="school_name"
                                               placeholder="نام رسمی مدرسه"
                                               class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-sm text-foreground px-5">
                                        @error('school_name')
                                        <div class="font-medium text-xs text-red-500 mr-2 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="mobile"
                                               class="font-semibold text-xs text-muted flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                            </svg>
                                            شماره تلفن همراه
                                        </label>
                                        <input type="tel" dir="ltr" id="mobile" wire:model="mobile"
                                               placeholder="09xxxxxxxxx"
                                               class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-sm text-foreground px-5 text-left">
                                        @error('mobile')
                                        <div class="font-medium text-xs text-red-500 mr-2 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="student_count"
                                               class="font-semibold text-xs text-muted flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                            تعداد دانش‌آموز
                                        </label>
                                        <input type="number" min="1" id="student_count" wire:model="student_count"
                                               placeholder="تعداد دانش‌آموزان مدرسه"
                                               class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-sm text-foreground px-5">
                                        @error('student_count')
                                        <div class="font-medium text-xs text-red-500 mr-2 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label for="state_id"
                                                   class="font-semibold text-xs text-muted flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                    <circle cx="12" cy="10" r="3"/>
                                                </svg>
                                                استان
                                            </label>
                                            <select id="state_id" wire:model.live="state_id"
                                                    class="form-select w-full h-12 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-sm text-foreground px-5">
                                                <option value="">انتخاب استان</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('state_id')
                                            <div class="font-medium text-xs text-red-500 mr-2 mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="city_id"
                                                   class="font-semibold text-xs text-muted flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                    <line x1="9" y1="9" x2="9.01" y2="9"/>
                                                    <line x1="15" y1="9" x2="15.01" y2="9"/>
                                                </svg>
                                                شهر
                                            </label>
                                            <select id="city_id" wire:model="city_id" @disabled(empty($state_id))
                                            class="form-select w-full h-12 !ring-0 !ring-offset-0 bg-secondary/60 backdrop-blur border-border focus:border-primary focus:bg-secondary transition-all rounded-xl text-sm text-foreground px-5 disabled:opacity-50">
                                                <option
                                                    value="">{{ empty($state_id) ? 'ابتدا استان را انتخاب کنید' : 'انتخاب شهر' }}</option>
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                            <div class="font-medium text-xs text-red-500 mr-2 mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 pt-3 border-t border-border">
                                        <p class="font-medium text-[11px] text-muted leading-5 max-w-[60%]">
                                            با ارسال این فرم، با
                                            <a href="{{ route('client.terms') }}"
                                               class="text-primary hover:underline font-bold">قوانین و مقررات</a>
                                            موافقت می‌نمایید.
                                        </p>
                                        <button type="submit"
                                                class="group h-12 inline-flex items-center justify-center bg-primary hover:bg-primary/90 transition-all rounded-full text-white px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02] disabled:opacity-60 disabled:hover:scale-100"
                                                wire:loading.attr="disabled" wire:target="submit">
                                        <span class="font-semibold text-sm flex items-center" wire:loading.remove
                                              wire:target="submit">
                                            ثبت درخواست
                                            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                            </svg>
                                        </span>
                                            <span wire:loading wire:target="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                                 preserveAspectRatio="xMidYMid" width="28px" height="28px"
                                                 style="shape-rendering: auto; display: block; background: transparent;">
                                                <g>
                                                    <path stroke="none" fill="#ffffff"
                                                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                                                          repeatCount="indefinite" dur="0.81s"
                                                                          type="rotate" attributeName="transform"/>
                                                    </path>
                                                </g>
                                            </svg>
                                        </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ★ گوی چرخان --}}
                <div class="orb-track">
                    <span class="orb"></span>
                    <span class="orb-trail"></span>
                </div>
            </div>
        </section>
        {{-- ===== Reveal-on-scroll JS ===== --}}
        @push('script')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, {
                        threshold: 0.1,
                        rootMargin: '0px 0px -50px 0px'
                    });

                    document.querySelectorAll('.reveal, .reveal-right, .reveal-left').forEach(el => {
                        observer.observe(el);
                    });
                });
            </script>
        @endpush
    </div>
</div>


