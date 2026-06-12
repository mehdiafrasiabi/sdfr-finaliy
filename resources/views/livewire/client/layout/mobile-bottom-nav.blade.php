<div>
    <style>
        /* notch دایره‌ای زیر لوگو وسط — با mask برش می‌خوره */
        .bottom-nav-notch {
            -webkit-mask-image: radial-gradient(circle 38px at 50% 0, transparent 0, transparent 37px, #000 38px);
            mask-image: radial-gradient(circle 37px at 50% 0, transparent 0, transparent 31px, #000 47px);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }
    </style>

    @if(\Illuminate\Support\Facades\Auth::check() && (request()->is('profile*') || request()->routeIs('client.profile.*')))
        @if(!request()->routeIs(['client.profile.classification.classify','client.profile.assessment.*','client.profile.trial.*','client.profile.essay-exam.test','client.profile.essay-exam.test','client.profile.typed-exam.test'])))
            <div x-data="{
                headerOpen: false,
                unreadCount: {{ $unreadCount }},
                currentPath: window.location.pathname,
                isActive(path) {
                    return this.currentPath === path || this.currentPath.startsWith(path + '/');
                }
             }"
                 x-init="document.addEventListener('livewire:navigated', () => { currentPath = window.location.pathname; });"
                 x-on:header-opened.window="headerOpen = true"
                 x-on:header-closed.window="headerOpen = false"
                 x-on:notification-read.window="if (unreadCount > 0) { unreadCount--; }">

                <div x-show="!headerOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-full"
                     class="md:hidden fixed bottom-2.5 left-5 right-5 z-50">

                    {{-- لوگو بیرون از nav قرار داره تا mask برشش نزنه --}}
                    <div class="absolute left-1/2 -translate-x-1/2 -top-8 z-10 pointer-events-none">
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center shadow-lg shadow-primary/40">
                            <img src="/client/assets/images/favicon.svg" alt="لوگو" class="w-10 h-10" style="filter: brightness(0) invert(1);">
                        </div>
                    </div>

                    {{-- border-t حذف شد چون لبه‌ی برش رو خراب می‌کرد --}}
                    <nav class="bg-background/95 backdrop-blur-xl shadow-lg shadow-black rounded-3xl
                                pb-[max(env(safe-area-inset-bottom),12px)] bottom-nav-notch">
                        <div class="flex items-center justify-around h-16 px-6 max-w-lg mx-auto">

                            <!-- داشبورد -->
                            <a href="{{ route('client.profile.dashboard') }}" wire:navigate
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.dashboard', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <div class="relative">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"/>
                                        <path d="M12 17.9902V14.9902"/>
                                    </svg>
                                    <span x-show="isActive('{{ route('client.profile.dashboard', [], false) }}')" x-cloak
                                          class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
                                </div>
                                <span class="text-[10px] font-semibold">داشبورد</span>
                            </a>

                            <!-- اطلاع رسانی -->
                            <a href="{{ route('client.profile.notification') }}" wire:navigate
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.notification', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <div class="relative">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z"/>
                                    </svg>
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                            <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white font-bold text-[8px]" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                                        </span>
                                    </template>
                                    <span x-show="isActive('{{ route('client.profile.notification', [], false) }}')" x-cloak
                                          class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
                                </div>
                                <span class="text-[10px] font-semibold">پیام ها</span>
                            </a>

                            <!-- جای خالی برای لوگو (لوگو بیرون از nav قرار داره) -->
                            <div class="w-16 flex-shrink-0" aria-hidden="true"></div>

                            <!-- تیکت و پشتیبانی -->
                            <a href="{{ route('client.profile.ticket') }}" wire:navigate
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.ticket', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <div class="relative">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M17.98 10.79V14.79C17.98 15.05 17.97 15.3 17.94 15.54C17.71 18.24 16.12 19.58 13.19 19.58H12.79C12.54 19.58 12.3 19.7 12.15 19.9L10.95 21.5C10.42 22.21 9.56 22.21 9.03 21.5L7.82999 19.9C7.69999 19.73 7.41 19.58 7.19 19.58H6.79001C3.60001 19.58 2 18.79 2 14.79V10.79C2 7.86001 3.35001 6.27001 6.04001 6.04001C6.28001 6.01001 6.53001 6 6.79001 6H13.19C16.38 6 17.98 7.60001 17.98 10.79Z" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21.98 6.79001V10.79C21.98 13.73 20.63 15.31 17.94 15.54C17.97 15.3 17.98 15.05 17.98 14.79V10.79C17.98 7.60001 16.38 6 13.19 6H6.79004C6.53004 6 6.28004 6.01001 6.04004 6.04001C6.27004 3.35001 7.86004 2 10.79 2H17.19C20.38 2 21.98 3.60001 21.98 6.79001Z" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13.4955 13.25H13.5045" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9.9955 13.25H10.0045" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.4955 13.25H6.5045" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span x-show="isActive('{{ route('client.profile.ticket', [], false) }}')" x-cloak
                                          class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
                                </div>
                                <span class="text-[10px] font-semibold">پشتیبانی</span>
                            </a>

                            <!-- آچار فرانسه -->
                            <a class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all text-muted hover:text-foreground">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7">
                                        <path d="M1 19.894a3.1 3.1 0 0 0 3.098 3.102 3.149 3.149 0 0 0 2.232-.933l10.268-11.938a1.451 1.451 0 0 1 .535-.343.898.898 0 0 1 .088 0 3.932 3.932 0 0 0 3.668-.573 6.235 6.235 0 0 0 2.106-3.958.621.621 0 0 0-.108-.442l-.113-.141-.11-.06a.647.647 0 0 0-.704.06l-2.88 2.239-1.244-.927-.6-1.531 2.889-2.245a.652.652 0 0 0 .237-.644l-.045-.17-.073-.096a.638.638 0 0 0-.42-.241 6.047 6.047 0 0 0-4.32 1.032 4.209 4.209 0 0 0-1.222 4.789 6.976 6.976 0 0 1-.44.593L1.91 17.697A3.085 3.085 0 0 0 1 19.895zm1.588-1.463L14.55 8.168a5.545 5.545 0 0 0 .734-1.037l.099-.204-.09-.208a3.239 3.239 0 0 1 .824-3.844 4.799 4.799 0 0 1 2.632-.87l-2.228 1.732a.84.84 0 0 0-.264.957l.679 1.73a.752.752 0 0 0 .093.163l1.562 1.203a.815.815 0 0 0 .997-.012l2.202-1.712a4.94 4.94 0 0 1-1.516 2.353 2.904 2.904 0 0 1-2.79.396l-.124-.026a2.42 2.42 0 0 0-.28-.006 2.169 2.169 0 0 0-1.194.642L5.597 21.383A2.108 2.108 0 0 1 2 19.894a2.082 2.082 0 0 1 .588-1.463z"/>
                                        <path d="M4.1 21h.8A1.101 1.101 0 0 0 6 19.9v-.8A1.101 1.101 0 0 0 4.9 18h-.8A1.101 1.101 0 0 0 3 19.1v.8A1.101 1.101 0 0 0 4.1 21zM4 19.1a.1.1 0 0 1 .1-.1h.8a.1.1 0 0 1 .1.1v.8a.1.1 0 0 1-.1.1h-.8a.1.1 0 0 1-.1-.1z"/>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-semibold">آچار فرانسه</span>
                            </a>

                        </div>
                    </nav>
                </div>

                <div class="md:hidden h-[calc(5rem+env(safe-area-inset-bottom))]"></div>
            </div>
        @endif
    @endif
</div>
