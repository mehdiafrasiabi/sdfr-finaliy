<div>
    @php
        // (B1) رنگِ زمینه‌ی آواتار بر اساسِ جنسیت: پسر = primary، دختر = صورتی.
        $avatarBg = ($gender ?? null) === 'female' ? '#ec4899' : 'hsl(var(--primary))';
    @endphp
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
            class="header-main bg-background/95 backdrop-blur-xl border-b border-border transition-all duration-300 rounded-b-[24px] md:rounded-b-none"
            :class="{
        'fixed top-0 left-0 right-0 z-50 shadow-lg': isScrolled || offcanvasOpen || profileModalOpen,
        'relative z-30': !isScrolled && !offcanvasOpen && !profileModalOpen
    }">
            <div class="max-w-7xl relative px-4 mx-auto">
                <!-- Desktop Header -->
                <div class="hidden lg:flex items-center gap-8 h-20">
                    <div class="flex items-center gap-3">
                        <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary"
                           wire:ignore>
                            <img src="/client/assets/images/theme/intro/header.png" width="90px" alt="Logo">
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="flex items-center gap-5">
                        <div class="relative group/categories">
                            <a href="{{route('client.home')}}" wire:navigate
                               class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground @if(request()->routeIs('client.home')) text-primary @endif">
                                <span class="font-semibold">صفحه اصلی</span>
                            </a>
                        </div>
                        <div class="relative group/categories">
                            <a href="{{route('client.about-us')}}" wire:navigate
                               class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground @if(request()->routeIs('client.about-us')) text-primary @endif ">
                                <span class="font-semibold">درباره ما</span>
                            </a>
                        </div>
                        <div class="relative group/categories">
                            <a href="{{route('client.contact-us')}}" wire:navigate
                               class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground @if(request()->routeIs('client.contact-us')) text-primary @endif">
                                <span class="font-semibold">ارتباط با ما</span>
                            </a>
                        </div>
                        <div class="relative group/categories">
                            <a href="{{route('client.download')}}" wire:navigate
                               class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground @if(request()->routeIs('client.download')) text-primary @endif">
                                <span class="font-semibold">نصب اپلیکیشن</span>
                            </a>
                        </div>
                        <div class="relative group/categories">
                            <a href="{{route('client.terms')}}" wire:navigate
                               class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground @if(request()->routeIs('client.terms')) text-primary @endif">
                                <span class="font-semibold">قوانین و مقررات</span>
                            </a>
                        </div>
                        <ul class="flex items-center gap-5">

                            <div class="relative group/categories">

                                <div
                                    class="absolute right-0 top-full opacity-0 invisible transition-all group-hover/categories:opacity-100 group-hover/categories:visible pt-5 z-10">
                                    <ul class="flex flex-col relative w-56 min-h-[100px] bg-background border border-border shadow-2xl shadow-black/5 rounded-xl">

                                        <li class="group">
                                            <a
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">درباره ما</span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm"></span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm"></span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </ul>
                    </div>

                    <div class="flex items-center md:gap-5 gap-3 mr-auto">
                        <div type="button" id="dark-mode-button"></div>

                        @if(\Illuminate\Support\Facades\Auth::check())
                            <div class="relative">
                                <!-- Desktop Profile Button -->
                                <button class="flex items-center sm:gap-3 gap-1 group"
                                        @click="desktopProfileOpen = !desktopProfileOpen">
                  <span
                      style="background: {{ $avatarBg }}"
                      class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white ring-2 ring-transparent group-hover:ring-primary/20 transition-all overflow-hidden">
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
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </span>
                                </button>
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
                                        class="w-[280px] rounded-2xl bg-secondary shadow-2xl shadow-black/30 ring-1 ring-black/5 dark:ring-white/10 border border-slate-200 dark:border-slate-800 overflow-hidden">
                                        <!-- Header - Avatar & Name -->
                                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                                            <div class="flex items-center gap-3 mb-0">
                                                <div
                                                    style="background: {{ $avatarBg }}"
                                                    class="w-12 h-12 rounded-full text-white flex items-center justify-center overflow-hidden flex-shrink-0">
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


                                        <!-- Menu Items -->
                                        <div class="py-2">
                                            <!-- Panel -->
                                            <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     class="w-5 h-5 text-muted"
                                                     strokeWidth="2">
                                                    <path
                                                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M12 17.9902V14.9902" stroke="currentColor"
                                                          stroke-width="1.5" stroke-linecap="round"
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
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-muted">
                                                    <path d="M8 21H16" stroke="currentColor" stroke-width="2"
                                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12 17V21" stroke="currentColor" stroke-width="2"
                                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path
                                                        d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
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
                        <button @click="openProfileModal()" data-tour="m-menu"
                                style="background: {{ $avatarBg }}"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full text-white hover:opacity-90 transition-opacity overflow-hidden">
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

        <div class="pt-16 lg:pt-20" x-show="isScrolled || offcanvasOpen || profileModalOpen" x-cloak></div>
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



                            <a href="{{route('client.about-us')}}" wire:navigate @click="closeMenu()"
                               class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg"
                                             class="w-6 h-6 text-blue-600 dark:text-blue-400">
                                            <path
                                                d="M15 11C16.6569 11 18 9.65685 18 8C18 6.34315 16.6569 5 15 5C13.3431 5 12 6.34315 12 8C12 9.65685 13.3431 11 15 11Z"
                                                stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 20C10 17.7909 12.2386 16 15 16C17.7614 16 20 17.7909 20 20"
                                                  stroke="currentColor" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M9 10C10.3807 10 11.5 8.88071 11.5 7.5C11.5 6.11929 10.3807 5 9 5C7.61929 5 6.5 6.11929 6.5 7.5C6.5 8.88071 7.61929 10 9 10Z"
                                                stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"/>
                                            <path
                                                d="M4 19C4 17.067 5.79086 15.5 8 15.5C8.76835 15.5 9.48983 15.678 10.1 16"
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
                                            <path
                                                d="M8 2H14L20 8V20C20 21.105 19.105 22 18 22H8C6.895 22 6 21.105 6 20V4C6 2.895 6.895 2 8 2Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M14 2V8H20"
                                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path d="M9 14.5L10.5 16L13.5 13"
                                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path d="M9 10.5H15"
                                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
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
                                            <path
                                                d="M4 6.5H20C21.105 6.5 22 7.395 22 8.5V17.5C22 18.605 21.105 19.5 20 19.5H4C2.895 19.5 2 18.605 2 17.5V8.5C2 7.395 2.895 6.5 4 6.5Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M3.5 8.5L12 14.25L20.5 8.5"
                                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
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
                                    <div
                                        class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
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
                                                stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                            <path
                                                d="M13.5 3.5H17.5C18.052 3.5 18.5 3.948 18.5 4.5V8.5C18.5 9.052 18.052 9.5 17.5 9.5H13.5C12.948 9.5 12.5 9.052 12.5 8.5V4.5C12.5 3.948 12.948 3.5 13.5 3.5Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                            <path
                                                d="M6.5 14.5H10.5C11.052 14.5 11.5 14.948 11.5 15.5V19.5C11.5 20.052 11.052 20.5 10.5 20.5H6.5C5.948 20.5 5.5 20.052 5.5 19.5V15.5C5.5 14.948 5.948 14.5 6.5 14.5Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                            <path
                                                d="M13.5 14.5H17.5C18.052 14.5 18.5 14.948 18.5 15.5V19.5C18.5 20.052 18.052 20.5 17.5 20.5H13.5C12.948 20.5 12.5 20.052 12.5 19.5V15.5C12.5 14.948 12.948 14.5 13.5 14.5Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
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

        @if(\Illuminate\Support\Facades\Auth::check())
            <div x-cloak class="lg:hidden">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[60] transition-opacity duration-300"
                     :class="profileModalOpen ? 'opacity-100 visible' : 'opacity-0 invisible pointer-events-none'"
                     @click="profileModalOpen = false">
                </div>

                <!-- Modal Panel -->
                <div class="fixed inset-y-0 right-0 w-full z-[60]"
                     x-show="profileModalOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-250"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     dir="rtl">

                    <div class="glass h-full flex flex-col overflow-hidden">

                        <!-- Top bar: close button سمت راست -->
                        <div class="flex items-center justify-end pt-4 pb-2 px-4 shrink-0">
                            <button @click="profileModalOpen = false"
                                    class="w-8 h-8 rounded-full bg-foreground/10 hover:bg-foreground/20 flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2" stroke="currentColor" class="w-4 h-4 text-foreground/60">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- ─── بخش بالا: آواتار + نام + شماره ─── -->
                        <div class="px-5 pt-3 pb-4 shrink-0">
                            <div class="flex items-center gap-4">
                                {{-- آواتار --}}
                                <div
                                    style="background: {{ $avatarBg }}"
                                    class="w-16 h-16 rounded-full ring-2 ring-white/10 text-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($profilePictureUrl)
                                        <img src="{{ $profilePictureUrl }}"
                                             class="w-full h-full object-cover rounded-full" alt="avatar">
                                    @elseif($this->defaultAvatarType === 'female')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor"
                                             class="w-8 h-8 text-foreground/70">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 3.75a4.5 4.5 0 0 0-4.5 4.5v.334a4.5 4.5 0 1 0 9 0V8.25a4.5 4.5 0 0 0-4.5-4.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor"
                                             class="w-8 h-8 text-foreground/70">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                        </svg>
                                    @endif
                                </div>

                                {{-- نام و شماره --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-foreground font-bold text-base leading-tight">{{ auth()->user()->name ?? '' }}</h3>
                                        <a wire:navigate href="{{ route('client.profile.edit') }}"
                                           @click="profileModalOpen = false"
                                           class="w-7 h-7 rounded-lg bg-[#2b2b31] hover:bg-[#34343c] flex items-center justify-center flex-shrink-0 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="2" stroke="currentColor"
                                                 class="w-3.5 h-3.5 text-foreground/50">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Z"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <p class="text-foreground/50 text-sm font-mono"
                                       style="direction:ltr; text-align:right;">{{ auth()->user()->mobile ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- ─── محتوای اسکرول‌پذیر ─── -->
                        <div class="flex-1 overflow-y-auto px-4 pb-8 space-y-2.5">

                            @php
                                $icons = [
                                    'home'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"/><path d="M12 17.9902V14.9902" stroke-linecap="round" stroke-linejoin="round"/>',
                                    'bell'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z"/>',
                                    'screen'    => '<path d="M8 21H16" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 17V21" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z" stroke-linecap="round" stroke-linejoin="round"/>',
                                    'book'      => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                                    'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                                    'edit'      => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
                                    'file'      => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
                                    'layers'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/>',
                                    'money'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                                    'chat'      => '<path d="M17.98 10.79V14.79C17.98 15.05 17.97 15.3 17.94 15.54C17.71 18.24 16.12 19.58 13.19 19.58H12.79C12.54 19.58 12.3 19.7 12.15 19.9L10.95 21.5C10.42 22.21 9.56 22.21 9.03 21.5L7.82999 19.9C7.69999 19.73 7.41 19.58 7.19 19.58H6.79001C3.60001 19.58 2 18.79 2 14.79V10.79C2 7.86001 3.35001 6.27001 6.04001 6.04001C6.28001 6.01001 6.53001 6 6.79001 6H13.19C16.38 6 17.98 7.60001 17.98 10.79Z" stroke-linecap="round" stroke-linejoin="round"/>',
                                    'settings'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Z"/>',
                                ];

                                $menuGroups = [
                                    [
                                        'label' => 'پرتال',
                                        'items' => [
                                            ['label' => 'داشبورد',    'route' => route('client.profile.dashboard'),  'icon' => 'home'],
                                            ['label' => 'اطلاع رسانی',   'route' => route('client.profile.notification'), 'icon' => 'bell'],
                                        ],
                                    ],
                                    [
                                        'label' => 'SDFR',
                                        'items' => [
                                            ['label' => 'اتاق مشاوره',     'route' => route('client.profile.consultation.sessions'),   'icon' => 'screen'],
                                            ['label' => 'برنامه درسی',     'route' => route('client.profile.plan'),                    'icon' => 'book'],
                                            ['label' => 'گزارش درسی',      'route' => route('client.profile.report'),                  'icon' => 'clipboard'],
                                            ['label' => 'آزمون',           'route' => route('client.profile.typed-exam.list'),         'icon' => 'edit'],
                                            ['label' => 'کارنامه وضعیت',   'route' => route('client.profile.reportStudentStudy'),      'icon' => 'file'],
                                            ['label' => 'طبقه‌بندی دروس',  'route' => route('client.profile.classification.projects'), 'icon' => 'layers'],
                                        ],
                                    ],
                                    [
                                        'label' => 'مالی و پشتیبانی',
                                        'items' => [
                                            ['label' => 'امور مالی',        'route' => route('client.profile.financial'), 'icon' => 'money'],
                                            ['label' => 'پشتیبانی',         'route' => route('client.profile.ticket'),   'icon' => 'chat'],
                                            ['label' => 'ویرایش پروفایل',   'route' => route('client.profile.edit'),     'icon' => 'settings'],
                                        ],
                                    ],
                                ];
                            @endphp

                            @foreach($menuGroups as $group)
                                {{-- عنوان گروه --}}
                                <p class="text-foreground/40 text-xs font-bold text-right pt-2 pb-1 px-1">{{ $group['label'] }}</p>

                                {{-- کارت‌ها: هر آیتم باکس جداگانه --}}
                                <div class="space-y-2">
                                    @foreach($group['items'] as $item)
                                        <a wire:navigate href="{{ $item['route'] }}"
                                           @click="profileModalOpen = false"
                                           class="flex items-center gap-3 px-4 py-3.5 bg-[#2b2b31] rounded-2xl active:bg-[#34343c] hover:bg-[#34343c] transition-colors">
                                            {{-- ایکون --}}
                                            <div
                                                class="w-9 h-9 rounded-xl bg-secondary flex items-center justify-center flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor"
                                                     class="w-4 h-4 text-foreground/60">
                                                    {!! $icons[$item['icon']] ?? '' !!}
                                                </svg>
                                            </div>
                                            {{-- متن --}}
                                            <span
                                                class="text-sm font-semibold text-foreground">{{ $item['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach

                            {{-- خروج --}}
                            <div class="pt-2">
                                <a href="{{ route('client.logout') }}"
                                   class="flex items-center gap-3 bg-red-500/10 border border-red-500/20 rounded-2xl px-4 py-3.5 active:scale-[0.98] transition-transform">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-red-500/15 flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-red-400">خروج از حساب کاربری</span>
                                </a>
                            </div>

                            <div class="h-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Scroll to Top Button -->
    </div>
</div>
