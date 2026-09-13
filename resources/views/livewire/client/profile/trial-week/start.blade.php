<div x-data="{ loading: false }">

    {{-- دکمه شروع هفته آزمایشی --}}
    <button
        type="button"
        wire:click="openConfirm"
        wire:loading.attr="disabled"
        @click="loading = true"
        data-elevated="true"
        class="btn-press group relative inline-flex items-center gap-3 px-6 py-4 bg-success hover:bg-success/90 text-success-foreground rounded-2xl font-bold text-sm shadow-lg shadow-success/30 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-success/40 transition-all duration-300 w-full justify-center md:w-auto">

        <span>شروع یک هفته آزمایشی رایگان</span>

        {{-- آیکون «رعد/جرقه» توی دیکشنری Keyline نیست، همون SVG قبلی نگه داشته شد. --}}
        <span x-show="!$wire.isLoading">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </span>
        <x-ui.spinner wire:loading wire:target="openConfirm" />

        <span class="absolute -top-2 -left-2 bg-warning text-warning-foreground text-[10px] font-black px-2 py-0.5 rounded-full">
            رایگان
        </span>
    </button>

    {{-- مودال تایید --}}
    @if($showConfirmModal)
        <div class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
             x-data x-init="window.SdfrModalScrollLock.lock()"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             wire:click="closeConfirm" @click="window.SdfrModalScrollLock.unlock()"
             @keydown.escape.window="$wire.closeConfirm(); window.SdfrModalScrollLock.unlock()"
        ></div>

        <div class="fixed inset-0 z-[101] flex items-end sm:items-center justify-center p-0 sm:p-4"
             @click.self="$wire.closeConfirm(); window.SdfrModalScrollLock.unlock()">

            <div class="relative w-full sm:max-w-md bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl p-8 text-center"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-90">

                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" wire:click="closeConfirm" @click="window.SdfrModalScrollLock.unlock()"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary"
                        data-elevated="false">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <div class="flex items-center justify-center w-20 h-20 bg-success/15 rounded-full mx-auto mb-6 mt-4">
                    <x-ui.icon name="sparkles" class="w-10 h-10 text-success"/>
                </div>

                <h2 class="text-xl font-black text-foreground mb-3">شروع هفته آزمایشی</h2>
                <p class="text-muted text-sm leading-relaxed mb-6">
                    با شروع هفته آزمایشی، به مدت <span class="text-success font-bold">۷ روز</span> به امکانات سایت دسترسی خواهید داشت.
                    پس از تکمیل مراحل، برنامه مطالعاتی شخصی برای شما ساخته می‌شود.
                    <br><br>
                    <span class="font-semibold text-foreground">آیا می‌خواهید ادامه دهید؟</span>
                </p>

                <div class="flex gap-3 border-t border-border pt-5">
                    <x-ui.button wire:click="confirm" variant="success" icon="check" block>بله، شروع می‌کنم</x-ui.button>
                    <x-ui.button wire:click="closeConfirm" @click="window.SdfrModalScrollLock.unlock()" variant="secondary-outline" icon="x" block>انصراف</x-ui.button>
                </div>
            </div>
        </div>
    @endif

    {{-- مودال فرم اطلاعات --}}
    @if($showFormModal)
        <div class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
             x-data x-init="window.SdfrModalScrollLock.lock()"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             wire:click="closeForm" @click="window.SdfrModalScrollLock.unlock()"
             @keydown.escape.window="$wire.closeForm(); window.SdfrModalScrollLock.unlock()"
        ></div>

        <div class="fixed inset-0 z-[101] flex items-end sm:items-center justify-center p-0 sm:p-4"
             @click.self="$wire.closeForm(); window.SdfrModalScrollLock.unlock()">

            <div class="relative w-full sm:max-w-lg bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col max-h-[92vh] sm:max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-90">

                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" wire:click="closeForm" @click="window.SdfrModalScrollLock.unlock()"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary"
                        data-elevated="false">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                {{-- هدر --}}
                <div class="flex items-center gap-3 p-6 pb-4 border-b border-border shrink-0">
                    <div class="w-10 h-10 rounded-full bg-success/15 flex items-center justify-center shrink-0">
                        <x-ui.icon name="user" class="w-5 h-5 text-success"/>
                    </div>
                    <h3 class="font-black text-foreground">تکمیل اطلاعات</h3>
                </div>

                {{-- بدنه فرم --}}
                <div class="p-6 space-y-5 overflow-y-auto">

                    @if($errors->has('general'))
                        <div class="p-4 bg-error/10 border border-error/30 rounded-xl text-error text-sm">
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    {{-- پایه تحصیلی --}}
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">
                            پایه تحصیلی
                            <span class="text-error">*</span>
                        </label>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach([9 => 'نهم', 10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'] as $gradeVal => $gradeLabel)
                                <x-ui.button type="button"
                                             wire:click="$set('grade', {{ $gradeVal }})"
                                             variant="{{ $grade == $gradeVal ? 'success' : 'secondary-outline' }}"
                                             size="sm" block
                                             :icon="$grade == $gradeVal ? 'check' : null">
                                    {{ $gradeLabel }}
                                </x-ui.button>
                            @endforeach
                        </div>
                        @error('grade') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- رشته (فقط اگر پایه ۱۰، ۱۱، ۱۲ باشد) --}}
                    @if($grade != 9)
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">
                            رشته تحصیلی
                            <span class="text-error">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'] as $fieldVal => $fieldLabel)
                                <x-ui.button type="button"
                                             wire:click="$set('field', '{{ $fieldVal }}')"
                                             variant="{{ $field === $fieldVal ? 'success' : 'secondary-outline' }}"
                                             size="sm" block
                                             :icon="$field === $fieldVal ? 'check' : null">
                                    {{ $fieldLabel }}
                                </x-ui.button>
                            @endforeach
                        </div>
                        @error('field') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    {{-- تلفن پدر --}}
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">
                            تلفن پدر
                            <span class="text-error">*</span>
                        </label>
                        <input type="tel"
                               wire:model.live="fatherMobile"
                               placeholder="09xxxxxxxxx"
                               dir="ltr"
                               maxlength="11"
                               class="w-full px-4 py-3 bg-secondary border border-border rounded-xl text-foreground placeholder-muted focus:outline-none focus:border-success focus:ring-2 focus:ring-success/20 transition-all text-left">
                        @error('fatherMobile') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- تلفن مادر --}}
                    <div>
                        <label class="block text-sm font-bold text-foreground mb-2">
                            تلفن مادر
                            <span class="text-error">*</span>
                        </label>
                        <input type="tel"
                               wire:model.live="motherMobile"
                               placeholder="09xxxxxxxxx"
                               dir="ltr"
                               maxlength="11"
                               class="w-full px-4 py-3 bg-secondary border border-border rounded-xl text-foreground placeholder-muted focus:outline-none focus:border-success focus:ring-2 focus:ring-success/20 transition-all text-left">
                        @error('motherMobile') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- دکمه ثبت --}}
                <div class="p-6 pt-4 border-t border-border shrink-0">
                    <button wire:click="submit"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            type="button"
                            data-elevated="true"
                            class="btn-press w-full py-4 bg-success hover:bg-success/90 disabled:opacity-50 text-success-foreground rounded-xl font-black text-base transition-all duration-200 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submit">ثبت و شروع هفته آزمایشی</span>
                        <span wire:loading wire:target="submit">در حال پردازش...</span>
                        <span wire:loading.remove wire:target="submit">
                            <x-ui.icon name="check" class="w-4 h-4"/>
                        </span>
                        <x-ui.spinner wire:loading wire:target="submit" />
                    </button>
                    <p class="text-center text-xs text-muted mt-3">
                        با ثبت اطلاعات، <span class="text-success font-semibold">۷ روز</span> آزمایشی شما آغاز می‌شود
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
