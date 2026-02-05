<div>
    @push('link')
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

    @endpush
    <div x-data="mobileMenuHandler()" x-init="init()" @resize.window="handleResize()">

        <!-- Banners Section -->
        <div class="banners-wrapper transition-all duration-300 ease-out overflow-hidden"
             :class="{
             'max-h-0 opacity-0': bannersHidden,
             'max-h-40 opacity-100': !bannersHidden
         }">

            <!-- PWA Banner -->
            <div id="pwaBanner" dir="rtl" class="w-full relative z-20" x-show="!pwaBannerClosed">
                <div class="w-full border-b border-slate-200/60 dark:border-slate-700/60
               bg-gradient-to-l from-blue-700 via-blue-600 to-indigo-700
               dark:from-slate-900 dark:via-slate-900 dark:to-slate-800
               text-white">
                    <div class="max-w-6xl mx-auto px-4 py-3 md:py-4 flex flex-col md:flex-row items-center justify-between gap-3">
                        <div class="flex items-center gap-3 text-center md:text-right">
                            <div class="shrink-0 w-10 h-10 rounded-2xl bg-white/15 dark:bg-white/10 flex items-center justify-center shadow-inner">
                                <span class="text-xl">📱</span>
                            </div>
                            <div>
                                <p class="font-extrabold text-base sm:text-lg md:text-xl leading-snug">
                                    همین حالا <span class="text-yellow-300">SDFR</span> رو روی موبایلت داشته باش
                                </p>
                                <p class="text-xs sm:text-sm text-white/80 dark:text-white/70 mt-0.5">
                                    نصب سریع، دسترسی راحت، تجربه بهتر ✨
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <button id="installApp" type="button"
                                    class="group relative overflow-hidden rounded-full px-4 sm:px-5 py-2 text-sm font-bold
                                       bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98]
                                       shadow-md shadow-emerald-500/30 transition">
                                <span class="relative z-10">نصب اپلیکیشن</span>
                            </button>
                            <button @click="pwaBannerClosed = true" type="button"
                                    class="rounded-full px-4 sm:px-5 py-2 text-sm font-bold
                                       bg-rose-500 hover:bg-rose-400 active:scale-[0.98]
                                       shadow-md shadow-rose-500/25 transition">
                                نمی‌خوام
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                        <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary">
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
                                <a href="{{route('client.shop')}}" wire:navigate
                                   class="inline-flex text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold">دوره ها</span>
                                </a>
                            </li>
                            <div class="relative group/categories">
                                <a href="#"
                                   class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold text-sm">بلاگ</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </a>
                                <div class="absolute right-0 top-full opacity-0 invisible transition-all group-hover/categories:opacity-100 group-hover/categories:visible pt-5 z-10">
                                    <ul class="flex flex-col relative w-56 min-h-[100px] bg-background border border-border shadow-2xl shadow-black/5 rounded-xl">
                                        <li class="group">
                                            <a href="{{route('client.blog')}}" wire:navigate
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">مقالات</span>
                                            </a>
                                        </li>
                                        <li class="group">
                                            <a href="{{route('client.course')}}" wire:navigate
                                               class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                                <span class="font-semibold text-sm">دوره های آموزشی</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="relative group/categories">
                                <a href="#"
                                   class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold text-sm">لینک های مفید</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </a>
                                <div class="absolute right-0 top-full opacity-0 invisible transition-all group-hover/categories:opacity-100 group-hover/categories:visible pt-5 z-10">
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
                                <button class="flex items-center sm:gap-3 gap-1 group" @click="desktopProfileOpen = !desktopProfileOpen">
            <span class="inline-flex items-center justify-center w-9 h-9 bg-secondary rounded-full text-foreground ring-2 ring-transparent group-hover:ring-primary/20 transition-all">
                <img src="{{ (auth()->check() && auth()->user()->picture && file_exists(public_path('user/img/'.auth()->id().'/'.auth()->user()->picture)))
                    ? asset('user/img/'.auth()->id().'/'.auth()->user()->picture)
                    : asset('client/assets/images/avatars/01.jpeg') }}"
                     class="rounded-full w-full h-full object-cover">
            </span>
                                    <span class="flex flex-col items-start text-xs space-y-1">
                <span class="font-semibold text-foreground">{{auth()->user()->name}} عزیز</span>
                <span class="font-semibold text-muted">خوش آمـــدی</span>
            </span>
                                    <span class="text-foreground transition-transform duration-200" :class="desktopProfileOpen ? 'rotate-180' : ''">
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

                                    <div class="w-[280px] rounded-2xl bg-white dark:bg-slate-800 shadow-2xl shadow-black/20 border border-slate-200 dark:border-slate-700 overflow-hidden">

                                        <!-- Header - Avatar & Name -->
                                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                    <img src="{{ (auth()->check() && auth()->user()->picture && file_exists(public_path('user/img/'.auth()->id().'/'.auth()->user()->picture)))
                                ? asset('user/img/'.auth()->id().'/'.auth()->user()->picture)
                                : asset('client/assets/images/avatars/01.jpeg') }}"
                                                         class="rounded-full w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm font-bold text-foreground truncate">{{auth()->user()->name}}</h3>
                                                    <p class="text-xs text-muted truncate">{{auth()->user()->mobile ?? ''}}</p>
                                                </div>
                                            </div>

                                            <!-- View Profile Link -->
                                            <a href="{{route('client.profile.dashboard')}}" wire:navigate
                                               class="flex items-center justify-center gap-1 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors py-1.5">
                                                <span class="text-sm font-bold">مشاهده پروفایل</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                                </svg>
                                            </a>
                                        </div>

                                        <!-- Stats -->
                                        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                            <div class="flex items-center justify-around">
                                                <!-- Coins -->
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg">🪙</span>
                                                    <span class="text-xs font-bold text-orange-500">۱۸۰۰ سکه</span>
                                                </div>
                                                <!-- Vision -->
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-blue-500">۰ ویژن</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Menu Items -->
                                        <div class="py-2">
                                            <!-- Panel Maz -->
                                            <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-muted">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                                </svg>
                                                <span class="text-sm font-semibold">پنل ماز</span>
                                            </a>

                                            <!-- Calendar -->
                                            <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-muted">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                                </svg>
                                                <span class="text-sm font-semibold">تقویم آموزشی</span>
                                            </a>

                                            <!-- Messages -->
                                            <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                               class="flex items-center gap-3 px-5 py-2.5 text-foreground hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-muted">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                                </svg>
                                                <span class="text-sm font-semibold">پیام‌های من</span>
                                            </a>
                                        </div>

                                        <!-- Logout -->
                                        <div class="border-t border-slate-200 dark:border-slate-700">
                                            <a href="{{route('client.logout')}}"
                                               class="flex items-center gap-3 px-5 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
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
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
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
                    <a href="{{route('client.home')}}" class="absolute left-1/2 -translate-x-1/2">
                        <img src="/client/assets/images/theme/intro/header.png" width="90px" alt="Logo">
                    </a>

                    <!-- دکمه پروفایل/ورود - سمت راست -->
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <button @click="openProfileModal()"
                                class="inline-flex items-center justify-center w-10 h-10 bg-secondary rounded-full text-foreground hover:bg-secondary/80 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                        </button>
                    @else
                        <a href="{{route('client.auth.login')}}"
                           class="inline-flex items-center justify-center w-10 h-10 bg-secondary rounded-full text-foreground hover:bg-secondary/80 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
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
            <div class="fixed right-0 w-[85%] max-w-sm bg-background  shadow-2xl z-40 overflow-hidden transition-transform duration-300 ease-out"
                 :class="offcanvasOpen ? 'translate-x-0' : 'translate-x-full'"
                 :style="'top: 64px; height: calc(100vh - 64px);'">

                <!-- Menu Content -->
                <div class="overflow-y-auto h-full pb-20">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <div class="p-4 space-y-2">

                            <!-- بخش اول - لینک‌های سریع -->
                            <div class="space-y-1 pb-3 border-b border-border">
                                <a href="{{route('client.home')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                                                <path d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-foreground">صفحه اصلی</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="{{route('client.shop')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                                                <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                                <rect x="1" y="3" width="22" height="5"></rect>
                                                <line x1="10" y1="12" x2="14" y2="12"></line>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-foreground">فروشگاه سال تحصیلی ۱۴۰۴-۱۴۰۵</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>

                                <a href="{{route('client.blog')}}" wire:navigate @click="closeMenu()"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21.9299 6.76001L18.5599 20.29C18.3199 21.3 17.4199 22 16.3799 22H3.23989C1.72989 22 0.649901 20.5199 1.0999 19.0699L5.30989 5.55005C5.59989 4.61005 6.46991 3.95996 7.44991 3.95996H19.7499C20.6999 3.95996 21.4899 4.53997 21.8199 5.33997C22.0099 5.76997 22.0499 6.26001 21.9299 6.76001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
                                                <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9.67993 6.38049L10.7199 2.06055" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M16.3799 6.38977L17.3199 2.0498" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-foreground">مقالات</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </a>
                            </div>

                            <!-- بخش دوم - منوی اصلی -->
                            <div class="pt-4 space-y-3">
                                @php($active = request()->routeIs('client.profile.dashboard*'))
                                <a href="{{route('client.profile.dashboard')}}" wire:navigate @click="closeMenu()"
                                    @class([
                                      'relative flex items-center w-full rounded-2xl border px-4 py-3 transition-all',
                                      'border-slate-200/70 dark:border-slate-700/70 bg-background hover:bg-secondary/40 text-foreground' => !$active,
                                      'border-blue-200/80 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 shadow-sm' => $active,
                                      "after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-right-[10px]
                                       after:border-t-[10px] after:border-b-[10px] after:border-l-[10px]
                                       after:border-t-transparent after:border-b-transparent after:border-l-blue-500
                                       dark:after:border-l-blue-400" => $active,
                                    ])>
                            <span class="w-10 shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </span>
                                    <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">صفحه شخصی</span>
                                    <span class="w-10 shrink-0"></span>
                                </a>

                                @php($active = request()->routeIs('client.profile.reportStudentStudy'))
                                <a href="{{route('client.profile.reportStudentStudy')}}" wire:navigate @click="closeMenu()"
                                    @class([
                                      'relative flex items-center w-full rounded-2xl border px-4 py-3 transition-all',
                                      'border-slate-200/70 dark:border-slate-700/70 bg-background hover:bg-secondary/40 text-foreground' => !$active,
                                      'border-blue-200/80 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 shadow-sm' => $active,
                                      "after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-right-[10px]
                                       after:border-t-[10px] after:border-b-[10px] after:border-l-[10px]
                                       after:border-t-transparent after:border-b-transparent after:border-l-blue-500
                                       dark:after:border-l-blue-400" => $active,
                                    ])>
                            <span class="w-10 shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                                    <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">کارنامه وضعیت تحصیلی</span>
                                    <span class="w-10 shrink-0"></span>
                                </a>

                                @php($active = request()->routeIs('client.profile.installment'))
                                <a href="{{route('client.profile.installment')}}" wire:navigate @click="closeMenu()"
                                    @class([
                                      'relative flex items-center w-full rounded-2xl border px-4 py-3 transition-all',
                                      'border-slate-200/70 dark:border-slate-700/70 bg-background hover:bg-secondary/40 text-foreground' => !$active,
                                      'border-blue-200/80 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 shadow-sm' => $active,
                                      "after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-right-[10px]
                                       after:border-t-[10px] after:border-b-[10px] after:border-l-[10px]
                                       after:border-t-transparent after:border-b-transparent after:border-l-blue-500
                                       dark:after:border-l-blue-400" => $active,
                                    ])>
                            <span class="w-10 shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                            </span>
                                    <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">پرداخت قسطی</span>
                                    <span class="w-10 shrink-0"></span>
                                </a>

                                @php($active = request()->routeIs('client.profile.wallet'))
                                <a href="{{route('client.profile.wallet')}}" wire:navigate @click="closeMenu()"
                                    @class([
                                      'relative flex items-center w-full rounded-2xl border px-4 py-3 transition-all',
                                      'border-slate-200/70 dark:border-slate-700/70 bg-background hover:bg-secondary/40 text-foreground' => !$active,
                                      'border-blue-200/80 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 shadow-sm' => $active,
                                      "after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-right-[10px]
                                       after:border-t-[10px] after:border-b-[10px] after:border-l-[10px]
                                       after:border-t-transparent after:border-b-transparent after:border-l-blue-500
                                       dark:after:border-l-blue-400" => $active,
                                    ])>
                            <span class="w-10 shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                            </span>
                                    <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">کیف پول</span>
                                    <span class="w-10 shrink-0"></span>
                                </a>

                                @php($active = request()->routeIs('client.profile.financial'))
                                <a href="{{route('client.profile.financial')}}" wire:navigate @click="closeMenu()"
                                    @class([
                                      'relative flex items-center w-full rounded-2xl border px-4 py-3 transition-all',
                                      'border-slate-200/70 dark:border-slate-700/70 bg-background hover:bg-secondary/40 text-foreground' => !$active,
                                      'border-blue-200/80 dark:border-blue-500/30 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 shadow-sm' => $active,
                                      "after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:-right-[10px]
                                       after:border-t-[10px] after:border-b-[10px] after:border-l-[10px]
                                       after:border-t-transparent after:border-b-transparent after:border-l-blue-500
                                       dark:after:border-l-blue-400" => $active,
                                    ])>
                            <span class="w-10 shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                                    <polyline points="17 2 12 7 7 2"></polyline>
                                </svg>
                            </span>
                                    <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">تراکنش های مالی</span>
                                    <span class="w-10 shrink-0"></span>
                                </a>
                            </div>

                            <!-- Divider -->
                            <div class="h-px bg-border my-4"></div>

                            <!-- Logout -->
                            <a href="{{route('client.logout')}}"
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold text-sm hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                                </svg>
                                خروج از حساب
                            </a>
                        </div>
                    @else

                        <!-- بخش اول - لینک‌های سریع -->
                        <div class="space-y-1 pb-3 border-b border-border">
                            <a href="{{route('client.home')}}" wire:navigate @click="closeMenu()"
                               class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                                            <path d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-foreground">صفحه اصلی</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                </svg>
                            </a>

                            <a href="{{route('client.shop')}}" wire:navigate @click="closeMenu()"
                               class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                                            <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                            <rect x="1" y="3" width="22" height="5"></rect>
                                            <line x1="10" y1="12" x2="14" y2="12"></line>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-foreground">فروشگاه سال تحصیلی ۱۴۰۴-۱۴۰۵</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                </svg>
                            </a>

                            <a href="{{route('client.blog')}}" wire:navigate @click="closeMenu()"
                               class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.9299 6.76001L18.5599 20.29C18.3199 21.3 17.4199 22 16.3799 22H3.23989C1.72989 22 0.649901 20.5199 1.0999 19.0699L5.30989 5.55005C5.59989 4.61005 6.46991 3.95996 7.44991 3.95996H19.7499C20.6999 3.95996 21.4899 4.53997 21.8199 5.33997C22.0099 5.76997 22.0499 6.26001 21.9299 6.76001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
                                            <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9.67993 6.38049L10.7199 2.06055" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16.3799 6.38977L17.3199 2.0498" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-foreground">مقالات</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                </svg>
                            </a>
                        </div>
                    @endif
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
                <div class="fixed inset-x-0 bottom-0 z-[60] max-h-[90vh] overflow-hidden transition-transform duration-300 ease-out"
                     :class="profileModalOpen ? 'translate-y-0' : 'translate-y-full'"
                     x-init="$el.style.display = profileModalOpen ? 'block' : 'none'"
                     x-show="profileModalOpen">

                    <!-- Background with rounded corners -->
                    <div class="relative bg-slate-600 dark:bg-slate-700 rounded-t-[40px] shadow-2xl overflow-hidden">

                        <!-- SVG Pattern Background -->
                        <div class="absolute inset-0 opacity-10 pointer-events-none">
                            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="mobile-profile-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
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
                                <div class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm p-1 shadow-xl mb-4 ring-4 ring-white/20">
                                    <div class="w-full h-full rounded-full bg-slate-400 dark:bg-slate-500 flex items-center justify-center overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-white font-bold text-lg mb-1">امیرمحمد نیک نام</h3>
                                <span class="text-white/70 text-sm">۰۹۰۳۳۶۱۷۶۰</span>
                            </div>

                            <!-- White Card -->
                            <div class="bg-white dark:bg-slate-100 rounded-3xl shadow-2xl p-5 mb-4">
                                <!-- View Profile Link -->
                                <a href="{{route('client.profile.dashboard')}}" wire:navigate @click="profileModalOpen = false"
                                   class="flex items-center justify-center gap-2 text-blue-600 hover:text-blue-700 transition-colors py-2 mb-4">
                                    <span class="text-sm font-bold">مشاهده پروفایل</span>
                                </a>

                                <!-- Stats Row -->
                                <div class="flex items-center justify-around border-t border-slate-200 pt-4">
                                    <!-- Coins -->
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                            <span class="text-xl">🪙</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-orange-500">۱۸۰۰ سکه</p>
                                        </div>
                                    </div>

                                    <!-- Separator -->
                                    <div class="w-px h-12 bg-slate-200"></div>

                                    <!-- Vision -->
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-blue-600">۰ ویژن</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Scrollable Menu Container -->
                            <div class="max-h-[40vh] overflow-y-auto space-y-1">
                                <!-- Panel Maz -->
                                <a wire:navigate href="{{route('client.profile.dashboard')}}" @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">پنل ماز</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                    </svg>
                                </a>

                                <!-- Calendar -->
                                <a wire:navigate href="{{route('client.profile.dashboard')}}" @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">تقویم آموزشی</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                </a>

                                <!-- Messages -->
                                <a wire:navigate href="{{route('client.profile.dashboard')}}" @click="profileModalOpen = false"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] text-white">
                                    <span class="font-semibold text-sm">پیام‌های من</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                    </svg>
                                </a>

                                <!-- Logout -->
                                <a href="{{route('client.logout')}}"
                                   class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-red-500/20 hover:bg-red-500/30 backdrop-blur-sm transition-all active:scale-[0.98] text-white mt-2">
                                    <span class="font-semibold text-sm">خروج از حساب کاربری</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Scroll to Top Button -->
        <div class="fixed bottom-24 left-6 z-30 lg:hidden transition-all duration-300"
             :class="(isScrolled && !offcanvasOpen && !profileModalOpen) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4 pointer-events-none'">
            <button @click="scrollToTop()"
                    class="w-12 h-12 rounded-full bg-slate-800 dark:bg-slate-700 text-white shadow-lg flex items-center justify-center hover:bg-slate-700 dark:hover:bg-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
                </svg>
            </button>
        </div>
    </div>

    @push('script')


    @endpush
</div>
