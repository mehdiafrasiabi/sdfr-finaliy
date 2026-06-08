<div>

    @push('link')
        <style>
            html { scroll-behavior: smooth; }

            /* ---------- Custom scrollbar ---------- */
            ::-webkit-scrollbar { width: 10px; }
            ::-webkit-scrollbar-track { background: hsl(var(--background)); }
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
            @keyframes float-slow { 0%,100% { transform: translate(0,0) scale(1);} 50% { transform: translate(20px,-30px) scale(1.05);} }
            @keyframes float-reverse { 0%,100% { transform: translate(0,0) scale(1);} 50% { transform: translate(-25px,20px) scale(1.08);} }
            .blob-1 { animation: float-slow 12s ease-in-out infinite; }
            .blob-2 { animation: float-reverse 14s ease-in-out infinite; }

            /* ---------- Shimmer ---------- */
            @keyframes shimmer { 0% { background-position: -200% 0;} 100% { background-position: 200% 0;} }
            .shimmer-text {
                background: linear-gradient(90deg, hsl(var(--foreground)) 0%, hsl(var(--primary)) 50%, hsl(var(--foreground)) 100%);
                background-size: 200% 100%;
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: shimmer 4s linear infinite;
            }

            /* ---------- Floaty ---------- */
            @keyframes floaty { 0%,100% { transform: translateY(0);} 50% { transform: translateY(-10px);} }
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

            /* ---------- Orbiting blue orb ---------- */
            .orbit-wrap { position: relative; }
            .orbit-wrap .orb-track {
                position: absolute; bottom: -32px; left: 0; right: 0; height: 24px;
                pointer-events: none; z-index: 5;
            }
            .orbit-wrap .orb-track::before {
                content: ''; position: absolute; top: 50%; left: 8%; right: 8%; height: 1px;
                background: linear-gradient(to left, transparent, hsl(217 91% 60% / 0.5), transparent);
                transform: translateY(-50%);
            }
            .orbit-wrap .orb {
                position: absolute; top: 50%; right: 0; width: 14px; height: 14px; margin-top: -7px;
                border-radius: 999px;
                background: radial-gradient(circle at 30% 30%, #93c5fd, #3b82f6 55%, #1d4ed8);
                box-shadow: 0 0 12px rgba(96,165,250,.9), 0 0 24px rgba(59,130,246,.6), 0 0 40px rgba(37,99,235,.4);
                animation: orbit-rtl 6s linear infinite, glow-pulse 2.5s ease-in-out infinite;
            }
            .orbit-wrap .orb-trail {
                position: absolute; top: 50%; right: 0; width: 7px; height: 7px; margin-top: -3.5px;
                border-radius: 999px; background: #93c5fd; box-shadow: 0 0 8px rgba(147,197,253,.8);
                opacity: .5; animation: orbit-rtl 6s linear infinite; animation-delay: -0.35s;
            }
            @keyframes orbit-rtl {
                0% { right: 0; transform: translateY(0) scale(1);}
                25% { right: 50%; transform: translateY(-8px) scale(1.2);}
                50% { right: calc(100% - 14px); transform: translateY(0) scale(1);}
                75% { right: 50%; transform: translateY(8px) scale(0.8);}
                100% { right: 0; transform: translateY(0) scale(1);}
            }
            @keyframes glow-pulse {
                0%,100% { box-shadow: 0 0 12px rgba(96,165,250,.9), 0 0 24px rgba(59,130,246,.6);}
                50% { box-shadow: 0 0 20px rgba(96,165,250,1), 0 0 40px rgba(59,130,246,.8), 0 0 60px rgba(37,99,235,.5);}
            }

            /* ---------- Side floating decorations ---------- */
            .side-deco { position: fixed; z-index: 1; pointer-events: none; opacity: 0.2; }
            .side-deco-right { right: 24px; top: 25%; animation: side-float-1 7s ease-in-out infinite; }
            .side-deco-left { left: 24px; top: 60%; animation: side-float-2 9s ease-in-out infinite; }
            .side-deco-right-2 { right: 40px; top: 75%; animation: side-float-1 11s ease-in-out infinite; }
            @keyframes side-float-1 { 0%,100% { transform: translateY(0) rotate(0deg);} 50% { transform: translateY(-40px) rotate(8deg);} }
            @keyframes side-float-2 { 0%,100% { transform: translateY(0) rotate(0deg);} 50% { transform: translateY(30px) rotate(-10deg);} }
            @media (max-width: 1024px) { .side-deco { display: none; } }

            /* ---------- Reveal on scroll ---------- */
            .reveal { opacity: 0; transform: translateY(30px);
                transition: opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1);
                will-change: opacity, transform; }
            .reveal.is-visible { opacity: 1; transform: translateY(0); }
            .reveal-right { opacity: 0; transform: translateX(40px);
                transition: opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1); }
            .reveal-right.is-visible { opacity: 1; transform: translateX(0); }
            .reveal-left { opacity: 0; transform: translateX(-40px);
                transition: opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1); }
            .reveal-left.is-visible { opacity: 1; transform: translateX(0); }
            .reveal-delay-1 { transition-delay: 0.1s; }
            .reveal-delay-2 { transition-delay: 0.2s; }
            .reveal-delay-3 { transition-delay: 0.3s; }
            .reveal-delay-4 { transition-delay: 0.4s; }

            /* ---------- Marquee logos ---------- */
            @keyframes marquee-rtl { from { transform: translateX(0);} to { transform: translateX(-50%);} }
            .marquee-track { display: flex; width: max-content; animation: marquee-rtl 28s linear infinite; }
            .marquee-mask {
                -webkit-mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent);
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent);
            }

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
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
    </svg>
    <svg class="side-deco side-deco-left w-10 h-10 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
    </svg>
    <svg class="side-deco side-deco-right-2 w-8 h-8 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
    </svg>

    <div dir="rtl" class="max-w-7xl mx-auto px-4 space-y-16 md:space-y-24 py-6">

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
                            <span class="font-semibold text-xs text-foreground">پلتفرم هوشمند پایش و مشاوره‌ی تحصیلی</span>
                        </div>

                        <h1 class="font-black text-3xl md:text-5xl text-foreground" style="line-height: 1.5">
                            با <span class="shimmer-text">SDFR</span>
                            مسیر پیشرفت تحصیلی را
                            <span class="shimmer-text">هوشمند</span>
                            طی کنید
                        </h1>

                        <p class="font-medium text-sm md:text-base text-muted leading-8 max-w-2xl">
                            ما در <span class="font-black text-foreground">SDFR</span>
                            یک زیرساخت یکپارچه برای پایش مطالعه، برنامه‌ریزی هفتگی،
                            مشاوره‌ی تخصصی و گزارش‌گیری هوشمند ساخته‌ایم تا دانش‌آموزان،
                            اولیا و مدارس بتوانند با اطمینان و بر پایه‌ی داده،
                            تصمیم بگیرند و پیشرفت کنند.
                        </p>

                        <div class="flex flex-wrap gap-3 pt-2">
                            <a href="#about"
                               class="inline-flex items-center justify-center h-12 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-sm px-8">
                                بیشتر درباره‌ی ما
                            </a>
                            <a href="{{ route('client.download') }}"
                               class="group relative inline-flex items-center justify-center h-12 bg-primary hover:bg-primary/90 transition-all rounded-full text-white font-bold text-sm px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02]">
                                <span>دانلود اپلیکیشن</span>
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                </svg>
                            </a>
                        </div>

                        <div class="flex flex-wrap items-center gap-5 pt-4">
                            <div class="flex -space-x-2 space-x-reverse">
                                @foreach (['#6366f1','#8b5cf6','#ec4899','#f59e0b'] as $c)
                                    <div class="w-8 h-8 rounded-full border-2 border-background" style="background: {{ $c }}"></div>
                                @endforeach
                            </div>
                            <div class="text-xs">
                                <div class="font-bold text-foreground">+۱۰٬۰۰۰ دانش‌آموز</div>
                                <div class="text-muted">با ما در حال پیشرفت‌اند</div>
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
                                        <svg class="w-10 h-10 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="font-black text-foreground text-lg">یادگیری داده‌محور</h3>
                                <p class="font-medium text-xs text-muted leading-6">
                                    هر تصمیم آموزشی بر پایه‌ی گزارش‌های دقیق و لحظه‌ای،
                                    نه حدس و گمان.
                                </p>
                                <div class="grid grid-cols-2 gap-3 w-full pt-2 border-t border-border">
                                    <div class="text-center">
                                        <div class="font-black text-primary text-lg">۲۴/۷</div>
                                        <div class="text-[10px] text-muted">همراهی</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="font-black text-primary text-lg">+۹۸٪</div>
                                        <div class="text-[10px] text-muted">رضایت</div>
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
            <div class="orb-track"><span class="orb"></span><span class="orb-trail"></span></div>
        </section>


        {{-- ===================== ABOUT / WHO WE ARE ===================== --}}
        <section id="about" class="relative space-y-10 scroll-mt-24 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
            <div class="grid md:grid-cols-12 gap-8 md:gap-12 items-center">
                <div class="md:col-span-6 space-y-5 reveal-right">
                    <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                        <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                        </svg>
                        <span class="font-semibold text-xs text-foreground">ما که هستیم؟</span>
                    </div>
                    <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                        تیمی که <span class="shimmer-text">آموزش</span> را با تکنولوژی متحول می‌کند
                    </h2>
                    <p class="font-medium text-sm text-muted leading-8">
                        SDFR زاده‌ی این باور است که هر دانش‌آموز با برنامه‌ی درست، همراهی پیوسته
                        و بازخورد دقیق می‌تواند به بهترین نسخه‌ی خود تبدیل شود. ما ابزارهای پراکنده‌ی
                        مطالعه، مشاوره و گزارش‌گیری را در یک پلتفرم منسجم گرد هم آورده‌ایم تا
                        تجربه‌ی یادگیری ساده، شفاف و قابل‌اندازه‌گیری شود.
                    </p>
                    <p class="font-medium text-sm text-muted leading-8">
                        از دانش‌آموز و اولیا تا مشاور و مدیر مدرسه؛ همه در یک اکوسیستم واحد،
                        تصویری روشن از مسیر پیشرفت در اختیار دارند.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('client.about-us') }}"
                           class="inline-flex items-center justify-center h-11 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-xs px-6">
                            صفحه‌ی درباره‌ی ما
                        </a>
                        <a href="{{ route('client.contact-us') }}"
                           class="inline-flex items-center justify-center h-11 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-xs px-6">
                            تماس با ما
                        </a>
                    </div>
                </div>

                <div class="md:col-span-6 reveal-left">
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $aboutCards = [
                                ['t' => 'ماموریت ما', 'd' => 'دسترس‌پذیر کردن مشاوره و برنامه‌ریزی تحصیلی باکیفیت برای همه.', 'icon' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>'],
                                ['t' => 'چشم‌انداز', 'd' => 'تبدیل‌شدن به مرجع اول پایش هوشمند مطالعه در ایران.', 'icon' => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>'],
                                ['t' => 'ارزش‌ها', 'd' => 'شفافیت، همراهی پیوسته و تصمیم‌گیری بر پایه‌ی داده.', 'icon' => '<path d="M12 2 4 5v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V5z"/>'],
                                ['t' => 'تعهد ما', 'd' => 'پشتیبانی انسانی و واقعی در کنار ابزارهای هوشمند.', 'icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>'],
                            ];
                        @endphp
                        @foreach($aboutCards as $i => $c)
                            <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 reveal reveal-delay-{{ $i + 1 }}">
                                <span class="inline-flex items-center justify-center w-11 h-11 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $c['icon'] !!}</svg>
                                </span>
                                <h3 class="font-black text-foreground text-base">{{ $c['t'] }}</h3>
                                <p class="font-medium text-xs text-muted leading-6">{{ $c['d'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>


        {{-- ===================== BRAND / SDFR PILLARS ===================== --}}
        <section class="relative space-y-10 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">معنای SDFR</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">
                    چهار ستون اصلی <span class="shimmer-text">الگوی آموزشی</span> ما
                </h2>
                <p class="font-medium text-sm text-muted max-w-2xl mx-auto leading-7">
                    نام برند ما از چهار اصل بنیادین که هویت آموزشی SDFR را شکل می‌دهد گرفته شده است.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-5" dir="ltr">
                @php
                    $pillars = [
                        ['letter' => 'S', 'en' => 'Specific',   'fa' => 'منحصر به فرد', 'desc' => 'برنامه‌ریزی اختصاصی بر اساس وضعیت هر دانش‌آموز.'],
                        ['letter' => 'D', 'en' => 'Discussion', 'fa' => 'مبحثی',         'desc' => 'تمرکز بر مباحث درسی و یادگیری عمیق و گام‌به‌گام.'],
                        ['letter' => 'F', 'en' => 'Flexible',   'fa' => 'انعطاف‌پذیر',    'desc' => 'انطباق برنامه با شرایط واقعی دانش‌آموز و مدرسه.'],
                        ['letter' => 'R', 'en' => 'Reportage',  'fa' => 'گزارش‌محور',     'desc' => 'گزارش‌های دقیق و دوره‌ای برای مدیران و اولیا.'],
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
            <div class="overflow-hidden rounded-3xl relative p-6 md:p-8">
                <div class="absolute inset-0 grid-bg-sm pointer-events-none"></div>
                <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-40 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @php
                        $stats = [
                            ['v' => '+۱۰٬۰۰۰', 'l' => 'دانش‌آموز فعال', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                            ['v' => '+۲۰۰',    'l' => 'مدرسه‌ی همکار',  'icon' => '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>'],
                            ['v' => '+۱۵۰',    'l' => 'مشاور تخصصی',    'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                            ['v' => '۹۸٪',     'l' => 'رضایت کاربران',  'icon' => '<polyline points="20 6 9 17 4 12"/>'],
                        ];
                    @endphp
                    @foreach($stats as $i => $s)
                        <div class="relative text-center space-y-2 p-4 rounded-2xl reveal reveal-delay-{{ $i + 1 }}">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-primary/10 text-primary rounded-xl mb-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $s['icon'] !!}</svg>
                            </div>
                            <div class="font-black text-foreground text-2xl md:text-3xl">{{ $s['v'] }}</div>
                            <div class="font-medium text-xs text-muted">{{ $s['l'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="orb-track"><span class="orb"></span><span class="orb-trail"></span></div>
        </section>


        {{-- ====================== WHAT WE DO (3 CORE) ====================== --}}
        <section class="relative space-y-10 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">ما چه می‌کنیم</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">
                    سه گام تا <span class="shimmer-text">پیشرفت پایدار</span>
                </h2>
                <p class="font-medium text-sm text-muted leading-7">
                    کل تجربه‌ی SDFR حول سه محور بنیادین می‌چرخد که در کنار هم
                    یک چرخه‌ی کامل یادگیری می‌سازند.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-4 md:gap-5">
                @php
                    $core = [
                        ['t' => 'پایش مطالعه', 'd' => 'ثبت دقیق ساعت مطالعه، گزارش‌های روزانه و رصد لحظه‌ای عملکرد دانش‌آموز در طول هفته.', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                        ['t' => 'برنامه‌ریزی هوشمند', 'd' => 'تدوین برنامه‌ی هفتگی اختصاصی توسط مشاور، متناسب با هدف، توان و شرایط واقعی هر دانش‌آموز.', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                        ['t' => 'گزارش‌گیری دقیق', 'd' => 'کارنامه‌ها و نمودارهای پیشرفت که تصویری شفاف از روند یادگیری به دانش‌آموز، اولیا و مدرسه می‌دهد.', 'icon' => '<line x1="3" y1="3" x2="3" y2="21"/><line x1="3" y1="21" x2="21" y2="21"/><polyline points="7 16 11 12 15 16 21 10"/>'],
                    ];
                @endphp
                @foreach($core as $i => $c)
                    <div class="glow-on-hover glass rounded-2xl p-6 space-y-4 group reveal reveal-delay-{{ $i + 1 }}">
                        <div class="relative">
                            <div class="absolute inset-0 bg-primary/20 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <span class="relative inline-flex items-center justify-center w-14 h-14 bg-primary/10 text-primary border border-primary/20 rounded-2xl group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $c['icon'] !!}</svg>
                            </span>
                        </div>
                        <h3 class="font-black text-foreground text-xl">{{ $c['t'] }}</h3>
                        <p class="font-medium text-sm text-muted leading-7">{{ $c['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- ====================== FEATURES GRID ====================== --}}
        <section id="features" class="relative space-y-10 scroll-mt-24 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">امکانات پلتفرم</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">
                    هرآنچه برای <span class="shimmer-text">یادگیری مؤثر</span> لازم دارید
                </h2>
                <p class="font-medium text-sm text-muted leading-7">
                    مجموعه‌ای از ابزارهای حرفه‌ای که تجربه‌ی مطالعه و مشاوره را در یک جا متمرکز می‌کند.
                </p>
            </div>

            @php
                $features = [
                    ['title' => 'داشبورد هوشمند', 'desc' => 'نمای لحظه‌ای از ساعت مطالعه، برنامه‌ی امروز و وضعیت کلی دانش‌آموز در یک نگاه.', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>'],
                    ['title' => 'ثبت ساعت مطالعه', 'desc' => 'تایمر هوشمند ثبت زمان مطالعه برای هر بخش از برنامه، با رصد دقیق زمان مفید.', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                    ['title' => 'برنامه‌ی هفتگی', 'desc' => 'برنامه‌ی مطالعاتی اختصاصی هر دانش‌آموز با جزئیات کامل ساعت، آزمون و درس.', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                    ['title' => 'اتاق مشاوره', 'desc' => 'جلسات مشاوره‌ی هدفمند با مشاوران تخصصی برای هدایت مسیر تحصیلی دانش‌آموز.', 'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                    ['title' => 'آزمون‌های آنلاین', 'desc' => 'برگزاری آزمون‌های تایپی و تشریحی همراه با نتیجه و بازخورد تخصصی.', 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                    ['title' => 'کارنامه‌ی هوشمند', 'desc' => 'کارنامه‌ی ماهانه و نمودارهای پیشرفت برای شناسایی نقاط ضعف و قوت.', 'icon' => '<line x1="3" y1="3" x2="3" y2="21"/><line x1="3" y1="21" x2="21" y2="21"/><polyline points="7 16 11 12 15 16 21 10"/>'],
                    ['title' => 'تایمر پومودرو', 'desc' => 'ابزار تمرکز با چرخه‌های ۲۵ دقیقه‌ای و آمار جلسات تمرکز برای مطالعه‌ی عمیق‌تر.', 'icon' => '<circle cx="12" cy="13" r="8"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="9" y1="2" x2="15" y2="2"/>'],
                    ['title' => 'سامانه‌ی پشتیبانی', 'desc' => 'ارتباط مستقیم با تیم پشتیبانی برای پیگیری ساختارمند مسائل آموزشی و فنی.', 'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
                    ['title' => 'مدیریت مالی و کیف پول', 'desc' => 'کیف پول دیجیتال، پیگیری اقساط و تاریخچه‌ی تراکنش‌ها در یک مکان امن.', 'icon' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>'],
                ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                @foreach($features as $i => $f)
                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 group reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                <div class="absolute inset-0 bg-primary/20 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="relative inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $f['icon'] !!}</svg>
                                </span>
                            </div>
                            <h3 class="font-black text-foreground text-lg leading-tight">{{ $f['title'] }}</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- ====================== FOR WHOM ====================== --}}
        <section class="relative space-y-10 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">برای چه کسانی؟</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">
                    یک پلتفرم، <span class="shimmer-text">سه مخاطب</span>
                </h2>
                <p class="font-medium text-sm text-muted leading-7">
                    SDFR همه‌ی ذی‌نفعان مسیر تحصیل را در یک اکوسیستم واحد همراهی می‌کند.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-4 md:gap-5">
                @php
                    $audiences = [
                        ['t' => 'دانش‌آموزان', 'd' => 'برنامه‌ی روشن، ثبت مطالعه، آزمون و کارنامه؛ همه‌چیز برای تمرکز بر یادگیری.', 'items' => ['داشبورد و برنامه‌ی شخصی', 'آزمون و کارنامه‌ی هوشمند', 'دسترسی به مشاور و پشتیبان'], 'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>'],
                        ['t' => 'اولیا', 'd' => 'تصویری شفاف از روند پیشرفت فرزند، بدون نیاز به حدس و نگرانی.', 'items' => ['گزارش پیشرفت قابل‌فهم', 'اطلاع از وضعیت مطالعه', 'ارتباط با تیم آموزشی'], 'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
                        ['t' => 'مدارس', 'd' => 'پنل مدیریتی برای رصد عملکرد کل دانش‌آموزان و تصمیم‌گیری داده‌محور.', 'items' => ['داشبورد مدیریتی مدرسه', 'گزارش‌گیری دوره‌ای', 'پشتیبانی اختصاصی قرارداد'], 'icon' => '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>'],
                    ];
                @endphp
                @foreach($audiences as $i => $a)
                    <div class="glow-on-hover glass rounded-2xl p-6 space-y-4 reveal reveal-delay-{{ $i + 1 }}">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl shrink-0">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                            </span>
                            <h3 class="font-black text-foreground text-lg">{{ $a['t'] }}</h3>
                        </div>
                        <p class="font-medium text-xs text-muted leading-6">{{ $a['d'] }}</p>
                        <ul class="space-y-2 pt-1">
                            @foreach($a['items'] as $it)
                                <li class="flex items-center gap-2.5">
                                    <span class="flex items-center justify-center w-5 h-5 bg-primary/15 text-primary border border-primary/20 rounded-md shrink-0">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    <span class="font-semibold text-xs text-foreground leading-5">{{ $it }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('client.schools') }}"
                   class="inline-flex items-center justify-center h-11 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-xs px-7">
                    مدیر مدرسه هستید؟ طرح همکاری مدارس
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                </a>
            </div>
        </section>


        {{-- ======================= HOW IT WORKS ======================= --}}
        <section class="relative space-y-10 reveal">
            <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    <span class="font-semibold text-xs text-foreground">شروع ساده</span>
                </div>
                <h2 class="font-black text-2xl md:text-3xl text-foreground">
                    در چهار گام <span class="shimmer-text">همراه ما</span> شوید
                </h2>
                <p class="font-medium text-sm text-muted max-w-2xl mx-auto leading-7">
                    از نصب اپلیکیشن تا دریافت برنامه‌ی شخصی، تنها چند قدم با شروع فاصله دارید.
                </p>
            </div>

            @php
                $steps = [
                    ['n' => '۰۱', 't' => 'نصب و ثبت‌نام', 'd' => 'اپلیکیشن را نصب کرده و حساب کاربری خود را در چند دقیقه می‌سازید.'],
                    ['n' => '۰۲', 't' => 'تعیین هدف', 'd' => 'هدف‌ها و وضعیت تحصیلی شما بررسی و برنامه‌ی اولیه تنظیم می‌شود.'],
                    ['n' => '۰۳', 't' => 'مطالعه و ثبت', 'd' => 'طبق برنامه پیش می‌روید و ساعت مطالعه و گزارش‌ها را ثبت می‌کنید.'],
                    ['n' => '۰۴', 't' => 'پایش و رشد', 'd' => 'با کارنامه و گزارش‌های دقیق، مسیر را اصلاح کرده و پیشرفت می‌کنید.'],
                ];
            @endphp

            <div class="relative grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="hidden lg:block absolute top-12 right-[12.5%] left-[12.5%] h-px bg-gradient-to-l from-transparent via-primary/30 to-transparent"></div>
                @foreach($steps as $i => $s)
                    <div class="glow-on-hover glass rounded-2xl p-5 space-y-3 relative reveal reveal-delay-{{ $i + 1 }}">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-4xl text-primary/20">{{ $s['n'] }}</span>
                            <span class="relative flex items-center justify-center w-10 h-10 bg-primary/10 text-primary border border-primary/20 rounded-xl">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
                            </span>
                        </div>
                        <h3 class="font-black text-foreground text-base">{{ $s['t'] }}</h3>
                        <p class="font-medium text-xs text-muted leading-6">{{ $s['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- ====================== WHY US ====================== --}}
        <section class="relative rounded-3xl glass orbit-wrap reveal">
            <div class="overflow-hidden rounded-3xl relative">
                <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                <div class="absolute -top-10 -left-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl blob-1"></div>
                <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-primary/15 rounded-full blur-3xl blob-2"></div>

                <div class="relative grid md:grid-cols-12 gap-8 items-center p-6 md:p-12">
                    <div class="md:col-span-6 space-y-5 reveal-right">
                        <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <span class="font-semibold text-xs text-foreground">چرا SDFR؟</span>
                        </div>
                        <h2 class="font-black text-2xl md:text-3xl text-foreground leading-tight">
                            تفاوت ما در <span class="shimmer-text">همراهی واقعی</span> است
                        </h2>
                        <p class="font-medium text-sm text-muted leading-8">
                            ما فقط یک ابزار نیستیم؛ ترکیبی از تکنولوژی هوشمند و پشتیبانی انسانی هستیم
                            که در هر گام کنار دانش‌آموز می‌ماند تا یادگیری از یک تکلیف به یک تجربه‌ی
                            لذت‌بخش و قابل‌سنجش تبدیل شود.
                        </p>
                        <ul class="grid sm:grid-cols-2 gap-3 pt-2">
                            @foreach([
                                'یکپارچگی همه‌ی ابزارها در یک پلتفرم',
                                'پشتیبانی انسانی و واقعی ۲۴ ساعته',
                                'گزارش‌های شفاف و قابل‌ارائه',
                                'برنامه‌ی کاملاً اختصاصی هر فرد',
                            ] as $item)
                                <li class="flex items-start gap-2.5">
                                    <span class="flex items-center justify-center w-6 h-6 bg-primary/15 text-primary border border-primary/20 rounded-md mt-0.5 shrink-0">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    <span class="font-semibold text-sm text-foreground leading-6">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="md:col-span-6 reveal-left">
                        <div class="glass-strong rounded-2xl p-5 space-y-4 shadow-xl shadow-primary/10">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                    </svg>
                                    <span class="font-bold text-xs text-muted">نمونه‌ی روند پیشرفت</span>
                                </div>
                                <span class="inline-flex items-center gap-1 font-semibold text-[10px] text-primary glass rounded-full px-2 py-0.5">
                                    <span class="relative flex w-1.5 h-1.5">
                                        <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                        <span class="relative inline-flex w-1.5 h-1.5 bg-primary rounded-full"></span>
                                    </span>
                                    زنده
                                </span>
                            </div>
                            <div class="space-y-3">
                                @foreach([
                                    ['name' => 'تمرکز و تداوم مطالعه', 'val' => '۸۸٪', 'w' => '88%'],
                                    ['name' => 'پیشرفت در آزمون‌ها', 'val' => '۷۹٪', 'w' => '79%'],
                                    ['name' => 'پایبندی به برنامه', 'val' => '۹۳٪', 'w' => '93%'],
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
            <div class="orb-track"><span class="orb"></span><span class="orb-trail"></span></div>
        </section>


        {{-- ====================== FINAL CTA ====================== --}}
        <section class="reveal">
            <div class="relative rounded-3xl glass orbit-wrap">
                <div class="overflow-hidden rounded-3xl relative p-8 md:p-14 text-center">
                    <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                    <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-48 bg-primary/25 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 right-1/4 w-72 h-72 bg-primary/15 rounded-full blur-3xl blob-1 pointer-events-none"></div>

                    <div class="relative max-w-2xl mx-auto space-y-6">
                        <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-2">
                            <span class="relative flex w-2 h-2">
                                <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                <span class="relative inline-flex w-2 h-2 bg-primary rounded-full"></span>
                            </span>
                            <span class="font-semibold text-xs text-foreground">همین امروز شروع کنید</span>
                        </div>
                        <h2 class="font-black text-2xl md:text-4xl text-foreground leading-tight">
                            آماده‌اید مسیر تحصیل را <span class="shimmer-text">هوشمند</span> کنید؟
                        </h2>
                        <p class="font-medium text-sm md:text-base text-muted leading-8">
                            به جمع هزاران دانش‌آموز و ده‌ها مدرسه‌ای بپیوندید که با SDFR
                            یادگیری را شفاف، هدفمند و قابل‌اندازه‌گیری کرده‌اند.
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                            <a href="{{ route('client.download') }}"
                               class="group relative inline-flex items-center justify-center h-12 bg-primary hover:bg-primary/90 transition-all rounded-full text-white font-bold text-sm px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02]">
                                <span>دانلود اپلیکیشن</span>
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                            </a>
                            <a href="{{ route('client.contact-us') }}"
                               class="inline-flex items-center justify-center h-12 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-sm px-8">
                                تماس با ما
                            </a>
                        </div>
                    </div>
                </div>
                <div class="orb-track"><span class="orb"></span><span class="orb-trail"></span></div>
            </div>
        </section>

    </div>

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
                }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                document.querySelectorAll('.reveal, .reveal-right, .reveal-left').forEach(el => {
                    observer.observe(el);
                });
            });
        </script>
    @endpush
</div>
