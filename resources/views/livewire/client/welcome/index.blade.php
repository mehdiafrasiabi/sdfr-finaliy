<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-900 py-12 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-3">
                به سامانه خوش آمدید
            </h1>
            <p class="text-slate-600 dark:text-slate-300 text-base">
                برای ادامه، یکی از مسیرهای زیر را انتخاب کنید.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            {{-- شروع آزمایشی --}}
            <div class="group rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm hover:shadow-lg transition-all">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">شروع هفته آزمایشی</h2>
                <p class="text-slate-600 dark:text-slate-300 text-sm leading-7 mb-6">
                    یک هفته رایگان از خدمات مشاوره ما استفاده کنید. تخصیص پشتیبان، طبقه‌بندی و برنامه‌ریزی هفتگی شامل می‌شود.
                </p>
                <button wire:click="openTrialForm"
                    class="w-full px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition-colors">
                    شروع آزمایشی
                </button>
            </div>

            {{-- خرید مستقیم --}}
            <div class="group rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-sm hover:shadow-lg transition-all">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">خرید مستقیم دوره</h2>
                <p class="text-slate-600 dark:text-slate-300 text-sm leading-7 mb-6">
                    دوره سالانه را بر اساس پایه تحصیلی خود خریداری کنید. قیمت به‌صورت ماهانه پلکانی محاسبه می‌شود.
                </p>
                <button wire:click="goToPurchase"
                    class="w-full px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-colors">
                    مشاهده قیمت و خرید
                </button>
            </div>
        </div>
    </div>

    @if($showTrialForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl bg-white dark:bg-slate-800 shadow-2xl border border-slate-200 dark:border-slate-700">
                <form wire:submit.prevent="startTrial" class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">شروع هفته آزمایشی</h3>
                        <button type="button" wire:click="closeTrialForm" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">پایه تحصیلی</label>
                            <select wire:model.live="grade" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-2.5">
                                <option value="9">نهم</option>
                                <option value="10">دهم</option>
                                <option value="11">یازدهم</option>
                                <option value="12">دوازدهم</option>
                            </select>
                            @error('grade') <small class="text-red-500">{{ $message }}</small> @enderror
                        </div>

                        @if($grade != 9)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">رشته</label>
                                <select wire:model="field" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-2.5">
                                    <option value="math">ریاضی</option>
                                    <option value="experimental">تجربی</option>
                                    <option value="human">انسانی</option>
                                </select>
                                @error('field') <small class="text-red-500">{{ $message }}</small> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">تلفن پدر</label>
                            <input wire:model="fatherMobile" type="text" placeholder="09xxxxxxxxx"
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-2.5">
                            @error('fatherMobile') <small class="text-red-500">{{ $message }}</small> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">تلفن مادر</label>
                            <input wire:model="motherMobile" type="text" placeholder="09xxxxxxxxx"
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-2.5">
                            @error('motherMobile') <small class="text-red-500">{{ $message }}</small> @enderror
                        </div>

                        @error('general') <div class="text-sm text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeTrialForm" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-semibold">انصراف</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold">شروع</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
