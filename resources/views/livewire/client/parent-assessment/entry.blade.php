<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image: linear-gradient(to right, rgba(59,130,246,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59,130,246,0.05) 1px, transparent 1px); background-size: 48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(59,130,246,0.1) 0%, transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-lg">
        @if($expired)
            <div class="text-center space-y-4 p-8 rounded-2xl" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);">
                <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center" style="background:rgba(239,68,68,0.15);">
                    <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/></svg>
                </div>
                <h1 class="text-xl font-black">لینک منقضی شده است</h1>
                <p class="text-sm text-white/50 leading-7">این لینک دیگر معتبر نیست. لطفاً با همکاران ما تماس بگیرید.</p>
                <p class="text-xs text-white/25">کد ارجاع: {{ \Illuminate\Support\Str::limit($token, 8, '...') }}</p>
            </div>
        @else
            <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                <div class="h-1 w-full" style="background:linear-gradient(to left,#3b82f6,#8b5cf6,#ec4899);"></div>
                <div class="p-7 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-black text-white">{{ $invitation->parent_role_label }} گرامی،</h1>
                            <p class="text-xs text-white/40 mt-1">فرزند شما: <span class="text-blue-400 font-semibold">{{ $invitation->user->name }}</span></p>
                        </div>
                    </div>
                    <p class="text-sm text-white/60 leading-7">فرزند شما تست‌های روان‌شناختی را تکمیل کرده است. برای طراحی برنامه‌ی متناسب‌تر، دیدگاه شما نیز ارزشمند است.</p>
                    <div class="rounded-xl p-4 space-y-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="text-xs font-bold text-white/70">چه چیزی می‌پرسیم؟</p>
                        @foreach(['دیدگاه شما درباره‌ی ارتباط فرزندتان با درس و نمره','دیدگاه شما درباره‌ی دوستان و محیط اجتماعی'] as $item)
                            <div class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.2);">
                                    <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                                <span class="text-xs text-white/55">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);">
                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-white/55">تکمیل این تست‌ها حدود <span class="text-blue-400 font-bold">۵ تا ۸ دقیقه</span> زمان می‌برد.</p>
                    </div>
                    <button wire:click="proceed" class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]"
                            style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                        شروع تست‌ها
                    </button>
                    <p class="text-[11px] text-white/25 text-center">اعتبار این لینک تا {{ optional($invitation->expires_at)->format('Y/m/d') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
