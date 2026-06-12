<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image: linear-gradient(to right, rgba(59,130,246,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59,130,246,0.05) 1px, transparent 1px); background-size: 48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(59,130,246,0.1) 0%, transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-md">
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="h-1 w-full" style="background:linear-gradient(to left,#3b82f6,#8b5cf6,#ec4899);"></div>
            <div class="p-7 space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                        <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white">پنل والدین</h1>
                        <p class="text-xs text-white/40 mt-1">مشاهده وضعیت تحصیلی فرزند شما</p>
                    </div>
                </div>

                <p class="text-sm text-white/60 leading-7">
                    برای ورود، <span class="text-blue-400 font-semibold">کد ملی دانش‌آموز</span> و
                    <span class="text-blue-400 font-semibold">شماره موبایل پدر یا مادر</span>
                    (همان شماره‌ای که هنگام ثبت‌نام اعلام شده) را وارد کنید.
                </p>

                <form wire:submit.prevent="login" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-white/70 mb-2">کد ملی دانش‌آموز</label>
                        <input type="text" wire:model="nationalCode" inputmode="numeric" maxlength="10" dir="ltr"
                               placeholder="0012345678"
                               class="w-full h-12 rounded-xl px-4 text-sm text-white placeholder-white/25 text-left outline-none focus:ring-2 focus:ring-blue-500/50 transition"
                               style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);">
                        @error('nationalCode')
                        <p class="text-xs text-red-400 mt-2 leading-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/70 mb-2">شماره موبایل پدر یا مادر</label>
                        <input type="text" wire:model="mobile" inputmode="numeric" maxlength="11" dir="ltr"
                               placeholder="09123456789"
                               class="w-full h-12 rounded-xl px-4 text-sm text-white placeholder-white/25 text-left outline-none focus:ring-2 focus:ring-blue-500/50 transition"
                               style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);">
                        @error('mobile')
                        <p class="text-xs text-red-400 mt-2 leading-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60"
                            style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                        <span wire:loading.remove wire:target="login">ورود به پنل والدین</span>
                        <span wire:loading wire:target="login">در حال بررسی...</span>
                    </button>
                </form>

                <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-white/55 leading-6">اطلاعات نمایش‌داده‌شده مطابق آخرین جلسه مشاوره برگزارشده برای فرزند شماست.</p>
                </div>
            </div>
        </div>
    </div>
</div>
