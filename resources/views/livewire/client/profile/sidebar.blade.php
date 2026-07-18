<div class="hidden md:block"
     x-data="{ unreadCount: {{ $unreadCount }} }"
     x-on:notification-read.window="if (unreadCount > 0) { unreadCount--; }">

    <ul class="flex flex-col space-y-3 glass rounded-2xl p-5">
        <li>

            <a wire:navigate data-tour="sb-dashboard" href="{{route('client.profile.dashboard')}}"
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
            <a wire:navigate data-tour="sb-consultation" href="{{route('client.profile.consultation.sessions')}}"
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
            <a wire:navigate href="{{route('client.profile.advisor-chat')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.advisor-chat') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <div class="relative">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
                    </svg>
                    @if(!$advisorChatLocked && $advisorUnread > 0)
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white font-bold text-[8px]">{{ $advisorUnread > 9 ? '9+' : $advisorUnread }}</span>
                        </span>
                    @endif
                </div>
                <span class="font-semibold text-xs flex items-center gap-1.5">
                    ارتباط با مشاور
                    @if($advisorChatLocked)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5 text-muted">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    @endif
                </span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.advisor-change-request')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.advisor-change-request') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h6m-6 5h8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 3l4 4l-4 4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21l-4-4l4-4" />
                </svg>
                <span class="font-semibold text-xs">درخواست جابجایی مشاور</span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-plan" href="{{route('client.profile.plan')}}"
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
            <a wire:navigate data-tour="sb-report" href="{{route('client.profile.report')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.report') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5">
                    <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V5C15 5.55228 14.5523 6 14 6H10C9.44772 6 9 5.55228 9 5V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.5 12L10 13.5L12 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 12.5H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.5 17L10 18.5L12 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 17.5H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-semibold text-xs">گزارش های درسی </span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-exam" href="{{route('client.profile.typed-exam.list')}}"
               class="w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.typed-exam.list') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5">
                    <path d="M19 13V19C19 20.1046 18.1046 21 17 21H5C3.89543 21 3 20.1046 3 19V7C3 5.89543 3.89543 5 5 5H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17.5 3.5C18.3284 2.67157 19.6716 2.67157 20.5 3.5C21.3284 4.32843 21.3284 5.67157 20.5 6.5L11 16L7 17L8 13L17.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 5L19 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-semibold text-xs">آزمون ها</span>
            </a>
        </li>

            <li>
                <a wire:navigate href="{{route('client.profile.sample-questions')}}"
                   class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.sample-questions') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 16h6"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/>
                    </svg>
                    <span class="font-semibold text-xs">نمونه سوالات</span>
                </a>
            </li>
        <li>
            <a wire:navigate data-tour="sb-smart-report" href="{{route('client.profile.reportStudentStudy')}}"
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
                <span class="font-semibold text-xs">کارنامه هوشمند</span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-classification" href="{{route('client.profile.classification.projects')}}"
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

                <span class="font-semibold text-xs">امور مالی </span>
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
               class="w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.logout') ? 'bg-red-500 text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-red-500 hover:text-primary-foreground px-4' }}">
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
