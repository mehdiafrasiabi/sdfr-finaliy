<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background:radial-gradient(ellipse 70% 50% at 50% 30%,rgba(34,197,94,0.08) 0%,transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-md text-center">
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="h-1 w-full" style="background:linear-gradient(to left,#22c55e,#3b82f6,#8b5cf6);"></div>
            <div class="p-8 space-y-5">
                {{-- آیکون موفقیت --}}
                <div class="relative mx-auto w-20 h-20">
                    <div class="absolute inset-0 rounded-full animate-ping" style="background:rgba(34,197,94,0.15);"></div>
                    <div class="relative w-20 h-20 rounded-full flex items-center justify-center" style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);">
                        <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <h1 class="text-2xl font-black text-white mb-2">ممنون از همراهی شما 🙏</h1>
                    <p class="text-sm text-white/55 leading-7">پاسخ‌های شما با موفقیت ثبت شد. این اطلاعات به مشاور تحصیلی فرزندتان منتقل می‌شود.</p>
                </div>

                @if($invitation && $invitation->user)
                    <div class="px-4 py-3 rounded-xl" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="text-xs text-white/40">{{ $invitation->parent_role_label }} گرامی <span class="text-white/60 font-semibold">{{ $invitation->user->name }}</span></p>
                        <p class="text-xs text-white/30 mt-1">به‌روزرسانی نهایی پس از مشاوره اطلاع داده می‌شود.</p>
                    </div>
                @endif

                <div class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-xs text-emerald-400 font-semibold">می‌توانید این صفحه را ببندید</span>
                </div>
            </div>
        </div>
    </div>
</div>
