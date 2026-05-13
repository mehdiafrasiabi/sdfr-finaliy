<div>
    @assets
        <style>

            /* انیمیشن‌های نرم */
            .banners-wrapper {
                transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            }

            /* هدر چسبان */
            .header-main.fixed {
                animation: slideDown 0.25s ease-out;
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* انیمیشن Profile Modal برای موبایل */
            @keyframes slideUpModal {
                from {
                    transform: translateY(100%);
                }
                to {
                    transform: translateY(0);
                }
            }
        </style>
    @endassets
    <div x-data="mobileMenuHandler()" x-init="init()" @resize.window="handleResize()">



        <!-- Header -->
        <header

            class="header-main bg-background/95 backdrop-blur-xl border-b border-border transition-all duration-300"
            :class="{
                'fixed top-0 left-0 right-0 z-50 shadow-lg': isScrolled || offcanvasOpen || profileModalOpen,
                'relative z-30': !isScrolled && !offcanvasOpen && !profileModalOpen
            }">
            <div class="max-w-7xl relative px-4 mx-auto">

                <!-- Desktop Header -->
                <div class="hidden lg:flex items-center gap-8 h-20">
                    <div class="flex items-center gap-3">
                        <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary" wire:ignore>
                            <img src="/client/assets/images/theme/intro/header.png" width="90px" alt="Logo">
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="flex items-center gap-5">
                        <div class="relative group/categories">
                            <a href="{{route('client.home')}}"
                               class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                                <span class="font-semibold">صفحه اصلی</span>
                            </a>
                        </div>
                        <ul class="flex items-center gap-5">
                            <li>
                                <a href="{{route('client.blog')}}" wire:navigate
                                   class="inline-flex text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold">مقالات</span>
                                </a>
                            </li>
                            <div class="relative group/categories">
                                <a href="#"
                                   class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold text-sm">لینک های مفید</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </a>
                                <div
                                    class="absolute right-0 top-full opacity-0 invisible transition-all group-hover/categories:opacity-100 group-hover/categories:visible pt-5 z-10">
                                    <ul class="flex flex-col relative w-56 min-h-[100px] bg-background border border-border shadow-2xl shadow-black/5 rounded-xl">

                                        <li class="group">
                                            <a href="{{route('client.about-us')}}" wire:navigate
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">درباره ما</span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a href="{{route('client.contact-us')}}" wire:navigate
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">ارتباط با ما</span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a href="{{route('client.download')}}" wire:navigate
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">نصب اپلیکیشن</span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a href="{{route('client.terms')}}" wire:navigate
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">قوانین و مقررات</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <li>
                                <a target="_blank" href="https://survey.porsline.ir/s/stlcBHD8"
                                   class="inline-flex text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold">همکاری با مجموعه</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center md:gap-5 gap-3 mr-auto">
                        <div type="button" id="dark-mode-button"></div>

                        @if(\Illuminate\Support\Facades\Auth::check())
                            <div class="relative">
                                <!-- Desktop Profile Button -->
                                <button class="flex items-center sm:gap-3 gap-1 group"
                                        @click="desktopProfileOpen = !desktopProfileOpen">
                  <span class="inline-flex items-center justify-center w-9 h-9 bg-secondary rounded-full text-foreground ring-2 ring-transparent group-hover:ring-primary/20 transition-all overflow-hidden">
                @if($profilePictureUrl)
                          <img src="{{ $profilePictureUrl }}" class="rounded-full w-full h-full object-cover"
                               alt="avatar">
                      @elseif($this->defaultAvatarType === 'female')
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                               stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3.75a4.5 4.5 0 0 0-4.5 4.5v.334a4.5 4.5 0 1 0 9 0V8.25a4.5 4.5 0 0 0-4.5-4.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                    </svg>
                      @else
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                               stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                          </svg>
                      @endif
            </span>
                                    <span class="flex flex-col items-start text-xs space-y-1">
                                        <span class="font-semibold text-foreground">{{auth()->user()->name}} عزیز</span>
                                        <span class="font-semibold text-muted">خوش آمـــدی</span>
                                    </span>
                                    <span class="text-foreground transition-transform duration-200"
                                          :class="desktopProfileOpen ? 'rotate-180' : ''">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </span>
                                </button>

                                <!-- Backdrop Overlay -->
                                <div x-show="desktopProfileOpen"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     @click="desktopProfileOpen = false"
                                     class="fixed inset-0 bg-slate-700/60 backdrop-blur-sm z-40">
                                </div>

                                <!-- Profile Dropdown Menu - Desktop - طراحی ساده مطابق تصویر -->
                                <div class="absolute left-0 pt-3 z-50"
                                     x-show="desktopProfileOpen"
                                     x-cloak
                                     @click.outside="desktopProfileOpen = false"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-4"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-4">

                                    <div
                                        class="w-[280px] rounded-2xl bg-white dark:bg-slate-500 shadow-2xl shadow-black/20 border border-slate-200 dark:border-slate-700 overflow-hidden">

                                        <!-- Header - Avatar & Name -->
                                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                                            <div class="flex items-center gap-3 mb-0">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                    @if($profilePictureUrl)
                                                        <img src="{{ $profilePictureUrl }}"
                                                             class="rounded-full w-full h-full object-cover"
                                                             alt="avatar">
                                                    @elseif($this->defaultAvatarType === 'female')
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
                                                             class="w-6 h-6 text-slate-500 dark:text-slate-200">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M12 3.75a4.5 4.5 0 0 0-4.5 4.5v.334a4.5 4.5 0 1 0 9 0V8.25a4.5 4.5 0 0 0-4.5-4.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
                                                             class="w-6 h-6 text-slate-500 dark:text-slate-200">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                                        </svg>
                                                    @endif                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm font-bold text-foreground truncate">{{auth()->user()->name}}</h3>
                                                    <p class="text-xs text-muted truncate">{{auth()->user()->mobile ?? ''}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stats -->
                                        <div
                                            class="px-5 py-3 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                            <div class="flex items-center justify-around">
                                                <!-- Coins -->
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-yellow-500">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0
           1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12
           c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182
           s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0
           9 9 0 0 1 18 0Z"></path>
                                    </svg>
                                                    </span>
                                                    <span class="text-xs font-bold text-orange-500">0 سکه</span>
                                                </div>
                                                <!-- Vision -->
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">

                                                        <path d="M3 7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7V17C21 19.2091 19.2091 21 17 21H7C4.79086 21 3 19.2091 3 17V7Z" stroke-linecap="round" stroke-linejoin="round"></path>

                                                        <path d="M12 12C12 10.3431 13.3431 9 15 9H20C20.5523 9 21 9.44772 21 10V14C21 14.5523 20.5523 15 20 15H15C13.3431 15 12 13.6569 12 12Z" stroke-linecap="round" stroke-linejoin="round"></path>

                                                        <path d="M15 12L15.1 12" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    <span class="text-xs font-bold text-blue-500">۰ تومان</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Menu Items -->
                                        <div class="py-2">
                                            <!-- Panel -->
                                            <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                     class="w-5 h-5 text-muted"
                                                     strokeWidth="2">
                                                    <path
                                                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                          stroke-linejoin="round"></path>
                                                </svg>
                                                <span class="text-sm font-semibold">پیشخوان</span>
                                            </a>

                                            <!-- Calendar -->
                                            <a wire:navigate href="{{route('client.profile.notification')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">

                                                <svg class="w-5 h-5 text-muted"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="1.5">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z"/>
                                                </svg>
                                                <span class="text-sm font-semibold">اطلاع رسانی</span>
                                            </a>

                                            <!-- Messages -->
                                            <a wire:navigate href="{{route('client.profile.consultation.sessions')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-muted">
                                                    <path d="M8 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12 17V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <span class="text-sm font-semibold">اتاق مشاوره</span>
                                            </a>
                                        </div>

                                        <!-- Logout -->
                                        <div class="border-t border-slate-200 dark:border-slate-700">
                                            <a href="{{route('client.logout')}}"
                                               class="flex items-center gap-3 px-5 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                                                </svg>
                                                <span class="text-sm font-bold">خروج از حساب کاربری</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{route('client.auth.login')}}"
                               class="inline-flex items-center justify-center gap-1 h-10 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="w-6 h-6">
                                    <path fill-rule="evenodd"
                                          d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm">پرتال دانش آموزی</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Mobile Header -->
                <div class="flex lg:hidden items-center justify-between h-16">

                    <!-- دکمه‌های سمت چپ -->
                    <div class="flex items-center gap-2">
                        <!-- دکمه منو -->
                        <button type="button"
                                class="inline-flex items-center justify-center w-10 h-10 bg-secondary rounded-full text-foreground hover:bg-secondary/80 transition-colors"
                                @click="toggleMenu()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      x-show="!offcanvasOpen"
                                      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      x-show="offcanvasOpen"
                                      x-cloak
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- لوگو - وسط -->
                    <a href="{{route('client.home')}}" class="absolute left-1/2 -translate-x-1/2" wire:ignore>
                        <img src="/client/assets/images/theme/intro/header.png" width="90px" alt="Logo">
                    </a>

                    <!-- دکمه پروفایل/ورود - سمت راست -->
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <button @click="openProfileModal()"
                                class="inline-flex items-center justify-center w-10 h-10 bg-secondary rounded-full text-foreground hover:bg-secondary/80 transition-colors overflow-hidden">
                            @if($profilePictureUrl)
                                <img src="{{ $profilePictureUrl }}" class="w-full h-full object-cover rounded-full"
                                     alt="avatar">
                            @elseif($this->defaultAvatarType === 'female')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 3.75a4.5 4.5 0 0 0-4.5 4.5v.334a4.5 4.5 0 1 0 9 0V8.25a4.5 4.5 0 0 0-4.5-4.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                            @endif
                        </button>
                    @else
                        <a href="{{route('client.auth.login')}}"
                           class="inline-flex items-center justify-center w-10 h-10 bg-secondary rounded-full text-foreground hover:bg-secondary/80 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Spacer for fixed header -->
        <div class="h-16 lg:h-20" x-show="isScrolled || offcanvasOpen || profileModalOpen" x-cloak></div>

        <!-- Mobile Menu Offcanvas -->
        <div x-cloak class="lg:hidden">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 transition-opacity duration-300"
                 :class="offcanvasOpen ? 'opacity-100 visible' : 'opacity-0 invisible pointer-events-none'"
                 @click="closeMenu()">
            </div>

            <!-- Menu Panel - زیر هدر -->
            <div
                class="fixed right-0 w-[85%] max-w-sm bg-background  shadow-2xl z-40 overflow-hidden transition-transform duration-300 ease-out"
                :class="offcanvasOpen ? 'translate-x-0' : 'translate-x-full'"
                :style="'top: 64px; height: calc(100vh - 64px);'">

                <!-- Menu Content -->
                <div class="overflow-y-auto h-full pb-20">
                        <div class="p-4 space-y-2">

                            <!-- بخش اول - لینک‌های سریع -->
                            <div class="space-y-1 pb-3 border-b border-border">
                                <a href="{{route('client.home')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 class="w-5 h-5 text-blue-600 dark:text-blue-400">
                                                <path
                                                    d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                                <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-foreground">صفحه اصلی</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="#" style="display:none"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        </div>
                                        <span
                                            class="text-sm font-semibold text-foreground">حذف شده</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="{{route('client.blog')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21.9299 6.76001L18.5599 20.29C18.3199 21.3 17.4199 22 16.3799 22H3.23989C1.72989 22 0.649901 20.5199 1.0999 19.0699L5.30989 5.55005C5.59989 4.61005 6.46991 3.95996 7.44991 3.95996H19.7499C20.6999 3.95996 21.4899 4.53997 21.8199 5.33997C22.0099 5.76997 22.0499 6.26001 21.9299 6.76001Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
                                                <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6"
                                                      stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9.67993 6.38049L10.7199 2.06055" stroke="currentColor"
                                                      stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                                <path d="M16.3799 6.38977L17.3199 2.0498" stroke="currentColor"
                                                      stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                                <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5"
                                                      stroke-miterlimit="10" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                                <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5"
                                                      stroke-miterlimit="10" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-foreground">مقالات</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="{{route('client.about-us')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 class="w-6 h-6 text-blue-600 dark:text-blue-400">

                                                <!-- سر نفر جلو -->
                                                <path d="M15 11C16.6569 11 18 9.65685 18 8C18 6.34315 16.6569 5 15 5C13.3431 5 12 6.34315 12 8C12 9.65685 13.3431 11 15 11Z"
                                                      stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"/>

                                                <!-- بدن نفر جلو -->
                                                <path d="M10 20C10 17.7909 12.2386 16 15 16C17.7614 16 20 17.7909 20 20"
                                                      stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"/>

                                                <!-- سر نفر عقب -->
                                                <path d="M9 10C10.3807 10 11.5 8.88071 11.5 7.5C11.5 6.11929 10.3807 5 9 5C7.61929 5 6.5 6.11929 6.5 7.5C6.5 8.88071 7.61929 10 9 10Z"
                                                      stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"/>

                                                <!-- بدن نفر عقب -->
                                                <path d="M4 19C4 17.067 5.79086 15.5 8 15.5C8.76835 15.5 9.48983 15.678 10.1 16"
                                                      stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"/>

                                            </svg>

                                        </div>
                                        <span class="text-sm font-semibold text-foreground">درباره ما</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="{{route('client.terms')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 class="w-6 h-6 text-blue-600 dark:text-blue-400">
                                                <path d="M8 2H14L20 8V20C20 21.105 19.105 22 18 22H8C6.895 22 6 21.105 6 20V4C6 2.895 6.895 2 8 2Z"
                                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M14 2V8H20"
                                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9 14.5L10.5 16L13.5 13"
                                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9 10.5H15"
                                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>

                                        </div>
                                        <span class="text-sm font-semibold text-foreground">قوانین و مقررات</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="{{route('client.contact-us')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 class="w-6 h-6 text-blue-600 dark:text-blue-400">
                                                <path d="M4 6.5H20C21.105 6.5 22 7.395 22 8.5V17.5C22 18.605 21.105 19.5 20 19.5H4C2.895 19.5 2 18.605 2 17.5V8.5C2 7.395 2.895 6.5 4 6.5Z"
                                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M3.5 8.5L12 14.25L20.5 8.5"
                                                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>

                                        </div>
                                        <span class="text-sm font-semibold text-foreground">ارتباط با ما</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>
                                <a href="{{route('client.download')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-blue-600 dark:text-blue-400"
                                            >
                                                <path
                                                    d="M6.5 3.5H10.5C11.052 3.5 11.5 3.948 11.5 4.5V8.5C11.5 9.052 11.052 9.5 10.5 9.5H6.5C5.948 9.5 5.5 9.052 5.5 8.5V4.5C5.5 3.948 5.948 3.5 6.5 3.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M13.5 3.5H17.5C18.052 3.5 18.5 3.948 18.5 4.5V8.5C18.5 9.052 18.052 9.5 17.5 9.5H13.5C12.948 9.5 12.5 9.052 12.5 8.5V4.5C12.5 3.948 12.948 3.5 13.5 3.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M6.5 14.5H10.5C11.052 14.5 11.5 14.948 11.5 15.5V19.5C11.5 20.052 11.052 20.5 10.5 20.5H6.5C5.948 20.5 5.5 20.052 5.5 19.5V15.5C5.5 14.948 5.948 14.5 6.5 14.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M13.5 14.5H17.5C18.052 14.5 18.5 14.948 18.5 15.5V19.5C18.5 20.052 18.052 20.5 17.5 20.5H13.5C12.948 20.5 12.5 20.052 12.5 19.5V15.5C12.5 14.948 12.948 14.5 13.5 14.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-foreground">نصب SDFR</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor"
                                         class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Mobile Profile Modal -->
        @if(\Illuminate\Support\Facades\Auth::check())
            <div x-cloak class="lg:hidden">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-slate-700/80 backdrop-blur-sm z-[60] transition-opacity duration-300"
                     :class="profileModalOpen ? 'opacity-100 visible' : 'opacity-0 invisible pointer-events-none'"
                     @click="profileModalOpen = false">
                </div>

                <!-- Modal Panel -->
                <div
                    class="fixed inset-x-0 bottom-0 z-[60] max-h-[90vh] overflow-hidden transition-transform duration-300 ease-out"
                    :class="profileModalOpen ? 'translate-y-0' : 'translate-y-full'"
                    x-init="$el.style.display = profileModalOpen ? 'block' : 'none'"
                    x-show="profileModalOpen">

                    <!-- Background with rounded corners -->
                    <div class="relative bg-slate-600 dark:bg-slate-700 rounded-t-[40px] shadow-2xl overflow-hidden">

                        <!-- SVG Pattern Background -->
                        <div class="absolute inset-0 opacity-10 pointer-events-none">
                            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="mobile-profile-pattern" x="0" y="0" width="40" height="40"
                                             patternUnits="userSpaceOnUse">
                                        <circle cx="20" cy="20" r="2" fill="white"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#mobile-profile-pattern)"/>
                            </svg>
                        </div>

                        <!-- Drag Handle -->
                        <div class="relative flex justify-center pt-4 pb-3" @click="profileModalOpen = false">
                            <div class="w-12 h-1.5 rounded-full bg-white/30 cursor-pointer"></div>
                        </div>

                        <!-- Content Container -->
                        <div class="relative px-6 pb-6">
                            <!-- Avatar & Info -->
                            <div class="flex flex-col items-center text-center mb-6">
                                <div
                                    class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm p-1 shadow-xl mb-4 ring-4 ring-white/20">
                                    <div
                                        class="w-full h-full rounded-full bg-slate-400 dark:bg-slate-500 flex items-center justify-center overflow-hidden">
                                        @if($profilePictureUrl)
                                            <img src="{{ $profilePictureUrl }}"
                                                 class="w-full h-full object-cover rounded-full" alt="avatar">
                                        @elseif($this->defaultAvatarType === 'female')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 3.75a4.5 4.5 0 0 0-4.5 4.5v.334a4.5 4.5 0 1 0 9 0V8.25a4.5 4.5 0 0 0-4.5-4.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <h3 class="text-white font-bold text-lg mb-1">{{ auth()->user()->name ??''}}</h3>
                                <span class="text-white/70 text-sm">{{ auth()->user()->mobile ?? '-' }}</span>
                            </div>

                            <!-- White Card -->
                            <div class="bg-white dark:bg-slate-100 rounded-3xl shadow-2xl p-5 mb-4">
                                <!-- View Profile Link -->
                                <p
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-center gap-2 text-blue-600 hover:text-blue-700 transition-colors py-2 mb-2">
                                    <span class="text-sm font-bold">اعتبار من </span>
                                </p>

                                <!-- Stats Row -->
                                <div class="flex items-center justify-around border-t border-slate-200 pt-4">
                                    <!-- Coins -->
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                            <span class="text-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-yellow-500">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0
           1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12
           c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182
           s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0
           9 9 0 0 1 18 0Z"></path>
                                    </svg>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-orange-500">0 سکه</p>
                                        </div>
                                    </div>

                                    <!-- Separator -->
                                    <div class="w-px h-12 bg-slate-200"></div>

                                    <!-- Vision -->
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">

                                                <path d="M3 7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7V17C21 19.2091 19.2091 21 17 21H7C4.79086 21 3 19.2091 3 17V7Z" stroke-linecap="round" stroke-linejoin="round"></path>

                                                <path d="M12 12C12 10.3431 13.3431 9 15 9H20C20.5523 9 21 9.44772 21 10V14C21 14.5523 20.5523 15 20 15H15C13.3431 15 12 13.6569 12 12Z" stroke-linecap="round" stroke-linejoin="round"></path>

                                                <path d="M15 12L15.1 12" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-blue-600">۰ تومان </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Scrollable Menu Container -->
                            <div class="max-h-[40vh] overflow-y-auto space-y-1">
                                <!-- Panel Maz -->
                                <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">پیشخوان</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         strokeWidth="2">
                                        <path
                                            d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                </a>

                                <!-- Calendar -->
                                <a wire:navigate href="{{route('client.profile.notification')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">اطلاع رسانی</span>
                                    <svg class="w-5 h-5"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z"/>
                                    </svg>
                                </a>
                                <!-- Calendar -->
                                <a wire:navigate href="{{route('client.profile.consultation.sessions')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">اتاق مشاوره</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5">
                                        <path d="M8 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M12 17V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path
                                            d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>

                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.studySession')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">ثبت ساعت مطالعه</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         class="w-5 h-5">

                                        <circle cx="10" cy="10" r="8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"/>

                                        <path d="M10 5v5h4"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.plan')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">برنامه درسی</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-book-open w-5 h-5">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                </a>
                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.report')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">گزارش درسی</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         strokeWidth="2">
                                        <path
                                            d="M21.9299 6.76001L18.5599 20.29C18.3199 21.3 17.4199 22 16.3799 22H3.23989C1.72989 22 0.649901 20.5199 1.0999 19.0699L5.30989 5.55005C5.59989 4.61005 6.46991 3.95996 7.44991 3.95996H19.7499C20.6999 3.95996 21.4899 4.53997 21.8199 5.33997C22.0099 5.76997 22.0499 6.26001 21.9299 6.76001Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"></path>
                                        <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6" stroke="currentColor"
                                              stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M9.67993 6.38049L10.7199 2.06055" stroke="currentColor" stroke-width="1.5"
                                              stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M16.3799 6.38977L17.3199 2.0498" stroke="currentColor" stroke-width="1.5"
                                              stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.typed-exam.list')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">آزمون</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-edit w-5 h-5">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>

                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.reportStudentStudy')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">کارنامه وضعیت</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text w-5 h-5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </a>

                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.classification.projects')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">طبقه بندی دروس</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3">
                                        </path>
                                    </svg>
                                </a>
                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.wallet')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">کیف پول</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5">

                                        <path d="M3 7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7V17C21 19.2091 19.2091 21 17 21H7C4.79086 21 3 19.2091 3 17V7Z"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"/>

                                        <path d="M12 12C12 10.3431 13.3431 9 15 9H20C20.5523 9 21 9.44772 21 10V14C21 14.5523 20.5523 15 20 15H15C13.3431 15 12 13.6569 12 12Z"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"/>

                                        <path d="M15 12L15.1 12"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                <a wire:navigate href="{{route('client.profile.financial')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">تراکنش های مالی</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         class="w-5 h-5">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0
           1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12
           c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182
           s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0
           9 9 0 0 1 18 0Z"/>
                                    </svg>

                                </a>
                                <a wire:navigate href="{{route('client.profile.installment')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">اقساط و شهریه</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         strokeWidth="2">
                                        <path
                                            d="M22 6V8.42C22 10 21 11 19.42 11H16V4.01C16 2.9 16.91 2 18.02 2C19.11 2.01 20.11 2.45 20.83 3.17C21.55 3.9 22 4.9 22 6Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M2 7V21C2 21.83 2.94 22.3 3.6 21.8L5.31 20.52C5.71 20.22 6.27 20.26 6.63 20.62L8.29 22.29C8.68 22.68 9.32 22.68 9.71 22.29L11.39 20.61C11.74 20.26 12.3 20.22 12.69 20.52L14.4 21.8C15.06 22.29 16 21.82 16 21V4C16 2.9 16.9 2 18 2H7H6C3 2 2 3.79 2 6V7Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M9 13.0098H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M9 9.00977H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M5.99561 13H6.00459" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M5.99561 9H6.00459" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>

                                </a>
                                <a wire:navigate href="{{route('client.profile.ticket')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">تیکت و پشتیبانی</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         strokeWidth="2">
                                        <path
                                            d="M17.98 10.79V14.79C17.98 15.05 17.97 15.3 17.94 15.54C17.71 18.24 16.12 19.58 13.19 19.58H12.79C12.54 19.58 12.3 19.7 12.15 19.9L10.95 21.5C10.42 22.21 9.56 22.21 9.03 21.5L7.82999 19.9C7.69999 19.73 7.41 19.58 7.19 19.58H6.79001C3.60001 19.58 2 18.79 2 14.79V10.79C2 7.86001 3.35001 6.27001 6.04001 6.04001C6.28001 6.01001 6.53001 6 6.79001 6H13.19C16.38 6 17.98 7.60001 17.98 10.79Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M21.98 6.79001V10.79C21.98 13.73 20.63 15.31 17.94 15.54C17.97 15.3 17.98 15.05 17.98 14.79V10.79C17.98 7.60001 16.38 6 13.19 6H6.79004C6.53004 6 6.28004 6.01001 6.04004 6.04001C6.27004 3.35001 7.86004 2 10.79 2H17.19C20.38 2 21.98 3.60001 21.98 6.79001Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M13.4955 13.25H13.5045" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M9.9955 13.25H10.0045" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M6.4955 13.25H6.5045" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                </a>

                                <a wire:navigate href="{{route('client.profile.star')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">ستاره ها(بزودی)</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.5"
                                         class="w-5 h-5">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 0 0 .95.69h4.154c.969 0 1.371 1.24.588 1.81l-3.36 2.441a1 1 0 0 0-.364 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.36-2.441a1 1 0 0 0-1.175 0l-3.36 2.441c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 0 0-.364-1.118L2.49 9.377c-.783-.57-.38-1.81.588-1.81h4.154a1 1 0 0 0 .95-.69l1.287-3.95Z"/>
                                    </svg>
                                </a>
                                <a wire:navigate href="{{route('client.profile.edit')}}"
                                   @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">ویرایش پروفایل</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125">
                                        </path>
                                    </svg>
                                </a>

                                <!-- Logout -->
                                <a href="{{route('client.logout')}}"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-red-500/20 hover:bg-red-500/30 backdrop-blur-sm transition-all active:scale-[0.98] text-white mt-2">
                                    <span class="font-semibold text-sm">خروج از حساب کاربری</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Scroll to Top Button -->

    </div>

</div>
