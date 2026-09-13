<div class="hidden md:block"
     x-data="{ unreadCount: {{ $unreadCount }} }"
     x-on:notification-read.window="if (unreadCount > 0) { unreadCount--; }">

    <ul class="flex flex-col space-y-3 glass rounded-2xl p-5">
        <li>

            <a wire:navigate data-tour="sb-dashboard" href="{{route('client.profile.dashboard')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full  {{ request()->routeIs('client.profile.dashboard') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }} "
               data-elevated="false">

                {{-- آیکون «خانه» توی دیکشنری Keyline نیست (x-ui.icon) — همون SVG
                     اختصاصیِ قبلی نگه داشته شد تا آیکون حدسی/غیرواقعی جایگزینش نشه. --}}
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
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.notification') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <div class="relative">
                    <x-ui.icon name="bell" class="w-6 h-6"/>

                    <template x-if="unreadCount > 0">
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                            <span
                                class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-error text-error-foreground font-bold text-[8px]"
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
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.consultation.sessions') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="monitor" class="w-5 h-5"/>
                <span class="font-semibold text-xs">اتاق مشاوره</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.advisor-chat')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.advisor-chat') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <div class="relative">

                    {{-- آیکون «حباب گفتگو» توی دیکشنری Keyline نیست، همون SVG قبلی نگه داشته شد. --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3L18 3C19.6569 3 21 4.3431 21 6L21 14C21 15.6569 19.6569 17 18 17L7 17L3 21L3 6C3 4.3431 4.3431 3 6 3Z" fill="none"/>
                    </svg>
                    @if(!$advisorChatLocked && $advisorUnread > 0)
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                            <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-error text-error-foreground font-bold text-[8px]">{{ $advisorUnread > 9 ? '9+' : $advisorUnread }}</span>
                        </span>
                    @endif
                </div>
                <span class="font-semibold text-xs flex items-center gap-1.5">
                    ارتباط با مشاور
                    @if($advisorChatLocked)
                        <x-ui.icon name="lock" class="w-3.5 h-3.5 text-muted"/>
                    @endif
                </span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.advisor-change-request')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.advisor-change-request') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="git-compare-arrows" class="w-5 h-5"/>
                <span class="font-semibold text-xs">درخواست جابجایی مشاور</span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-plan" href="{{route('client.profile.plan')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.plan') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="book-open" class="w-5 h-5"/>
                <span class="font-semibold text-xs">برنامه های مطالعاتی </span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-report" href="{{route('client.profile.report')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.report') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="list-check" class="w-5 h-5"/>
                <span class="font-semibold text-xs">گزارش های درسی </span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-exam" href="{{route('client.profile.typed-exam.list')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.typed-exam.list') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="square-pen" class="w-5 h-5"/>
                <span class="font-semibold text-xs">آزمون ها</span>
            </a>
        </li>

            <li>
                <a wire:navigate href="{{route('client.profile.sample-questions')}}"
                   class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.sample-questions') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
                   data-elevated="false">
                    <x-ui.icon name="receipt" class="w-5 h-5"/>
                    <span class="font-semibold text-xs">نمونه سوالات</span>
                </a>
            </li>
        <li>
            <a wire:navigate data-tour="sb-smart-report" href="{{route('client.profile.reportStudentStudy')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.reportStudentStudy') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="sparkles" class="w-5 h-5"/>
                <span class="font-semibold text-xs">کارنامه هوشمند</span>
            </a>
        </li>
        <li>
            <a wire:navigate data-tour="sb-classification" href="{{route('client.profile.classification.projects')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.classification.projects') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="layers" class="w-5 h-5"/>
                <span class="font-semibold text-xs">طبقه‌بندی دروس</span>
            </a>
        </li>

        <li>
            <a wire:navigate href="{{route('client.profile.financial')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.financial') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">

                <x-ui.icon name="wallet" class="w-5 h-5"/>

                <span class="font-semibold text-xs">امور مالی </span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.ticket')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3  rounded-full {{ request()->routeIs('client.profile.ticket') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="info" class="w-5 h-5"/>
                <span class="font-semibold text-xs">تیکت و پشتیبانی</span>
            </a>
        </li>
        <li>
            <a wire:navigate href="{{route('client.profile.edit')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.edit') ? 'bg-primary text-primary-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-primary hover:text-primary-foreground px-4' }}"
               data-elevated="false">
                <x-ui.icon name="pen-line" class="w-5 h-5"/>
                <span class="font-semibold text-xs">ویرایش پروفایل</span>
            </a>
        </li>
        <li>
            <a href="{{route('client.logout')}}"
               class="btn-press w-full h-11 inline-flex items-center text-right gap-3 rounded-full {{ request()->routeIs('client.profile.logout') ? 'bg-error text-error-foreground px-4' : ' bg-background text-muted transition-colors hover:bg-error hover:text-error-foreground px-4' }}"
               data-elevated="false">
                {{-- آیکون «خروج» (در + فلش) توی دیکشنری Keyline نیست، همون SVG قبلی نگه داشته شد. --}}
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
