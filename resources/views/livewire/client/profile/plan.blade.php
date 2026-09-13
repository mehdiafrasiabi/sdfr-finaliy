<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">

                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">برنامه ها</div>
                        </div>

                        <!-- tabs container -->
                        <div class="space-y-5" wire:poll.visible.30000ms>

                            <div>
                                <div>
                                    @if($weeklyPrograms->count() > 0)
                                        <div class="mt-6 space-y-4">
                                            @foreach($weeklyPrograms as $program)
                                                <div wire:key="program-card-{{ $program->id }}"
                                                     x-data="{ expanded: false }"
                                                     class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                                    {{-- ═══════════════════════════════════
                                                         موبایل: تصویر بالا، اطلاعات وسط، دکمه‌ها پایین
                                                    ════════════════════════════════════ --}}
                                                    <div class="md:hidden">

                                                        {{-- تصویر بالا --}}
                                                        <x-ui.thumbnail class="w-full h-36">
                                                            <img src="/client/icons/plan.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                        </x-ui.thumbnail>

                                                        {{-- اطلاعات --}}
                                                        <div class="p-4 space-y-3" dir="rtl">
                                                            <h3 class="font-bold text-foreground text-base">
                                                                {{ $this->programTitle($program) }}
                                                            </h3>
                                                        </div>

                                                        {{-- دکمه‌های موبایل --}}
                                                        <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                            <x-ui.button href="{{ route('client.profile.consultation.weekly-program', $program->id) }}"
                                                                         wire:navigate variant="primary" icon="chevron-left" block>
                                                                مشاهده برنامه
                                                            </x-ui.button>

                                                            {{-- دستی (نه x-ui.button) چون آیکونش باید با چرخش ۱۸۰
                                                                 درجه بین باز/بسته انیمیشن بگیره --}}
                                                            <button type="button" @click="expanded = !expanded" data-elevated="false"
                                                                    class="btn-press w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                                <span>مشاهده جزئیات</span>
                                                                <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                                                           x-bind:class="{ 'rotate-180': expanded }"/>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- ═══════════════════════════════════
                                                         دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه‌ها وسط‌چین عمودی
                                                    ════════════════════════════════════ --}}
                                                    <div class="hidden md:flex flex-row min-h-[130px]">

                                                        {{-- ستون تصویر — دارک‌مودِ دسکتاپ عمداً همون هگزِ سفارشیِ
                                                             #1e3a5f/#1e40af نگه داشته شده (نه x-ui.thumbnail)،
                                                             طبق همون قرارِ قبلی درباره‌ی این گرادیان‌های آبی --}}
                                                        <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                            <img src="/client/icons/plan.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                        </div>

                                                        {{-- محتوا: items-center برای وسط‌چینی عمودی دکمه‌ها --}}
                                                        <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                                            {{-- راست: عنوان + تاریخ + بج‌ها --}}
                                                            <div class="space-y-2 flex-1 min-w-0">
                                                                <h3 class="font-bold text-foreground text-base">
                                                                    {{ $this->programTitle($program) }}
                                                                </h3>

                                                            </div>

                                                            {{-- چپ: دکمه‌ها --}}
                                                            <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                                <x-ui.button href="{{ route('client.profile.consultation.weekly-program', $program->id) }}"
                                                                             wire:navigate variant="primary" icon="eye">
                                                                    مشاهده برنامه
                                                                </x-ui.button>

                                                                {{-- دستی (نه x-ui.button) چون آیکونش باید با چرخش
                                                                     ۱۸۰ درجه بین باز/بسته انیمیشن بگیره --}}
                                                                <button type="button" @click="expanded = !expanded" data-elevated="false"
                                                                        class="btn-press inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                                    <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                                                               x-bind:class="{ 'rotate-180': expanded }"/>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- ═══ جزئیات (مشترک موبایل و دسکتاپ) ═══ --}}
                                                    <div x-show="expanded"
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                                         class="border-border bg-background/50 p-4"
                                                         style="display: none;">
                                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <x-ui.icon name="calendar" class="w-6 h-6 text-primary mb-2"/>
                                                                <span class="text-xs text-muted">تاریخ شروع</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ jdate($program->start_date)->format('d %B') }}</span>
                                                            </div>

                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <x-ui.icon name="calendar" class="w-6 h-6 mb-2 text-warning"/>
                                                                <span class="text-xs text-muted">تاریخ پایان</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ jdate($program->end_date)->format('d %B') }}</span>
                                                            </div>

                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <x-ui.icon name="clock" class="w-6 h-6 text-success mb-2"/>
                                                                <span class="text-xs text-muted">ساعت کل برنامه</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ $program->total_hours }} ساعت</span>
                                                            </div>

                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <x-ui.icon name="list-check" class="w-6 h-6 mb-2 text-info"/>
                                                                <span class="text-xs text-muted">تعداد تست</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ $program->total_tests }} تست</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <x-ui.empty-state title="برنامه‌ای وجود ندارد!">
                                            هنوز برنامه‌ای برای شما ثبت نشده است.
                                        </x-ui.empty-state>
                                    @endif

                                    <!-- پیجینیشن -->
                                    @if($weeklyPrograms->hasPages())
                                        <div class="mt-6">
                                            {{ $weeklyPrograms->links('components.ui.pagination') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
