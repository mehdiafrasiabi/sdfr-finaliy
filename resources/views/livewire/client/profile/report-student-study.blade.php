<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <!-- user:menus -->
                <livewire:client.profile.sidebar />
                <!-- end user:menus -->
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">

                    @if($hideForExamProgramTrialStudent)
                        <x-ui.empty-state title="کارنامه‌ای برای نمایش وجود ندارد.">
                            فعلاً داده‌ای برای صدور یا نمایش کارنامه ثبت نشده است.
                        </x-ui.empty-state>
                    @else
                    <div class="space-y-5">
                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">کارنامه هوشمند</div>
                        </div>
                        <!-- end section:title -->

                        <div class="space-y-5">
                            <div>
                                @if($isTrial && ! $reportUnlocked)
                                    {{-- کارنامه‌ی هفته آزمایشی تا روز ششم قفل است --}}
                                    <div class="flex flex-col items-center justify-center text-center space-y-5 py-14">
                                        <div class="w-16 h-16 rounded-2xl bg-secondary border border-border flex items-center justify-center">
                                            <x-ui.icon name="lock" class="w-8 h-8 text-muted"/>
                                        </div>
                                        <div class="space-y-2">
                                            <h2 class="font-bold text-xl text-foreground">کارنامه‌ی هفته آزمایشی هنوز فعال نشده</h2>
                                            <p class="text-sm text-muted leading-7 max-w-md">
                                                کارنامه‌ی تحلیلی تو از <span class="font-bold text-foreground">روز ششم</span> هفته‌ی آزمایشی فعال می‌شود.
                                                @if($trialDay)
                                                    <br>الان روز <span class="font-bold text-foreground">{{ $trialDay }}</span>م هستی.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @elseif($smartCards->isNotEmpty())
                                    <div class="space-y-4">
                                        @foreach($smartCards as $card)
                                            <div class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                                {{-- ═══════════════════════════════════
                                                     موبایل: تصویر بالا، اطلاعات وسط، دکمه پایین
                                                ════════════════════════════════════ --}}
                                                <div class="md:hidden">

                                                    {{-- تصویر بالا --}}
                                                    <x-ui.thumbnail class="w-full h-36">
                                                        <img src="/client/icons/karname.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                    </x-ui.thumbnail>

                                                    {{-- اطلاعات --}}
                                                    <div class="p-4 space-y-3" dir="rtl">
                                                        <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
                                                            {{ $isExamProgramReport ? 'برنامه امتحانی' : ($isTrial ? 'یک هفته آزمایشی' : $card->month_name) }}
                                                            <x-ui.status-badge status="active"/>
                                                        </h3>

                                                        @unless($isTrial || $isExamProgramReport)<p class="text-sm text-muted">سال {{ $card->jalali_year }}</p>@endunless

                                                        <p class="text-xs text-muted">
                                                            <span class="inline-flex items-center gap-1">
                                                                <x-ui.icon name="calendar" class="w-3.5 h-3.5"/>
                                                                از {{ $card->jalali_start }} تا {{ $card->jalali_end }}
                                                            </span>
                                                        </p>
                                                    </div>

                                                    {{-- دکمه موبایل --}}
                                                    <div class="px-4 pb-4" dir="rtl">
                                                        <x-ui.button href="{{ route('client.profile.smartReportCard.show', $card->id) }}"
                                                                     wire:navigate variant="primary" icon="chevron-left" block>
                                                            مشاهده کارنامه
                                                        </x-ui.button>
                                                    </div>
                                                </div>

                                                {{-- ═══════════════════════════════════
                                                     دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه وسط‌چین عمودی
                                                ════════════════════════════════════ --}}
                                                <div class="hidden md:flex flex-row min-h-[130px]">

                                                    {{-- ستون تصویر — دارک‌مودِ دسکتاپ عمداً همون هگزِ سفارشیِ
                                                         #1e3a5f/#1e40af نگه داشته شده (نه x-ui.thumbnail)،
                                                         طبق همون قرارِ قبلی درباره‌ی این گرادیان‌های آبی --}}
                                                    <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                        <img src="/client/icons/karname.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                    </div>

                                                    {{-- محتوا --}}
                                                    <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                                        {{-- راست: عنوان + سال + تاریخ + بج --}}
                                                        <div class="space-y-2 flex-1 min-w-0">
                                                            <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
                                                                {{ $isExamProgramReport ? 'برنامه امتحانی' : ($isTrial ? 'یک هفته آزمایشی' : $card->month_name) }}
                                                                @unless($isTrial || $isExamProgramReport)<span class="text-sm text-muted font-normal">سال {{ $card->jalali_year }}</span>@endunless
                                                            </h3>

                                                            <p class="text-sm text-muted">
                                                                <span class="inline-flex items-center gap-1">
                                                                    <x-ui.icon name="calendar" class="w-3.5 h-3.5"/>
                                                                    از {{ $card->jalali_start }} تا {{ $card->jalali_end }}
                                                                </span>
                                                            </p>

                                                            <div class="flex flex-wrap items-center gap-1.5">
                                                                <x-ui.status-badge status="active"/>
                                                            </div>
                                                        </div>

                                                        {{-- چپ: دکمه --}}
                                                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                            <x-ui.button href="{{ route('client.profile.smartReportCard.show', $card->id) }}"
                                                                         wire:navigate variant="primary" icon="eye">
                                                                مشاهده کارنامه
                                                            </x-ui.button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <x-ui.empty-state title="کارنامه هوشمندی برای شما فعال نشده است.">
                                        پس از فعال‌سازی توسط مشاور، ماه‌های فعال در این بخش نمایش داده می‌شود.
                                    </x-ui.empty-state>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
