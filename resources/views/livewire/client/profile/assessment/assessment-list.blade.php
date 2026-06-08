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

        @if($isAllDone)
            {{-- ─── تمام شد ─── --}}
            <div class="rounded-2xl overflow-hidden text-center" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);">
                <div class="h-1" style="background:linear-gradient(to left,#22c55e,#3b82f6,#8b5cf6);"></div>
                <div class="p-8 space-y-5">
                    <div class="relative mx-auto w-20 h-20">
                        <div class="absolute inset-0 rounded-full animate-ping" style="background:rgba(34,197,94,0.12);"></div>
                        <div class="relative w-20 h-20 rounded-full flex items-center justify-center" style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);">
                            <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-white mb-2">از تو ممنونیم! 🌱</h1>
                        <p class="text-sm text-white/55 leading-7">هر دو مرحله‌ی آزمون‌ها با موفقیت تکمیل شد. نتایج ثبت شد و حالا می‌توانیم ادامه‌ی مسیر هفته‌ی آزمایشی را شروع کنیم.</p>
                    </div>
                    <div class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-xs text-emerald-400 font-semibold">{{ $answeredTotal }} سوال پاسخ داده شد</span>
                    </div>
                    <button wire:click="continueToGuide"
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
