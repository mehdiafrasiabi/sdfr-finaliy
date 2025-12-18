<div class="max-w-7xl space-y-14 px-4 mx-auto">

    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- user:menus -->

            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->

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

                <!-- end section:title -->


                <!-- راهنمای پیام‌ها -->

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">

                    <div class="flex items-start gap-3">

                        <div class="flex-shrink-0">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6 text-blue-500">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>

                            </svg>

                        </div>

                        <div>

                            <h4 class="font-bold text-blue-700 dark:text-blue-400 mb-1">راهنمای پیام‌ها</h4>

                            <p class="text-sm text-blue-600 dark:text-blue-300">

                                در این بخش رویدادها و اخبارهای مهم را به شما اعلام می‌کنیم. مانند؛ تغییر در روند برگزاری
                                اتاق مشاوره و آزمون SDFR، زمان برگزاری آزمون‌های کلاسی و همچنین اعلام زمان ارسال تکالیف و
                                ...

                            </p>

                        </div>

                    </div>

                </div>


                <!-- تب‌های دسته‌بندی -->

                <div class="flex flex-wrap gap-2">

                    @foreach($categories as $key => $label)

                        <button

                            wire:click="setCategory('{{ $key }}')"

                            class="relative inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all

                                {{ $activeCategory === $key

                                    ? 'bg-primary text-primary-foreground shadow-md'

                                    : 'bg-background border border-border text-foreground hover:bg-muted' }}">

                            {{ $label }}

                            @if(isset($unreadCounts[$key]) && $unreadCounts[$key] > 0)

                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold rounded-full

                                    {{ $activeCategory === $key

                                        ? 'bg-white/20 text-white'

                                        : 'bg-red-500 text-white' }}">

                                    {{ $unreadCounts[$key] }}

                                </span>

                            @endif

                        </button>

                    @endforeach

                </div>


                <!-- section:notifications:wrapper -->

                <div class="space-y-4" wire:poll.keep-alive.10s>

                    @forelse($notifications as $recipient)

                        @php

                            $notif = $recipient->notification;

                            $categoryColors = [

                                'announcement' => 'border-l-blue-500',

                                'special' => 'border-l-orange-500',

                                'advisor' => 'border-l-green-500',

                                'supporter' => 'border-l-purple-500',

                            ];

                            $categoryIcons = [

                                'announcement' => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',

                                'special' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z',

                                'advisor' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',

                                'supporter' => 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155',

                            ];

                            $borderColor = $categoryColors[$notif->category] ?? 'border-l-gray-500';

                            $iconPath = $categoryIcons[$notif->category] ?? $categoryIcons['announcement'];

                        @endphp



                        <div
                            class="bg-background border border-border rounded-xl overflow-hidden {{ $borderColor }} border-l-4 transition-all hover:shadow-md">

                            <div class="p-5">

                                <div class="flex flex-col md:flex-row md:items-start gap-4">

                                    <!-- آیکون -->

                                    <div class="flex-shrink-0">

                                        <div class="w-12 h-12 rounded-full flex items-center justify-center

                                            {{ $notif->category === 'special' ? 'bg-orange-100 dark:bg-orange-900/30' : 'bg-blue-100 dark:bg-blue-900/30' }}">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"

                                                 class="w-6 h-6 {{ $notif->category === 'special' ? 'text-orange-500' : 'text-blue-500' }}">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="{{ $iconPath }}"/>

                                            </svg>

                                        </div>

                                    </div>


                                    <!-- محتوا -->

                                    <div class="flex-grow">

                                        <div
                                            class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-1">

                                            <h3 class="font-bold text-foreground">{{ $notif->title }}</h3>

                                            <div class="flex items-center gap-2">

                                                <span class="text-xs text-muted">

                                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($recipient->created_at)->ago() }}

                                                </span>

                                                @if(!$recipient->is_read)

                                                    <span
                                                        class="px-2 py-0.5 text-xs font-bold bg-red-500 text-white rounded-full">جدید</span>

                                                @endif

                                            </div>

                                        </div>


                                        <!-- محتوای پیام -->

                                        <div class="bg-muted/30 dark:bg-muted/10 rounded-lg p-4 mb-4">

                                            <p class="text-sm text-muted leading-relaxed whitespace-pre-line">{{ $notif->body }}</p>

                                        </div>


                                        <!-- دکمه‌ها -->

                                        <div class="flex items-center justify-between">

                                            <div class="flex items-center gap-2">

                                                @if($recipient->is_read)

                                                    <button disabled

                                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-xs font-medium cursor-default">

                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                             class="w-4 h-4">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M4.5 12.75l6 6 9-13.5"/>

                                                        </svg>

                                                        خواندن

                                                    </button>

                                                @else

                                                    <button wire:click="markAsRead({{ $recipient->id }})"

                                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-primary-foreground rounded-full text-xs font-medium hover:opacity-90 transition-opacity">

                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                             class="w-4 h-4">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M4.5 12.75l6 6 9-13.5"/>

                                                        </svg>

                                                        خواندن

                                                    </button>

                                                @endif

                                            </div>


                                            @if($recipient->is_read && $recipient->read_at)

                                                <span class="text-xs text-muted">

                                                    خوانده شده در {{ \Morilog\Jalali\Jalalian::fromDateTime($recipient->read_at)->format('H:i - Y/m/d') }}

                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="flex flex-col items-center justify-center py-16">

                            <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35 mb-6"
                                 alt="..."/>

                            <div class="text-center space-y-2">

                                <h2 class="font-bold text-xl text-foreground">پیامی وجود ندارد</h2>

                                <p class="text-muted text-sm">در حال حاضر پیامی در این بخش برای شما وجود ندارد.</p>

                            </div>

                        </div>

                    @endforelse

                </div>

                <!-- end section:notifications:wrapper -->


                <!-- Pagination -->

                @if($notifications->hasPages())

                    <div class="p-5 text-xs text-muted whitespace-nowrap">

                        {{ $notifications->links('layouts.client.pagination') }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
