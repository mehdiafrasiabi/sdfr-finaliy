{{--
    این مودال یک پراپرتیِ عمومیِ $show دارد (که خودِ کلاسِ Livewire با
    updatedShow() بهش گوش می‌ده و فرم رو با بسته‌شدن ریست می‌کنه)، برای همین
    برخلافِ نسخه‌ی قبلی (که هیچ x-show‌ای نداشت و همیشه تمام‌صفحه رندر می‌شد)،
    همین $show رو به Alpine گره می‌زنیم تا انیمیشنِ باز/بسته‌شدن (کشویی از
    پایین در موبایل، scale از وسط در دسکتاپ) داشته باشه — دقیقاً همون الگویِ
    یکسانِ استانداردِ همه‌ی مودال‌های پروژه (دستگیره‌ی کشویی + دکمه‌ی بستنِ
    گرد در گوشه + کلیک روی پس‌زمینه برای بستن).
--}}
@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

<div
    x-data="{ show: @entangle('show') }"
    x-effect="show ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
    x-cloak
>
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
        @click="$wire.close()"
        style="display: none;"
    ></div>

    <div
        x-show="show"
        class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
        @click.self="$wire.close()"
        style="display: none;"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            class="relative w-full sm:max-w-sm max-h-[90vh] overflow-y-auto bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl p-6"
            dir="rtl"
        >
            <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>

            <button type="button" @click="$wire.close()" data-elevated="false"
                    class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                <x-ui.icon name="x" class="w-4 h-4"/>
            </button>

            <h3 class="text-xl font-extrabold mb-6 text-center text-foreground border-b border-border pb-3">
                فرم پیش جلسه مشاوره
            </h3>

            {{-- Step Content --}}
            <div class="space-y-6">
                @if($step === 1)
                    <h4 class="text-lg font-semibold mb-3 text-foreground">📘 تکالیف</h4>
                    <div class="space-y-4">
                        @foreach($homeworks as $i=>$hw)
                            <div wire:key="hw-{{ $i }}">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">نام درس:</label>
                                        <input type="text" wire:model="homeworks.{{$i}}.lesson"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">تعداد پارت:</label>
                                        <input type="number" dir="ltr" wire:model="homeworks.{{$i}}.parts"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">تاریخ:</label>
                                        <input type="date" dir="ltr" wire:model="homeworks.{{$i}}.date"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                </div>
                                @unless($loop->last)
                                    <hr class="border-dashed border-border my-4">
                                @endunless
                            </div>
                        @endforeach
                        <x-ui.button type="button" wire:click="addHomework" variant="secondary" size="sm" icon="plus">
                            افزودن تکلیف
                        </x-ui.button>
                    </div>
                @elseif($step === 2)
                    <h4 class="text-lg font-semibold mb-3 text-foreground">📝 آزمون‌ها</h4>
                    <div class="space-y-3">
                        @foreach($exams as $i=>$ex)
                            <div wire:key="exam-{{ $i }}">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">درس آزمون:</label>
                                        <input type="text" wire:model="exams.{{$i}}.lesson"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">تاریخ آزمون:</label>
                                        <input type="date" wire:model="exams.{{$i}}.date"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                </div>
                                @unless($loop->last)
                                    <hr class="border-dashed border-border my-4">
                                @endunless
                            </div>
                        @endforeach
                        <x-ui.button type="button" wire:click="addExam" variant="secondary" size="sm" icon="plus">
                            افزودن آزمون
                        </x-ui.button>
                    </div>

                @elseif($step === 3)
                    <h4 class="text-lg font-semibold mb-3 text-foreground">⏰ اوقات فراغت</h4>
                    <div class="space-y-4">
                        @foreach($free_times as $i=>$ft)
                            <div wire:key="ft-{{ $i }}">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">روز :</label>
                                        <input type="text" wire:model="free_times.{{$i}}.day"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-medium text-xs text-muted">ساعت:</label>
                                        <input type="date" wire:model="free_times.{{$i}}.time"
                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border rounded-xl text-sm text-foreground px-5 focus:border-primary focus:ring-1 focus:ring-primary/20">
                                    </div>
                                </div>
                                @unless($loop->last)
                                    <hr class="border-dashed border-border my-4">
                                @endunless
                            </div>
                        @endforeach
                        <x-ui.button type="button" wire:click="addFreeTime" variant="secondary" size="sm" icon="plus">
                            افزودن زمان
                        </x-ui.button>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="mt-8 flex justify-between items-center border-t border-border pt-4 gap-2">
                @if($step>1)
                    <x-ui.button type="button" wire:click="prevStep" variant="secondary-outline" icon="chevron-right">
                        قبلی
                    </x-ui.button>
                @endif
                <div class="flex items-center gap-2 mr-auto">
                    @if($step<3)
                        <x-ui.button type="button" wire:click="nextStep" variant="primary" icon="chevron-left">
                            بعدی
                        </x-ui.button>
                    @else
                        <x-ui.button type="button" wire:click="save" variant="success" icon="check">
                            ثبت فرم
                        </x-ui.button>
                    @endif
                    <x-ui.button type="button" wire:click="close" variant="secondary-outline" icon="x">
                        بستن
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>
