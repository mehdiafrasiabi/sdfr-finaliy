<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0" style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0" style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(59,130,246,0.10) 0%,transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-lg">
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.08);">
            <div class="h-1" style="background:linear-gradient(to left,#3b82f6,#8b5cf6,#ec4899);"></div>
            <div class="p-7 space-y-5">

                {{-- آیکون + عنوان --}}
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                         style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                        <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        @if($status['is_first_entry'])
                            <h1 class="text-xl font-black text-white">خوش اومدی{{ $userName ? '، ' . $userName : '' }} 👋</h1>
                            <p class="text-xs text-white/40 mt-1">مرحله‌ی {{ $title }}</p>
                        @elseif($status['percent'] >= 100)
                            <h1 class="text-xl font-black text-white">آفرین! 🎉</h1>
                            <p class="text-xs text-white/40 mt-1">مرحله‌ی {{ $title }}</p>
                        @else
                            <h1 class="text-xl font-black text-white">خوش برگشتی{{ $userName ? '، ' . $userName : '' }} 👋</h1>
                            <p class="text-xs text-white/40 mt-1">مرحله‌ی {{ $title }}</p>
                        @endif
                    </div>
                </div>

                {{-- توضیح --}}
                <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                    @if($status['is_first_entry'])
                        <p class="text-sm text-white/60 leading-7">{{ $description }}</p>
                    @elseif($status['percent'] >= 100)
                        <p class="text-sm text-white/60 leading-7">مرحله‌ی قبلی با موفقیت تمام شد. حالا بریم سراغ <span class="text-white font-bold">{{ $title }}</span>.</p>
                        <p class="text-sm text-white/50 leading-7 mt-2">{{ $description }}</p>
                    @else
                        <p class="text-sm text-white/60 leading-7">مرحله‌ی <span class="text-white font-bold">{{ $title }}</span> هنوز ادامه دارد. تا الان به <span class="text-blue-400 font-bold">{{ $status['answered'] }}</span> سوال از <span class="text-white font-bold">{{ $status['total_questions'] }}</span> سوال این مرحله پاسخ دادی.</p>
                    @endif
                </div>

                {{-- progress --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-white/40">پیشرفت این مرحله</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-white/40">{{ $status['answered'] }} / {{ $status['total_questions'] }}</span>
                            <span class="text-xs font-black text-blue-400">{{ $status['percent'] }}%</span>
                        </div>
                    </div>
                    <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                        <div class="h-full rounded-full transition-all duration-700"
                             style="background:linear-gradient(to left,#3b82f6,#8b5cf6);width:{{ $status['percent'] }}%;"></div>
                    </div>
                </div>

                <button wire:click="proceed"
                        wire:loading.attr="disabled"
                        class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60"
                        style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                    {{ $status['is_first_entry'] ? 'شروع پاسخ‌گویی' : 'ادامه‌ی پاسخ‌گویی' }}
                </button>

                <p class="text-[11px] text-white/25 text-center">پاسخ‌ها خودکار ذخیره می‌شوند. هر وقت بخواهی می‌توانی ادامه دهی.</p>
            </div>
        </div>
    </div>
</div>
