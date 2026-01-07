<div>
    @if(\Illuminate\Support\Facades\Auth::check() && (request()->is('profile*') || request()->routeIs('client.profile.*')))

        <div x-data="{ servicesOpen: false, headerOpen: false }"

             x-init="$watch('servicesOpen', value => {

         if (value) {

             document.body.style.overflow = 'hidden';

         } else {

             document.body.style.overflow = '';

         }

     })"

             x-on:header-opened.window="headerOpen = true; servicesOpen = false"

             x-on:header-closed.window="headerOpen = false">


            <!-- Mobile Sticky Bottom Navigation -->

            <div x-show="!headerOpen"

                 x-transition:enter="transition ease-out duration-300"

                 x-transition:enter-start="opacity-0 translate-y-full"

                 x-transition:enter-end="opacity-100 translate-y-0"

                 x-transition:leave="transition ease-in duration-200"

                 x-transition:leave-start="opacity-100 translate-y-0"

                 x-transition:leave-end="opacity-0 translate-y-full"

                 class="md:hidden fixed bottom-0 left-0 right-0 z-50">

                <!-- Services Popup Overlay -->

                <div x-show="servicesOpen"

                     x-transition:enter="transition ease-out duration-300"

                     x-transition:enter-start="opacity-0"

                     x-transition:enter-end="opacity-100"

                     x-transition:leave="transition ease-in duration-200"

                     x-transition:leave-start="opacity-100"

                     x-transition:leave-end="opacity-0"

                     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40"

                     x-on:click="servicesOpen = false">

                </div>


                <!-- Services Popup Menu -->

                <div x-show="servicesOpen"

                     x-transition:enter="transition ease-out duration-300"

                     x-transition:enter-start="opacity-0 scale-50"

                     x-transition:enter-end="opacity-100 scale-100"

                     x-transition:leave="transition ease-in duration-200"

                     x-transition:leave-start="opacity-100 scale-100"

                     x-transition:leave-end="opacity-0 scale-50"

                     class="fixed bottom-24 left-1/2 -translate-x-1/2 z-50"

                     x-cloak>

                    <div class="relative w-48 h-48">

                        <!-- اتاق مشاوره - Top -->

                        <a href="{{ route('client.profile.consultation.sessions') }}" wire:navigate

                           class="absolute top-0 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 group">

                            <div
                                class="w-12 h-12 rounded-full bg-primary flex items-center justify-center shadow-lg shadow-primary/30 transition-transform group-hover:scale-110">

                                <svg class="w-5 h-5 text-primary-foreground" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2">

                                    <path
                                        d="M8 21H16M12 17V21M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z"
                                        stroke-linecap="round" stroke-linejoin="round"/>

                                </svg>

                            </div>

                            <span
                                class="text-xs font-semibold text-foreground bg-background/90 px-2 py-0.5 rounded-full">اتاق مشاوره</span>

                        </a>


                        <!-- برنامه - Right -->

                        <a href="{{ route('client.profile.plan') }}" wire:navigate

                           class="absolute top-1/2 right-0 -translate-y-1/2 flex flex-col items-center gap-1 group">

                            <div
                                class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 transition-transform group-hover:scale-110">

                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>

                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>

                                </svg>

                            </div>

                            <span
                                class="text-xs font-semibold text-foreground bg-background/90 px-2 py-0.5 rounded-full">برنامه</span>

                        </a>


                        <!-- گزارش - Bottom -->

                        <a href="{{ route('client.profile.report') }}" wire:navigate

                           class="absolute bottom-0 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 group">

                            <div
                                class="w-12 h-12 rounded-full bg-amber-500 flex items-center justify-center shadow-lg shadow-amber-500/30 transition-transform group-hover:scale-110">

                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5">

                                    <path
                                        d="M21.9299 6.76001L18.5599 20.29C18.3199 21.3 17.4199 22 16.3799 22H3.23989C1.72989 22 0.649901 20.5199 1.0999 19.0699L5.30989 5.55005C5.59989 4.61005 6.46991 3.95996 7.44991 3.95996H19.7499C20.6999 3.95996 21.4899 4.53997 21.8199 5.33997C22.0099 5.76997 22.0499 6.26001 21.9299 6.76001Z"
                                        stroke="currentColor" stroke-miterlimit="10"/>

                                    <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6" stroke="currentColor"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>

                                    <path d="M7.69995 12H15.7" stroke="currentColor" stroke-miterlimit="10"
                                          stroke-linecap="round" stroke-linejoin="round"/>

                                    <path d="M6.69995 16H14.7" stroke="currentColor" stroke-miterlimit="10"
                                          stroke-linecap="round" stroke-linejoin="round"/>

                                </svg>

                            </div>

                            <span
                                class="text-xs font-semibold text-foreground bg-background/90 px-2 py-0.5 rounded-full">گزارش</span>

                        </a>


                        <!-- آزمون - Left -->

                        <a href="{{ route('client.profile.typed-exam.list') }}" wire:navigate

                           class="absolute top-1/2 left-0 -translate-y-1/2 flex flex-col items-center gap-1 group">

                            <div
                                class="w-12 h-12 rounded-full bg-rose-500 flex items-center justify-center shadow-lg shadow-rose-500/30 transition-transform group-hover:scale-110">

                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>

                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>

                                </svg>

                            </div>

                            <span
                                class="text-xs font-semibold text-foreground bg-background/90 px-2 py-0.5 rounded-full">آزمون</span>

                        </a>

                    </div>

                </div>


                <!-- Bottom Navigation Bar -->

                <nav class="bg-background/95 backdrop-blur-xl border-t border-border shadow-lg shadow-black/10">

                    <div class="flex items-center justify-around h-16 px-2 max-w-lg mx-auto"
                         style="padding-bottom: env(safe-area-inset-bottom);">

                        <!-- داشبورد -->

                        <a href="{{ route('client.profile.dashboard') }}" wire:navigate

                           class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all {{ request()->routeIs('client.profile.dashboard') ? 'text-primary' : 'text-muted hover:text-foreground' }}">

                            <div class="relative">

                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">

                                    <path
                                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"/>

                                    <path d="M12 17.9902V14.9902"/>

                                </svg>

                                @if(request()->routeIs('client.profile.dashboard'))

                                    <span
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>

                                @endif

                            </div>

                            <span class="text-[10px] font-semibold">داشبورد</span>

                        </a>


                        <!-- اطلاع رسانی -->

                        <a href="{{ route('client.profile.notification') }}" wire:navigate

                           class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all {{ request()->routeIs('client.profile.notification') ? 'text-primary' : 'text-muted hover:text-foreground' }}">

                            <div class="relative">

                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">

                                    <path fill-rule="evenodd"
                                          d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z"
                                          clip-rule="evenodd"/>

                                </svg>

                                @if($unreadCount > 0)

                                    <span class="absolute -top-1 -right-1 flex h-4 w-4">

                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>

                                <span
                                    class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white font-bold text-[8px]">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>

                            </span>

                                @endif

                                @if(request()->routeIs('client.profile.notification'))

                                    <span
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>

                                @endif

                            </div>

                            <span class="text-[10px] font-semibold">اطلاع رسانی</span>

                        </a>


                        <!-- سرویس‌ها (Center Button with Favicon) -->

                        <button type="button"

                                x-on:click="servicesOpen = !servicesOpen"

                                class="relative flex items-center justify-center -mt-6">

                            <div
                                class="w-14 h-14 rounded-full bg-primary flex items-center justify-center shadow-lg shadow-primary/40 transition-all duration-300"

                                :class="servicesOpen ? 'rotate-45 scale-110' : ''">

                                <img src="/client/assets/images/favicon.svg"

                                     alt="سرویس‌ها"

                                     class="w-8 h-8 transition-transform duration-300"

                                     style="filter: brightness(0) invert(1);">

                            </div>

                            <span class="absolute -bottom-4 text-[10px] font-semibold text-foreground">سرویس‌ها</span>

                        </button>


                        <!-- تیکت و پشتیبانی -->

                        <a href="{{ route('client.profile.ticket') }}" wire:navigate

                           class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all {{ request()->routeIs('client.profile.ticket') ? 'text-primary' : 'text-muted hover:text-foreground' }}">

                            <div class="relative">

                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5">

                                    <path
                                        d="M17.98 10.79V14.79C17.98 15.05 17.97 15.3 17.94 15.54C17.71 18.24 16.12 19.58 13.19 19.58H12.79C12.54 19.58 12.3 19.7 12.15 19.9L10.95 21.5C10.42 22.21 9.56 22.21 9.03 21.5L7.82999 19.9C7.69999 19.73 7.41 19.58 7.19 19.58H6.79001C3.60001 19.58 2 18.79 2 14.79V10.79C2 7.86001 3.35001 6.27001 6.04001 6.04001C6.28001 6.01001 6.53001 6 6.79001 6H13.19C16.38 6 17.98 7.60001 17.98 10.79Z"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>

                                    <path
                                        d="M21.98 6.79001V10.79C21.98 13.73 20.63 15.31 17.94 15.54C17.97 15.3 17.98 15.05 17.98 14.79V10.79C17.98 7.60001 16.38 6 13.19 6H6.79004C6.53004 6 6.28004 6.01001 6.04004 6.04001C6.27004 3.35001 7.86004 2 10.79 2H17.19C20.38 2 21.98 3.60001 21.98 6.79001Z"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>

                                    <path d="M13.4955 13.25H13.5045" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"/>

                                    <path d="M9.9955 13.25H10.0045" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"/>

                                    <path d="M6.4955 13.25H6.5045" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"/>

                                </svg>

                                @if(request()->routeIs('client.profile.ticket'))

                                    <span
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>

                                @endif

                            </div>

                            <span class="text-[10px] font-semibold">پشتیبانی</span>

                        </a>


                        <!-- طبقه بندی -->

                        <a href="{{ route('client.profile.classification.projects') }}" wire:navigate

                           class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all {{ request()->routeIs('client.profile.classification.*') ? 'text-primary' : 'text-muted hover:text-foreground' }}">

                            <div class="relative">

                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>

                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>

                                </svg>

                                @if(request()->routeIs('client.profile.classification.*'))

                                    <span
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>

                                @endif

                            </div>

                            <span class="text-[10px] font-semibold">طبقه بندی</span>

                        </a>

                    </div>

                </nav>

            </div>


            <!-- Spacer for bottom navigation -->

            <div class="md:hidden h-20"></div>

        </div>

    @endif
</div>
