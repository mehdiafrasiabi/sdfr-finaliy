<div
    x-data="{
    offcanvasOpen: false,
    closing: false,

    savedScroll: 0,
    drawerTop: 0,

    beforeH: 0,
    headerShift: 0,

    measure() {
      this.beforeH = Math.round(this.$refs.beforeHeader?.getBoundingClientRect().height || 0);
      this.drawerTop = Math.round(this.$refs.mainHeader?.getBoundingClientRect().height || 0);
    },

    lockScroll() {
      this.savedScroll = window.scrollY || 0;

      // جلوگیری از تکان عرضی وقتی scrollbar حذف میشه (دسکتاپ)
      const sbw = window.innerWidth - document.documentElement.clientWidth;

      document.body.style.position = 'fixed';
      document.body.style.top = `-${this.savedScroll}px`;
      document.body.style.left = '0';
      document.body.style.right = '0';
      document.body.style.width = '100%';
      if (sbw > 0) document.body.style.paddingRight = `${sbw}px`;

      document.body.classList.add('touch-none');
    },

    unlockScroll() {
      const y = this.savedScroll || 0;

      document.body.style.position = '';
      document.body.style.top = '';
      document.body.style.left = '';
      document.body.style.right = '';
      document.body.style.width = '';
      document.body.style.paddingRight = '';
      document.body.classList.remove('touch-none');

      window.scrollTo(0, y);
    },

    openMenu() {
      this.measure();
      this.lockScroll();

      // اگر بالای صفحه‌ایم، هدر رو فقط ویژوال میاریم بالا (بدون reflow)
      this.headerShift = (this.savedScroll <= 1) ? this.beforeH : 0;

      this.offcanvasOpen = true;
    },

    closeMenu() {
      this.closing = true;
      this.offcanvasOpen = false;
    },

    onDrawerTransitionEnd(e) {
      // فقط وقتی transform تموم شد
      if (e.propertyName !== 'transform') return;

      if (this.closing) {
        this.closing = false;
        this.headerShift = 0; // هدر برگرده سرجاش
        this.unlockScroll();
      }
    },

    init() {
      this.$nextTick(() => this.measure());

      const ro = new ResizeObserver(() => this.measure());
      if (this.$refs.mainHeader) ro.observe(this.$refs.mainHeader);
      if (this.$refs.beforeHeader) ro.observe(this.$refs.beforeHeader);
    }
  }"
    @keydown.escape.window="closeMenu()"
    @resize.window="measure()"
