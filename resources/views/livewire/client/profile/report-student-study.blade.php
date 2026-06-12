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
                                @if($smartCards->isNotEmpty())
                                    <div class="space-y-4">
                                        @foreach($smartCards as $card)
                                            <div class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                                {{-- ═══════════════════════════════════
                                                     موبایل: تصویر بالا، اطلاعات وسط، دکمه پایین
                                                ════════════════════════════════════ --}}
                                                <div class="md:hidden">

                                                    {{-- تصویر بالا --}}
                                                    <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-blue-600 dark:text-blue-300 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                                        </svg>
                                                    </div>

                                                    {{-- اطلاعات --}}
                                                    <div class="p-4 space-y-3" dir="rtl">
                                                        <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
                                                            {{ $card->month_name }}
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-500/15 text-green-600 dark:text-green-400 text-xs rounded-full">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                                فعال
                                                            </span>
                                                        </h3>

                                                        <p class="text-sm text-muted">سال {{ $card->jalali_year }}</p>

                                                        <p class="text-xs text-muted">
                                                            <span class="inline-flex items-center gap-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                                                </svg>
                                                                از {{ $card->jalali_start }} تا {{ $card->jalali_end }}
                                                            </span>
                                                        </p>
                                                    </div>

                                                    {{-- دکمه موبایل --}}
                                                    <div class="px-4 pb-4" dir="rtl">
                                                        <a wire:navigate href="{{ route('client.profile.smartReportCard.show', $card->id) }}"
                                                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                            مشاهده کارنامه
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18L9 12L15 6"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                {{-- ═══════════════════════════════════
                                                     دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه وسط‌چین عمودی
                                                ════════════════════════════════════ --}}
                                                <div class="hidden md:flex flex-row min-h-[130px]">

                                                    {{-- ستون تصویر --}}
                                                    <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-blue-600 dark:text-blue-200 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                                        </svg>
                                                    </div>

                                                    {{-- محتوا --}}
                                                    <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                                        {{-- راست: عنوان + سال + تاریخ + بج --}}
                                                        <div class="space-y-2 flex-1 min-w-0">
                                                            <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
                                                                {{ $card->month_name }}
                                                                <span class="text-sm text-muted font-normal">سال {{ $card->jalali_year }}</span>
                                                            </h3>

                                                            <p class="text-sm text-muted">
                                                                <span class="inline-flex items-center gap-1">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                                                    </svg>
                                                                    از {{ $card->jalali_start }} تا {{ $card->jalali_end }}
                                                                </span>
                                                            </p>

                                                            <div class="flex flex-wrap items-center gap-1.5">
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-500/15 text-green-600 dark:text-green-400 text-xs rounded-full">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                                    فعال
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{-- چپ: دکمه --}}
                                                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                            <a wire:navigate href="{{ route('client.profile.smartReportCard.show', $card->id) }}"
                                                               class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                مشاهده کارنامه
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center space-y-8 py-12">
                                        <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                                        <div class="text-center space-y-2">
                                            <h2 class="font-bold text-xl text-foreground">کارنامه هوشمندی برای شما فعال نشده است.</h2>
                                            <p class="text-sm text-muted">پس از فعال‌سازی توسط مشاور، ماه‌های فعال در این بخش نمایش داده می‌شود.</p>
                                        </div>
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
