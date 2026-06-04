<div class="hidden md:block"
     x-data="{ unreadCount: {{ $unreadCount }} }"
     x-on:notification-read.window="if (unreadCount > 0) { unreadCount--; }">
    <div class="flex items-center gap-5 mb-5">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden bg-secondary from-blue-100  flex items-center justify-center">
                @if($profilePictureUrl)
                    <img src="{{ $profilePictureUrl }}" class="w-full h-full object-cover rounded-full" alt="avatar">
                @elseif($this->defaultAvatarType === 'female')
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-pink-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-pink-500">
                        <!-- سر -->
                        <circle cx="12" cy="7" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <!-- بدن -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21c0-3.866 2.91-7 6.5-7s6.5 3.134 6.5 7"/>
                        <!-- موهای بلند (برای تمایز) -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 6.5C8.5 5.5 9 4 12 4s3.5 1.5 3.5 2.5"/>
                    </svg>
                @endif
            </div>
            <div class="flex flex-col items-start space-y-1">
                <span class="text-xs text-muted"> سلام ! </span>
                <div
                    class="line-clamp-1 font-semibold text-sm text-foreground cursor-default">{{auth()->user()->name}}
                    <span class="text-xs text-muted">عزیز</span>
                </div>
            </div>
        </div>
    </div>
    <ul class="flex flex-col space-y-3 bg-secondary rounded-2xl p-5">
        @if(auth()->user()?->isSchoolStudent())
            <li>
                <a wire:navigate href="{{ route('client.profile.school.dashboard') }}"
                   class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.school.dashboard') ? 'bg-primary text-primary-foreground px-4' : 'bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 12 2.25 21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>
                    </svg>
                    <span class="font-semibold text-xs">داشبورد</span>
                </a>
            </li>
            <li>
                <a wire:navigate href="{{ route('client.profile.school.report.index') }}"
                   class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.school.report.index') ? 'bg-primary text-primary-foreground px-4' : 'bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25"/>
                    </svg>
                    <span class="font-semibold text-xs">گزارش‌های من</span>
                </a>
            </li>
            <li>
                <a wire:navigate href="{{ route('client.profile.school.report.create') }}"
                   class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.school.report.create') ? 'bg-primary text-primary-foreground px-4' : 'bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span class="font-semibold text-xs">ثبت گزارش جدید</span>
                </a>
            </li>
        @else
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
                <div class="relative">
                    <svg class="w-6 h-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.5">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z"/>
                    </svg>

                    <template x-if="unreadCount > 0">
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            <span
                                class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white font-bold text-[8px]"
                                x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </span>
                    </template>
                </div>
                <span class="font-semibold text-xs flex items-center gap-2">
                    اطلاع رسانی

                </span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.consultation.sessions')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.consultation.sessions') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
            <a wire:navigate href="{{route('client.profile.consultation.class-schedule')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.consultation.class-schedule') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="font-semibold text-xs">برنامه کلاسی</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.appointment')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.appointment') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="font-semibold text-xs">تعیین وقت جلسه</span>
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
            <a wire:navigate href="{{route('client.profile.studySession')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.studySession') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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

                <span class="font-semibold text-xs">ثبت ساعت مطالعه</span>
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
            <a wire:navigate href="{{route('client.profile.typed-exam.list')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.typed-exam.list') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
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
            <a wire:navigate href="{{route('client.profile.typed-exam.list')}}?tab=essay"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="w-5 h-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span class="font-semibold text-xs">آزمون تشریحی</span>
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
            <a wire:navigate href="{{route('client.profile.classification.projects')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.classification') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3">
                    </path>
                </svg>
                <span class="font-semibold text-xs">طبقه‌بندی دروس</span>
            </a>
        </li>
        <li>

            <a wire:navigate href="{{route('client.profile.wallet')}}"

               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.wallet') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">

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


                <span class="font-semibold text-xs">کیف پول</span>

            </a>

        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.financial')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.financial') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">

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
        @endif
    </ul>

</div>
