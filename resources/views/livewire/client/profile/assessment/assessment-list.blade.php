<div class="min-h-screen bg-background text-foreground relative overflow-x-hidden flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0" style="background:radial-gradient(ellipse 70% 45% at 50% 0%, hsl(var(--primary) / .08) 0%, transparent 70%);"></div>
   @push('link')
        <style>
            .choice-card {
                position: relative;
                min-height: 8.5rem;
                overflow: hidden;
                isolation: isolate;
                background:
                    radial-gradient(circle at 12% 0%, hsl(var(--primary) / .14), transparent 16rem),
                    linear-gradient(180deg, hsl(var(--secondary) / .82), hsl(var(--secondary) / .56));
                box-shadow:
                    inset 0 1px 0 hsl(var(--foreground) / .05),
                    0 18px 40px hsl(var(--background) / .28);
            }

            .choice-card::before {
                content: "";
                position: absolute;
                inset: 1px;
                border-radius: calc(1rem - 1px);
                pointer-events: none;
                background: linear-gradient(135deg, hsl(var(--primary) / .10), transparent 42%);
                opacity: .9;
            }

            .choice-card > * {
                position: relative;
                z-index: 1;
            }

            .choice-card:hover,
            .choice-card:focus-visible {
                border-color: hsl(var(--primary) / .5);
                box-shadow:
                    inset 0 1px 0 hsl(var(--foreground) / .07),
                    0 20px 44px hsl(var(--background) / .34);
            }

            .choice-card:focus-visible {
                outline: 2px solid hsl(var(--primary) / .75);
                outline-offset: 3px;
            }
        </style>
   @endpush

    <div class="relative z-10 w-full {{ ($isAllDone && !$showChoice) ? 'max-w-4xl' : 'max-w-lg' }}">

        @if(session()->has('info'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5 bg-primary/10 border border-primary/25">
                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-sm text-primary">{{ session('info') }}</p>
            </div>
        @endif

        @php $percent = $totalQuestions > 0 ? (int) round(($answeredTotal / $totalQuestions) * 100) : 0; @endphp

        @if($isAllDone && $showChoice)
            {{-- ─── انتخاب مسیر: هفته آزمایشی یا خرید ─── --}}
            <div wire:key="assessment-choice-panel" class="rounded-2xl overflow-hidden border border-border glass">
                <div class="h-1 bg-primary"></div>
                <div class="p-6 sm:p-7 space-y-5">
                    <div class="text-center">
                        <h1 class="text-2xl font-black mb-2">مسیرت را انتخاب کن</h1>
                        <p class="text-sm text-muted leading-7">کارنامه‌ات آماده شد؛ حالا یکی از دو مسیر زیر را برای ادامه انتخاب کن.</p>
                    </div>

                    @if (session()->has('error'))
                        <div class="px-4 py-3 rounded-xl text-sm text-rose-500 bg-rose-500/10 border border-rose-500/25">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button type="button" wire:click="confirmTrial" wire:loading.attr="disabled" wire:target="confirmTrial"
                            class="choice-card w-full rounded-2xl border border-border p-5 text-right transition-all duration-200 hover:-translate-y-0.5 active:scale-[0.99] disabled:opacity-60">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary rounded-full px-2.5 py-1 mb-3 bg-primary/12">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span> رایگان
                        </div>
                        <h3 class="font-black text-lg mb-1">{{ $trialChoiceCopy['title'] }}</h3>
                        <p class="text-xs text-muted leading-6">
                            <span wire:loading.remove wire:target="confirmTrial">{{ $trialChoiceCopy['description'] }}</span>
                            <span wire:loading wire:target="confirmTrial" class="inline-flex items-center gap-2">
                                <span class="inline-block w-3.5 h-3.5 rounded-full border-2 border-current bg-secondary border-t-current animate-spin"></span>
                                در حال آماده‌سازی…
                            </span>
                        </p>
                        <span class="mt-3 inline-flex text-[11px] font-black text-primary">{{ $trialChoiceCopy['cta'] }}</span>
                    </button>

                    <button type="button" wire:click="goToPurchase" wire:loading.attr="disabled"
                            class="choice-card w-full rounded-2xl border border-primary/25 p-5 text-right transition-all duration-200 hover:-translate-y-0.5 active:scale-[0.99] disabled:opacity-60">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary rounded-full px-2.5 py-1 mb-3 bg-primary/12">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            کامل
                        </div>
                        <h3 class="font-black text-lg mb-1">خرید دوره</h3>
                        <p class="text-xs text-muted leading-6">دسترسی کامل به همه‌ی امکانات از همین امروز</p>
                    </button>

                    <button type="button" wire:click="$set('showChoice', false)"
                            class="w-full text-center text-xs text-muted hover:text-foreground transition-colors py-1">
                        بازگشت به کارنامه‌ی تحلیلی
                    </button>
                </div>
            </div>

        @elseif($isAllDone)
            {{-- ─── کارنامه تحلیلی وضعیت (واکنش‌گرا: یک‌ستونه موبایل، شبکه‌ای دسکتاپ) ─── --}}
            <div wire:key="assessment-summary-panel" class="rounded-2xl overflow-hidden border border-border glass" x-data="{ showDetails: false }">
                <div class="h-1 bg-primary"></div>
                <div class="p-6 sm:p-8">
                    <div class="text-center mb-7">
                        <div class="relative mx-auto w-16 h-16 mb-4">
                            <div class="absolute inset-0 rounded-full animate-ping bg-emerald-500/12"></div>
                            <div class="relative w-16 h-16 rounded-full flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30">
                                <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black mb-2">کارنامه‌ی تحلیلی تو آماده شد</h1>
                        <p class="text-sm text-muted leading-7 max-w-xl mx-auto">این خلاصه‌ی تحلیل روان‌شناختی - تحصیلی توست؛ برنامه‌ات بر همین اساس طراحی می‌شود.</p>
                    </div>

                    @if($summary)
                        <div class="grid gap-5 md:grid-cols-2">

                            {{-- ───── VARK + نمودار رادار ───── --}}
                            @if(!empty($summary['vark']['profile']))
                                @php
                                    $mods = array_values($summary['vark']['modalities']);
                                    $N = max(1, count($mods));
                                    $cx = 130; $cy = 130; $RR = 88;
                                    $poly = [];
                                    $axisPts = [];
                                    $lbls = [];
                                    foreach ($mods as $i => $m) {
                                        $ang = -M_PI / 2 + $i * 2 * M_PI / $N;
                                        $p   = max(0, min(100, (int) ($m['percent'] ?? 0)));
                                        $r   = $RR * $p / 100;
                                        $poly[]    = round($cx + $r * cos($ang), 1) . ',' . round($cy + $r * sin($ang), 1);
                                        $axisPts[] = [round($cx + $RR * cos($ang), 1), round($cy + $RR * sin($ang), 1)];
                                        $lbls[]    = [
                                            'x' => round($cx + ($RR + 20) * cos($ang), 1),
                                            'y' => round($cy + ($RR + 20) * sin($ang), 1),
                                            't' => $m['title'] ?? '',
                                            'p' => $p,
                                        ];
                                    }
                                    $polyStr = implode(' ', $poly);
                                @endphp
                                <div class="rounded-xl p-4 bg-background border border-border md:col-span-2">
                                    <div class="text-sm font-bold mb-3">سبک یادگیری (VARK): <span class="text-primary">{{ $summary['vark']['profile'] }}</span></div>
                                    <div class="grid gap-4 sm:grid-cols-2 items-center">
                                        {{-- نمودار رادار (SVG خالص) --}}
                                        <div class="flex justify-center">
                                            <svg viewBox="0 0 260 260" class="w-full max-w-[240px]" aria-hidden="true">
                                                @foreach([22, 44, 66, 88] as $ring)
                                                    <circle cx="130" cy="130" r="{{ $ring }}" fill="none" stroke="hsl(var(--border))" stroke-width="1" opacity="0.7"/>
                                                @endforeach
                                                @foreach($axisPts as $ap)
                                                    <line x1="130" y1="130" x2="{{ $ap[0] }}" y2="{{ $ap[1] }}" stroke="hsl(var(--border))" stroke-width="1" opacity="0.7"/>
                                                @endforeach
                                                <polygon points="{{ $polyStr }}" fill="hsl(var(--primary) / .25)" stroke="hsl(var(--primary))" stroke-width="2" stroke-linejoin="round"/>
                                                @foreach(explode(' ', $polyStr) as $pt)
                                                    @php [$px, $py] = explode(',', $pt); @endphp
                                                    <circle cx="{{ $px }}" cy="{{ $py }}" r="3.5" fill="hsl(var(--primary))"/>
                                                @endforeach
                                                @foreach($lbls as $lb)
                                                    <text x="{{ $lb['x'] }}" y="{{ $lb['y'] }}" text-anchor="middle" dominant-baseline="middle"
                                                          font-size="11" font-weight="700" fill="hsl(var(--foreground))">{{ $lb['t'] }}</text>
                                                    <text x="{{ $lb['x'] }}" y="{{ $lb['y'] + 13 }}" text-anchor="middle" dominant-baseline="middle"
                                                          font-size="10" font-weight="800" fill="hsl(var(--primary))">{{ $lb['p'] }}%</text>
                                                @endforeach
                                            </svg>
                                        </div>
                                        {{-- میله‌های درصد --}}
                                        <div class="space-y-2.5">
                                            @foreach($mods as $m)
                                                <div>
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-xs font-bold {{ ($m['dominant'] ?? false) ? 'text-primary' : '' }}">{{ $m['title'] }}</span>
                                                        <span class="text-[11px] font-black {{ ($m['dominant'] ?? false) ? 'text-primary' : 'text-muted' }}">{{ $m['percent'] }}%</span>
                                                    </div>
                                                    <div class="h-2 rounded-full overflow-hidden bg-border/60">
                                                        <div class="h-full rounded-full bg-primary {{ ($m['dominant'] ?? false) ? '' : 'opacity-60' }}" style="width:{{ $m['percent'] }}%;"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ───── انتخاب: نمایش جزییات یا ادامه ───── --}}
                            <div x-show="!showDetails" class="md:col-span-2 flex gap-3 pt-1">
                                <button type="button" @click="showDetails = true"
                                        class="flex-1 h-12 px-2 rounded-xl font-bold text-xs sm:text-sm text-foreground bg-secondary border border-border transition-all duration-200 hover:bg-background active:scale-[0.98] inline-flex items-center justify-center gap-1.5 sm:gap-2">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span class="whitespace-nowrap">نمایش جزییات</span>
                                </button>
                                <button wire:click="continueToChoice" wire:loading.attr="disabled" wire:target="continueToChoice"
                                        class="flex-1 h-12 px-2 rounded-xl font-bold text-xs sm:text-sm text-primary-foreground bg-primary transition-all duration-200 hover:opacity-90 active:scale-[0.98] disabled:opacity-60 inline-flex items-center justify-center gap-1.5 sm:gap-2">
                                    <span wire:loading.remove wire:target="continueToChoice" class="inline-flex items-center justify-center gap-1.5 sm:gap-2">
                                        <span class="whitespace-nowrap">ادامه میدهم</span>
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                                    </span>
                                    <span wire:loading wire:target="continueToChoice" class="inline-flex items-center gap-2">
                                        <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                                        لطفا منتظر بمانید...
                                    </span>
                                </button>
                            </div>

                            {{-- ───── تست‌های اختصاصی: تحلیل دقیق هر شاخص (فقط با «نمایش جزییات») ───── --}}
                            <div x-show="showDetails" x-cloak class="contents">
                                @foreach($summary['custom'] as $testName => $facets)
                                    <div class="rounded-xl p-4 bg-background border border-border">
                                        <div class="text-sm font-bold mb-3">{{ $testName }}</div>
                                        <div class="space-y-3">
                                            @foreach($facets as $f)
                                                @php
                                                    $lvl = $f['level'] ?? 'medium';
                                                    $lvlColor = $lvl === 'high' ? '#22c55e' : ($lvl === 'low' ? '#f59e0b' : '#3b82f6');
                                                    $lvlText  = $lvl === 'high' ? 'بالا' : ($lvl === 'low' ? 'پایین' : 'متوسط');
                                                @endphp
                                                <div>
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-xs font-bold">{{ $f['label'] }}</span>
                                                        <span class="text-[10px] font-bold rounded-full px-2 py-0.5"
                                                              style="color:{{ $lvlColor }};background:{{ $lvlColor }}1a;">{{ $lvlText }} · {{ $f['percent'] }}%</span>
                                                    </div>
                                                    <div class="h-1.5 rounded-full overflow-hidden mb-1 bg-border/60">
                                                        <div class="h-full rounded-full" style="width:{{ $f['percent'] }}%;background:{{ $lvlColor }};"></div>
                                                    </div>
                                                    @if(!empty($f['text']) && $f['text'] !== '—')
                                                        <p class="text-[11px] text-muted leading-5">{{ $f['text'] }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl p-4 text-center text-xs text-muted leading-6 bg-background/40 border border-border">
                            تحلیلی برای نمایش وجود ندارد.
                        </div>
                    @endif

                    <button x-show="showDetails" x-cloak wire:click="continueToChoice" wire:loading.attr="disabled" wire:target="continueToChoice"
                            class="w-full md:w-auto md:min-w-[260px] md:mx-auto md:flex h-12 mt-6 rounded-xl font-bold text-sm text-primary-foreground bg-primary transition-all duration-200 hover:opacity-90 active:scale-[0.98] disabled:opacity-60 items-center justify-center">
                        <span wire:loading.remove wire:target="continueToChoice">ادامه میدهم</span>
                        <span wire:loading wire:target="continueToChoice" class="inline-flex items-center gap-2">
                            <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                            لطفا منتظر بمانید...
                        </span>
                    </button>
                </div>
            </div>

        @else
            {{-- ─── خوش آمدی / شروع ─── --}}
            <div wire:key="assessment-start-panel" class="rounded-2xl overflow-hidden border border-border bg-secondary">
                <div class="h-1 bg-primary"></div>
                <div class="p-6 sm:p-7 space-y-6">

                    <div class="text-center">
                        @if($hasStarted)
                            <h1 class="text-2xl font-black mb-2">خوش برگشتی! 👋</h1>
                            <p class="text-sm text-muted leading-7">از همان‌جایی که رها کردی ادامه می‌دهیم.</p>
                        @else
                            <h1 class="text-2xl font-black mb-2">آنالیز تحصیلی-روانشناسی</h1>
                            <p class="text-sm text-muted leading-7">این پلتفرم هوشمند، خدمات آموزشی را متناسب با نیازهای تحصیلی - روان‌شناختی تو ارائه می‌دهد.</p>
                        @endif
                    </div>

                    {{-- پیشرفت کلی روی همهٔ آزمون‌ها --}}
                    <div class="rounded-xl p-4 space-y-2.5 bg-background/40 border border-border">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-muted">پیشرفت کلی</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-muted">{{ $answeredTotal }} / {{ $totalQuestions }}</span>
                                <span class="text-xs font-black text-primary">{{ $percent }}%</span>
                            </div>
                        </div>
                        <div class="h-2 rounded-full overflow-hidden bg-border/60">
                            <div class="h-full rounded-full bg-primary transition-all duration-700" style="width:{{ $percent }}%;"></div>
                        </div>
                    </div>

                    <button wire:click="start"
                            wire:loading.attr="disabled" wire:target="start"
                            class="w-full h-12 rounded-xl font-bold text-sm text-primary-foreground bg-primary transition-all duration-200 hover:opacity-90 active:scale-[0.98] disabled:opacity-60">
                        <span wire:loading.remove wire:target="start">{{ $hasStarted ? 'ادامه‌ی آزمون' : 'شروع آزمون' }}</span>
                        <span wire:loading wire:target="start" class="inline-flex items-center gap-2">
                            <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                            لحظه‌ای...
                        </span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
