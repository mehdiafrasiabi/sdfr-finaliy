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
        @if(session()->has('success'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);">
                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <p class="text-sm text-emerald-300">{{ session('success') }}</p>
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
                            <span wire:loading.remove wire:target="confirmTrial">تجربه‌ی کامل امکانات بدون پرداخت — مشاور جذب اختصاصی برایت انتخاب می‌شود</span>
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
                        <h1 class="text-2xl font-black text-white mb-2">کارنامه‌ی تحلیلی تو 🌱</h1>
                        <p class="text-sm text-white/55 leading-7">همه‌ی آزمون‌ها تکمیل شد. این خلاصه‌ی وضعیت توست — بر همین اساس برنامه‌ات طراحی می‌شود.</p>
                    </div>

                    @if($summary)
                        {{-- MBTI --}}
                        @if(!empty($summary['mbti']['type']))
                            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                                <div class="flex items-center gap-2.5 mb-2 flex-wrap">
                                    <span class="text-base font-black text-blue-400 tracking-widest">{{ $summary['mbti']['type'] }}</span>
                                    <span class="text-sm font-bold text-white">{{ $summary['mbti']['title'] }}</span>
                                    <span class="text-[10px] text-white/40 rounded-full px-2 py-0.5" style="background:rgba(255,255,255,0.06);">تیپ شخصیتی</span>
                                </div>
                                <p class="text-xs text-white/55 leading-6">{{ $summary['mbti']['description'] }}</p>
                                @if(!empty($summary['mbti']['study_tip']) && $summary['mbti']['study_tip'] !== '—')
                                    <div class="mt-2.5 text-[11px] text-emerald-300 rounded-lg px-3 py-2 leading-5" style="background:rgba(34,197,94,0.08);">
                                        💡 {{ $summary['mbti']['study_tip'] }}
                                    </div>
                                @endif
                            </div>
                        @endif

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

                        {{-- تست‌های اختصاصی: نقاط قوت / نیازمند تقویت --}}
                        @foreach($summary['custom'] as $testName => $facets)
                            @php
                                $strengths  = collect($facets)->filter(fn($f) => $f['level'] === 'high');
                                $weaknesses = collect($facets)->filter(fn($f) => $f['level'] === 'low');
                            @endphp
                            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                                <div class="text-sm font-bold text-white mb-3">{{ $testName }}</div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-[11px] font-bold text-emerald-400 mb-2">نقاط قوت</div>
                                        @forelse($strengths as $f)
                                            <div class="text-xs text-white/80 mb-1.5">✅ {{ $f['label'] }} <span class="text-white/35">({{ $f['percent'] }}%)</span></div>
                                        @empty
                                            <div class="text-[11px] text-white/30">—</div>
                                        @endforelse
                                    </div>
                                    <div>
                                        <div class="text-[11px] font-bold text-amber-400 mb-2">نیازمند تقویت</div>
                                        @forelse($weaknesses as $f)
                                            <div class="text-xs text-white/80 mb-1.5">⚠️ {{ $f['label'] }} <span class="text-white/35">({{ $f['percent'] }}%)</span></div>
                                        @empty
                                            <div class="text-[11px] text-white/30">—</div>
                                        @endforelse
                                    </div>
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

                    <div class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-xs text-emerald-400 font-semibold">{{ $answeredTotal }} سوال پاسخ داده شد</span>
                    </div>

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

                    {{-- نشانگر مراحل --}}
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center gap-2">
                            <span class="flex w-8 h-8 items-center justify-center rounded-full text-xs font-black"
                                  style="{{ $currentStage >= 1 ? 'background:rgba(59,130,246,0.8);color:white;' : 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.1);' }}">۱</span>
                            <span class="text-xs font-bold {{ $currentStage == 1 ? 'text-blue-400' : 'text-white/30' }}">MBTI</span>
                        </div>
                        <div class="h-px w-8" style="background:rgba(255,255,255,0.1);"></div>
                        <div class="flex items-center gap-2">
                            <span class="flex w-8 h-8 items-center justify-center rounded-full text-xs font-black"
                                  style="{{ $currentStage >= 2 ? 'background:rgba(59,130,246,0.8);color:white;' : 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.1);' }}">۲</span>
                            <span class="text-xs font-bold {{ $currentStage == 2 ? 'text-blue-400' : 'text-white/30' }}">مایندست</span>
                        </div>
                    </div>

                    {{-- بج مرحله --}}
                    <div class="flex justify-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full"
                             style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></div>
                            <span class="text-xs font-bold text-blue-300">مرحله‌ی {{ $currentStage == 1 ? 'یک — MBTI' : 'دو — مایندست' }}</span>
                        </div>
                    </div>

                    {{-- عنوان --}}
                    <div class="text-center">
                        @if($hasStarted)
                            <h1 class="text-2xl font-black text-white mb-2">خوش برگشتی! 👋</h1>
                            <p class="text-sm text-white/55 leading-7">از همان‌جایی که رها کردی ادامه می‌دهیم؛ مستقیم می‌روی سراغ سوال بعدی.</p>
                        @else
                            <h1 class="text-2xl font-black text-white mb-2">خوش اومدی! 👋</h1>
                            <p class="text-sm text-white/55 leading-7">برای طراحی برنامه‌ی اختصاصی‌ات ابتدا آزمون شخصیت‌شناسی را در دو مرحله انجام می‌دهیم.</p>
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
