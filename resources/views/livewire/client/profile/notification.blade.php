<div class="max-w-7xl space-y-14 px-4 mx-auto">

    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-6">

                <!-- section:title -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground text-lg">پیام‌ها (اطلاع‌رسانی)</div>
                </div>

                <!-- تب‌های دسته‌بندی -->
                <x-ui.segmented-tabs
                    :items="$categories"
                    :active="$activeCategory"
                    :badges="$unreadCounts"
                    method="setCategory"
                />

                <div wire:loading.flex wire:target="setCategory" class="hidden flex-col items-center justify-center gap-3 py-16">
                    <x-ui.spinner size="lg" class="text-primary" />
                    <span class="text-sm text-muted">در حال بارگذاری...</span>
                </div>

                <div wire:loading.remove wire:target="setCategory">

                <!-- لیست پیام‌ها -->
                <div class="space-y-4">
                    @forelse($notifications as $recipient)
                        @php
                            $notif = $recipient->notification;

                            // رنگ‌بندی معنایی هر دسته: announcement=info, special=warning, advisor=success
                            // (border-r-* عمداً به‌جای border-* استفاده شده چون فقط لبه‌ی راست باید
                            // رنگی/ضخیم بشه، نه هر ۴ ضلع — border-* رنگ رو روی هر ۴ ضلع ست می‌کنه)
                            $borderColors = [
                                'announcement' => 'border-r-info',
                                'special'      => 'border-r-warning',
                                'advisor'      => 'border-r-success',
                            ];
                            $bgColors = [
                                'announcement' => 'bg-info/15',
                                'special'      => 'bg-warning/15',
                                'advisor'      => 'bg-success/15',
                            ];
                            $iconColors = [
                                'announcement' => 'text-info',
                                'special'      => 'text-warning',
                                'advisor'      => 'text-success',
                            ];
                            $iconNames = [
                                'announcement' => 'bell',
                                'special'      => 'sparkles',
                                'advisor'      => 'user',
                            ];

                            $cat         = $notif->category ?? 'announcement';
                            $borderColor = $borderColors[$cat] ?? 'border-r-border';
                            $bgColor     = $bgColors[$cat]     ?? 'bg-secondary';
                            $iconColor   = $iconColors[$cat]   ?? 'text-muted';
                            $iconName    = $iconNames[$cat]    ?? 'bell';
                        @endphp

                        <div class="glass border border-border border-r-4 {{ $borderColor }} rounded-xl overflow-hidden transition-all hover:shadow-lg">

                        <div class="p-4 md:p-5">
                                {{-- ── دسکتاپ ── --}}
                                <div class="hidden sm:flex items-center justify-between gap-4" dir="rtl">

                                    {{-- راست: آیکون + عنوان --}}
                                    <div class="flex items-center gap-3">
                                        <div
                                                class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $bgColor }}">
                                            <x-ui.icon name="{{ $iconName }}" class="w-5 h-5 {{ $iconColor }}"/>
                                        </div>
                                        <h3 class="font-bold text-sm md:text-base text-foreground">
                                            {{$notif->title}}
                                        </h3>
                                    </div>

                                    {{-- چپ: دکمه + جدید + زمان --}}
                                    <div class="flex items-center gap-3 flex-shrink-0" dir="ltr">
                                        @if(!$recipient->is_read)
                                            {{-- آیکون این دکمه دستی داخل اسلات گذاشته شده (نه پراپ icon) چون در
                                                 حالت wire:loading باید کاملاً جای متن را با اسپینر عوض کند --}}
                                            <x-ui.button type="button" wire:click="markAsRead({{ $recipient->id }})"
                                                         wire:loading.attr="disabled" wire:target="markAsRead({{ $recipient->id }})"
                                                         variant="primary" size="sm" pill>
                                                <span wire:loading.remove wire:target="markAsRead({{ $recipient->id }})" class="inline-flex items-center gap-1.5">
                                                    خواندن <x-ui.icon name="check" class="w-3 h-3 md:w-4 md:h-4"/>
                                                </span>
                                                <span wire:loading wire:target="markAsRead({{ $recipient->id }})">
                                                    <x-ui.spinner size="xs"/>
                                                </span>
                                            </x-ui.button>
                                        @endif

                                        @if(!$recipient->is_read)
                                            <span
                                                    class="px-2 py-0.5 text-[10px] font-bold bg-error text-error-foreground rounded-full">جدید</span>
                                        @endif

                                        <span class="text-xs text-muted font-bold whitespace-nowrap " dir="rtl">
                                            {{ \Morilog\Jalali\Jalalian::fromDateTime($recipient->created_at)->ago() }}
                                        </span>
                                    </div>
                                </div>

                                {{-- ── موبایل ── --}}
                                <div class="sm:hidden" dir="rtl">
                                    {{-- ردیف ۱: آیکون + عنوان --}}
                                    <div class="flex items-center gap-3 mb-3">
                                        <div
                                                class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center {{ $bgColor }}">
                                            <x-ui.icon name="{{ $iconName }}" class="w-4 h-4 {{ $iconColor }}"/>
                                        </div>
                                        <h3 class="font-bold text-sm text-foreground">
                                            {{ $categories[$cat] ?? 'اطلاعیه' }}{{ $cat === 'special' ? ' های SDFR' : '' }}
                                        </h3>
                                    </div>
                                    {{-- ردیف ۲: زمان (چپ) + جدید (راست) --}}
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[12px] font-bold text-muted">
                                            {{ \Morilog\Jalali\Jalalian::fromDateTime($recipient->created_at)->ago() }}
                                        </span>
                                        @if(!$recipient->is_read)
                                            <span
                                                    class="px-2 py-0.5 text-[10px] font-bold bg-error text-error-foreground rounded-full">جدید</span>
                                        @endif
                                    </div>
                                    {{-- ردیف ۳: دکمه full-width --}}
                                    @if(!$recipient->is_read)
                                        <x-ui.button type="button" wire:click="markAsRead({{ $recipient->id }})"
                                                     wire:loading.attr="disabled" wire:target="markAsRead({{ $recipient->id }})"
                                                     variant="outline" pill block>
                                            <span wire:loading.remove wire:target="markAsRead({{ $recipient->id }})" class="inline-flex items-center gap-1.5">
                                                خواندن <x-ui.icon name="check" class="w-4 h-4"/>
                                            </span>
                                            <span wire:loading wire:target="markAsRead({{ $recipient->id }})">
                                                <x-ui.spinner size="xs"/>
                                            </span>
                                        </x-ui.button>
                                    @endif
                                </div>

                                {{-- ═══ محتوای پیام ═══ --}}
                                <div class="bg-secondary rounded-xl p-4 md:p-5 border border-border mt-4">
                                    <p class="text-xs md:text-sm text-muted font-bold leading-relaxed text-right whitespace-pre-line">
                                        {{ $notif->body }}
                                    </p>
                                </div>

                            </div>
                        </div>

                    @empty
                        <x-ui.empty-state title="پیامی وجود ندارد">
                            در حال حاضر پیامی در این بخش برای شما وجود ندارد.
                        </x-ui.empty-state>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="mt-4">
                        {{ $notifications->links('components.ui.pagination') }}
                    </div>
                @endif

                </div>

            </div>
        </div>

    </div>
</div>
