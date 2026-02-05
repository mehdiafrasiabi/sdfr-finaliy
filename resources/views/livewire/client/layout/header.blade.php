<div>
    @push('link')
        <style>
            [x-cloak] { display: none !important; }

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
                        <div class="flex items-center gap-3 text-center md:text-right">K
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
        <header class="header-main bg-background/95 backdrop-blur-xl border-b border-border transition-all duration-300"
                :class="{
                'fixed top-0 left-0 right-0 z-50 shadow-lg': isScrolled || offcanvasOpen,
                'relative z-30': !isScrolled && !offcanvasOpen
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
                            <div class="relative" x-data="{ isOpen: false }">
                                <button class="flex items-center sm:gap-3 gap-1" x-on:click="isOpen = !isOpen">
                                <span class="inline-flex items-center justify-center w-9 h-9 bg-secondary rounded-full text-foreground">
                                    <img src="{{ (auth()->check() && auth()->user()->picture && file_exists(public_path('user/img/'.auth()->id().'/'.auth()->user()->picture)))
                                        ? asset('user/img/'.auth()->id().'/'.auth()->user()->picture)
                                        : asset('client/assets/images/avatars/01.jpeg') }}"
                                         class="rounded-full w-full h-full object-cover">
                                </span>
                                    <span class="flex flex-col items-start text-xs space-y-1">
                                    <span class="font-semibold text-foreground">{{auth()->user()->name}} عزیز</span>
                                    <span class="font-semibold text-muted">خوش آمـــدی</span>
                                </span>
                                    <span class="text-foreground transition-transform" :class="isOpen ? 'rotate-180' : ''">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </span>
                                </button>
                                <div class="absolute top-full left-0 pt-3" x-show="isOpen" x-on:click.outside="isOpen = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0">
                                    <div class="w-56 bg-background border border-border rounded-xl shadow-2xl shadow-black/5 p-3">
                                        <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                           class="flex items-center gap-2 w-full text-foreground transition-colors hover:text-primary px-3 py-2 rounded-lg hover:bg-secondary/50">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.02 2.84L3.63 7.04C2.73 7.74 2 9.23 2 10.36V17.77C2 20.09 3.89 21.99 6.21 21.99H17.79C20.11 21.99 22 20.09 22 17.78V10.5C22 9.29 21.19 7.74 20.2 7.05L14.02 2.72C12.62 1.74 10.37 1.79 9.02 2.84Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 17.99V14.99" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="font-semibold text-xs">پنل کاربری</span>
                                        </a>
                                        <a wire:navigate href="{{route('client.profile.plan')}}"
                                           class="flex items-center gap-2 w-full text-foreground transition-colors hover:text-primary px-3 py-2 rounded-lg hover:bg-secondary/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                                            </svg>
                                            <span class="font-semibold text-xs">برنامه های مشاوره ای</span>
                                        </a>
                                        <a href="{{route('client.logout')}}"
                                           class="flex items-center gap-2 w-full text-red-500 transition-colors hover:text-red-700 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold text-xs">خروج از حساب کاربری</span>
                                        </a>
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
                        <a href="{{route('client.profile.dashboard')}}" wire:navigate
                           class="inline-flex items-center justify-center w-10 h-10 bg-secondary rounded-full text-foreground hover:bg-secondary/80 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                        </a>
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
        <div class="h-16 lg:h-20" x-show="isScrolled || offcanvasOpen" x-cloak></div>

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
                        <div class="p-4 space-y-2">
                            <div class="text-center py-8">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 dark:text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                    </svg>
                                </div>
                                <p class="text-muted mb-4">برای دسترسی به پنل کاربری وارد شوید</p>
                                <a href="{{route('client.auth.login')}}"
                                   class="inline-flex items-center justify-center gap-2 h-12 bg-primary rounded-xl text-primary-foreground transition-all hover:opacity-80 px-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold">ورود / ثبت‌نام</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Scroll to Top Button -->
        <div class="fixed bottom-24 left-6 z-30 lg:hidden transition-all duration-300"
             :class="(isScrolled && !offcanvasOpen) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4 pointer-events-none'">
            <button @click="scrollToTop()"
                    class="w-12 h-12 rounded-full bg-slate-800 dark:bg-slate-700 text-white shadow-lg flex items-center justify-center hover:bg-slate-700 dark:hover:bg-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
                </svg>
            </button>
        </div>
    </div>

    @push('script')
        <script>
            function mobileMenuHandler() {
                return {
                    offcanvasOpen: false,
                    isScrolled: false,
                    isMobile: false,
                    pwaBannerClosed: false,
                    bannersHidden: false,
                    isClosingMenu: false,

                    init() {
                        this.checkMobile();
                        this.checkScroll();

                        window.addEventListener('scroll', () => this.checkScroll(), { passive: true });

                        // Watch برای قفل اسکرول
                        this.$watch('offcanvasOpen', (value) => {
                            if (value) {
                                document.body.style.overflow = 'hidden';
                            } else {
                                document.body.style.overflow = '';
                            }
                        });
                    },

                    checkMobile() {
                        this.isMobile = window.innerWidth < 1024;
                    },

                    checkScroll() {
                        this.isScrolled = window.scrollY > 50;
                    },

                    handleResize() {
                        this.checkMobile();
                        if (!this.isMobile && this.offcanvasOpen) {
                            this.closeMenu();
                        }
                    },

                    toggleMenu() {
                        if (this.offcanvasOpen) {
                            this.closeMenu();
                        } else {
                            this.openMenu();
                        }
                    },

                    openMenu() {
                        // اگر اسکرول نشده، بنرها رو مخفی کن
                        if (!this.isScrolled) {
                            this.bannersHidden = true;
                        }

                        // منو رو باز کن
                        this.offcanvasOpen = true;
                    },

                    closeMenu() {
                        if (this.isClosingMenu) return;
                        this.isClosingMenu = true;

                        // اول منو رو ببند
                        this.offcanvasOpen = false;

                        // اگر اسکرول نشده، بعد از مکث بنرها رو نمایش بده
                        if (!this.isScrolled) {
                            setTimeout(() => {
                                this.bannersHidden = false;
                                this.isClosingMenu = false;
                            }, 350); // مکث کوچک
                        } else {
                            this.isClosingMenu = false;
                        }
                    },

                    scrollToTop() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                }
            }
        </script>

    @endpush
</div>
