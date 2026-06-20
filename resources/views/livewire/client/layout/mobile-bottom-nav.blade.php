<div>
    @push('link')
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

    @endpush
    @if(\Illuminate\Support\Facades\Auth::check() && (request()->is('profile*') || request()->routeIs('client.profile.*') ))
        {{-- جدید --}}
        @if(!request()->routeIs(['client.profile.classification.classify','client.profile.assessment.*','client.profile.trial.*','client.profile.essay-exam.test','client.profile.essay-exam.test','client.profile.typed-exam.test']))
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
                    <div class="absolute left-1/2 -translate-x-1/2 z-10" style="top: -1rem">
                        <a wire:navigate href="{{route('client.profile.dashboard')}}"   data-tour="nav-logo"
                           class="w-16 h-16 rounded-full bg-primary flex items-center justify-center shadow-lg shadow-primary/40">
                            <img src="/client/assets/images/favicon.svg" alt="لوگو" class="w-10 h-10" style="filter: brightness(0) invert(1);">
                        </a>
                    </div>

                    {{-- border-t حذف شد چون لبه‌ی برش رو خراب می‌کرد --}}
                    <nav data-tour="navbar" class="bg-background/95 backdrop-blur-xl shadow-lg shadow-black rounded-3xl
                                pb-[max(env(safe-area-inset-bottom),12px)] bottom-nav-notch">
                        <div class="flex items-center justify-around h-16 px-6 max-w-lg mx-auto">
                            {{-- اتاق مشاوره --}}
                            <a href="{{ route('client.profile.consultation.sessions') }}" wire:navigate data-tour="nav-consultation"
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.consultation.sessions', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path d="M8 21H16" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12 17V21" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M20 3H4C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H20C21.1046 17 22 16.1046 22 15V5C22 3.89543 21.1046 3 20 3Z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="text-[10px] font-semibold transition-all overflow-hidden"
                                      :class="isActive('{{ route('client.profile.consultation.sessions', [], false) }}') ? 'max-h-4 opacity-100' : 'max-h-0 opacity-0'">
        اتاق مشاوره
    </span>
                            </a>

                            {{-- برنامه درسی --}}
                            <a href="{{ route('client.profile.plan') }}" wire:navigate   data-tour="nav-plan"
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.plan', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-semibold transition-all overflow-hidden"
                                      :class="isActive('{{ route('client.profile.plan', [], false) }}') ? 'max-h-4 opacity-100' : 'max-h-0 opacity-0'">
        برنامه درسی
    </span>
                            </a>

                            {{-- جای خالی لوگو --}}
                            <div class="w-16 flex-shrink-0" aria-hidden="true"></div>

                            {{-- گزارش --}}
                            <a href="{{ route('client.profile.report') }}" wire:navigate
                               data-tour="nav-report"
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.report', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="text-[10px] font-semibold transition-all overflow-hidden"
                                      :class="isActive('{{ route('client.profile.report', [], false) }}') ? 'max-h-4 opacity-100' : 'max-h-0 opacity-0'">
        گزارش
    </span>
                            </a>
                            {{-- آزمون --}}
                            <a href="{{ route('client.profile.typed-exam.list') }}" wire:navigate
                               data-tour="nav-exam"
                               class="flex flex-col items-center justify-center gap-1 min-w-[60px] py-2 px-1 rounded-xl transition-all text-muted hover:text-foreground"
                               :class="isActive('{{ route('client.profile.typed-exam.list', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <span class="text-[10px] font-semibold transition-all overflow-hidden"
                                      :class="isActive('{{ route('client.profile.typed-exam.list', [], false) }}') ? 'max-h-4 opacity-100' : 'max-h-0 opacity-0'">
        آزمون
    </span>
                            </a>
                        </div>
                    </nav>
                </div>

                <div class="md:hidden h-[calc(5rem+env(safe-area-inset-bottom))]"></div>
            </div>
        @endif
    @endif
</div>
