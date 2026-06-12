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
                <!-- تب‌های دسته‌بندی -->
                <div class="flex justify-center md:justify-end" dir="rtl">
                    <div class="inline-flex items-center gap-1 p-1 bg-background w rounded-full border border-border">
                        @foreach($categories as $key => $label)
                            <button
                                wire:click="setCategory('{{ $key }}')"
                                class="relative inline-flex items-center gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium transition-all
                {{ $activeCategory === $key
                    ? 'bg-secondary text-primary shadow-sm'
                    : 'text-foreground/70 hover:text-foreground' }}">
                                {{ $label }}
                                @if(isset($unreadCounts[$key]) && $unreadCounts[$key] > 0)
                                    <span class="inline-flex items-center justify-center min-w-[18px] h-4 px-1 text-[10px] font-bold rounded-full bg-red-500 text-white">
                        {{ $unreadCounts[$key] }}
                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- لیست پیام‌ها -->
                <div class="space-y-4">
                    @forelse($notifications as $recipient)
                        @php
                            $notif = $recipient->notification;

                                $borderColors = [
                                    'announcement' => 'border-right: 4px solid #3b82f6;',
                                    'special'      => 'border-right: 4px solid #f97316;',
                                    'advisor'      => 'border-right: 4px solid #22c55e;',
                                ];
                            $bgColors = [
                                'announcement' => 'bg-blue-500/15',
                                'special'      => 'bg-orange-100 dark:bg-orange-900/30',
                                'advisor'      => 'bg-green-100 dark:bg-green-900/30',
                            ];
                            $iconColors = [
                                'announcement' => 'text-blue-500 dark:text-blue-400',
                                'special'      => 'text-orange-500 dark:text-orange-400',
                                'advisor'      => 'text-green-500 dark:text-green-400',
                            ];
                            $icons = [
                                'announcement' => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
                                'special'      => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z',
                                'advisor'      => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                            ];

                            $cat         = $notif->category ?? 'announcement';
                            $borderColor = $borderColors[$cat] ?? 'border-l-gray-500';
                            $bgColor     = $bgColors[$cat]     ?? 'bg-gray-100 dark:bg-gray-900/30';
                            $iconColor   = $iconColors[$cat]   ?? 'text-gray-500';
                            $iconPath    = $icons[$cat]        ?? $icons['announcement'];
                        @endphp

                        <div class="glass border border-border rounded-xl overflow-hidden transition-all hover:shadow-lg" style="{{ $borderColor }}">

                        <div class="p-4 md:p-5">
                                {{-- ── دسکتاپ ── --}}
                                <div class="hidden sm:flex items-center justify-between gap-4" dir="rtl">

                                    {{-- راست: آیکون + عنوان --}}
                                    <div class="flex items-center gap-3">
                                        <div
                                                class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $bgColor }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"
                                                 class="w-5 h-5 {{ $iconColor }}">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="{{ $iconPath }}"/>
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-sm md:text-base text-foreground">
                                            {{$notif->title}}
                                        </h3>
                                    </div>

                                    {{-- چپ: دکمه + جدید + زمان --}}
                                    <div class="flex items-center gap-3 flex-shrink-0" dir="ltr">
                                        @if(!$recipient->is_read)

                                            <button wire:click="markAsRead({{ $recipient->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="markAsRead({{ $recipient->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3 md:px-4 py-1.5 md:py-2 bg-primary text-primary-foreground rounded-full text-xs font-medium hover:opacity-90 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="2" stroke="currentColor"
                                                     class="w-3 h-3 md:w-4 md:h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                                <span wire:loading.remove
                                                      wire:target="markAsRead({{ $recipient->id }})">خواندن</span>
                                                <span wire:loading wire:target="markAsRead({{ $recipient->id }})"
                                                      class="inline-block w-3.5 h-3.5 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                                            </button>
                                        @endif

                                        @if(!$recipient->is_read)
                                            <span
                                                    class="px-2 py-0.5 text-[10px] font-bold bg-red-500 text-white rounded-full">جدید</span>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"
                                                 class="w-4 h-4 {{ $iconColor }}">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="{{ $iconPath }}"/>
                                            </svg>
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
                                                    class="px-2 py-0.5 text-[10px] font-bold bg-red-500 text-white rounded-full">جدید</span>
                                        @endif
                                    </div>
                                    {{-- ردیف ۳: دکمه full-width --}}
                                    @if(!$recipient->is_read)
                                        <button wire:click="markAsRead({{ $recipient->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="markAsRead({{ $recipient->id }})"
                                                class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 border-2 border-primary text-primary rounded-full text-xs font-bold hover:bg-primary hover:text-primary-foreground transition-all">

                                            <svg wire:loading.remove wire:target="markAsRead({{ $recipient->id }})"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="2" stroke="currentColor" class="w-3 h-3 md:w-4 md:h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                            <span wire:loading.remove wire:target="markAsRead({{ $recipient->id }})">خواندن</span>

                                            <span wire:loading wire:target="markAsRead({{ $recipient->id }})"
                                                  class="inline-block w-3.5 h-3.5 rounded-full border-2 border-current border-t-transparent animate-spin"></span>
                                        </button>
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
                        <div class="flex flex-col items-center justify-center py-12 md:py-16">
                            <img src="/client/assets/images/theme/empty.svg"
                                 class="w-full max-w-[200px] md:max-w-xs opacity-35 mb-4 md:mb-6"
                                 alt="پیامی وجود ندارد"/>
                            <div class="text-center space-y-2">
                                <h2 class="font-bold text-lg md:text-xl text-foreground">پیامی وجود ندارد</h2>
                                <p class="text-muted text-xs md:text-sm">در حال حاضر پیامی در این بخش برای شما وجود
                                    ندارد.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="mt-4">
                        {{ $notifications->links('layouts.client.pagination') }}
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
