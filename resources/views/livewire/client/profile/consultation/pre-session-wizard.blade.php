{{--
    توضیح رفع باگ: قبلاً x-data به یک تابع سراسری با نام preSessionWizard() که
    داخل یک بلوک @script جدا تعریف شده بود اشاره می‌کرد. چون اجرای @script به
    چرخه‌ی عمر کامپوننت Livewire گره خورده و تضمینی وجود ندارد که زودتر از
    ارزیابی x-data روی همین المنت اجرا شود (به‌خصوص هنگام ورود با wire:navigate)،
    گاهی Alpine با خطای «preSessionWizard is not defined» / «init is not
    defined» / «scrollModalTop is not defined» مواجه می‌شد و کل این بخش از
    صفحه (از جمله انیمیشن مودال‌ها و قفلِ اسکرول پشت مودال) از کار می‌افتاد.
    رفع شد با inline کردن مستقیمِ آبجکت داخل x-data (همان الگویی که در بقیه‌ی
    فایل‌های این پروژه مثل weekly-program-view.blade.php استفاده شده)، بدون
    وابستگی به هیچ تابع سراسری یا زمان‌بندیِ اسکریپت جداگانه.
--}}
<div class="min-h-screen bg-background sm:py-10 relative" dir="rtl"
     x-data="{
         init() {
             Livewire.on('success', () => {
                 if (navigator.vibrate) navigator.vibrate(20);
             });

             // قفل کردن اسکرول صفحه پشت مودال
             const lockScroll = (locked) => {
                 if (locked) {
                     document.body.dataset.scrollY = window.scrollY;
                     document.body.style.position = 'fixed';
                     document.body.style.top = `-${window.scrollY}px`;
                     document.body.style.left = '0';
                     document.body.style.right = '0';
                     document.body.style.width = '100%';
                 } else {
                     const y = parseInt(document.body.dataset.scrollY || '0', 10);
                     document.body.style.position = '';
                     document.body.style.top = '';
                     document.body.style.left = '';
                     document.body.style.right = '';
                     document.body.style.width = '';
                     window.scrollTo(0, y);
                 }
             };

             // وضعیت اولیه + watcher روی openCard
             if (this.$wire.openCard) lockScroll(true);
             this.$wire.$watch('openCard', (value) => {
                 lockScroll(!!value);
             });
         },
         scrollModalTop() {
             const ids = ['exam-scroll','qa-scroll','assignment-scroll','requested-scroll'];
             for (const id of ids) {
                 const el = document.getElementById(id);
                 if (el) {
                     el.scrollTo({ top: 0, behavior: 'smooth' });
                     break;
                 }
             }
         },
     }"
     x-init="init()"
     @keydown.escape.window="$wire.closeModal()"
     @scroll-modal-top.window="scrollModalTop()">

    @assets
    <style>
        .bg-grid {
            background-image:
                linear-gradient(to right, hsl(var(--border) / 0.3) 1px, transparent 1px),
                linear-gradient(to bottom, hsl(var(--border) / 0.3) 1px, transparent 1px);
            background-size: 28px 28px;
            -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 30%, transparent 80%);
            mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 30%, transparent 80%);
        }

        /* card-in / pulse-dot / icon-box rotate / badge-pop / item-in / field-reveal / live-dot — حذف شد */

        .pre-card {
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s cubic-bezier(.16,1,.3,1);
            position: relative;
        }
        @media (hover:hover) {
            .pre-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.18); }
        }

        /* دکمه‌ی چشم روی هر کارت */
        .eye-peek-btn { transition: transform .15s cubic-bezier(.16,1,.3,1), filter .15s ease, background-color .15s ease; }
        .eye-peek-btn:hover { filter: brightness(1.2); }

        @media (prefers-reduced-motion: reduce) {
            .pre-card:hover { transform: none; }
        }

        /* باکسِ «خلاصه و ثبت نهایی» — متمایز از بقیه تا کاربر متوجهِ قدمِ آخر شود */
        @keyframes summaryGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgb(236 72 153 / .35); }
            50%      { box-shadow: 0 0 0 8px rgb(236 72 153 / 0); }
        }
        .summary-card { animation: summaryGlow 2.4s ease-out infinite; }
        .summary-cta  { animation: summaryGlow 2.4s ease-out infinite; }

        .btn-primary-fancy { position:relative;overflow:hidden;background:linear-gradient(135deg,rgb(37 99 235),rgb(59 130 246));color:white;transition:box-shadow .15s ease, transform .1s cubic-bezier(.16,1,.3,1);box-shadow:0 4px 14px rgb(59 130 246/.35); }
        .btn-primary-fancy:hover:not(:disabled){box-shadow:0 6px 20px rgb(59 130 246/.5)}
        .btn-primary-fancy:active:not(:disabled){transform:scale(.98)}

        /* توضیح رفع کد نامرتب: بخش «ساعت/دقیقه/تعداد پارت» با کامپوننت مشترک
           x-ui.duration-part-picker (سبک چرخشیِ آیفون‌مانند، مطابق
           _sudden-event-content.blade.php) جایگزین شد؛ کلاس‌های counter-wrap/
           counter-btn/counter-input که فقط برای نسخهٔ قبلیِ این کنترل‌ها بودند
           و دیگر جایی استفاده نمی‌شوند حذف شدند. */

        /* ─── sheet/modal ───
           بازطراحی شد: قبلاً پنل مودال از کلاس .glass (پس‌زمینه‌ی گرادیانِ تیره +
           بلورِ سنگین) استفاده می‌کرد که ظاهر «شیشه‌ای» نامطلوبی داشت؛ حالا مثل
           ظاهر x-ui.modal یک پنل ساده و توپُر (bg-background + border + سایه) است،
           فقط بدون استفاده از خودِ کامپوننت x-ui.modal (چون این مودال‌ها به‌جای یک
           boolean ساده، وضعیت مشترک $openCard/$viewOnly را بین ۵ نوع محتوای متفاوت
           به اشتراک می‌گذارند و این با معماری event-driven آن کامپوننت جور نیست). */
        .sheet-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);z-index:90}
        .sheet{
            position:fixed;left:0;right:0;bottom:0;
            background:hsl(var(--background));
            border-top:1px solid hsl(var(--border));
            border-radius:28px 28px 0 0;
            max-height:92dvh;
            display:flex;flex-direction:column;
            z-index:100;
            padding-bottom:env(safe-area-inset-bottom,0);
            box-shadow:0 -20px 60px rgba(0,0,0,.3);
            overflow:hidden; /* مهم: جلوگیری از زدن رنگ پس‌زمینه آیکن به بیرون */
        }
        @media(min-width:768px){
            .sheet{
                left:50%;top:50%;bottom:auto;right:auto;
                transform:translate(-50%,-50%);
                width:90%;max-width:680px;
                border-radius:24px;
                border:1px solid hsl(var(--border));
                max-height:88dvh;
                box-shadow:0 25px 50px -12px rgba(0,0,0,.4);
            }
        }
        .sheet-handle{width:44px;height:5px;background:hsl(var(--muted-foreground)/.35);border-radius:999px;margin:10px auto 4px}
        @media(min-width:768px){.sheet-handle{display:none}}

        /* ─── modal enter/leave transitions (Alpine x-transition) ───
           موبایل: باز شدن از پایین صفحه، بسته شدن با اسلاید نرم از بالا (موقعیت فعلی) به پایین (خارج صفحه).
           دسکتاپ: باز/بسته شدن با فید + اسکیل ملایم. */
        .sheet-ov-enter-active,.sheet-ov-leave-active{transition:opacity .25s ease}
        .sheet-ov-enter-start,.sheet-ov-leave-end{opacity:0}
        .sheet-ov-enter-end,.sheet-ov-leave-start{opacity:1}

        .sheet-tr-enter-active{transition:transform .32s cubic-bezier(.16,1,.3,1),opacity .32s ease}
        .sheet-tr-leave-active{transition:transform .28s cubic-bezier(.4,0,.2,1),opacity .22s ease}
        .sheet-tr-enter-start,.sheet-tr-leave-end{opacity:0;transform:translateY(100%)}
        .sheet-tr-enter-end,.sheet-tr-leave-start{opacity:1;transform:translateY(0)}
        @media(min-width:768px){
            .sheet-tr-enter-start,.sheet-tr-leave-end{opacity:0;transform:translate(-50%,-46%) scale(.96)}
            .sheet-tr-enter-end,.sheet-tr-leave-start{opacity:1;transform:translate(-50%,-50%) scale(1)}
        }

        /* دکمه‌ی شیشه‌ای کارت‌های خالی (هنوز چیزی ثبت نشده) */
        .glass-btn{transition:filter .15s ease,background-color .15s ease}
        .glass-btn:hover{filter:brightness(1.15)}

        .date-grid-btn{transition:background .15s ease, border-color .15s ease, color .15s ease}
        .date-grid-btn.active{background:linear-gradient(135deg,rgb(37 99 235),rgb(59 130 246));color:white;border-color:rgb(37 99 235);box-shadow:0 4px 14px rgb(59 130 246/.4)}
        @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}
    </style>
    @endassets


    @php
        // آیکون‌ها از دیکشنری واقعی x-ui.icon (Keyline Icons) — به‌جز «qas» که معادل
        // دقیقی (حبابِ گفتگو) توی دیکشنری نداره و به‌عنوان استثنا svg دستی نگه داشته شده.
        $cards = [
            'exams' => ['title'=>'امتحانات','desc'=>'','count'=>count($exams),'color'=>'blue','hex'=>'59 130 246','icon'=>'receipt'],
            'qas' => ['title'=>'پرسش و پاسخ کلاسی','desc'=>'','count'=>count($qas),'color'=>'emerald','hex'=>'16 185 129','icon'=>null,'icon_svg'=>'<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
            'assignments' => ['title'=>'تکالیف','desc'=>'','count'=>count($assignments),'color'=>'violet','hex'=>'139 92 246','icon'=>'list-check'],
            'requested' => ['title'=>'پارت درخواستی','desc'=>'','count'=>count($requestedParts),'color'=>'orange','hex'=>'249 115 22','icon'=>'layers'],
            'summary' => ['title'=>'خلاصه و ثبت نهایی','desc'=>'','count'=>count($exams)+count($qas)+count($assignments)+count($requestedParts),'color'=>'pink','hex'=>'236 72 153','icon'=>'check'],
        ];
    @endphp

    <div class="absolute inset-0 bg-grid pointer-events-none"></div>

    <div class="container mx-auto px-3 sm:px-4 max-w-5xl relative">

        {{-- HEADER --}}
        <div class="relative overflow-hidden rounded-3xl border border-border shadow-sm mb-6">
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary to-primary/70"></div>
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary-foreground/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-10 w-72 h-72 bg-primary-foreground/20 rounded-full blur-3xl"></div>

            <div class="relative pr-5 pl-5 py-6 sm:pr-7 sm:pl-7 sm:py-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-primary-foreground mb-1">پیش‌جلسه مشاوره ({{ $session->title }})</h1>
                        <p class="mt-2 text-xs sm:text-sm text-primary-foreground/80">
                            تاریخ جلسه: <span class="font-semibold">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                            @if($session->session_time)
                                <span class="mx-1 text-primary-foreground/70">•</span>
                                <span>ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-col items-stretch gap-2 sm:items-end">
                        @php
                            // (E2) در حالتِ هفتهٔ آزمایشی، بازگشت به صفحهٔ راهنما (guide) — نه لیستِ جلسات.
                            $u = auth()->user();
                            $inTrial = $u && $u->trialWeek && ! $u->isSchoolStudent()
                                && ! ($u->student && $u->student->hasActivePaidAccess());
                            $backRoute = $inTrial
                                ? route('client.profile.trial.guide')
                                : route('client.profile.consultation.sessions');
                        @endphp
                        <a wire:navigate href="{{ $backRoute }}"
                           class="btn-press inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary-foreground/15 hover:bg-primary-foreground/25 backdrop-blur px-4 py-2 text-xs sm:text-sm font-medium text-primary-foreground transition-colors" data-elevated="false">
                            <span>بازگشت{{ $inTrial ? ' به راهنما' : ' به لیست' }}</span>
                            {{-- استثنا: آیکون «بازگشت/undo» منحنی در دیکشنری موجود نیست --}}
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(!$canEdit)
            <div class="mb-6 flex items-start gap-2 rounded-2xl border border-warning/30 bg-warning/10 px-4 py-3 text-xs text-warning">
                <x-ui.icon name="triangle-alert" class="w-5 h-5 mt-0.5 shrink-0"/>
                <p class="leading-6">زمان ویرایش پیش‌جلسه به پایان رسیده است. فقط می‌توانید اطلاعات ثبت‌شده را مشاهده کنید.</p>
            </div>
        @endif

        @if($schoolLocked)
            <div class="mb-6 flex items-start gap-2 rounded-2xl border border-info/30 bg-info/10 px-4 py-3 text-xs text-info">
                <x-ui.icon name="info" class="w-5 h-5 mt-0.5 shrink-0"/>
                <div class="flex-1 space-y-3">
                    <p class="leading-6">چون در حال حاضر مدرسه نمی‌روی، بخش‌های «امتحانات»، «پرسش و پاسخ کلاسی» و «تکالیف» برای تو غیرفعال‌اند. فقط <strong>پارت درخواستی</strong> را ثبت کن.</p>
                    <x-ui.button href="{{ route('client.profile.consultation.class-schedule', ['from' => 'pre-session', 'return_to' => request()->fullUrl()]) }}" wire:navigate variant="info-soft" size="sm" icon="arrow-left">
                        همین حالا تغییرش بده
                    </x-ui.button>
                </div>
            </div>
        @endif

        {{-- راهنما --}}
        <div dir="rtl" class="mb-6 rounded-2xl border border-border bg-secondary p-4">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-muted leading-relaxed space-y-1">
                    <p>- هر کارت یک بخش از پیش‌جلسه است؛ روی هر کدام بزن و موارد هفته‌ی پیش رو را ثبت کن.</p>
                    <p>- <strong class="text-foreground">امتحانات:</strong> امتحان‌هایی که در پیش داری، همراه با تاریخ و زمان موردنیاز.</p>
                    <p>- <strong class="text-foreground">پرسش و پاسخ کلاسی:</strong> پرسش‌و‌پاسخ‌هایی که باید برایشان آماده شوی.</p>
                    <p>- <strong class="text-foreground">تکالیف:</strong> تکالیفی که باید تا تاریخ مشخص انجام دهی.</p>
                    <p>- <strong class="text-foreground">پارت درخواستی:</strong> درس‌هایی که می‌خواهی حتماً در برنامه‌ات باشند.</p>
                    <p>- بعد از تکمیل بخش‌ها، از کارت <strong class="text-foreground">«خلاصه و ثبت نهایی»</strong> پیش‌جلسه را نهایی کن.</p>
                </div>
            </div>
        </div>

        {{-- CARDS GRID --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($cards as $key => $card)
                @php $isLockedCard = $schoolLocked && in_array($key, ['exams', 'qas', 'assignments'], true); @endphp
                <div class="pre-card group rounded-2xl glass border-2 p-5 {{ $isLockedCard ? 'opacity-55' : '' }} {{ $key === 'summary' ? 'summary-card col-span-2 lg:col-span-3 border-pink-500/60 bg-pink-500/[0.06] ring-2 ring-pink-500/30' : 'border-border bg-card' }}"
                     style="--accent: rgb({{ $card['hex'] }});">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl border"
                             style="background:rgb({{ $card['hex'] }}/.1);color:rgb({{ $card['hex'] }});border-color:rgb({{ $card['hex'] }}/.3);">
                            @if($card['icon'])
                                <x-ui.icon :name="$card['icon']" class="w-6 h-6"/>
                            @else
                                {{-- استثنا: حبابِ گفتگو برای «qas» در دیکشنری موجود نیست --}}
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $card['icon_svg'] !!}</svg>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if(!$isLockedCard && $key !== 'summary')
                                {{-- دکمه‌ی چشم: نمایش سریع فقط‌خواندنی همین کارت --}}
                                <button type="button" wire:click="openModal('{{ $key }}', true)"
                                        title="نمایش سریع"
                                        class="btn-press glass-btn eye-peek-btn w-8 h-8 rounded-full border flex items-center justify-center text-muted-foreground hover:text-foreground transition-all active:scale-90" data-elevated="false"
                                        style="background:rgb({{ $card['hex'] }}/.08);border-color:rgb({{ $card['hex'] }}/.25);">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            @endif
                            @if($isLockedCard)
                                <span class="inline-flex items-center gap-1 rounded-full border border-border bg-secondary px-2.5 py-1 text-[11px] font-black text-muted-foreground">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    قفل
                                </span>
                            @elseif($key === 'summary')
                                <span class="inline-flex items-center gap-1 rounded-full border border-pink-500/40 bg-pink-500/10 px-2.5 py-1 text-[11px] font-black text-pink-500">
                                    <span class="w-1.5 h-1.5 bg-pink-500 rounded-full animate-pulse"></span> قدم آخر
                                </span>
                            @elseif($card['count'] > 0)
                                <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-[11px] font-black"
                                      style="background:rgb({{ $card['hex'] }}/.1);color:rgb({{ $card['hex'] }});border-color:rgb({{ $card['hex'] }}/.3);">
                                    {{ $card['count'] }} مورد
                                </span>
                            @endif
                        </div>
                    </div>
                    <h3 class="font-black text-base tracking-tight text-foreground mb-1">{{ $card['title'] }}</h3>
                    <p class="text-xs text-muted-foreground leading-6 mb-5">{{ $key === 'summary' ? 'وقتی همه‌ی موارد بالا را ثبت کردی، این دکمه را بزن تا پیش‌جلسه‌ات نهایی و ارسال شود.' : $card['desc'] }}</p>

                    @if($isLockedCard)
                        <div class="text-center py-2.5 text-xs text-muted-foreground italic">چون مدرسه نمی‌روی، نیازی به این بخش نداری</div>
                    @elseif($key === 'summary')
                        <button wire:click="openModal('summary')" data-elevated="true"
                                class="btn-press summary-cta w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-base font-black text-white transition-all hover:scale-[1.01] active:scale-[0.98]"
                                style="background:linear-gradient(135deg,rgb({{ $card['hex'] }}),rgb({{ $card['hex'] }}/.85));box-shadow:0 4px 14px rgb({{ $card['hex'] }}/.4);">
                            مشاهده خلاصه و ثبت نهایی
                            <x-ui.icon name="arrow-left" class="w-4 h-4"/>
                        </button>
                    @elseif($canEdit)
                        @if($card['count'] > 0)
                            <button wire:click="openModal('{{ $key }}')" data-elevated="true"
                                    class="btn-press w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all active:scale-[0.97]"
                                    style="background:linear-gradient(135deg,rgb({{ $card['hex'] }}),rgb({{ $card['hex'] }}/.85));box-shadow:0 4px 14px rgb({{ $card['hex'] }}/.4);">
                                ویرایش
                                <x-ui.icon name="pen-line" class="w-4 h-4"/>
                            </button>
                        @else
                            <button wire:click="openModal('{{ $key }}')" data-elevated="false"
                                    class="btn-press glass-btn w-full inline-flex items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-bold transition-all active:scale-[0.97]"
                                    style="background:rgb({{ $card['hex'] }}/.08);border-color:rgb({{ $card['hex'] }}/.3);color:rgb({{ $card['hex'] }});backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);">
                                ثبت
                                <x-ui.icon name="plus" class="w-4 h-4"/>
                            </button>
                        @endif
                    @else
                        @if($card['count'] > 0)
                            <button wire:click="openModal('{{ $key }}', true)" data-elevated="false"
                                    class="btn-press w-full inline-flex items-center justify-center gap-2 rounded-xl bg-secondary hover:bg-secondary/70 px-4 py-2.5 text-sm font-bold text-foreground border border-border transition-all active:scale-[0.97]">
                                مشاهده
                                <x-ui.icon name="eye" class="w-4 h-4"/>
                            </button>
                        @else
                            <div class="text-center py-2.5 text-xs text-muted-foreground italic bg-secondary rounded-xl">وجود ندارد!</div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ════════════ MODALS ════════════ --}}
        {{--
            ساختار جدید: هدر (با border-b) | بدنه = کانتنت + دکمه (یک پس‌زمینه واحد، بدون border بین‌شون)
        --}}

        {{-- ── EXAMS MODAL ── --}}
        @if($openCard === 'exams')
            <div wire:key="modal-exams">
                <div class="sheet-overlay"
                     x-transition:enter="sheet-ov-enter-active"
                     x-transition:enter-start="sheet-ov-enter-start"
                     x-transition:enter-end="sheet-ov-enter-end"
                     x-transition:leave="sheet-ov-leave-active"
                     x-transition:leave-start="sheet-ov-leave-start"
                     x-transition:leave-end="sheet-ov-leave-end"
                     wire:click="closeModal"></div>
                <div class="sheet"
                     x-transition:enter="sheet-tr-enter-active"
                     x-transition:enter-start="sheet-tr-enter-start"
                     x-transition:enter-end="sheet-tr-enter-end"
                     x-transition:leave="sheet-tr-leave-active"
                     x-transition:leave-start="sheet-tr-leave-start"
                     x-transition:leave-end="sheet-tr-leave-end"
                     @click.stop>
                    <div class="sheet-handle"></div>

                    {{-- HEADER (جدا با border-b) --}}
                    <div class="shrink-0 px-5 py-4 border-b border-border flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-600 border border-blue-500/30 flex items-center justify-center">
                                <x-ui.icon name="receipt" class="w-5 h-5"/>
                            </div>
                            <div>
                                <h3 class="font-black text-base">امتحانات هفته پیش رو</h3>
                                <p class="text-[11px] text-muted-foreground mt-0.5">امتحاناتی که در پیش داری رو ثبت کن</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($viewOnly)
                                <span class="hidden sm:inline-flex items-center gap-1 rounded-full border border-border bg-secondary px-2.5 py-1 text-[10px] font-bold text-muted-foreground">
                                    <x-ui.icon name="eye" class="w-3 h-3"/>
                                    فقط نمایش
                                </span>
                            @endif
                            <button wire:click="closeModal" class="btn-press w-9 h-9 rounded-xl hover:bg-muted text-muted-foreground hover:text-foreground transition-all active:scale-90 flex items-center justify-center" data-elevated="false">
                                <x-ui.icon name="x" class="w-5 h-5"/>
                            </button>
                        </div>
                    </div>

                    {{-- BODY (کانتنت + دکمه به صورت یک تکه، یک پس‌زمینه) --}}
                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="flex-1 overflow-y-auto p-5 space-y-5" x-ref="examScroll" id="exam-scroll">

                            @if(count($exams) > 0)
                                <div>
                                    <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5">
                                        <x-ui.icon name="check" class="w-3.5 h-3.5 text-success"/>
                                        ثبت شده ({{ count($exams) }} مورد)
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($exams as $exam)
                                            <div class="flex items-center justify-between rounded-xl bg-blue-500/10 border border-blue-500/20 px-3 py-2.5 text-xs sm:text-sm">
                                                <div>
                                                    <span class="text-muted-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($exam['exam_date'],'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }} :</span>
                                                    <span class="font-bold mr-1">{{ $exam['subject'] }}</span>
                                                    <span class="text-muted-foreground">({{ $exam['part_count'] }} پارت | {{ $exam['time_per_part'] }} دقیقه)</span>
                                                </div>
                                                @if($canEdit && !$viewOnly)
                                                    <button wire:click="deleteExam({{ $exam['id'] }})" class="btn-press text-error hover:bg-error/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all active:scale-90 mr-2 shrink-0" data-elevated="false">
                                                        <x-ui.icon name="trash" class="w-4 h-4"/>
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($canEdit && !$viewOnly)
                                <div class="space-y-4 {{ count($exams) > 0 ? 'pt-4 border-border' : '' }}">
                                    <h4 class="text-xs font-bold text-muted-foreground flex items-center gap-1.5">
                                        <x-ui.icon name="plus" class="w-3.5 h-3.5 text-blue-500"/>
                                        افزودن امتحان جدید
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                            @if(count($availableSubjects) > 0)
                                                <x-ui.select wire:model.live="examForm.cc_subject_id"
                                                             :options="array_map(fn($s)=>['id'=>$s['id'],'name'=>$s['name'].' ('.($s['type']==='general'?'عمومی':'تخصصی').')'],$availableSubjects)"
                                                             value-key="id" label-key="name" placeholder="انتخاب درس..."/>
                                            @else
                                                <input type="text" wire:model="examForm.subject" placeholder="مثال: ریاضی" class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                            @endif
                                            @error('examForm.subject')<span class="mt-1 block text-xs text-error">{{ $message }}</span>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1.5">فصل</label>
                                            <div wire:key="exam-chapter-{{ $examForm['cc_subject_id'] }}">
                                                <x-ui.select wire:model="examForm.cc_chapter_id" :options="$availableChapters" value-key="id" label-key="name" placeholder="ابتدا درس را انتخاب کنید" :disabled="count($availableChapters)===0"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold mb-2">تاریخ امتحان</label>
                                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                            @foreach($availableDates as $dateItem)
                                                <button type="button" wire:click="$set('examForm.exam_date','{{ $dateItem['value'] }}')"
                                                        data-elevated="false" class="btn-press date-grid-btn flex flex-col items-center justify-center px-2 py-2.5 rounded-xl border-2 text-xs {{ $examForm['exam_date']===$dateItem['value'] ? 'active' : 'border-border bg-background hover:border-blue-400' }}">
                                                    <span class="font-bold text-[11px]">{{ $dateItem['day_name'] }}</span>
                                                    <span class="text-[10px] opacity-80 mt-0.5">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                        @error('examForm.exam_date')<span class="mt-1 block text-xs text-error">{{ $message }}</span>@enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold mb-1">برای مطالعه‌ی این امتحان چقدر زمان نیاز داری؟</label>
                                        <p class="text-[11px] text-muted-foreground mb-3">حداقل ۱۵ دقیقه — این زمان به چند پارت تقسیم می‌شود</p>
                                        <x-ui.duration-part-picker
                                            part-count-model="examForm.part_count"
                                            hours-model="examForm.hours"
                                            minutes-model="examForm.minutes"
                                        />
                                        @error('examForm.part_count')<span class="mt-1.5 block text-xs text-error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($canEdit && !$viewOnly)
                            <div class="shrink-0 px-5 pb-5 pt-2">
                                <button wire:click="addExam" wire:loading.attr="disabled" wire:target="addExam" data-elevated="true"
                                        class="btn-press btn-primary-fancy w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black">
                                    <span wire:loading.remove wire:target="addExam" class="flex items-center gap-2">
                                        افزودن امتحان
                                        <x-ui.icon name="plus" class="w-4 h-4"/>
                                    </span>
                                    <x-ui.spinner size="sm" class="text-white" wire:loading wire:target="addExam" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ── QAS MODAL ── --}}
        @if($openCard === 'qas')
            <div wire:key="modal-qas">
                <div class="sheet-overlay"
                     x-transition:enter="sheet-ov-enter-active"
                     x-transition:enter-start="sheet-ov-enter-start"
                     x-transition:enter-end="sheet-ov-enter-end"
                     x-transition:leave="sheet-ov-leave-active"
                     x-transition:leave-start="sheet-ov-leave-start"
                     x-transition:leave-end="sheet-ov-leave-end"
                     wire:click="closeModal"></div>
                <div class="sheet"
                     x-transition:enter="sheet-tr-enter-active"
                     x-transition:enter-start="sheet-tr-enter-start"
                     x-transition:enter-end="sheet-tr-enter-end"
                     x-transition:leave="sheet-tr-leave-active"
                     x-transition:leave-start="sheet-tr-leave-start"
                     x-transition:leave-end="sheet-tr-leave-end"
                     @click.stop>
                    <div class="sheet-handle"></div>

                    <div class="shrink-0 px-5 py-4 border-b border-border flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 border border-emerald-500/30 flex items-center justify-center">
                                {{-- استثنا: حبابِ گفتگو در دیکشنری موجود نیست --}}
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/></svg>
                            </div>
                            <div><h3 class="font-black text-base">پرسش و پاسخ کلاسی</h3><p class="text-[11px] text-muted-foreground mt-0.5">پرسش‌و‌پاسخ‌های هفته پیش رو</p></div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($viewOnly)
                                <span class="hidden sm:inline-flex items-center gap-1 rounded-full border border-border bg-secondary px-2.5 py-1 text-[10px] font-bold text-muted-foreground">
                                    <x-ui.icon name="eye" class="w-3 h-3"/>
                                    فقط نمایش
                                </span>
                            @endif
                            <button wire:click="closeModal" class="btn-press w-9 h-9 rounded-xl hover:bg-muted transition-all active:scale-90 flex items-center justify-center" data-elevated="false"><x-ui.icon name="x" class="w-5 h-5"/></button>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="flex-1 overflow-y-auto p-5 space-y-5" id="qa-scroll">
                            @if(count($qas) > 0)
                                <div>
                                    <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5"><x-ui.icon name="check" class="w-3.5 h-3.5 text-success"/>ثبت شده ({{ count($qas) }} مورد)</h4>
                                    <div class="space-y-2">
                                        @foreach($qas as $qa)
                                            <div class="flex items-center justify-between rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-3 py-2.5 text-xs sm:text-sm">
                                                <div>
                                                    <span class="text-muted-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($qa['qa_date'],'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }} :</span>
                                                    <span class="font-bold mr-1">{{ $qa['subject'] }}</span>
                                                    <span class="text-muted-foreground">({{ $qa['part_count'] }} پارت | {{ $qa['time_per_part'] }} دقیقه)</span>
                                                </div>
                                                @if($canEdit && !$viewOnly)
                                                    <button wire:click="deleteQa({{ $qa['id'] }})" class="btn-press text-error hover:bg-error/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all active:scale-90 mr-2 shrink-0" data-elevated="false"><x-ui.icon name="trash" class="w-4 h-4"/></button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($canEdit && !$viewOnly)
                                <div class="space-y-4 {{ count($qas) > 0 ? 'pt-4 border-t border-border' : '' }}">
                                    <h4 class="text-xs font-bold text-muted-foreground">افزودن پرسش‌و‌پاسخ جدید</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                            @if(count($availableSubjects) > 0)
                                                <x-ui.select wire:model.live="qaForm.cc_subject_id" :options="array_map(fn($s)=>['id'=>$s['id'],'name'=>$s['name'].' ('.($s['type']==='general'?'عمومی':'تخصصی').')'],$availableSubjects)" value-key="id" label-key="name" placeholder="انتخاب درس..."/>
                                            @else
                                                <input type="text" wire:model="qaForm.subject" placeholder="مثال: فیزیک" class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm">
                                            @endif
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1.5">فصل</label>
                                            <div wire:key="qa-chapter-{{ $qaForm['cc_subject_id'] }}">
                                                <x-ui.select wire:model="qaForm.cc_chapter_id" :options="$availableChapters" value-key="id" label-key="name" placeholder="ابتدا درس را انتخاب کنید" :disabled="count($availableChapters)===0"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold mb-2">تاریخ پرسش و پاسخ</label>
                                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                            @foreach($availableDates as $dateItem)
                                                <button type="button" wire:click="$set('qaForm.qa_date','{{ $dateItem['value'] }}')"
                                                        data-elevated="false" class="btn-press date-grid-btn flex flex-col items-center justify-center px-2 py-2.5 rounded-xl border-2 text-xs {{ $qaForm['qa_date']===$dateItem['value'] ? 'active' : 'border-border bg-background hover:border-blue-400' }}">
                                                    <span class="font-bold text-[11px]">{{ $dateItem['day_name'] }}</span>
                                                    <span class="text-[10px] opacity-80 mt-0.5">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                        @error('qaForm.qa_date')<span class="mt-1 block text-xs text-error">{{ $message }}</span>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold mb-1">برای آمادگی چقدر زمان نیاز داری؟</label>
                                        <p class="text-[11px] text-muted-foreground mb-3">حداقل ۱۵ دقیقه — این زمان به چند پارت تقسیم می‌شود</p>
                                        <x-ui.duration-part-picker
                                            part-count-model="qaForm.part_count"
                                            hours-model="qaForm.hours"
                                            minutes-model="qaForm.minutes"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($canEdit && !$viewOnly)
                            <div class="shrink-0 px-5 pb-5 pt-2">
                                <button wire:click="addQa" wire:loading.attr="disabled" wire:target="addQa" data-elevated="true"
                                        class="btn-press w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all active:scale-[0.98]"
                                        style="background:linear-gradient(135deg,rgb(5 150 105),rgb(16 185 129));box-shadow:0 4px 14px rgb(16 185 129/.4);">
                                    <span wire:loading.remove wire:target="addQa" class="flex items-center gap-2">افزودن پرسش‌و‌پاسخ<x-ui.icon name="plus" class="w-4 h-4"/></span>
                                    <x-ui.spinner size="sm" class="text-white" wire:loading wire:target="addQa" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ── ASSIGNMENTS MODAL ── --}}
        @if($openCard === 'assignments')
            <div wire:key="modal-assignments">
                <div class="sheet-overlay"
                     x-transition:enter="sheet-ov-enter-active"
                     x-transition:enter-start="sheet-ov-enter-start"
                     x-transition:enter-end="sheet-ov-enter-end"
                     x-transition:leave="sheet-ov-leave-active"
                     x-transition:leave-start="sheet-ov-leave-start"
                     x-transition:leave-end="sheet-ov-leave-end"
                     wire:click="closeModal"></div>
                <div class="sheet"
                     x-transition:enter="sheet-tr-enter-active"
                     x-transition:enter-start="sheet-tr-enter-start"
                     x-transition:enter-end="sheet-tr-enter-end"
                     x-transition:leave="sheet-tr-leave-active"
                     x-transition:leave-start="sheet-tr-leave-start"
                     x-transition:leave-end="sheet-tr-leave-end"
                     @click.stop>
                    <div class="sheet-handle"></div>

                    <div class="shrink-0 px-5 py-4 border-b border-border flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-violet-500/10 text-violet-600 border border-violet-500/30 flex items-center justify-center"><x-ui.icon name="list-check" class="w-5 h-5"/></div>
                            <div><h3 class="font-black text-base">تکالیف هفته</h3><p class="text-[11px] text-muted-foreground mt-0.5">تکالیف هفته‌ی پیش رو</p></div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($viewOnly)
                                <span class="hidden sm:inline-flex items-center gap-1 rounded-full border border-border bg-secondary px-2.5 py-1 text-[10px] font-bold text-muted-foreground">
                                    <x-ui.icon name="eye" class="w-3 h-3"/>
                                    فقط نمایش
                                </span>
                            @endif
                            <button wire:click="closeModal" class="btn-press w-9 h-9 rounded-xl hover:bg-muted transition-all active:scale-90 flex items-center justify-center" data-elevated="false"><x-ui.icon name="x" class="w-5 h-5"/></button>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="flex-1 overflow-y-auto p-5 space-y-5" id="assignment-scroll">
                            @if(count($assignments) > 0)
                                <div>
                                    <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5"><x-ui.icon name="check" class="w-3.5 h-3.5 text-success"/>ثبت شده ({{ count($assignments) }} مورد)</h4>
                                    <div class="space-y-2">
                                        @foreach($assignments as $assignment)
                                            <div class="flex items-center justify-between rounded-xl bg-violet-500/10 border border-violet-500/20 px-3 py-2.5 text-xs sm:text-sm">
                                                <div>
                                                    <span class="text-muted-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($assignment['due_date'],'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }} :</span>
                                                    <span class="font-bold mr-1">{{ $assignment['subject'] }}</span>
                                                    <span class="text-muted-foreground">({{ $assignment['part_count'] }} پارت | {{ $assignment['time_per_part'] }} دقیقه)</span>
                                                </div>
                                                @if($canEdit && !$viewOnly)
                                                    <button wire:click="deleteAssignment({{ $assignment['id'] }})" class="btn-press text-error hover:bg-error/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all active:scale-90 mr-2 shrink-0" data-elevated="false"><x-ui.icon name="trash" class="w-4 h-4"/></button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($canEdit && !$viewOnly)
                                <div class="space-y-4 {{ count($assignments) > 0 ? 'pt-4 border-t border-border' : '' }}">
                                    <h4 class="text-xs font-bold text-muted-foreground">افزودن تکلیف جدید</h4>
                                    <div>
                                        <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                        @if(count($availableSubjects) > 0)
                                            <x-ui.select wire:model.live="assignmentForm.cc_subject_id" :options="array_map(fn($s)=>['id'=>$s['id'],'name'=>$s['name'].' ('.($s['type']==='general'?'عمومی':'تخصصی').')'],$availableSubjects)" value-key="id" label-key="name" placeholder="انتخاب درس..."/>
                                        @else
                                            <input type="text" wire:model="assignmentForm.subject" placeholder="مثال: شیمی" class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold mb-2">تاریخ تحویل تکلیف</label>
                                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                            @foreach($availableDates as $dateItem)
                                                <button type="button" wire:click="$set('assignmentForm.due_date','{{ $dateItem['value'] }}')"
                                                        data-elevated="false" class="btn-press date-grid-btn flex flex-col items-center justify-center px-2 py-2.5 rounded-xl border-2 text-xs {{ $assignmentForm['due_date']===$dateItem['value'] ? 'active' : 'border-border bg-background hover:border-blue-400' }}">
                                                    <span class="font-bold text-[11px]">{{ $dateItem['day_name'] }}</span>
                                                    <span class="text-[10px] opacity-80 mt-0.5">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                        @error('assignmentForm.due_date')<span class="mt-1 block text-xs text-error">{{ $message }}</span>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold mb-1">برای انجام این تکلیف چقدر زمان نیاز داری؟</label>
                                        <p class="text-[11px] text-muted-foreground mb-3">حداقل ۱۵ دقیقه — این زمان به چند پارت تقسیم می‌شود</p>
                                        <x-ui.duration-part-picker
                                            part-count-model="assignmentForm.part_count"
                                            hours-model="assignmentForm.hours"
                                            minutes-model="assignmentForm.minutes"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($canEdit && !$viewOnly)
                            <div class="shrink-0 px-5 pb-5 pt-2">
                                <button wire:click="addAssignment" wire:loading.attr="disabled" wire:target="addAssignment" data-elevated="true"
                                        class="btn-press w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all active:scale-[0.98]"
                                        style="background:linear-gradient(135deg,rgb(124 58 237),rgb(139 92 246));box-shadow:0 4px 14px rgb(139 92 246/.4);">
                                    <span wire:loading.remove wire:target="addAssignment" class="flex items-center gap-2">افزودن تکلیف<x-ui.icon name="plus" class="w-4 h-4"/></span>
                                    <x-ui.spinner size="sm" class="text-white" wire:loading wire:target="addAssignment" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ── REQUESTED PARTS MODAL ── --}}
        @if($openCard === 'requested')
            <div wire:key="modal-requested">
                <div class="sheet-overlay"
                     x-transition:enter="sheet-ov-enter-active"
                     x-transition:enter-start="sheet-ov-enter-start"
                     x-transition:enter-end="sheet-ov-enter-end"
                     x-transition:leave="sheet-ov-leave-active"
                     x-transition:leave-start="sheet-ov-leave-start"
                     x-transition:leave-end="sheet-ov-leave-end"
                     wire:click="closeModal"></div>
                <div class="sheet"
                     x-transition:enter="sheet-tr-enter-active"
                     x-transition:enter-start="sheet-tr-enter-start"
                     x-transition:enter-end="sheet-tr-enter-end"
                     x-transition:leave="sheet-tr-leave-active"
                     x-transition:leave-start="sheet-tr-leave-start"
                     x-transition:leave-end="sheet-tr-leave-end"
                     @click.stop>
                    <div class="sheet-handle"></div>

                    <div class="shrink-0 px-5 py-4 border-b border-border flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-orange-500/10 text-orange-600 border border-orange-500/30 flex items-center justify-center"><x-ui.icon name="layers" class="w-5 h-5"/></div>
                            <div><h3 class="font-black text-base">پارت درخواستی</h3><p class="text-[11px] text-muted-foreground mt-0.5">درس‌هایی که می‌خوای در برنامه باشن</p></div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($viewOnly)
                                <span class="hidden sm:inline-flex items-center gap-1 rounded-full border border-border bg-secondary px-2.5 py-1 text-[10px] font-bold text-muted-foreground">
                                    <x-ui.icon name="eye" class="w-3 h-3"/>
                                    فقط نمایش
                                </span>
                            @endif
                            <button wire:click="closeModal" class="btn-press w-9 h-9 rounded-xl hover:bg-muted transition-all active:scale-90 flex items-center justify-center" data-elevated="false"><x-ui.icon name="x" class="w-5 h-5"/></button>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="flex-1 overflow-y-auto p-5 space-y-5" id="requested-scroll">
                            @if(count($requestedParts) > 0)
                                <div>
                                    <h4 class="text-xs font-bold text-muted-foreground mb-2 flex items-center gap-1.5"><x-ui.icon name="check" class="w-3.5 h-3.5 text-success"/>ثبت شده ({{ count($requestedParts) }} مورد)</h4>
                                    <div class="space-y-2">
                                        @foreach($requestedParts as $rp)
                                            <div class="flex items-start justify-between rounded-xl bg-orange-500/10 border border-orange-500/20 px-3 py-2.5 text-xs sm:text-sm">
                                                <div class="flex-1">
                                                    <span class="font-bold">{{ $rp['subject'] }}</span>
                                                    <span class="text-muted-foreground mr-1">({{ $rp['part_count'] }} پارت | {{ $rp['time_per_part'] }} دقیقه)</span>
                                                    @if(!empty($rp['description']))<p class="mt-1 text-muted-foreground text-[11px] leading-5">{{ $rp['description'] }}</p>@endif
                                                </div>
                                                @if($canEdit && !$viewOnly)
                                                    <button wire:click="deleteRequestedPart({{ $rp['id'] }})" class="btn-press text-error hover:bg-error/10 rounded-lg w-8 h-8 flex items-center justify-center transition-all active:scale-90 mr-2 shrink-0" data-elevated="false"><x-ui.icon name="trash" class="w-4 h-4"/></button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($canEdit && !$viewOnly)
                                <div class="space-y-4 {{ count($requestedParts) > 0 ? 'pt-4 border-t border-border' : '' }}">
                                    <h4 class="text-xs font-bold text-muted-foreground">افزودن پارت درخواستی</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1.5">درس را انتخاب کنید</label>
                                            @if(count($availableGradeSubjects) > 0)
                                                {{-- wire:ignore: گزینه‌ها ثابت‌اند؛ جلوگیری از morph لایوویر روی x-for های تو‌درتو که باعث پریدن/خراب‌شدن select فصل می‌شد --}}
                                                <div wire:ignore>
                                                    <x-ui.select-grouped wire:model.live="requestedPartForm.cc_subject_id" :groups="$availableGradeSubjects" group-label-key="grade_label" group-items-key="subjects" value-key="id" label-key="name" placeholder="انتخاب درس..." :searchable="true" search-placeholder="جستجوی درس..."/>
                                                </div>
                                            @else
                                                <input type="text" wire:model="requestedPartForm.subject" placeholder="مثال: ریاضی" class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm">
                                            @endif
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1.5">فصل <span class="text-muted-foreground font-normal">(اختیاری)</span></label>
                                            {{-- wire:key وابسته به درس: با تغییر درس، select فصل بازسازی و فصل‌های جدید نمایش داده می‌شوند --}}
                                            <div wire:key="rp-chapter-{{ $requestedPartForm['cc_subject_id'] }}">
                                                <x-ui.select wire:model="requestedPartForm.cc_chapter_id" :options="$requestedPartChapters" value-key="id" label-key="name" placeholder="ابتدا درس را انتخاب کنید" :disabled="count($requestedPartChapters)===0"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold mb-1.5">توضیحات <span class="text-muted-foreground font-normal">(اختیاری)</span></label>
                                        <textarea wire:model="requestedPartForm.description" rows="3" placeholder="مثال: از ابتدای فصل تا مبحث مشتق" class="w-full rounded-xl border border-border bg-background px-3 py-2.5 text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold mb-1">چقدر زمان برای این درس می‌خوای؟</label>
                                        <p class="text-[11px] text-muted-foreground mb-3">حداقل ۱۵ دقیقه — این زمان به چند پارت تقسیم می‌شود</p>
                                        <x-ui.duration-part-picker
                                            part-count-model="requestedPartForm.part_count"
                                            hours-model="requestedPartForm.hours"
                                            minutes-model="requestedPartForm.minutes"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($canEdit && !$viewOnly)
                            <div class="shrink-0 px-5 pb-5 pt-2">
                                <button wire:click="addRequestedPart" wire:loading.attr="disabled" wire:target="addRequestedPart" data-elevated="true"
                                        class="btn-press w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all active:scale-[0.98]"
                                        style="background:linear-gradient(135deg,rgb(234 88 12),rgb(249 115 22));box-shadow:0 4px 14px rgb(249 115 22/.4);">
                                    <span wire:loading.remove wire:target="addRequestedPart" class="flex items-center gap-2">افزودن پارت درخواستی<x-ui.icon name="plus" class="w-4 h-4"/></span>
                                    <x-ui.spinner size="sm" class="text-white" wire:loading wire:target="addRequestedPart" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ── SUMMARY MODAL ── --}}
        @if($openCard === 'summary')
            <div wire:key="modal-summary">
                <div class="sheet-overlay"
                     x-transition:enter="sheet-ov-enter-active"
                     x-transition:enter-start="sheet-ov-enter-start"
                     x-transition:enter-end="sheet-ov-enter-end"
                     x-transition:leave="sheet-ov-leave-active"
                     x-transition:leave-start="sheet-ov-leave-start"
                     x-transition:leave-end="sheet-ov-leave-end"
                     wire:click="closeModal"></div>
                <div class="sheet"
                     x-transition:enter="sheet-tr-enter-active"
                     x-transition:enter-start="sheet-tr-enter-start"
                     x-transition:enter-end="sheet-tr-enter-end"
                     x-transition:leave="sheet-tr-leave-active"
                     x-transition:leave-start="sheet-tr-leave-start"
                     x-transition:leave-end="sheet-tr-leave-end"
                     @click.stop>
                    <div class="sheet-handle"></div>

                    <div class="shrink-0 px-5 py-4 border-b border-border flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-pink-500/10 text-pink-600 border border-pink-500/30 flex items-center justify-center"><x-ui.icon name="check" class="w-5 h-5"/></div>
                            <div><h3 class="font-black text-base">خلاصه پیش‌جلسه</h3><p class="text-[11px] text-muted-foreground mt-0.5">قبل از ثبت نهایی مرور کنید</p></div>
                        </div>
                        <button wire:click="closeModal" class="btn-press w-9 h-9 rounded-xl hover:bg-muted transition-all active:scale-90 flex items-center justify-center" data-elevated="false"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <div class="flex-1 overflow-y-auto p-5 space-y-4">
                            <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                                <h4 class="mb-3 text-xs font-bold text-blue-600 flex items-center gap-2"><x-ui.icon name="receipt" class="w-4 h-4"/>امتحانات ({{ count($exams) }} مورد)</h4>
                                <div class="overflow-x-auto -mx-1">
                                    <table class="w-full text-xs sm:text-sm border-collapse">
                                        <thead>
                                        <tr class="text-muted-foreground border-b border-border">
                                            <th class="text-right py-2 px-2 font-semibold">درس</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">پارت | دقیقه</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">تاریخ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($exams as $exam)
                                            <tr class="border-b border-dashed border-border last:border-b-0">
                                                <td class="py-2 px-2 font-medium">{{ $exam['subject'] }}</td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">
                                                     {{ $exam['part_count'] }} پارت | {{ $exam['time_per_part'] }}دقیقه</td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($exam['exam_date'],'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="py-3 text-center text-muted-foreground">وجود ندارد!</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                                <h4 class="mb-3 text-xs font-bold text-emerald-600 flex items-center gap-2"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/></svg>پرسش‌و‌پاسخ ({{ count($qas) }} مورد)</h4>
                                <div class="overflow-x-auto -mx-1">
                                    <table class="w-full text-xs sm:text-sm border-collapse">
                                        <thead>
                                        <tr class="text-muted-foreground border-b border-border">
                                            <th class="text-right py-2 px-2 font-semibold">درس</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">پارت | دقیقه</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">تاریخ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($qas as $qa)
                                            <tr class="border-b border-dashed border-border last:border-b-0">
                                                <td class="py-2 px-2 font-medium">{{ $qa['subject'] }}</td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">{{ $qa['part_count'] }} پارت |  {{ $qa['time_per_part'] }}دقیقه </td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($qa['qa_date'],'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="py-3 text-center text-muted-foreground">وجود ندارد!</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                                <h4 class="mb-3 text-xs font-bold text-violet-600 flex items-center gap-2">
                                    <x-ui.icon name="list-check" class="w-4 h-4"/>تکالیف ({{ count($assignments) }} مورد)</h4>
                                <div class="overflow-x-auto -mx-1">
                                    <table class="w-full text-xs sm:text-sm border-collapse">
                                        <thead>
                                        <tr class="text-muted-foreground border-b border-border">
                                            <th class="text-right py-2 px-2 font-semibold">درس</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">پارت | دقیقه</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">تاریخ تحویل</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($assignments as $assignment)
                                            <tr class="border-b border-dashed border-border last:border-b-0">
                                                <td class="py-2 px-2 font-medium">{{ $assignment['subject'] }}</td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">{{ $assignment['part_count'] }} پارت | {{ $assignment['time_per_part'] }}دقیقه</td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($assignment['due_date'],'UTC')->setTimezone('Asia/Tehran'))->format('Y/m/d') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="py-3 text-center text-muted-foreground">وجود ندارد!</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="rounded-xl border border-border bg-muted/30 p-3 sm:p-4">
                                <h4 class="mb-3 text-xs font-bold text-orange-600 flex items-center gap-2"><x-ui.icon name="layers" class="w-4 h-4"/>پارت درخواستی ({{ count($requestedParts) }} مورد)</h4>
                                <div class="overflow-x-auto -mx-1">
                                    <table class="w-full text-xs sm:text-sm border-collapse">
                                        <thead>
                                        <tr class="text-muted-foreground border-b border-border">
                                            <th class="text-right py-2 px-2 font-semibold">درس</th>
                                            <th class="text-center py-2 px-2 font-semibold whitespace-nowrap">پارت | دقیقه</th>
                                            <th class="text-right py-2 px-2 font-semibold">توضیحات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($requestedParts as $rp)
                                            <tr class="border-b border-dashed border-border last:border-b-0">
                                                <td class="py-2 px-2 font-medium">{{ $rp['subject'] }}</td>
                                                <td class="py-2 px-2 text-center text-muted-foreground whitespace-nowrap">{{ $rp['part_count'] }} پارت | {{ $rp['time_per_part'] }}دقیقه </td>
                                                <td class="py-2 px-2 text-muted-foreground">{{ $rp['description'] ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="py-3 text-center text-muted-foreground">وجود ندارد!</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if($canEdit)
                            <div class="shrink-0 px-5 pb-5 pt-2">
                                <button wire:click="finalSubmit" wire:loading.attr="disabled" wire:target="finalSubmit" data-elevated="true"
                                        class="btn-press w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3.5 text-sm font-black text-white transition-all active:scale-[0.98]"
                                        style="background:linear-gradient(135deg,rgb(219 39 119),rgb(236 72 153));box-shadow:0 4px 14px rgb(236 72 153/.4);">
                                    <span wire:loading.remove wire:target="finalSubmit" class="flex items-center gap-2">ثبت نهایی پیش‌جلسه<x-ui.icon name="check" class="w-4 h-4"/></span>
                                    <x-ui.spinner size="sm" class="text-white" wire:loading wire:target="finalSubmit" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
