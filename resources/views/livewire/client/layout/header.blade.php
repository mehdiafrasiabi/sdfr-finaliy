<div>


    <div class="max-w-7xl relative px-4 mx-auto">

        <div class="flex items-center gap-8 h-20">
            <div class="flex items-center gap-3">
                <!-- offcanvas:button -->
                <button type="button"
                        class="lg:hidden inline-flex items-center justify-center relative w-10 h-10 bg-secondary rounded-full text-foreground"
                        x-on:click="offcanvasOpen = true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <!-- end offcanvas:button -->
                <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary">

                        <span class="flex flex-col items-start">
                        <img src="/client/assets/images/theme/intro/header.png" width="90px">
                        </span>
                </a>
            </div>
            <div class="lg:flex hidden items-center gap-5">
                <!-- categories -->
                <div class="relative group/categories">
                    <a href="{{route('client.home')}}"
                       class="inline-flex items-center gap-1 text-muted transition-colors hover:text-foreground">
                        <span class="font-semibold">صفحه اصلی</span>

                    </a>

                </div>
                <!-- end categories -->

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
                                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">\
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
                                    <a href="{{route('client.blog.ExamQuestion')}}" wire:navigate
                                       class="flex items-center relative text-foreground transition-colors hover:text-primary p-3">
                                        <span class="font-semibold text-sm">نمونه سوالات امتحانی</span>

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
                <!-- end menu -->
            </div>

            <div class="flex items-center md:gap-5 gap-3 mr-auto">
                <!-- darkMode:button -->
                <div type="button"
                     id="dark-mode-button">

                </div>
                <!-- end darkMode:button -->

                <!-- openSearchBox:button -->

                <!-- end openSearchBox:button -->
                @if(\Illuminate\Support\Facades\Auth::check())
                    <div
                        id="fullscreenBtn"
                        style="cursor: pointer"
                        class="inline-flex items-center justify-center relative w-10 h-10 bg-secondary rounded-full text-foreground ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-maximize w-5 h-5">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                        </svg>
                    </div>
                    <a href="{{route('client.checkout.cart')}}" wire:navigate
                       class="inline-flex items-center justify-center relative w-10 h-10 bg-secondary rounded-full text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                        </svg>
                        <span class="absolute -top-1 left-0 flex h-5 w-5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span
                                    class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">{{ $cart }}</span>
                            </span>
                    </a>
                @endif

                @if(\Illuminate\Support\Facades\Auth::check())
                    <!-- user:dropdown -->
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
                            <span class="text-foreground transition-transform"
                                  x-bind:class="isOpen ? 'rotate-180' : ''">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </span>
                        </button>
                        <div class="absolute top-full left-0 pt-3" x-show="isOpen"
                             x-on:click.outside="isOpen = false">
                            <div
                                class="w-56 bg-background border border-border rounded-xl shadow-2xl shadow-black/5 p-3">
                                <a href="{{route('client.profile.dashboard')}}"
                                   class="flex items-center gap-2 w-full text-foreground transition-colors hover:text-primary px-3 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
                                    </svg>
                                    <span class="font-semibold text-xs">مشاهده پروفایل</span>
                                </a>

                                <a href="{{route('client.profile.plan')}}"
                                   class="flex items-center gap-2 w-full text-foreground transition-colors hover:text-primary px-3 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                                    </svg>
                                    <span class="font-semibold text-xs">برنامه و گزارش های من</span>
                                </a>
                                <button type="button"
                                        class="flex items-center gap-2 w-full text-red-500 transition-colors hover:text-red-700 px-3 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                                    </svg>
                                    <a href="{{route('client.logout')}}" class="font-semibold text-xs">خروج از
                                        حساب</a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- end user:dropdown -->
                @else
                    <!-- login-register:button -->
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
                    <!-- end login-register:button -->
                @endif


            </div>
        </div>

        <!-- searchBox -->

        <!-- end searchBox -->
    </div>
    <!-- end container -->
    <!-- offcanvas -->
    <div x-cloak>
        <!-- offcanvas:box -->
        <div
            class="fixed inset-y-0 right-0 xs:w-80 w-72 h-screen bg-background rounded-l-2xl overflow-y-auto transition-transform z-50"
            x-bind:class="offcanvasOpen ? '!translate-x-0' : 'translate-x-full'">
            <!-- offcanvas:header -->
            <div class="flex items-center justify-between gap-x-4 sticky top-0 bg-background p-4 z-10">
                <a href="{{route('client.home')}}" class="inline-flex items-center gap-2 text-primary">

                        <span class="flex flex-col items-start">
                                <img src="/client/assets/images/theme/intro/header.png" width="90px">
                            </span>
                </a>

                <!-- offcanvas:close-button -->
                <button x-on:click="offcanvasOpen = false"
                        class="text-foreground focus:outline-none hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button><!-- end offcanvas:close-button -->
            </div><!-- end offcanvas header -->

            <!-- offcanvas:content -->
            <div class="space-y-5 p-4">

                <div class="h-px bg-border"></div>
                <input type="hidden" id="dark-mode-checkbox"/>

                @if(\Illuminate\Support\Facades\Auth::check())

                    <ul class="flex flex-col space-y-1 bg-secondary rounded-l-2xl p-5">
                        <li class="">
                            <a href="{{route('client.home')}}"
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg" strokeWidth="2" class="w-5 h-5">
                                    <path
                                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">صفحه اصلی</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.blog')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     class="w-5 h-5"
                                     xmlns="http://www.w3.org/2000/svg" strokewidth="2" strokeWidth="2">
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
                                    <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">مقالات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.blog.ExamQuestion')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     class="w-5 h-5"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 4H18C18.5304 4 19.0391 4.21071 19.4142 4.58579C19.7893 4.96086 20 5.46957 20 6V20C20 20.5304 19.7893 21.0391 19.4142 21.4142C19.0391 21.7893 18.5304 22 18 22H6C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20V6C4 5.46957 4.21071 4.96086 4.58579 4.58579C4.96086 4.21071 5.46957 4 6 4H8"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M15 2H9C8.44772 2 8 2.44772 8 3V5C8 5.55229 8.44772 6 9 6H15C15.5523 6 16 5.55229 16 5V3C16 2.44772 15.5523 2 15 2Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <line x1="15" y1="13" x2="9" y2="13" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"></line>
                                    <line x1="15" y1="17" x2="9" y2="17" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"></line>
                                </svg>
                                <span class="font-semibold text-xs">نمونه سوال امتحانی</span>
                            </a>
                        </li>
                        <hr class="border-dashed">
                        <li>
                            <a wire:navigate href="{{route('client.profile.dashboard')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  {{ request()->routeIs('client.profile.dashboard') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }} ">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     strokeWidth="2">
                                    <path
                                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                </svg>

                                <span class="font-semibold text-xs">
                                         پیشخوان
                                </span>

                            </a>
                        </li>

                        <li>
                            <a wire:navigate href="{{route('client.profile.notification')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.notification') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                          d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-xs">
                                         اطلاع رسانی
                       @if($unreadCount > 0)
                                        <span class="absolute  ">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                        <span
                                            class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">
                                         {{ $unreadCount }}
                                        </span>
                              </span>
                                    @endif
                </span> </a>

                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.professionalTools.index')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.professionalTools.index') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-package w-5 h-5">
                                    <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                <span class="font-semibold text-xs">ابزار های حرفه ای</span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.reportStudentStudy')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border {{ request()->routeIs('client.profile.reportStudentStudy') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-file-text w-5 h-5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span class="font-semibold text-xs">کارنامه وضعیت ماهانه </span>
                            </a>
                        </li>
                        <hr>
                        <li>
                            <a wire:navigate href="{{route('client.profile.plan')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.plan') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-book-open w-5 h-5">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                                <span class="font-semibold text-xs">برنامه های مطالعاتی </span>
                            </a>
                        </li>

                        <li>
                            <a wire:navigate href="{{route('client.profile.meetGoogle')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.meetGoogle') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5">
                                    <path d="M8 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M12 17V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path
                                        d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">اتاق مشاوره</span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.report')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  {{ request()->routeIs('client.profile.report') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
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
                                    <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5"
                                          stroke-miterlimit="10"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5"
                                          stroke-miterlimit="10"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">گزارش های درسی </span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.typed-exam.list')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border {{ request()->routeIs('client.profile.typed-exam.list') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-edit w-5 h-5">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <span class="font-semibold text-xs">ازمون ها</span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.classification.projects')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border {{ request()->routeIs('client.profile.classification.projects') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="w-5 h-5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <span class="font-semibold text-xs">طبقه بندی</span>
                            </a>
                        </li>
                        <hr>
                        <li>
                            <a wire:navigate href="{{route('client.profile.financial')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  {{ request()->routeIs('client.profile.financial') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3">
                                    </path>
                                </svg>
                                <span class="font-semibold text-xs">تراکنش های مالی</span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.installment')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  {{ request()->routeIs('client.profile.installment') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     strokeWidth="2">
                                    <path
                                        d="M22 6V8.42C22 10 21 11 19.42 11H16V4.01C16 2.9 16.91 2 18.02 2C19.11 2.01 20.11 2.45 20.83 3.17C21.55 3.9 22 4.9 22 6Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M2 7V21C2 21.83 2.94 22.3 3.6 21.8L5.31 20.52C5.71 20.22 6.27 20.26 6.63 20.62L8.29 22.29C8.68 22.68 9.32 22.68 9.71 22.29L11.39 20.61C11.74 20.26 12.3 20.22 12.69 20.52L14.4 21.8C15.06 22.29 16 21.82 16 21V4C16 2.9 16.9 2 18 2H7H6C3 2 2 3.79 2 6V7Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M9 13.0098H12" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M9 9.00977H12" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M5.99561 13H6.00459" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M5.99561 9H6.00459" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">اقساط شهریه </span>
                            </a>
                        </li>

                        <li>
                            <a wire:navigate href="{{route('client.profile.ticket')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  {{ request()->routeIs('client.profile.ticket') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     strokeWidth="2">
                                    <path
                                        d="M17.98 10.79V14.79C17.98 15.05 17.97 15.3 17.94 15.54C17.71 18.24 16.12 19.58 13.19 19.58H12.79C12.54 19.58 12.3 19.7 12.15 19.9L10.95 21.5C10.42 22.21 9.56 22.21 9.03 21.5L7.82999 19.9C7.69999 19.73 7.41 19.58 7.19 19.58H6.79001C3.60001 19.58 2 18.79 2 14.79V10.79C2 7.86001 3.35001 6.27001 6.04001 6.04001C6.28001 6.01001 6.53001 6 6.79001 6H13.19C16.38 6 17.98 7.60001 17.98 10.79Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M21.98 6.79001V10.79C21.98 13.73 20.63 15.31 17.94 15.54C17.97 15.3 17.98 15.05 17.98 14.79V10.79C17.98 7.60001 16.38 6 13.19 6H6.79004C6.53004 6 6.28004 6.01001 6.04004 6.04001C6.27004 3.35001 7.86004 2 10.79 2H17.19C20.38 2 21.98 3.60001 21.98 6.79001Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M13.4955 13.25H13.5045" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M9.9955 13.25H10.0045" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M6.4955 13.25H6.5045" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">تیکت و پشتیبانی</span>
                            </a>
                        </li>

                        <li>
                            <a wire:navigate href="{{route('client.profile.star')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.star') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                          d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-xs">ستاره ها (بزودی)</span>
                            </a>
                        </li>
                        <li>
                            <a wire:navigate href="{{route('client.profile.edit')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.edit') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125">
                                    </path>
                                </svg>
                                <span class="font-semibold text-xs">ویرایش پروفایل</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.logout')}}"
                               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full border border-border  border border-border  border border-border  border border-border  border border-border  border border-border  {{ request()->routeIs('client.profile.logout') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15">
                                    </path>
                                </svg>
                                <span class="font-semibold text-xs">خروج از حساب</span>
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="flex flex-col space-y-1">
                        <li>
                            <a href="{{route('client.home')}}"
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg" strokeWidth="2" class="w-5 h-5">
                                    <path
                                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">صفحه اصلی</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.shop')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-archive w-5 h-5">
                                    <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                    <rect x="1" y="3" width="22" height="5"></rect>
                                    <line x1="10" y1="12" x2="14" y2="12"></line>
                                </svg>
                                <span class="font-semibold text-xs">دوره ها </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.blog')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     class="w-5 h-5"
                                     xmlns="http://www.w3.org/2000/svg" strokewidth="2" strokeWidth="2">
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
                                    <path d="M7.69995 12H15.7" stroke="currentColor" stroke-width="1.5"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6.69995 16H14.7" stroke="currentColor" stroke-width="1.5"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">مقالات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.blog.ExamQuestion')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     class="w-5 h-5"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 4H18C18.5304 4 19.0391 4.21071 19.4142 4.58579C19.7893 4.96086 20 5.46957 20 6V20C20 20.5304 19.7893 21.0391 19.4142 21.4142C19.0391 21.7893 18.5304 22 18 22H6C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20V6C4 5.46957 4.21071 4.96086 4.58579 4.58579C4.96086 4.21071 5.46957 4 6 4H8"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M15 2H9C8.44772 2 8 2.44772 8 3V5C8 5.55229 8.44772 6 9 6H15C15.5523 6 16 5.55229 16 5V3C16 2.44772 15.5523 2 15 2Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <line x1="15" y1="13" x2="9" y2="13" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"></line>
                                    <line x1="15" y1="17" x2="9" y2="17" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round"></line>
                                </svg>
                                <span class="font-semibold text-xs">نمونه سوال امتحانی</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.course')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     class="w-5 h-5"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path d="M12 17V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round"></path>
                                    <path
                                        d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span class="font-semibold text-xs">دوره های آموزشی</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('client.about-us')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
                                    </path>
                                </svg>
                                <span class="font-semibold text-xs">درباره ما</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.contact-us')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                          d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.148a1.5 1.5 0 0 1 1.465 1.175l.716 3.223a1.5 1.5 0 0 1-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 0 0 6.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 0 1 1.767-1.052l3.223.716A1.5 1.5 0 0 1 18 15.352V16.5a1.5 1.5 0 0 1-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 0 1 2.43 8.326 13.019 13.019 0 0 1 2 5V3.5Z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold text-xs">ارتباط با ما</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('client.terms')}}" wire:navigate
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
                                    </path>
                                </svg>
                                <span class="font-semibold text-xs">قوانین و مقررات</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://survey.porsline.ir/s/stlcBHD8"
                               target="_blank"
                               class="w-full flex items-center gap-x-2 relative text-muted transition-all hover:text-foreground py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-users w-5 h-5">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="font-semibold text-xs">همکاری با مجموعه</span>
                            </a>
                        </li>
                    </ul>

                @endif
            </div>
            <!-- end offcanvas:content -->
        </div>
        <!-- end offcanvas:box -->

        <!-- offcanvas:overlay -->
        <div class="fixed inset-0 h-screen bg-secondary/80 cursor-pointer transition-all duration-1000 z-40"
             x-bind:class="offcanvasOpen ? 'opacity-100 visible' : 'opacity-0 invisible'"
             x-on:click="offcanvasOpen = false">
        </div><!-- end offcanvas:overlay -->
    </div>
    <!-- end offcanvas -->
    @push('script')
        <script>
            document.getElementById("fullscreenBtn").addEventListener("click", () => {
                let elem = document.documentElement; // کل صفحه

                if (!document.fullscreenElement) {
                    // ورود به تمام‌صفحه
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.mozRequestFullScreen) { // Firefox
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullscreen) { // Chrome, Safari
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) { // IE/Edge
                        elem.msRequestFullscreen();
                    }
                } else {
                    // خروج از تمام‌صفحه
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }
            });
        </script>
    @endpush

</div>