>


    <div
        x-ref="beforeHeader"
        data-before-header
        class="transition-opacity duration-200"
        :class="offcanvasOpen ? 'opacity-0 pointer-events-none' : 'opacity-100'"
    >
        <livewire:client.update-countdown-banner/>

        <div class="mb-1"></div>
        <!-- PWA Banner -->
        <div id="pwaBanner" dir="rtl" class="hidden w-full relative z-20">
            <div
                class="w-full border-b border-slate-200/60 dark:border-slate-700/60
           bg-gradient-to-l from-blue-700 via-blue-600 to-indigo-700
           dark:from-slate-900 dark:via-slate-900 dark:to-slate-800
           text-white">
                <div
                    class="max-w-6xl mx-auto px-4 py-3 md:py-4 flex flex-col md:flex-row items-center justify-between gap-3">

                    <div class="flex items-center gap-3 text-center md:text-right">
                        <div
                            class="shrink-0 w-10 h-10 rounded-2xl bg-white/15 dark:bg-white/10 flex items-center justify-center shadow-inner">
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
                            <span
                                class="absolute inset-0 opacity-0 group-hover:opacity-100 transition bg-white/10"></span>
                        </button>

                        <button id="closeBanner" type="button"
                                class="rounded-full px-4 sm:px-5 py-2 text-sm font-bold
                 bg-rose-500 hover:bg-rose-400 active:scale-[0.98]
                 shadow-md shadow-rose-500/25 transition">
                            بستن
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Header با انیمیشن smooth -->
    <header
        x-ref="mainHeader"
        class="sticky top-0 z-[70] bg-background/80 backdrop-blur-xl border-b border-border
         transition-transform duration-200 will-change-transform"
        :style="headerShift ? `transform: translateY(-${headerShift}px)` : ''"
    >


        <div>
            <div class="max-w-7xl relative px-4 mx-auto">

                <!-- هدر اصلی -->
                <div class="flex items-center gap-8 h-20 relative">
                    <div class="flex items-center gap-3">
                        <!-- دکمه همبرگر منو -->
                        <button type="button"
                                class="lg:hidden inline-flex items-center justify-center relative w-10 h-10 bg-secondary rounded-full text-foreground
                                       transition-transform active:scale-95"
                                x-on:click="offcanvasOpen ? closeMenu() : openMenu()">

                            <!-- hamburger -->
                            <svg x-cloak x-show="!offcanvasOpen" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-6 h-6 transition-transform duration-300"
                                 :class="offcanvasOpen ? 'rotate-90 opacity-0' : 'rotate-0 opacity-100'">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>

                            <!-- close (X) -->
                            <svg x-cloak x-show="offcanvasOpen" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-6 h-6 transition-transform duration-300"
                                 :class="offcanvasOpen ? 'rotate-0 opacity-100' : '-rotate-90 opacity-0'">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <!-- لوگو -->
                        <a href="{{ route('client.home') }}"
                           class="inline-flex items-center text-primary justify-center
                          w-fit md:w-auto
                          absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2
                          lg:static lg:transform-none">
                            <span class="flex flex-col items-center">
                                <img src="/client/assets/images/theme/intro/header.png"
                                     class="mx-auto md:mx-0"
                                     width="130px">
                            </span>
                        </a>

                    </div>

                    <!-- منوی دسکتاپ -->
                    <div class="lg:flex hidden items-center gap-5">
                        <div class="lg:flex hidden items-center gap-5">
                            <!-- categories -->
                            <div class="relative group/categories">
                                <a href="{{route('client.home')}}"
                                   class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                                    <span class="font-semibold">صفحه اصلی</span>
                                </a>
                            </div>

                            <!-- menu -->
                            <ul class="flex items-center gap-5">
                                <li>
                                    <a href="{{route('client.shop')}}" wire:navigate
                                       class="inline-flex text-muted transition-colors hover:text-foreground">
                                        <span class="font-semibold">دوره ها </span>
                                    </a>
                                </li>
                                <div class="relative group/categories">
                                    <a href="#"
                                       class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                                        <span class="font-semibold text-sm">بلاگ</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </a>
                                    <div
                                        class="absolute right-0 top-full opacity-0 invisible transition-all group-hover/categories:opacity-100 group-hover/categories:visible pt-5 z-10">
                                        <ul
                                            class="flex flex-col relative w-56 min-h-[100px] bg-background border border-border shadow-2xl shadow-black/5">
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
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    </a>
                                    <div
                                        class="absolute right-0 top-full opacity-0 invisible transition-all group-hover/categories:opacity-100 group-hover/categories:visible pt-5 z-10">
                                        <ul
                                            class="flex flex-col relative w-56 min-h-[100px] bg-background border border-border shadow-2xl shadow-black/5">
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
                                    <a target="_blank"
                                       href="https://survey.porsline.ir/s/stlcBHD8"
                                       class="inline-flex text-muted transition-colors hover:text-foreground">
                                        <span class="font-semibold">همکاری با مجموعه</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- بخش راست هدر -->
                    <div class="flex items-center md:gap-5 gap-3 mr-auto">
                        <div type="button" id="dark-mode-button"></div>

                        @if(\Illuminate\Support\Facades\Auth::check())
                            <div class="relative" x-data="{ isOpen: false }">
                                <button class="flex items-center sm:gap-3 gap-1" x-on:click="isOpen = !isOpen">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 bg-secondary rounded-full text-foreground">
                                    <img src="{{ (auth()->check() && auth()->user()->picture && file_exists(public_path('user/img/'.auth()->id().'/'.auth()->user()->picture)))
                          ? asset('user/img/'.auth()->id().'/'.auth()->user()->picture)
                             : asset('client/assets/images/avatars/01.jpeg') }}"
                                         class="rounded rounded-full">
                                </span>
                                    <span class="xs:flex flex-col items-start hidden text-xs space-y-1">
                                    <span class="font-semibold text-foreground"> {{auth()->user()->name}} عزیز</span>
                                    <span class="font-semibold text-muted">خوش آمـــدی</span>
                                </span>
                                    <span class="text-foreground transition-transform duration-300"
                                          x-bind:class="isOpen ? 'rotate-180' : ''">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </span>
                                </button>
                                <div class="absolute top-full left-0 pt-3 transition-all duration-300"
                                     x-show="isOpen"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     x-on:click.outside="isOpen = false">
                                    <div
                                        class="w-56 bg-background border border-border rounded-xl shadow-2xl shadow-black/5 p-3">
                                        <a wire:navigate href="{{route('client.profile.dashboard')}}"
                                           class="flex items-center gap-2 w-full text-foreground transition-colors hover:text-primary px-3 py-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" strokewidth="2">
                                                <path
                                                    d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5"
                                                      stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <span class="font-semibold text-xs">پنل کاربری</span>
                                        </a>

                                        <a wire:navigate href="{{route('client.profile.plan')}}"
                                           class="flex items-center gap-2 w-full text-foreground transition-colors hover:text-primary px-3 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                                            </svg>
                                            <span class="font-semibold text-xs">برنامه های مشاوره ای</span>
                                        </a>
                                        <a href="{{route('client.logout')}}"
                                           class="flex items-center gap-2 w-full text-red-500 transition-colors hover:text-red-700 px-3 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd"
                                                      d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="font-semibold text-xs">
                                                خروج از حساب کاربری
                                            </div>
                                        </a>
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

            </div>
        </div>

    </header>

    <!-- منوی موبایل با انیمیشن بهبود یافته -->
    <div x-cloak>
        <!-- offcanvas:box -->
        <div
            dir="rtl"
            class="fixed right-0 w-80 max-w-[85vw] bg-background rounded-l-3xl overflow-y-auto
         transition-transform duration-300 ease-out will-change-transform
         z-[50] shadow-2xl"
            :class="offcanvasOpen ? '!translate-x-0' : 'translate-x-full'"
            :style="`top:${drawerTop}px;height:calc(100dvh - ${drawerTop}px);`"
            @transitionend="onDrawerTransitionEnd($event)"
        >


        @if(\Illuminate\Support\Facades\Auth::check())
                <div class="p-4 space-y-2">

                    <!-- بخش اول -->
                    <div class="space-y-1 pb-3 border-b border-border">
                        <a href="{{route('client.shop')}}" wire:navigate @click="closeMenu()"
                           class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" strokewidth="2" class="w-5 h-5 text-blue-600 dark:text-blue-400">
                                        <path
                                            d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"></path>
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
                        <a href="{{route('client.shop')}}" wire:navigate @click="closeMenu()"
                           class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-archive w-5 h-5 text-blue-600 dark:text-blue-400">
                                        <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                        <rect x="1" y="3" width="22" height="5"></rect>
                                        <line x1="10" y1="12" x2="14" y2="12"></line>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-foreground">  فروشگاه سال تحصیلی ۱۴۰۴-۱۴۰۵</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="2" stroke="currentColor"
                                 class="w-5 h-5 text-muted group-hover:text-foreground transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </a>
                        <a href="{{route('client.shop')}}" wire:navigate @click="closeMenu()"
                           class="flex items-center justify-between px-4 py-3.5 rounded-2xl hover:bg-secondary/50 transition-all group">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                         xmlns="http://www.w3.org/2000/svg" strokewidth="2">
                                        <path
                                            d="M21.9299 6.76001L18.5599 20.29C18.3199 21.3 17.4199 22 16.3799 22H3.23989C1.72989 22 0.649901 20.5199 1.0999 19.0699L5.30989 5.55005C5.59989 4.61005 6.46991 3.95996 7.44991 3.95996H19.7499C20.6999 3.95996 21.4899 4.53997 21.8199 5.33997C22.0099 5.76997 22.0499 6.26001 21.9299 6.76001Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"></path>
                                        <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6"
                                              stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                              stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9.67993 6.38049L10.7199 2.06055" stroke="currentColor"
                                              stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M16.3799 6.38977L17.3199 2.0498" stroke="currentColor"
                                              stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5"
                                              stroke-miterlimit="10" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                        <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5"
                                              stroke-miterlimit="10" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
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

                    </div>

                    <!-- بخش دوم -->
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </span>
                            <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">
                                صفحه شخصی
                            </span>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">
                                کارنامه وضعیت تحصیلی
                            </span>
{{--                            <span class="w-10 shrink-0 flex items-center justify-center">--}}
{{--                                <span--}}
{{--                                    class="w-9 h-9 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center">--}}
{{--                                    11--}}
{{--                                </span>--}}
{{--                            </span>--}}
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                                    <polyline points="17 2 12 7 7 2"></polyline>
                                </svg>
                            </span>
                            <span class="flex-1 text-center text-sm @if($active) font-bold @else font-semibold @endif">تراکنش های مالی</span>
                            <span class="w-10 shrink-0"></span>
                        </a>





                    </div>
                </div>
            @else
                <div class="p-4 space-y-2">
                    <!-- مهمان -->
                </div>
            @endif
        </div>

        <!-- offcanvas:overlay -->
        <div
            class="fixed right-0 left-0 bottom-0 bg-secondary/80 cursor-pointer z-[40]
                   transition-all duration-300 ease-out"
            x-bind:class="offcanvasOpen ? 'opacity-100 visible' : 'opacity-0 invisible'"
            x-bind:style="`top:${drawerTop}px;`"
            x-on:click="closeMenu()">
        </div>
    </div>
</div>
