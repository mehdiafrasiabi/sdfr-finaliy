<div x-data="{ loading: false }">

    {{-- دکمه شروع هفته آزمایشی --}}
    <button
        wire:click="openConfirm"
        wire:loading.attr="disabled"
        @click="loading = true"
        class="group relative inline-flex items-center gap-3 px-6 py-4 bg-gradient-to-l from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300 w-full justify-center md:w-auto">

        <span x-show="!$wire.isLoading">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </span>
        <x-ui.spinner wire:loading wire:target="openConfirm" />

        <span>شروع یک هفته آزمایشی رایگان</span>

        <span class="absolute -top-2 -left-2 bg-amber-400 text-amber-900 text-[10px] font-black px-2 py-0.5 rounded-full">
            رایگان
        </span>
    </button>

    {{-- مودال تایید --}}
    @if($showConfirmModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-init="$el.scrollIntoView({behavior:'smooth'})"
         @keydown.escape.window="$wire.closeConfirm()">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeConfirm"></div>

        <div class="relative z-10 w-full max-w-md bg-background border border-border rounded-3xl shadow-2xl p-8 text-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-center w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-full mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>

            <h2 class="text-xl font-black text-foreground mb-3">شروع هفته آزمایشی</h2>
            <p class="text-muted text-sm leading-relaxed mb-6">
                با شروع هفته آزمایشی، به مدت <span class="text-emerald-500 font-bold">۷ روز</span> به امکانات سایت دسترسی خواهید داشت.
                پس از تکمیل مراحل، برنامه مطالعاتی شخصی برای شما ساخته می‌شود.
                <br><br>
                <span class="font-semibold text-foreground">آیا می‌خواهید ادامه دهید؟</span>
            </p>

            <div class="flex gap-3">
                <button wire:click="confirm"
                        class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold transition-colors">
                    بله، شروع می‌کنم
                </button>
                <button wire:click="closeConfirm"
                        class="flex-1 py-3 bg-secondary hover:bg-border text-foreground rounded-xl font-bold transition-colors">
                    انصراف
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- مودال فرم اطلاعات --}}
    @if($showFormModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         @keydown.escape.window="$wire.closeForm()">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeForm"></div>

        <div class="relative z-10 w-full max-w-lg bg-background border border-border rounded-3xl shadow-2xl my-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- هدر --}}
            <div class="flex items-center justify-between p-6 border-b border-border">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-black text-foreground">تکمیل اطلاعات</h3>
                </div>
                <button wire:click="closeForm" class="text-muted hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- بدنه فرم --}}
            <div class="p-6 space-y-5">

                @if($errors->has('general'))
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-600 dark:text-red-400 text-sm">
                        {{ $errors->first('general') }}
                    </div>
                @endif

                {{-- پایه تحصیلی --}}
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">
                        پایه تحصیلی
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach([9 => 'نهم', 10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'] as $gradeVal => $gradeLabel)
                            <button type="button"
                                    wire:click="$set('grade', {{ $gradeVal }})"
                                    class="py-3 rounded-xl text-sm font-bold border-2 transition-all duration-200 {{ $grade == $gradeVal ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'border-border bg-secondary text-foreground hover:border-emerald-300' }}">
                                {{ $gradeLabel }}
                            </button>
                        @endforeach
                    </div>
                    @error('grade') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- رشته (فقط اگر پایه ۱۰، ۱۱، ۱۲ باشد) --}}
                @if($grade != 9)
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">
                        رشته تحصیلی
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'] as $fieldVal => $fieldLabel)
                            <button type="button"
                                    wire:click="$set('field', '{{ $fieldVal }}')"
                                    class="py-3 rounded-xl text-sm font-bold border-2 transition-all duration-200 {{ $field === $fieldVal ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400' : 'border-border bg-secondary text-foreground hover:border-emerald-300' }}">
                                {{ $fieldLabel }}
                            </button>
                        @endforeach
                    </div>
                    @error('field') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- تلفن پدر --}}
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">
                        تلفن پدر
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="tel"
                           wire:model.live="fatherMobile"
                           placeholder="09xxxxxxxxx"
                           dir="ltr"
                           maxlength="11"
                           class="w-full px-4 py-3 bg-secondary border border-border rounded-xl text-foreground placeholder-muted focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-left">
                    @error('fatherMobile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- تلفن مادر --}}
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">
                        تلفن مادر
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="tel"
                           wire:model.live="motherMobile"
                           placeholder="09xxxxxxxxx"
                           dir="ltr"
                           maxlength="11"
                           class="w-full px-4 py-3 bg-secondary border border-border rounded-xl text-foreground placeholder-muted focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-left">
                    @error('motherMobile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- دکمه ثبت --}}
            <div class="p-6 pt-0">
                <button wire:click="submit"
                        wire:loading.attr="disabled"
                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 disabled:opacity-50 text-white rounded-xl font-black text-base transition-all duration-200 flex items-center justify-center gap-2">
                    <x-ui.spinner wire:loading wire:target="submit" />
                    <span wire:loading.remove wire:target="submit">ثبت و شروع هفته آزمایشی</span>
                    <span wire:loading wire:target="submit">در حال پردازش...</span>
                </button>
                <p class="text-center text-xs text-muted mt-3">
                    با ثبت اطلاعات، <span class="text-emerald-500 font-semibold">۷ روز</span> آزمایشی شما آغاز می‌شود
                </p>
            </div>
        </div>
    </div>
    @endif

</div>
