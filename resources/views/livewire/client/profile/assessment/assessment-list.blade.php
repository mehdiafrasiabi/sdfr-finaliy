<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-x-hidden flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0" style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0" style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(59,130,246,0.10) 0%,transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-lg">

        @if(session()->has('info'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);">
                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="text-sm text-blue-300">{{ session('info') }}</p>
            </div>
        @endif

        @php $percent = $totalQuestions > 0 ? (int) round(($answeredTotal / $totalQuestions) * 100) : 0; @endphp

        @if($isAllDone && $showChoice)
            {{-- ─── انتخاب مسیر: هفته آزمایشی یا خرید ─── --}}
            <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);">
                <div class="h-1" style="background:linear-gradient(to left,#22c55e,#3b82f6,#8b5cf6);"></div>
                <div class="p-7 space-y-5">
                    <div class="text-center">
                        <h1 class="text-2xl font-black text-white mb-2">مسیرت را انتخاب کن 🚀</h1>
                        <p class="text-sm text-white/55 leading-7">کارنامه‌ات آماده شد؛ حالا یکی از دو مسیر زیر را برای ادامه انتخاب کن.</p>
                    </div>

                    @if (session()->has('error'))
                        <div class="px-4 py-3 rounded-xl text-sm text-red-300" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button wire:click="confirmTrial" wire:loading.attr="disabled" wire:target="confirmTrial"
                            class="w-full rounded-2xl p-5 text-right transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] disabled:opacity-60"
                            style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.3);">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 rounded-full px-2.5 py-1 mb-3" style="background:rgba(34,197,94,0.12);">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> رایگان
                        </div>
                        <h3 class="font-black text-lg text-white mb-1">شروع ۱ هفته آزمایشی</h3>
                        <p class="text-xs text-white/55 leading-6">
                            <span wire:loading.remove wire:target="confirmTrial">تجربه‌ی کامل امکانات بدون پرداخت ، با نظارت مشاور اختصاصی</span>
                            <span wire:loading wire:target="confirmTrial" class="inline-flex items-center gap-2">
                                <span class="inline-block w-3.5 h-3.5 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                                در حال آماده‌سازی…
                            </span>
                        </p>
                    </button>

                    <button wire:click="goToPurchase" wire:loading.attr="disabled"
                            class="w-full rounded-2xl p-5 text-right transition-all duration-200 hover:scale-[1.01] active:scale-[0.99]"
                            style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.3);">
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-300 rounded-full px-2.5 py-1 mb-3" style="background:rgba(59,130,246,0.12);">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            کامل
                        </div>
                        <h3 class="font-black text-lg text-white mb-1">خرید دوره</h3>
                        <p class="text-xs text-white/55 leading-6">دسترسی کامل به همه‌ی امکانات از همین امروز</p>
                    </button>

                    <button wire:click="$set('showChoice', false)"
                            class="w-full text-center text-xs text-white/40 hover:text-white/70 transition-colors py-1">
                        بازگشت به کارنامه‌ی تحلیلی
                    </button>
                </div>
            </div>

        @elseif($isAllDone)
            {{-- ─── کارنامه تحلیلی وضعیت ─── --}}
            <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);">
                <div class="h-1" style="background:linear-gradient(to left,#22c55e,#3b82f6,#8b5cf6);"></div>
                <div class="p-7 space-y-5">
                    <div class="text-center">
                        <div class="relative mx-auto w-16 h-16 mb-4">
                            <div class="absolute inset-0 rounded-full animate-ping" style="background:rgba(34,197,94,0.12);"></div>
                            <div class="relative w-16 h-16 rounded-full flex items-center justify-center" style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);">
                                <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <h1 class="text-2xl font-black text-white mb-2">تحلیل نتیجه  Mindet Test </h1>
                        <p class="text-sm text-white/55 leading-7">این خلاصه‌ی تحلیل روانشناسی - تحصیلی شماست  بر همین اساس برنامه‌ شما طراحی می‌شود.</p>
                    </div>

                    @if($summary)
                        {{-- VARK --}}
                        @if(!empty($summary['vark']['profile']))
                            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                                <div class="text-sm font-bold text-white mb-3">سبک یادگیری (VARK): <span class="text-blue-400">{{ $summary['vark']['profile'] }}</span></div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    @foreach($summary['vark']['modalities'] as $m)
                                        <div class="rounded-lg p-2 text-center"
                                             style="border:1px solid {{ $m['dominant'] ? 'rgba(59,130,246,0.5)' : 'rgba(255,255,255,0.08)' }};{{ $m['dominant'] ? 'background:rgba(59,130,246,0.08);' : '' }}">
                                            <div class="text-[10px] text-white/45">{{ $m['title'] }}</div>
                                            <div class="font-black text-sm text-white">{{ $m['percent'] }}%</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- تست‌های اختصاصی: تحلیل دقیق هر شاخص --}}
                        @foreach($summary['custom'] as $testName => $facets)
                            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                                <div class="text-sm font-bold text-white mb-3">{{ $testName }}</div>
                                <div class="space-y-3">
                                    @foreach($facets as $f)
                                        @php
                                            $lvl = $f['level'] ?? 'medium';
                                            $lvlColor = $lvl === 'high' ? '#22c55e' : ($lvl === 'low' ? '#f59e0b' : '#60a5fa');
                                            $lvlText  = $lvl === 'high' ? 'بالا' : ($lvl === 'low' ? 'پایین' : 'متوسط');
                                        @endphp
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-white/85">{{ $f['label'] }}</span>
                                                <span class="text-[10px] font-bold rounded-full px-2 py-0.5"
                                                      style="color:{{ $lvlColor }};background:{{ $lvlColor }}1a;">{{ $lvlText }} · {{ $f['percent'] }}%</span>
                                            </div>
                                            <div class="h-1.5 rounded-full overflow-hidden mb-1" style="background:rgba(255,255,255,0.06);">
                                                <div class="h-full rounded-full" style="width:{{ $f['percent'] }}%;background:{{ $lvlColor }};"></div>
                                            </div>
                                            @if(!empty($f['text']) && $f['text'] !== '—')
                                                <p class="text-[11px] text-white/55 leading-5">{{ $f['text'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- پرچم‌ها --}}
                        @foreach($summary['flags'] as $flag)
                            <div class="rounded-xl p-3.5"
                                 style="border:1px solid {{ ($flag['severity'] ?? '') === 'critical' ? 'rgba(239,68,68,0.4)' : 'rgba(245,158,11,0.4)' }};background:{{ ($flag['severity'] ?? '') === 'critical' ? 'rgba(239,68,68,0.08)' : 'rgba(245,158,11,0.08)' }};">
                                <div class="text-xs font-bold text-white">{{ $flag['title'] ?? '' }}</div>
                                @if(!empty($flag['text']))
                                    <p class="text-[11px] text-white/55 leading-5 mt-1">{{ $flag['text'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-xl p-4 text-center text-xs text-white/45 leading-6" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                            تحلیلی برای نمایش وجود ندارد.
                        </div>
                    @endif
                    <button wire:click="continueToChoice"
                            class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]"
                            style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 4px 20px rgba(34,197,94,0.3);">
                        ادامه می‌دهیم
                    </button>
                </div>
            </div>

        @else
            {{-- ─── خوش آمدی ─── --}}
            <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);">
                <div class="h-1" style="background:linear-gradient(to left,#3b82f6,#8b5cf6,#ec4899);"></div>
                <div class="p-7 space-y-6">


                    {{-- عنوان --}}
                    <div class="text-center">
                        @if($hasStarted)
                            <h1 class="text-2xl font-black text-white mb-2">خوش برگشتی! 👋</h1>
                        @else
                            <h1 class="text-2xl font-black text-white mb-2">شروع تست مایندت</h1>
                            <p class="text-sm text-white/55 leading-7">این پلتفرم هوشمند تمامی خدمات آموزشی را متناسب با نیاز های تحصیلی - روانشناسی شما ارائه می دهد</p>
                        @endif
                    </div>

                    {{-- progress --}}
                    <div class="rounded-xl p-4 space-y-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-white/40">پیشرفت کلی</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-white/50">{{ $answeredTotal }} / {{ $totalQuestions }}</span>
                                <span class="text-xs font-black text-blue-400">{{ $percent }}%</span>
                            </div>
                        </div>
                        <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-700"
                                 style="background:linear-gradient(to left,#3b82f6,#8b5cf6);width:{{ $percent }}%;"></div>
                        </div>
                    </div>

                    <button wire:click="start"
                            wire:loading.attr="disabled"
                            class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60"
                            style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
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
