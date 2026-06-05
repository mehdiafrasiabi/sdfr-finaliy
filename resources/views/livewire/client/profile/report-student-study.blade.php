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
                    <!-- Help Box -->

                    <div class="space-y-5">
                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground"> کارنامه هوشمند</div>
                        </div>
                        <!-- end section:title -->


                        <!-- tabs container -->
                        <div class="space-y-5">

                            <!-- tabs:contents -->
                            <div>

                                <div>
                                    @if($smartCards->isNotEmpty())
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($smartCards as $card)
                                                <a wire:navigate href="{{ route('client.profile.smartReportCard.show', $card->id) }}"
                                                   class="group block bg-secondary border border-border rounded-2xl p-5 hover:border-primary transition-colors">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div>
                                                            <div class="font-black text-lg text-foreground">{{ $card->month_name }}</div>
                                                            <div class="text-xs text-muted mt-1">سال {{ $card->jalali_year }}</div>
                                                        </div>
                                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-green-500/15 text-green-600 dark:text-green-400 rounded-full px-2 py-1">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                            فعال
                                                        </span>
                                                    </div>
                                                    <div class="space-y-1 text-xs text-muted">
                                                        <div class="flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                                            </svg>
                                                            <span>از {{ $card->jalali_start }} تا {{ $card->jalali_end }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary group-hover:gap-2 transition-all">
                                                        مشاهده کارنامه
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                                                        </svg>
                                                    </div>
                                                </a>
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
                            </div><!-- end tabs:contents -->
                        </div><!-- end tabs container -->
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
