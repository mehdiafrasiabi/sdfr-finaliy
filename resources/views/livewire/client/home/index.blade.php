<div>
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

                /* ---------- Glass ---------- */
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

                /* ---------- Shimmer (gradient text) ---------- */
                @keyframes shimmer { 0% { background-position: -200% 0;} 100% { background-position: 200% 0;} }
                .shimmer-text {
                    background: linear-gradient(90deg, hsl(var(--foreground)) 0%, #38bdf8 35%, #2563eb 65%, hsl(var(--foreground)) 100%);
                    background-size: 200% 100%;
                    -webkit-background-clip: text;
                    background-clip: text;
                    -webkit-text-fill-color: transparent;
                    animation: shimmer 5s linear infinite;
                }

                /* ---------- Floaty ---------- */
                @keyframes floaty { 0%,100% { transform: translateY(0);} 50% { transform: translateY(-14px);} }
                .floaty { animation: floaty 6s ease-in-out infinite; }

                /* ---------- Glow on hover ---------- */
                .glow-on-hover { position: relative; transition: transform 0.3s ease, border-color 0.3s ease; }
                .glow-on-hover::after {
                    content: ''; position: absolute; inset: -1px; border-radius: inherit;
                    background: linear-gradient(135deg, hsl(var(--primary) / 0.5), transparent 60%);
                    opacity: 0; transition: opacity 0.3s ease; pointer-events: none; z-index: -1;
                }
                .glow-on-hover:hover { transform: translateY(-3px); }
                .glow-on-hover:hover::after { opacity: 1; }

                /* ---------- Reveal on scroll ---------- */
                .reveal { opacity: 0; transform: translateY(30px);
                    transition: opacity 0.8s cubic-bezier(0.16,1,0.3,1), transform 0.8s cubic-bezier(0.16,1,0.3,1);
                    will-change: opacity, transform; }
                .reveal.is-visible { opacity: 1; transform: translateY(0); }
                .reveal-up { opacity: 0; transform: translateY(40px);
                    transition: opacity 0.9s cubic-bezier(0.16,1,0.3,1), transform 0.9s cubic-bezier(0.16,1,0.3,1); }
                .reveal-up.is-visible { opacity: 1; transform: translateY(0); }
                .reveal-delay-1 { transition-delay: 0.1s; }
                .reveal-delay-2 { transition-delay: 0.2s; }
                .reveal-delay-3 { transition-delay: 0.3s; }

                /* ---------- Marquee logos ---------- */
                @keyframes marquee-rtl { from { transform: translateX(0);} to { transform: translateX(-50%);} }
                @keyframes marquee-ltr { from { transform: translateX(-50%);} to { transform: translateX(0);} }
                .marquee-track-rtl { display: flex; width: max-content; animation: marquee-rtl 34s linear infinite; }
                .marquee-track-ltr { display: flex; width: max-content; animation: marquee-ltr 34s linear infinite; }
                .marquee-mask {
                    -webkit-mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent);
                    mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent);
                }
                .marquee-pause:hover .marquee-track-rtl,
                .marquee-pause:hover .marquee-track-ltr { animation-play-state: paused; }

                /* ============== HERO phone mockup ============== */
                .hero-phone {
                    position: relative; width: 100%; max-width: 300px; margin-inline: auto;
                }
                @media (min-width: 640px) { .hero-phone { max-width: 330px; } }
                @media (min-width: 768px) { .hero-phone { max-width: 360px; } }

                .hero-phone__frame {
                    display: block; width: 100%; height: auto; position: relative; z-index: 3;
                    filter: drop-shadow(0 36px 70px rgba(0,0,0,.5)); pointer-events: none;
                }
                .hero-phone__screen {
                    position: absolute; z-index: 1;
                    top: 1.6%; bottom: 1.6%; left: 5.2%; right: 5.2%;
                    border-radius: 13% / 6.2%; overflow: hidden;
                    background: linear-gradient(180deg, #0b2a6b 0%, #06122e 42%, #020617 100%);
                }
                .hero-phone__video {
                    position: absolute; inset: 0; z-index: 2;
                    width: 100%; height: 100%; object-fit: cover;
                }
                .hero-phone__chat {
                    position: absolute; inset: 0; z-index: 1; padding: 19% 7% 7%;
                    display: flex; flex-direction: column; gap: 10px; direction: rtl;
                }
                .chat-bubble { max-width: 86%; font-size: 11px; line-height: 1.9; border-radius: 16px; padding: 8px 11px; }
                .chat-bubble--me { align-self: flex-end; background: #2563eb; color: #fff; border-bottom-left-radius: 5px; }
                .chat-bubble--bot { align-self: flex-start; background: rgba(255,255,255,.08); color: #e5edff; border-bottom-right-radius: 5px; backdrop-filter: blur(4px); }

                @keyframes chip-float-a { 0%,100% { transform: translateY(0);} 50% { transform: translateY(-12px);} }
                @keyframes chip-float-b { 0%,100% { transform: translateY(0);} 50% { transform: translateY(10px);} }
                .hero-chip--a { animation: chip-float-a 5s ease-in-out infinite; }
                .hero-chip--b { animation: chip-float-b 6s ease-in-out infinite; }

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

        <div dir="rtl">
            <div class="max-w-7xl mx-auto px-4 space-y-16 md:space-y-28 py-8 md:py-12">

                {{-- ============================= HERO ============================= --}}
                <section class="relative reveal text-center">
                    <div class="absolute inset-0 grid-bg pointer-events-none -z-10"></div>
                    <div class="absolute inset-0 pointer-events-none overflow-hidden -z-10">
                        <div class="blob-1 absolute -top-24 right-1/4 w-80 h-80 sm:w-96 sm:h-96 bg-primary/25 rounded-full blur-3xl"></div>
                        <div class="blob-2 absolute top-1/3 -left-16 w-80 h-80 sm:w-[28rem] sm:h-[28rem] bg-sky-500/15 rounded-full blur-3xl"></div>
                    </div>

                    <div class="max-w-3xl mx-auto space-y-6 md:space-y-7 pt-2 md:pt-6">
                        <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-2">
                            <span class="relative flex w-2 h-2">
                                <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                <span class="relative inline-flex w-2 h-2 bg-primary rounded-full"></span>
                            </span>
                            <span class="font-semibold text-[11px] sm:text-xs text-foreground">پلتفرم هوشمند پایش و مشاوره‌ی تحصیلی</span>
                        </div>

                        <h1 class="font-black text-4xl sm:text-5xl md:text-6xl text-foreground" style="line-height: 1.4">
                            مسیر رتبه‌برتر شدنت،<br/>
                            <span class="shimmer-text">هوشمند</span> و بدون توقف
                        </h1>

                        <p class="font-medium text-sm sm:text-base text-muted leading-8 max-w-xl mx-auto">
                            با <span class="font-black text-foreground">SDFR</span> ساعت مطالعه‌ات ثبت می‌شود،
                            برنامه‌ی هفتگی اختصاصی می‌گیری، با مشاور تخصصی در ارتباطی و هوش مصنوعی
                            هر روز عملکردت را تحلیل می‌کند تا حتی یک روز هم عقب نمانی.
                        </p>

                        <div class="flex flex-wrap items-center justify-center gap-3 pt-1">
                            <a href="{{ route('client.auth.login') }}" class="group inline-flex items-center justify-center h-12 bg-primary hover:bg-primary/90 transition-all rounded-full text-primary-foreground font-bold text-sm px-7 sm:px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02]">
                                <span>شروع رایگان</span>
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                </svg>
                            </a>
                            <a href="#features" class="inline-flex items-center justify-center h-12 glass hover:border-primary/60 transition-all rounded-full text-foreground font-bold text-sm px-7 sm:px-8">
                                مشاهده ویژگی‌ها
                            </a>
                        </div>
                    </div>

                    <div class="relative mt-12 md:mt-16">
                        <div class="hero-phone floaty">
                            <div class="hero-chip--a absolute z-30 top-[16%] right-0 sm:-right-6 md:-right-12">
                                <div class="glass rounded-2xl px-3 py-2 flex items-center gap-2 shadow-xl">
                                    <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-sky-500/15 text-sky-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                            <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                                        </svg>
                                    </span>
                                    <span class="font-bold text-[11px] text-foreground whitespace-nowrap">تحلیل هوشمند</span>
                                </div>
                            </div>
                            <div class="hero-chip--b absolute z-30 bottom-[24%] left-0 sm:-left-6 md:-left-12">
                                <div class="glass rounded-2xl px-3 py-2 flex items-center gap-2 shadow-xl">
                                    <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-500/15 text-emerald-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                        </svg>
                                    </span>
                                    <span class="font-bold text-[11px] text-foreground whitespace-nowrap">برنامه‌ی هفتگی</span>
                                </div>
                            </div>
                            <div class="hero-phone__screen">
                                <div class="hero-phone__chat">
                                    <div class="chat-bubble chat-bubble--me">سلام! نمی‌دونم امروز از کجا شروع کنم 😅</div>
                                    <div class="chat-bubble chat-bubble--bot">سلام 👋 طبق برنامه‌ت، الان وقت ریاضیه: ۴۵ دقیقه مبحث مشتق.</div>
                                    <div class="chat-bubble chat-bubble--me">بسیار عالی</div>
                                    <div class="chat-bubble chat-bubble--bot">بعدش یه آزمون کوتاه ۱۰ سؤالی می‌گیرم تا نقاط ضعفت مشخص بشه.</div>
                                    <div class="chat-bubble chat-bubble--bot">امروز تا اینجا ۳ ساعت و ۲۰ دقیقه مطالعه‌ی مفید داشتی، عالیه! 🔥</div>
                                </div>
                                <video class="hero-phone__video" autoplay loop muted playsinline preload="metadata">
                                    <source src="{{ asset('client/hero/app-demo.mp4') }}" type="video/mp4">
                                </video>
                            </div>
                            <img src="/client/iphone.avif" alt="اپلیکیشن SDFR" class="hero-phone__frame">
                        </div>
                    </div>
                </section>


                {{-- ===================== TRUST / LOGOS ===================== --}}
                <section class="relative space-y-8 reveal-up">
                    <div class="text-center space-y-3">
                        <h2 class="font-black text-2xl md:text-3xl text-foreground">بهترین‌ها به ما اعتماد کردند</h2>
                        <p class="font-medium text-sm text-muted px-4">مدارس و آموزشگاه‌هایی که مسیر دانش‌آموزانشان را به SDFR سپرده‌اند.</p>
                    </div>

                    @php
                        $logosRow1 = ['دبیرستان فرزانگان','مجتمع علامه حلی','دبیرستان شهید بهشتی','آموزشگاه نمونه','مدرسه‌ی ماندگار البرز','دبیرستان دکتر حسابی'];
                        $logosRow2 = ['مجتمع نیکان','دبیرستان مفید','آموزشگاه اندیشه','مدرسه‌ی سلام','دبیرستان رشد','مجتمع آفرینش'];
                    @endphp
                    <div class="relative marquee-mask marquee-pause">
                        <div class="marquee-track-rtl gap-3 sm:gap-4">
                            @foreach(array_merge($logosRow1, $logosRow1) as $logo)
                                <div class="flex-shrink-0 flex items-center justify-center h-14 sm:h-16 px-5 sm:px-7 glass rounded-2xl">
                                    <span class="font-bold text-xs sm:text-sm text-muted whitespace-nowrap">{{ $logo }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="relative marquee-mask marquee-pause">
                        <div class="marquee-track-ltr gap-3 sm:gap-4">
                            @foreach(array_merge($logosRow2, $logosRow2) as $logo)
                                <div class="flex-shrink-0 flex items-center justify-center h-14 sm:h-16 px-5 sm:px-7 glass rounded-2xl">
                                    <span class="font-bold text-xs sm:text-sm text-muted whitespace-nowrap">{{ $logo }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>


                {{-- ============ FEATURES — بخش اسکرول مشابه سایت Flowchat ============ --}}
                <section id="features" class="relative space-y-8 md:space-y-16 scroll-mt-24 reveal-up"
                         x-data="{ tab: 0 }"
                         @update-feature-tab.window="tab = parseInt($event.detail)">

                    <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>

                    <div class="text-center space-y-3 max-w-3xl mx-auto mb-8 md:mb-16">
                        <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5">
                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>
                            </svg>
                            <span class="font-semibold text-xs text-foreground">امکانات پلتفرم</span>
                        </div>
                        <h2 class="font-black text-2xl md:text-4xl text-foreground">
                            همه‌ی ابزارها در <span class="shimmer-text">یک پلتفرم</span>
                        </h2>
                        <p class="font-medium text-sm text-muted leading-7 px-4">
                            هرآنچه برای پایش، برنامه‌ریزی و پیشرفت لازم داری، یک‌جا و یکپارچه.
                        </p>
                    </div>

                    @php
                        $featureTabs = [
                            ['t' => 'داشبورد هوشمند',      'd' => 'نمای لحظه‌ای از ساعت مطالعه، برنامه‌ی امروز و وضعیت کلی دانش‌آموز در یک نگاه. تحلیلگرها اطلاعات رو به لحظه پردازش می‌کنند.', 'img' => 'dashboard.webp',
                             'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>'],
                            ['t' => 'برنامه‌ریزی هفتگی',   'd' => 'برنامه‌ی مطالعاتی اختصاصی هر دانش‌آموز با جزئیات کامل ساعت، آزمون و درس، که توسط مشاور تنظیم می‌شود تا مسیر پیشرفت مشخص باشه.', 'img' => 'planning.webp',
                             'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'],
                            ['t' => 'اتاق مشاوره',         'd' => 'جلسات مشاوره‌ی هدفمند و گفتگوی مستقیم با مشاوران تخصصی برای هدایت مسیر تحصیلی. ارتباط مستمر کلید موفقیته.', 'img' => 'consult.webp',
                             'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
                            ['t' => 'آزمون‌های آنلاین',    'd' => 'برگزاری آزمون‌های پیشرفته همراه با تصحیح و بازخورد تخصصی برای سنجش دقیق پیشرفت و شبیه‌سازی شرایط کنکور.', 'img' => 'exam.webp',
                             'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10.01-3-3"/>'],
                            ['t' => 'کارنامه و تحلیل',     'd' => 'کارنامه‌ی هوشمند و نمودارهای پیشرفت که نقاط ضعف و قوت رو کاملاً شفاف نشون میده تا برنامه‌ریزی هدفمندتر بشه.', 'img' => 'report.webp',
                             'icon' => '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>'],
                        ];
                    @endphp

                    <div class="flex flex-col md:flex-row gap-8 md:gap-16 relative items-start pb-10">

                        {{-- راست: متن‌ها (در حالت موبایل میاد زیر) --}}
                        <div class="w-full md:w-5/12 order-2 md:order-1 space-y-[20vh] md:space-y-[45vh] py-[5vh] md:py-[20vh]">
                            @foreach($featureTabs as $i => $f)
                                <div class="feature-text-block transition-all duration-700 ease-in-out"
                                     data-index="{{ $i }}"
                                     :class="tab === {{ $i }} ? 'opacity-100 translate-x-0' : 'opacity-20 md:translate-x-8 translate-x-0'">

                                    <div class="flex flex-col gap-4 mb-4">
                                        <span class="flex items-center justify-center w-14 h-14 rounded-2xl border transition-all shrink-0 shadow-lg shadow-primary/30"
                                              :class="tab === {{ $i }} ? 'bg-primary text-primary-foreground border-primary' : 'bg-primary/10 text-primary border-primary/20'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">{!! $f['icon'] !!}</svg>
                                        </span>
                                        <h3 class="font-black text-2xl sm:text-3xl text-foreground">{{ $f['t'] }}</h3>
                                    </div>
                                    <p class="font-medium text-base sm:text-lg text-muted leading-9">{{ $f['d'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- چپ: عکس اسکرول شونده (Sticky) --}}
                        <div class="w-full md:w-7/12 order-1 md:order-2 sticky top-24 md:top-32 h-[45vh] md:h-[calc(100vh-14rem)] flex items-center justify-center">
                            <div class="relative w-full h-full rounded-[2rem] glass p-2 overflow-hidden shadow-2xl transition-all duration-500">
                                <div class="absolute inset-0 grid-bg-sm pointer-events-none"></div>
                                <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-72 h-32 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>
                                <div class="relative w-full h-full rounded-3xl overflow-hidden bg-secondary/20 border border-white/5">
                                    @foreach($featureTabs as $i => $f)
                                        <img x-show="tab === {{ $i }}"
                                             x-transition:enter="transition ease-out duration-700"
                                             x-transition:enter-start="opacity-0 scale-105 translate-y-8"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-500 absolute inset-0"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 -translate-y-8"
                                             src="{{ asset('client/preview/'.$f['img']) }}"
                                             alt="{{ $f['t'] }}"
                                             class="absolute inset-0 w-full h-full object-cover">
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </section>


                {{-- ===================== PRICING ===================== --}}
                <section id="pricing" class="relative space-y-8 md:space-y-10 scroll-mt-24 reveal-up" x-data="{ yearly: false }">
                    <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>

                    <div class="text-center space-y-4 max-w-2xl mx-auto">
                        <h2 class="font-black text-3xl md:text-4xl">
                            <span class="shimmer-text">پلن‌های قیمت‌گذاری</span>
                        </h2>
                        <p class="font-medium text-sm text-muted px-4">بهترین پلن را متناسب با نیاز خودت انتخاب کن.</p>

                        <div class="inline-flex items-center gap-2 sm:gap-3 glass rounded-full p-1.5">
                            <button type="button" @click="yearly = false" class="h-9 rounded-full px-4 sm:px-5 font-bold text-xs transition-all" :class="!yearly ? 'bg-primary text-primary-foreground shadow' : 'text-muted'">ماهانه</button>
                            <button type="button" @click="yearly = true" class="h-9 rounded-full px-4 sm:px-5 font-bold text-xs transition-all flex items-center gap-2" :class="yearly ? 'bg-primary text-primary-foreground shadow' : 'text-muted'">
                                سالانه
                                <span class="inline-flex items-center rounded-full bg-emerald-500/15 text-emerald-400 text-[10px] font-black px-2 py-0.5">۲۰٪ تخفیف</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-5 max-w-5xl mx-auto items-stretch">
                        <div class="glow-on-hover glass rounded-3xl p-6 flex flex-col space-y-5">
                            <div class="space-y-1">
                                <h3 class="font-black text-xl text-foreground">پایه</h3>
                                <p class="font-medium text-xs text-muted">برای شروع و آشنایی با مسیر SDFR</p>
                            </div>
                            <div class="flex items-end gap-1 border-b border-border pb-5">
                                <span class="font-black text-3xl text-foreground" x-text="yearly ? '۲٬۸۷۰٬۰۰۰' : '۲۹۹٬۰۰۰'"></span>
                                <span class="text-xs text-muted pb-1" x-text="yearly ? 'تومان / سال' : 'تومان / ماه'"></span>
                            </div>
                            <ul class="space-y-3 flex-1">
                                @foreach(['برنامه‌ی هفتگی استاندارد','ثبت ساعت مطالعه','کارنامه‌ی ماهانه','پشتیبانی از طریق تیکت'] as $it)
                                    <li class="flex items-center gap-2.5">
                                        <span class="flex items-center justify-center w-5 h-5 bg-primary/15 text-primary border border-primary/20 rounded-md shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M20 6 9 17l-5-5"/></svg>
                                        </span>
                                        <span class="font-semibold text-xs text-foreground">{{ $it }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('client.auth.login') }}" class="inline-flex items-center justify-center h-11 glass hover:border-primary/60 rounded-full font-bold text-sm text-foreground transition-all">شروع با پلن پایه</a>
                        </div>

                        <div class="relative rounded-3xl p-6 flex flex-col space-y-5 bg-primary/[0.07] border-2 border-primary shadow-2xl shadow-primary/20 md:-translate-y-3 mt-3 md:mt-0">
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center bg-primary text-primary-foreground font-black text-[11px] rounded-full px-4 py-1 shadow-lg">پیشنهاد ویژه</div>
                            <div class="space-y-1">
                                <h3 class="font-black text-xl text-foreground">حرفه‌ای</h3>
                                <p class="font-medium text-xs text-muted">کامل‌ترین تجربه برای رتبه‌برتر شدن</p>
                            </div>
                            <div class="flex items-end gap-1 border-b border-border pb-5">
                                <span class="font-black text-3xl text-foreground" x-text="yearly ? '۵٬۷۶۰٬۰۰۰' : '۵۹۹٬۰۰۰'"></span>
                                <span class="text-xs text-muted pb-1" x-text="yearly ? 'تومان / سال' : 'تومان / ماه'"></span>
                            </div>
                            <ul class="space-y-3 flex-1">
                                @foreach(['همه‌ی امکانات پایه','مشاور تخصصی اختصاصی','برنامه‌ریزی کاملاً شخصی‌سازی‌شده','آزمون‌های آنلاین نامحدود','تحلیل هوشمند با هوش مصنوعی','پشتیبانی اولویت‌دار در تلگرام'] as $it)
                                    <li class="flex items-center gap-2.5">
                                        <span class="flex items-center justify-center w-5 h-5 bg-primary text-primary-foreground rounded-md shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M20 6 9 17l-5-5"/></svg>
                                        </span>
                                        <span class="font-semibold text-xs text-foreground">{{ $it }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('client.auth.login') }}" class="inline-flex items-center justify-center h-11 bg-primary hover:opacity-90 rounded-full font-bold text-sm text-primary-foreground transition-all shadow-lg shadow-primary/30">شروع با پلن حرفه‌ای</a>
                        </div>

                        <div class="glow-on-hover glass rounded-3xl p-6 flex flex-col space-y-5">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-black text-xl text-foreground">سازمانی</h3>
                                    <span class="font-medium text-[10px] text-muted">ویژه‌ی مدارس</span>
                                </div>
                                <p class="font-medium text-xs text-muted">برای مدارس و آموزشگاه‌ها</p>
                            </div>
                            <div class="flex items-end gap-1 border-b border-border pb-5">
                                <span class="font-black text-2xl text-foreground">تماس بگیرید</span>
                            </div>
                            <ul class="space-y-3 flex-1">
                                @foreach(['همه‌ی امکانات حرفه‌ای','پنل مدیریتی مدرسه','گزارش‌گیری دوره‌ای و تحلیلی','مدیر موفقیت اختصاصی','قرارداد و پشتیبانی ویژه'] as $it)
                                    <li class="flex items-center gap-2.5">
                                        <span class="flex items-center justify-center w-5 h-5 bg-primary/15 text-primary border border-primary/20 rounded-md shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M20 6 9 17l-5-5"/></svg>
                                        </span>
                                        <span class="font-semibold text-xs text-foreground">{{ $it }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('client.schools') }}" class="inline-flex items-center justify-center h-11 glass hover:border-primary/60 rounded-full font-bold text-sm text-foreground transition-all">طرح همکاری مدارس</a>
                        </div>
                    </div>
                </section>


                {{-- ===================== CONTACT ===================== --}}
                <section id="contact" class="relative space-y-8 md:space-y-10 scroll-mt-24 reveal-up">
                    <div class="absolute inset-0 grid-bg-sm pointer-events-none -z-10"></div>

                    <div class="text-center space-y-3 max-w-2xl mx-auto">
                        <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-2">
                            <svg class="w-3.5 h-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/>
                            </svg>
                            <span class="font-semibold text-xs text-foreground">تماس با ما</span>
                        </div>
                        <h2 class="font-black text-2xl md:text-4xl text-foreground">آماده‌ی شروع هستی؟</h2>
                        <p class="font-medium text-sm md:text-base text-muted px-4">سؤالی داری یا به مشاوره نیاز داری؟ تیم ما آماده‌ی پاسخگویی به توست.</p>
                    </div>

                    <div class="grid md:grid-cols-12 gap-6">
                        <div class="md:col-span-7">
                            <div class="glass rounded-3xl p-5 sm:p-6 md:p-8">
                                @if (session('contact_sent'))
                                    <div class="mb-5 flex items-center gap-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10.01-3-3"/></svg>
                                        <span class="font-semibold text-sm">پیامت با موفقیت ارسال شد. به‌زودی پاسخت را می‌دهیم.</span>
                                    </div>
                                @endif

                                <form wire:submit="submitContact" class="space-y-5">
                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <div class="space-y-2">
                                            <label class="font-semibold text-xs text-muted">نام و نام خانوادگی</label>
                                            <input type="text" wire:model="contact_name" placeholder="نام خود را وارد کن" class="w-full h-11 bg-white text-slate-900 border border-slate-200 rounded-xl px-4 text-sm placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                            @error('contact_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="font-semibold text-xs text-muted">ایمیل</label>
                                            <input type="email" dir="ltr" wire:model="contact_email" placeholder="example@email.com" class="w-full h-11 bg-white text-slate-900 border border-slate-200 rounded-xl px-4 text-sm text-right placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                            @error('contact_email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="font-semibold text-xs text-muted">شماره تماس</label>
                                            <input type="tel" dir="ltr" wire:model="contact_phone" placeholder="09123456789" class="w-full h-11 bg-white text-slate-900 border border-slate-200 rounded-xl px-4 text-sm text-right placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                            @error('contact_phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="font-semibold text-xs text-muted">موضوع</label>
                                            <input type="text" wire:model="contact_subject" placeholder="موضوع پیامت را بنویس" class="w-full h-11 bg-white text-slate-900 border border-slate-200 rounded-xl px-4 text-sm placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                            @error('contact_subject') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-muted">پیام</label>
                                        <textarea wire:model="contact_message" rows="5" placeholder="پیام خود را اینجا بنویس..." class="w-full bg-white text-slate-900 border border-slate-200 rounded-xl px-4 py-3 text-sm placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"></textarea>
                                        @error('contact_message') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                    <button type="submit" class="w-full h-12 inline-flex items-center justify-center gap-2 bg-primary hover:opacity-90 rounded-xl font-bold text-sm text-primary-foreground transition-all shadow-lg shadow-primary/25">
                                        <span wire:loading.remove wire:target="submitContact">ارسال پیام</span>
                                        <span wire:loading wire:target="submitContact">در حال ارسال...</span>
                                        <svg wire:loading.remove wire:target="submitContact" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="md:col-span-5 space-y-4">
                            @php
                                $contactCards = [
                                    ['t' => 'ایمیل',          'v' => 'info@sdfr.me',      'd' => 'پاسخگویی سریع در اسرع وقت', 'icon' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/>'],
                                    ['t' => 'تلفن تماس',       'v' => '۰۲۱-۱۲۳۴۵۶۷۸',       'd' => 'تماس مستقیم با تیم ما',     'icon' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>'],
                                    ['t' => 'تلگرام',         'v' => '@sdfr_support',      'd' => 'پشتیبانی سریع در تلگرام',    'icon' => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>'],
                                    ['t' => 'پشتیبانی آنلاین', 'v' => '۲۴/۷ پاسخگویی',       'd' => 'همیشه در کنار تو هستیم',     'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
                                ];
                            @endphp
                            @foreach($contactCards as $c)
                                <div class="glow-on-hover glass rounded-2xl p-5 flex items-center gap-4">
                                    <span class="flex items-center justify-center w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-xl shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">{!! $c['icon'] !!}</svg>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-black text-sm text-foreground">{{ $c['t'] }}</div>
                                        <div class="font-bold text-sm text-primary truncate" dir="ltr">{{ $c['v'] }}</div>
                                        <div class="font-medium text-[11px] text-muted">{{ $c['d'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>


                {{-- ===================== FINAL CTA ===================== --}}
                <section class="reveal-up">
                    <div class="relative rounded-3xl glass overflow-hidden">
                        <div class="absolute inset-0 grid-bg pointer-events-none"></div>
                        <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-48 bg-primary/25 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 right-1/4 w-72 h-72 bg-sky-500/15 rounded-full blur-3xl blob-1 pointer-events-none"></div>

                        <div class="relative max-w-2xl mx-auto text-center space-y-6 p-7 sm:p-10 md:p-14">
                            <h2 class="font-black text-2xl md:text-4xl text-foreground leading-tight">
                                آماده‌ای مسیر تحصیلت را <span class="shimmer-text">هوشمند</span> کنی؟
                            </h2>
                            <p class="font-medium text-sm md:text-base text-muted leading-8">
                                همین امروز به جمع هزاران دانش‌آموزی بپیوند که با SDFR یادگیری را شفاف، هدفمند و قابل‌اندازه‌گیری کرده‌اند.
                            </p>
                            <div class="flex flex-wrap items-center justify-center gap-3">
                                <a href="{{ route('client.auth.login') }}" class="group inline-flex items-center justify-center h-12 bg-primary hover:opacity-90 rounded-full font-bold text-sm text-primary-foreground px-8 shadow-lg shadow-primary/30 hover:scale-[1.02] transition-all">
                                    <span>شروع رایگان</span>
                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                                </a>
                                <a href="#contact" class="inline-flex items-center justify-center h-12 glass hover:border-primary/60 rounded-full font-bold text-sm text-foreground px-8 transition-all">
                                    تماس با ما
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        {{-- ===================== Reveal-on-scroll JS & IntersectionObserver ===================== --}}
        @push('script')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // انیمیشن‌های ساده حین اسکرول
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                    document.querySelectorAll('.reveal, .reveal-up').forEach(el => observer.observe(el));

                    // ==========================================
                    // بخش ردیابی اسکرول برای امکانات پلتفرم (مشابه Flowchat)
                    // ==========================================
                    const featureBlocks = document.querySelectorAll('.feature-text-block');
                    const featureObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            // وقتی بلاک متنی وارد ۵۰ درصد میانی صفحه میشه اینتراپت میده
                            if (entry.isIntersecting) {
                                const index = entry.target.getAttribute('data-index');
                                // ارسال ایونت به Alpine.js برای آپدیت کردن tab و عکس مربوطه
                                window.dispatchEvent(new CustomEvent('update-feature-tab', { detail: index }));
                            }
                        });
                    }, {
                        // تعیین محدوده‌ی فعال شدن در صفحه (کمی بالاتر از مرکز صفحه)
                        rootMargin: '-30% 0px -40% 0px',
                        threshold: 0
                    });

                    featureBlocks.forEach(block => featureObserver.observe(block));
                });
            </script>
        @endpush
    </div>
</div>
