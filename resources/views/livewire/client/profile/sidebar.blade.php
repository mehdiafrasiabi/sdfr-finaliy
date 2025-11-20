<div class="hidden md:block">
    <div class="flex items-center gap-5 mb-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden">
                <img src="{{ (auth()->check() && auth()->user()->picture && file_exists(public_path('user/img/'.auth()->id().'/'.auth()->user()->picture)))
                          ? asset('user/img/'.auth()->id().'/'.auth()->user()->picture)
                             : asset('client/assets/images/avatars/01.jpeg') }}"
                     class="w-full h-full object-cover"
                     alt="..."/>
            </div>
            <div class="flex flex-col items-start space-y-1">
                <span class="text-xs text-muted"> سلام !خوش اومدی  </span>
                <div
                    class="line-clamp-1 font-semibold text-sm text-foreground cursor-default">{{auth()->user()->name}}
                    <span class="text-xs text-muted">عزیز</span>
                </div>
            </div>
        </div>
    </div>
    <ul class="flex flex-col space-y-3 bg-secondary rounded-2xl p-5">
        <li>

            <a wire:navigate href="{{route('client.profile.dashboard')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full  {{ request()->routeIs('client.profile.dashboard') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }} ">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     strokeWidth="2">
                    <path
                        d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 17.9902V14.9902" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round"></path>
                </svg>

                <span class="font-semibold text-xs">
                    پیشخوان
                </span>

            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.notification')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.notification') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd"
                          d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z"
                          clip-rule="evenodd"></path>
                </svg>
                <span class="font-semibold text-xs" wire:poll.visible>
                    اطلاع رسانی
                       @if($unreadCount > 0)
                        <span class="absolute" >
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span
                                    class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs" >
                                 {{ $unreadCount }}
                                </span>
                      </span>
                    @endif
                </span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.professionalTools.index')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.professionalTools.index') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
            <a wire:navigate href="{{route('client.profile.plan')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.plan') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-book-open w-5 h-5">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span class="font-semibold text-xs">برنامه های مطالعاتی </span>
            </a>
        </li>

        <li>
            <a wire:navigate href="{{route('client.profile.meetGoogle')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.meetGoogle') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
                <span class="font-semibold text-xs">اتاق مشاوره</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.report')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.report') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">

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
                <span class="font-semibold text-xs">گزارش های درسی </span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.exam.list')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.exam.list') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-edit w-5 h-5">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span class="font-semibold text-xs">آزمون ها</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.reportStudentStudy')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.reportStudentStudy') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
        <li>
            <a wire:navigate href="{{route('client.profile.financial')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.financial') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.installment') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
                <span class="font-semibold text-xs">اقساط شهریه </span>
            </a>
        </li>

        <li>
            <a wire:navigate href="{{route('client.profile.ticket')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.ticket') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
                <span class="font-semibold text-xs">تیکت و پشتیبانی</span>
            </a>
        </li>

        <li>
            <a wire:navigate href="{{route('client.profile.star')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.star') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd"
                          d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                          clip-rule="evenodd"></path>
                </svg>
                <span class="font-semibold text-xs">ستاره ها (بزودی)</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.edit')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.edit') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.logout') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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

</div>

