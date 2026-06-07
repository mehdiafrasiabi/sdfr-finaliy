<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden flex items-center justify-center px-4 py-10" dir="rtl">

    {{-- grid background --}}
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image: linear-gradient(to right, rgba(59,130,246,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59,130,246,0.05) 1px, transparent 1px);
                background-size: 48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(59,130,246,0.1) 0%, transparent 70%);"></div>
    <div class="fixed top-0 right-0 w-[500px] h-[500px] pointer-events-none z-0"
         style="background: radial-gradient(circle, rgba(139,92,246,0.07), transparent 70%); transform: translate(40%,-40%);"></div>

    <div class="relative z-10 w-full max-w-lg">

        @if($expired)
            <div class="text-center space-y-4 p-8 rounded-2xl" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
                <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center" style="background: rgba(239,68,68,0.15);">
                    <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-black text-white">لینک منقضی شده است</h1>
                <p class="text-sm text-white/50 leading-7">این لینک دیگر معتبر نیست. لطفاً با همکاران ما تماس بگیرید.</p>
            </div>
        @else
            {{-- کارت اصلی --}}
            <div class="rounded-2xl overflow-hidden" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

                {{-- نوار رنگی بالا --}}
                <div class="h-1 w-full" style="background: linear-gradient(to left, #3b82f6, #8b5cf6, #ec4899);"></div>

                <div class="p-7">
                    {{-- آیکون --}}
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                             style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3);">
                            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <div>
                            @if($isFirstEntry)
                                <h1 class="text-xl font-black text-white leading-tight">{{ $invitation->parent_role_label }} گرامی، خوش آمدید 👋</h1>
                                <p class="text-xs text-white/40 mt-1">فرزند شما: <span class="text-blue-400 font-semibold">{{ $invitation->user->name }}</span></p>
                            @else
                                <h1 class="text-xl font-black text-white leading-tight">خوش برگشتید 👋</h1>
                                <p class="text-xs text-white/40 mt-1">ادامه‌ی پاسخ‌دهی</p>
                            @endif
                        </div>
                    </div>

                    @if($isFirstEntry)
                        <p class="text-sm text-white/60 leading-7 mb-4">
                            فرزند شما تست‌های روان‌شناختی را تکمیل کرده است. دیدگاه شما به‌عنوان والد برای طراحی برنامه‌ی دقیق‌تر ارزشمند است.
                        </p>
                        <div class="rounded-xl p-4 mb-5 space-y-2.5" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                            @foreach(['دیدگاه شما درباره‌ی ارتباط فرزندتان با درس','دیدگاه شما درباره‌ی محیط اجتماعی فرزندتان'] as $item)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0" style="background: rgba(59,130,246,0.2);">
                                        <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-white/60">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-white/60 leading-7 mb-4">
                            تا الان به <span class="text-white font-bold">{{ $answered }}</span> سوال از <span class="text-white font-bold">{{ $totalQuestions }}</span> سوال پاسخ داده‌اید. از همانجا ادامه خواهید داد.
                        </p>
                    @endif

                    {{-- progress --}}
                    <div class="mb-6 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-white/40">پیشرفت شما</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-white/60">{{ $answered }} / {{ $totalQuestions }}</span>
                                <span class="text-xs font-black text-blue-400">{{ $percent }}%</span>
                            </div>
                        </div>
                        <div class="h-2 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full transition-all duration-700"
                                 style="background: linear-gradient(to left, #3b82f6, #8b5cf6); width: {{ $percent }}%;"></div>
                        </div>
                    </div>

                    <button wire:click="proceed"
                            class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]"
                            style="background: linear-gradient(135deg, #3b82f6, #8b5cf6); box-shadow: 0 4px 20px rgba(59,130,246,0.3);">
                        @if($isFirstEntry) شروع تست‌ها @else ادامه‌ی تست‌ها @endif
                    </button>

                    <p class="text-[11px] text-white/25 mt-4 text-center">
                        پاسخ‌ها به‌صورت خودکار ذخیره می‌شوند.
                        @if($invitation->expires_at) اعتبار تا {{ $invitation->expires_at->format('Y/m/d') }} @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
