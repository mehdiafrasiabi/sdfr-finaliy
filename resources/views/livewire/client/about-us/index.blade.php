<div class="sdfr-about" dir="rtl">
    @assets
    <style>
        html { scroll-behavior: smooth; }
        .sdfr-about { --about-cyan: 188 100% 50%; --about-blue: 217 91% 60%; overflow: hidden; }
        .about-shell { width: min(100% - 2rem, 80rem); margin-inline: auto; }
        .about-grid {
            background-image: linear-gradient(to right, hsl(var(--border) / .34) 1px, transparent 1px),
                              linear-gradient(to bottom, hsl(var(--border) / .34) 1px, transparent 1px);
            background-size: 42px 42px;
            -webkit-mask-image: radial-gradient(ellipse 78% 70% at 50% 30%, #000 25%, transparent 100%);
            mask-image: radial-gradient(ellipse 78% 70% at 50% 30%, #000 25%, transparent 100%);
        }
        .about-glass {
            background: linear-gradient(145deg, hsl(var(--foreground) / .055), hsl(var(--foreground) / .018));
            border: 1px solid hsl(var(--border) / .72);
            box-shadow: inset 0 1px 0 hsl(var(--foreground) / .05), 0 24px 80px hsl(220 80% 2% / .24);
            backdrop-filter: blur(16px);
        }
        .about-glow { box-shadow: 0 0 0 1px hsl(var(--about-cyan) / .12), 0 24px 90px hsl(var(--about-blue) / .13); }
        .about-gradient-text {
            color: transparent;
            background: linear-gradient(100deg, #7dd3fc 0%, #38bdf8 35%, #818cf8 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }
        .about-dot {
            background-image: radial-gradient(circle, hsl(var(--primary) / .36) 1px, transparent 1.4px);
            background-size: 18px 18px;
        }
        .about-orbit { animation: about-orbit 18s linear infinite; }
        .about-float { animation: about-float 6s ease-in-out infinite; }
        .about-pulse { animation: about-pulse 2.2s ease-in-out infinite; }
        .about-reveal { opacity: 0; transform: translateY(26px); transition: opacity .75s ease, transform .75s ease; }
        .about-reveal.is-visible { opacity: 1; transform: translateY(0); }
        .about-counter { font-variant-numeric: tabular-nums; }
        .about-screen { aspect-ratio: 1280 / 680; }
        .about-screen img { width: 100%; height: auto; transform: translateY(0); }
        .about-tab[aria-selected="true"] { color: white; background: hsl(var(--primary)); box-shadow: 0 12px 30px hsl(var(--primary) / .22); }
        .about-step::after { content: ''; position: absolute; top: 2rem; right: calc(50% + 2rem); width: calc(100% - 4rem); height: 1px; background: linear-gradient(to left, hsl(var(--primary) / .5), hsl(var(--border))); }
        .about-step:last-child::after { display: none; }
        @keyframes about-orbit { to { transform: rotate(360deg); } }
        @keyframes about-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes about-pulse { 0%,100% { opacity: .45; transform: scale(.96); } 50% { opacity: 1; transform: scale(1.05); } }
        @media (max-width: 767px) {
            .about-shell { width: min(100% - 1rem, 80rem); }
            .about-step::after { display: none; }
            .about-screen { aspect-ratio: 16 / 10; }
            .about-screen img { min-height: 100%; width: auto; max-width: none; transform: translateX(15%); }
        }
        @media (prefers-reduced-motion: reduce) {
            .about-orbit, .about-float, .about-pulse { animation: none !important; }
            .about-reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
    @endassets

    @php
        $stats = [
            ['value' => '۴۷٬۸۶۰+', 'label' => 'ساعت مطالعه ثبت‌شده', 'detail' => 'هر دقیقه، قابل پیگیری'],
            ['value' => '۱٬۲۶۴٬۹۰۰+', 'label' => 'تست و تمرین حل‌شده', 'detail' => 'از برنامه تا تحلیل'],
            ['value' => '۱۸۶٬۴۲۰+', 'label' => 'گزارش روزانه ارسالی', 'detail' => 'تصویر واقعی از استمرار'],
            ['value' => '۳۸٬۷۴۰+', 'label' => 'بازخورد و پیگیری', 'detail' => 'مشاور، مدرسه و خانواده'],
        ];

        $studentFeatures = [
            ['title' => 'برنامه اختصاصی، نه نسخه عمومی', 'text' => 'برنامه هفتگی بر اساس پایه، رشته، مدرسه، آزمون‌ها، نقاط قوت و ضعف تو ساخته می‌شود.', 'metric' => '۶ بخش امروز', 'icon' => 'calendar'],
            ['title' => 'مطالعه‌ای که واقعاً ثبت می‌شود', 'text' => 'تایمر مطالعه، زمان واقعی هر پارت، جبران عقب‌ماندگی و روند هفتگی را شفاف ثبت می‌کند.', 'metric' => '۸۲٪ تحقق هدف', 'icon' => 'clock'],
            ['title' => 'آزمون و تست با تحلیل بعد از اجرا', 'text' => 'تعداد تست، دقت، آزمون تشریحی، کارنامه هوشمند و پیشرفت مبحثی کنار هم دیده می‌شوند.', 'metric' => '۲۱۴ تست این هفته', 'icon' => 'chart'],
            ['title' => 'مشاور همیشه در جریان است', 'text' => 'گزارش روزانه، پیام مستقیم، بازخورد و جلسه مشاوره باعث می‌شود هیچ افتی بی‌صدا نماند.', 'metric' => '۳ بازخورد تازه', 'icon' => 'chat'],
            ['title' => 'شناخت سبک یادگیری تو', 'text' => 'ارزیابی‌های روان‌شناختی و تحصیلی کمک می‌کنند برنامه با مدل یادگیری و شرایط شخصی تو هماهنگ شود.', 'metric' => 'پروفایل یادگیری فعال', 'icon' => 'spark'],
            ['title' => 'همه‌چیز در یک پنل به‌روز', 'text' => 'برنامه، گزارش، نمونه‌سؤال، آزمون، اطلاعیه، تیکت، امور مالی و کارنامه در یک تجربه یکپارچه است.', 'metric' => '۱۴ ابزار یکپارچه', 'icon' => 'grid'],
        ];

        $parentItems = [
            ['title' => 'پنل مستقل اولیا', 'text' => 'با ورود امن والدین، وضعیت مطالعه، گزارش‌ها و برنامه فرزندتان را بدون ورود به حساب دانش‌آموز می‌بینید.'],
            ['title' => 'گزارش ساده و قابل فهم', 'text' => 'ساعت برنامه‌ریزی‌شده و انجام‌شده، تعداد تست، گزارش‌های روزانه و نظر مشاور یک‌جا جمع می‌شود.'],
            ['title' => 'اطلاع‌رسانی و پیگیری به‌موقع', 'text' => 'افت ناگهانی، گزارش ناقص، جلسه مشاوره و نکات مهم از مسیر پیام و تماس ثبت‌شده پیگیری می‌شوند.'],
            ['title' => 'همراهی بدون کنترل فرسایشی', 'text' => 'به‌جای سؤال روزانه «درس خواندی؟»، با داده روشن و بازخورد مشاور گفت‌وگوی آرام‌تری شکل می‌گیرد.'],
        ];

        $schoolItems = [
            'داشبورد مدیریتیِ وضعیت تحصیلی دانش‌آموزان',
            'آمار جلسات مشاوره و عملکرد مشاوران',
            'ثبت نمره، کارنامه و روند رشد دروس',
            'ثبت تماس با اولیا و پیگیری موارد اضطراری',
            'گزارش‌های قابل ارائه و خروجی مدیریتی',
            'دسترسی تفکیک‌شده برای مدیر و تیم مدرسه',
        ];
    @endphp

    <section class="relative pt-5 md:pt-10 pb-16 md:pb-24">
        <div class="absolute inset-0 about-grid pointer-events-none"></div>
        <div class="absolute -top-32 -right-32 size-[28rem] rounded-full bg-sky-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-32 -left-40 size-[30rem] rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <div class="about-shell relative">
            <div class="grid lg:grid-cols-12 gap-8 items-center rounded-[2rem] about-glass about-glow p-5 md:p-10 lg:p-14 overflow-hidden">
                <div class="lg:col-span-7 space-y-7 relative z-10">
                    <div class="inline-flex items-center gap-2 rounded-full border border-sky-400/20 bg-sky-400/10 px-4 py-2 text-xs font-bold text-sky-300">
                        <span class="relative flex size-2">
                            <span class="absolute inset-0 rounded-full bg-sky-400 about-pulse"></span>
                            <span class="relative size-2 rounded-full bg-sky-300"></span>
                        </span>
                        بیش از یک پنل؛ یک سیستم پیگیری واقعی
                    </div>

                    <div class="space-y-4">
                        <h1 class="font-black text-3xl sm:text-4xl lg:text-6xl text-foreground leading-[1.45]">
                            SDFR؛ جایی که مسیر تحصیلی
                            <span class="about-gradient-text">دیده، سنجیده و پیگیری</span>
                            می‌شود
                        </h1>
                        <p class="max-w-2xl text-sm md:text-lg leading-8 md:leading-9 text-muted font-medium">
                            SDFR یک سامانه هوشمند مشاوره و مدیریت تحصیلی است؛ برنامه‌ریزی اختصاصی، ثبت مطالعه، آزمون و گزارش روزانه را به تحلیل مشاور، اطلاع‌رسانی خانواده و داشبورد مدرسه متصل می‌کند. نتیجه؟ هیچ دانش‌آموزی در مسیرش تنها یا نامرئی نمی‌ماند.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('client.onboarding') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-sm font-black text-primary-foreground shadow-lg shadow-primary/20 transition hover:-translate-y-0.5 hover:opacity-90">
                            شروع تجربه SDFR
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        <a href="#real-panel" class="inline-flex justify-center items-center gap-2 rounded-xl border border-border bg-foreground/5 px-6 py-3.5 text-sm font-bold text-foreground transition hover:bg-foreground/10">
                            دیدن پنل واقعی
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-x-6 gap-y-3 pt-2 text-xs font-bold text-muted">
                        <span class="inline-flex items-center gap-2"><span class="size-1.5 rounded-full bg-emerald-400"></span> مناسب دانش‌آموزان سراسر کشور</span>
                        <span class="inline-flex items-center gap-2"><span class="size-1.5 rounded-full bg-sky-400"></span> پنل اختصاصی اولیا و مدارس</span>
                        <span class="inline-flex items-center gap-2"><span class="size-1.5 rounded-full bg-violet-400"></span> همراهی مشاور متخصص</span>
                    </div>
                </div>

                <div class="lg:col-span-5 relative min-h-[360px] flex items-center justify-center">
                    <div class="absolute size-72 rounded-full border border-sky-400/20 about-orbit">
                        <span class="absolute -top-2 left-1/2 size-4 rounded-full bg-sky-400 shadow-[0_0_24px_#38bdf8]"></span>
                        <span class="absolute top-1/2 -right-2 size-3 rounded-full bg-indigo-400 shadow-[0_0_20px_#818cf8]"></span>
                    </div>
                    <div class="absolute size-52 rounded-full border border-dashed border-primary/25 about-orbit" style="animation-direction: reverse; animation-duration: 12s"></div>
                    <div class="relative about-float size-52 rounded-[2rem] about-glass p-5 flex flex-col justify-between text-center">
                        <div class="mx-auto size-16 rounded-2xl bg-primary/15 border border-primary/20 flex items-center justify-center">
                            <img src="/client/assets/images/favicon.svg" alt="نشان SDFR" class="size-11">
                        </div>
                        <div>
                            <div class="font-black text-4xl tracking-[.22em] text-foreground" dir="ltr">SDFR</div>
                            <div class="mt-2 text-xs font-bold text-sky-300">Study • Data • Feedback • Result</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-[10px] text-muted">
                            <div class="rounded-lg bg-foreground/5 py-2"><b class="block text-foreground text-sm">۲۴/۷</b>دسترسی</div>
                            <div class="rounded-lg bg-foreground/5 py-2"><b class="block text-foreground text-sm">۳۶۰°</b>تحلیل</div>
                            <div class="rounded-lg bg-foreground/5 py-2"><b class="block text-foreground text-sm">۱</b>مسیر</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-shell pb-20 md:pb-28 about-reveal" aria-labelledby="stats-title">
        <div class="text-center mb-8">
            <span class="text-xs font-black text-sky-400">وقتی فعالیت‌ها تبدیل به داده می‌شوند</span>
            <h2 id="stats-title" class="mt-3 text-2xl md:text-4xl font-black text-foreground">اعدادی از یک مسیر همیشه در حرکت</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5">
            @foreach($stats as $stat)
                <article class="about-glass rounded-2xl p-4 md:p-6 text-center transition duration-300 hover:-translate-y-1 hover:border-sky-400/30">
                    <div class="about-counter text-xl md:text-3xl font-black about-gradient-text" dir="ltr">{{ $stat['value'] }}</div>
                    <h3 class="mt-3 text-xs md:text-sm font-black text-foreground">{{ $stat['label'] }}</h3>
                    <p class="mt-2 text-[10px] md:text-xs text-muted">{{ $stat['detail'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="about-shell pb-20 md:pb-28 about-reveal">
        <div class="grid lg:grid-cols-12 gap-6 items-stretch">
            <div class="lg:col-span-5 about-glass rounded-3xl p-6 md:p-9 relative overflow-hidden">
                <div class="absolute inset-0 about-dot opacity-30 pointer-events-none"></div>
                <div class="relative">
                    <span class="inline-flex rounded-full bg-indigo-400/10 border border-indigo-400/20 px-3 py-1.5 text-xs font-black text-indigo-300">پشت محصول چه خبر است؟</span>
                    <h2 class="mt-5 text-2xl md:text-4xl leading-[1.55] font-black text-foreground">
                        این سیستم دو ساله نیست؛
                        <span class="about-gradient-text">پشت آن بیش از یک دهه تجربه</span>
                        ایستاده است
                    </h2>
                    <p class="mt-5 text-sm leading-8 text-muted font-medium text-justify">
                        SDFR از دل بیش از ۱۰ سال کار با دانش‌آموز، خانواده، آزمون، برنامه و چالش‌های واقعی مشاوره ساخته شده است. فناوری برای ما ویترین نیست؛ ابزاری است تا تجربه مشاور به یک فرایند دقیق، تکرارپذیر و قابل‌اندازه‌گیری تبدیل شود.
                    </p>
                </div>
            </div>
            <div class="lg:col-span-7 grid sm:grid-cols-2 gap-4">
                @foreach([
                    ['۱۰+ سال', 'تجربه آموزشی و مشاوره‌ای', 'شناخت مسئله پیش از ساخت نرم‌افزار'],
                    ['روزانه', 'بازخورد و پیگیری', 'نه فقط یک تماس کوتاه در پایان هفته'],
                    ['اختصاصی', 'برنامه برای هر دانش‌آموز', 'بر اساس شرایط واقعی زندگی و مدرسه'],
                    ['یکپارچه', 'دانش‌آموز، مشاور، اولیا و مدرسه', 'همه با یک تصویر مشترک از مسیر'],
                ] as $item)
                    <article class="rounded-2xl border border-border bg-foreground/[.025] p-6 flex flex-col justify-between min-h-44 transition hover:bg-foreground/[.05]">
                        <div class="text-2xl font-black text-sky-300">{{ $item[0] }}</div>
                        <div>
                            <h3 class="text-base font-black text-foreground">{{ $item[1] }}</h3>
                            <p class="mt-2 text-xs leading-6 text-muted">{{ $item[2] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="real-panel" class="about-shell pb-20 md:pb-28 about-reveal scroll-mt-24">
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="text-xs font-black text-sky-400">این یک طرح گرافیکی نیست</span>
            <h2 class="mt-3 text-2xl md:text-4xl font-black text-foreground">یک حساب دانش‌آموز واقعیِ نمایشی، با داده واقعی در سیستم</h2>
            <p class="mt-4 text-sm md:text-base leading-8 text-muted">برنامه، گزارش، مطالعه، اعلان، مدرسه، مشاور و پیشرفت درسی؛ همه همین حالا در پنل کار می‌کنند.</p>
        </div>
        <figure class="relative rounded-[1.75rem] about-glass about-glow p-2 md:p-4">
            <div class="flex items-center gap-2 px-3 py-2.5" dir="ltr">
                <span class="size-2.5 rounded-full bg-rose-400"></span>
                <span class="size-2.5 rounded-full bg-amber-400"></span>
                <span class="size-2.5 rounded-full bg-emerald-400"></span>
                <span class="ms-2 rounded-md bg-foreground/5 px-3 py-1 text-[10px] text-muted">panel.sdfr.ir / student</span>
                <span class="ms-auto inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-400"><i class="size-1.5 rounded-full bg-emerald-400"></i> آنلاین</span>
            </div>
            <div class="about-screen overflow-hidden rounded-2xl border border-border/70 bg-[#07101f]">
                <img src="/client/assets/images/about/sdfr-student-dashboard-demo.png" alt="اسکرین‌شات پنل نمایشی دانش‌آموز SDFR با برنامه، گزارش و ساعت مطالعه" loading="lazy">
            </div>
            <figcaption class="grid sm:grid-cols-4 gap-2 mt-3 px-1">
                @foreach([['۷ از ۷', 'گزارش روزانه'], ['۹:۰۳', 'مطالعه ثبت‌شده'], ['۸۲٪', 'تحقق برنامه'], ['۳', 'پیام تازه']] as $caption)
                    <div class="rounded-xl bg-foreground/[.035] px-4 py-3 text-center"><b class="text-sm text-sky-300">{{ $caption[0] }}</b><span class="text-[10px] text-muted me-2">{{ $caption[1] }}</span></div>
                @endforeach
            </figcaption>
        </figure>
    </section>

    <section class="about-shell pb-20 md:pb-28 about-reveal" x-data="{ audience: 'student' }">
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="text-xs font-black text-violet-400">برای هر نقش، یک پاسخ روشن</span>
            <h2 class="mt-3 text-2xl md:text-4xl font-black text-foreground">SDFR برای شما چه کاری انجام می‌دهد؟</h2>
        </div>
        <div class="about-glass rounded-3xl p-3 md:p-6">
            <div class="grid grid-cols-3 gap-2 rounded-2xl bg-foreground/[.035] p-1.5" role="tablist" aria-label="مخاطبان SDFR">
                <button type="button" class="about-tab rounded-xl px-2 py-3 text-xs md:text-sm font-black text-muted transition" :aria-selected="audience === 'student'" @click="audience = 'student'">دانش‌آموز</button>
                <button type="button" class="about-tab rounded-xl px-2 py-3 text-xs md:text-sm font-black text-muted transition" :aria-selected="audience === 'parent'" @click="audience = 'parent'">اولیا</button>
                <button type="button" class="about-tab rounded-xl px-2 py-3 text-xs md:text-sm font-black text-muted transition" :aria-selected="audience === 'school'" @click="audience = 'school'">مدرسه</button>
            </div>

            <div class="mt-5 md:mt-7">
                <div x-show="audience === 'student'" x-transition.opacity>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($studentFeatures as $feature)
                            <article class="rounded-2xl border border-border bg-background/35 p-5 min-h-56 flex flex-col justify-between transition hover:border-sky-400/30">
                                <div>
                                    <div class="size-11 rounded-xl bg-sky-400/10 border border-sky-400/20 flex items-center justify-center text-sky-300">
                                        @switch($feature['icon'])
                                            @case('calendar') <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg> @break
                                            @case('clock') <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> @break
                                            @case('chart') <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg> @break
                                            @case('chat') <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z"/></svg> @break
                                            @case('spark') <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3-1.5 4.5L6 9l4.5 1.5L12 15l1.5-4.5L18 9l-4.5-1.5L12 3Z"/><path d="m19 15-.7 2.3L16 18l2.3.7L19 21l.7-2.3L22 18l-2.3-.7L19 15Z"/></svg> @break
                                            @default <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>
                                        @endswitch
                                    </div>
                                    <h3 class="mt-4 text-base font-black text-foreground">{{ $feature['title'] }}</h3>
                                    <p class="mt-3 text-xs leading-6 text-muted">{{ $feature['text'] }}</p>
                                </div>
                                <div class="mt-4 rounded-lg bg-sky-400/[.07] px-3 py-2 text-xs font-black text-sky-300">{{ $feature['metric'] }}</div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div x-cloak x-show="audience === 'parent'" x-transition.opacity>
                    <div class="grid lg:grid-cols-12 gap-5">
                        <div class="lg:col-span-7 grid sm:grid-cols-2 gap-4">
                            @foreach($parentItems as $index => $item)
                                <article class="rounded-2xl border border-border bg-background/35 p-5">
                                    <span class="inline-flex size-8 items-center justify-center rounded-lg bg-emerald-400/10 text-xs font-black text-emerald-300">{{ $index + 1 }}</span>
                                    <h3 class="mt-4 font-black text-foreground">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-xs leading-6 text-muted">{{ $item['text'] }}</p>
                                </article>
                            @endforeach
                        </div>
                        <div class="lg:col-span-5 rounded-2xl border border-emerald-400/20 bg-emerald-400/[.055] p-5 md:p-7">
                            <div class="flex items-center justify-between">
                                <div><div class="text-xs text-emerald-300 font-black">پنل اولیای آراد</div><div class="mt-1 text-[10px] text-muted">گزارش هفته جاری</div></div>
                                <span class="size-10 rounded-xl bg-emerald-400/10 flex items-center justify-center"><svg class="size-5 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mt-6">
                                <div class="rounded-xl bg-background/50 p-4"><div class="text-2xl font-black text-foreground">۹:۰۳</div><div class="text-[10px] text-muted mt-1">مطالعه ثبت‌شده</div></div>
                                <div class="rounded-xl bg-background/50 p-4"><div class="text-2xl font-black text-foreground">۷/۷</div><div class="text-[10px] text-muted mt-1">گزارش ارسال‌شده</div></div>
                            </div>
                            <div class="mt-4 rounded-xl bg-background/50 p-4">
                                <div class="flex justify-between text-[10px] font-bold"><span class="text-muted">تحقق هدف مطالعه</span><span class="text-emerald-300">۸۲٪</span></div>
                                <div class="mt-3 h-2 rounded-full bg-foreground/10 overflow-hidden"><div class="h-full w-[82%] rounded-full bg-gradient-to-l from-emerald-400 to-sky-400"></div></div>
                            </div>
                            <div class="mt-4 rounded-xl border border-sky-400/15 bg-sky-400/[.06] p-4">
                                <div class="text-[10px] text-sky-300 font-black">نظر مشاور</div>
                                <p class="mt-2 text-xs leading-6 text-foreground">روند آراد منظم و رو به رشد است؛ این هفته تمرکز اصلی روی افزایش کیفیت تست‌های شیمی باشد.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-cloak x-show="audience === 'school'" x-transition.opacity>
                    <div class="grid lg:grid-cols-12 gap-5">
                        <div class="lg:col-span-5 rounded-2xl border border-indigo-400/20 bg-indigo-400/[.055] p-6">
                            <div class="text-xs font-black text-indigo-300">پنل مدیریت مدرسه</div>
                            <h3 class="mt-3 text-2xl font-black text-foreground">از حدس و تماس پراکنده، به مدیریت داده‌محور</h3>
                            <p class="mt-4 text-sm leading-8 text-muted">مدیر مدرسه می‌تواند وضعیت دانش‌آموزان، جلسات، نمره‌ها، تماس با والدین و موارد نیازمند پیگیری را در یک داشبورد متمرکز ببیند.</p>
                            <a href="{{ route('client.schools') }}" class="mt-6 inline-flex items-center gap-2 text-xs font-black text-indigo-300 hover:text-indigo-200">مشاهده طرح همکاری مدارس <span>←</span></a>
                        </div>
                        <div class="lg:col-span-7 grid sm:grid-cols-2 gap-3">
                            @foreach($schoolItems as $index => $item)
                                <div class="rounded-xl border border-border bg-background/35 p-4 flex gap-3 items-start">
                                    <span class="shrink-0 mt-0.5 size-6 rounded-full bg-indigo-400/10 text-indigo-300 flex items-center justify-center text-[10px] font-black">✓</span>
                                    <span class="text-xs leading-6 font-bold text-foreground">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-20 md:py-28 bg-foreground/[.018] border-y border-border/60 about-reveal">
        <div class="about-shell">
            <div class="text-center max-w-3xl mx-auto">
                <span class="text-xs font-black text-sky-400">چرخه‌ای که متوقف نمی‌شود</span>
                <h2 class="mt-3 text-2xl md:text-4xl font-black text-foreground">از شناخت تا نتیجه؛ هر قدم به قدم بعدی وصل است</h2>
            </div>
            <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-6 gap-4">
                @foreach([
                    ['۱', 'شناخت', 'ارزیابی شرایط و سبک یادگیری'],
                    ['۲', 'برنامه', 'طراحی مسیر اختصاصی هفته'],
                    ['۳', 'اجرا', 'مطالعه، تست و تکلیف'],
                    ['۴', 'گزارش', 'ثبت داده واقعی روزانه'],
                    ['۵', 'تحلیل', 'بازخورد مشاور و کارنامه'],
                    ['۶', 'پیگیری', 'اطلاع اولیا و اصلاح برنامه'],
                ] as $step)
                    <article class="about-step relative text-center rounded-2xl about-glass p-4 min-h-44">
                        <span class="relative z-10 mx-auto size-16 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-xl font-black text-sky-300">{{ $step[0] }}</span>
                        <h3 class="mt-4 font-black text-foreground">{{ $step[1] }}</h3>
                        <p class="mt-2 text-[10px] leading-5 text-muted">{{ $step[2] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-shell py-20 md:py-28 about-reveal">
        <div class="grid lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-5">
                <span class="text-xs font-black text-amber-400">اطلاع‌رسانی که به اقدام می‌رسد</span>
                <h2 class="mt-3 text-2xl md:text-4xl leading-[1.55] font-black text-foreground">هم دانش‌آموز می‌داند چه کند، هم خانواده می‌داند چه می‌گذرد</h2>
                <p class="mt-5 text-sm leading-8 text-muted">اعلان برنامه، بازخورد گزارش، یادآوری جلسه، پیام مشاور و موارد نیازمند پیگیری، در زمان مناسب به فرد مناسب می‌رسد. برای مدرسه نیز سابقه تماس با اولیا و پیگیری‌های اضطراری ثبت می‌شود.</p>
                <div class="mt-6 flex flex-wrap gap-2 text-[10px] font-bold text-muted">
                    <span class="rounded-full border border-border px-3 py-2">اعلان داخل پنل</span>
                    <span class="rounded-full border border-border px-3 py-2">پیامک‌های مهم</span>
                    <span class="rounded-full border border-border px-3 py-2">پیام مستقیم مشاور</span>
                    <span class="rounded-full border border-border px-3 py-2">ثبت تماس با اولیا</span>
                </div>
            </div>
            <div class="lg:col-span-7 relative">
                <div class="absolute inset-10 rounded-full bg-sky-400/10 blur-3xl"></div>
                <div class="relative space-y-3 max-w-xl mx-auto">
                    @foreach([
                        ['مشاور', 'گزارش امروزت بررسی شد', 'روند مطالعه‌ات خوب بوده؛ فردا تست‌های زمان‌دار شیمی را در اولویت بگذار.', '۲ دقیقه پیش', 'bg-sky-400/10 border-sky-400/20 text-sky-300', 'text-sky-300'],
                        ['SDFR', 'برنامه هفته جدید آماده است', '۶ پارت اختصاصی با مجموع ۱۱ ساعت مطالعه در پنل تو قرار گرفت.', 'امروز، ۱۰:۳۰', 'bg-violet-400/10 border-violet-400/20 text-violet-300', 'text-violet-300'],
                        ['پنل اولیا', 'گزارش هفتگی آراد', '۷ گزارش کامل • ۹ ساعت مطالعه • تحقق ۸۲ درصد از برنامه', 'جمعه، ۲۰:۰۰', 'bg-emerald-400/10 border-emerald-400/20 text-emerald-300', 'text-emerald-300'],
                    ] as $message)
                        <article class="about-glass rounded-2xl p-4 md:p-5 flex gap-4 transition hover:translate-x-1">
                            <span class="shrink-0 size-11 rounded-xl border flex items-center justify-center {{ $message[4] }}">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black {{ $message[5] }}">{{ $message[0] }}</span><time class="text-[9px] text-muted">{{ $message[3] }}</time></div>
                                <h3 class="mt-1.5 text-sm font-black text-foreground">{{ $message[1] }}</h3>
                                <p class="mt-1.5 text-xs leading-6 text-muted">{{ $message[2] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="about-shell pb-20 md:pb-28 about-reveal">
        <div class="rounded-[2rem] about-glass about-glow p-6 md:p-12 relative overflow-hidden">
            <div class="absolute inset-0 about-grid opacity-50 pointer-events-none"></div>
            <div class="relative grid lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8">
                    <span class="inline-flex rounded-full bg-sky-400/10 border border-sky-400/20 px-3 py-1.5 text-xs font-black text-sky-300">مسیر بعدی تو می‌تواند از همین‌جا شروع شود</span>
                    <h2 class="mt-5 text-2xl md:text-4xl leading-[1.55] font-black text-foreground">اگر قرار است برای هدفت وقت بگذاری، شایسته است مسیرت دقیق دیده شود.</h2>
                    <p class="mt-4 text-sm leading-8 text-muted">SDFR را از نزدیک تجربه کن؛ نه با وعده، با برنامه، داده، بازخورد و پیگیری واقعی.</p>
                </div>
                <div class="lg:col-span-4 flex flex-col gap-3">
                    <a href="{{ route('client.onboarding') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary px-6 py-4 text-sm font-black text-primary-foreground shadow-lg shadow-primary/20 hover:opacity-90">ثبت‌نام و شروع مسیر</a>
                    <a href="{{ route('client.parent.portal.login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-border bg-foreground/5 px-6 py-4 text-sm font-black text-foreground hover:bg-foreground/10">ورود به پنل اولیا</a>
                    <a href="{{ route('client.schools') }}" class="text-center text-xs font-bold text-sky-300 py-2 hover:text-sky-200">درخواست همکاری مدرسه ←</a>
                </div>
            </div>
        </div>
    </section>

    @script
    <script>
        const aboutObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    aboutObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        document.querySelectorAll('.about-reveal').forEach((element) => aboutObserver.observe(element));
    </script>
    @endscript
</div>
