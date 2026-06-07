<div>
    @php
        $authUser = \Illuminate\Support\Facades\Auth::user();
        $trialWeek = $authUser?->trialWeek;
        $hasFullAccess = $authUser && (
            ! $trialWeek
            || $trialWeek->status === \App\Models\TrialWeek::STATUS_PROGRAM_BUILT
        );
    @endphp

    @if($hasFullAccess && (request()->is('profile*') || request()->routeIs('client.profile.*')))
        @if(!request()->routeIs('client.profile.classification.classify'))
            <style>
                .nav-notch-bar {
                    position: relative;
                    background: transparent;
                }
                /* ===== Glass background (stronger frost for dark backgrounds) ===== */
                /* The nav background is drawn via clip-path to create the notch */
                .nav-notch-bg {
                    position: absolute;
                    inset: 0;
                    border-radius: 24px;
                    /* low white tint + heavy blur = real glass, not milky */
                    background:
                        linear-gradient(135deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.03)),
                        rgba(255, 255, 255, 0.06);
                    backdrop-filter: blur(40px) saturate(200%);
                    -webkit-backdrop-filter: blur(40px) saturate(200%);
                    border: 1px solid rgba(255, 255, 255, 0.16);
                    box-shadow:
                        0 12px 40px rgba(0, 0, 0, 0.40),
                        0 1px 0 rgba(255, 255, 255, 0.14) inset,
                        0 -1px 0 rgba(255, 255, 255, 0.04) inset;
                }
                /* ===== Active indicator (SVG dot under the selected icon) ===== */
                .nav-active-dot {
                    width: 6px;
                    height: 6px;
                    color: var(--primary, #22c55e);
                    transition: opacity 0.2s, transform 0.2s;
                }
                .nav-item-dot-placeholder {
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                    opacity: 0;
                }
                /* ===== Glass popup card (matched to nav frost intensity) ===== */
                .popup-glass-card {
                    background:
                        linear-gradient(135deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.05)),
                        rgba(255, 255, 255, 0.07);
                    backdrop-filter: blur(44px) saturate(200%);
                    -webkit-backdrop-filter: blur(44px) saturate(200%);
                    border: 1px solid rgba(255, 255, 255, 0.20);
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow:
                        0 20px 56px rgba(0, 0, 0, 0.48),
                        0 1px 0 rgba(255, 255, 255, 0.16) inset;
                }
                .popup-item-row {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 13px 16px;
                    transition: background 0.15s;
                    text-decoration: none;
                    color: inherit;
                    direction: rtl;
                }
                .popup-item-row:not(:last-child) {
                    border-bottom: 0.5px solid rgba(255, 255, 255, 0.10);
                }
                .popup-item-row:hover {
                    background: rgba(255, 255, 255, 0.08);
                }
                /* tail uses same frost as the card */
                .popup-tail {
                    width: 0;
                    height: 0;
                    border-left: 9px solid transparent;
                    border-right: 9px solid transparent;
                    border-top: 9px solid rgba(255, 255, 255, 0.16);
                    margin: 0 auto;
                }
            </style>

            <div x-data="{
                servicesOpen: false,
                headerOpen: false,
                unreadCount: {{ $unreadCount }},
                currentPath: window.location.pathname,
                isActive(path) {
                    return this.currentPath === path || this.currentPath.startsWith(path + '/');
                }
             }"
                 x-init="
                    document.addEventListener('livewire:navigated', () => {
                        currentPath = window.location.pathname;
                    });
                 "
                 x-on:header-opened.window="headerOpen = true; servicesOpen = false"
                 x-on:header-closed.window="headerOpen = false"
                 x-on:notification-read.window="if (unreadCount > 0) { unreadCount--; }">

                <!-- Mobile Sticky Bottom Navigation -->
                <div x-show="!headerOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-full"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-full"
                     class="md:hidden fixed bottom-2.5 left-5 right-5 z-50">

                    <!-- Services Popup Menu -->
                    <!-- IMPORTANT: no full-screen overlay / backdrop here.        -->
                    <!-- Blur happens ONLY behind the card itself (that's the glass) -->
                    <!-- so the page background is NOT blurred when the menu opens.  -->
                    <div x-show="servicesOpen"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute bottom-full left-1/2 -translate-x-1/2 z-50 w-64"
                         style="padding-bottom: 3rem;"
                         x-cloak>

                        <div class="popup-glass-card">

                            <!-- اتاق مشاوره -->
                            <a href="{{ route('client.profile.consultation.sessions') }}" wire:navigate
                               x-on:click="servicesOpen = false"
                               class="popup-item-row">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(59,130,246,0.22);">
                                    <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 21H16M12 17V21M20 3H4C2.9 3 2 3.9 2 5V15C2 16.1 2.9 17 4 17H20C21.1 17 22 16.1 22 15V5C22 3.9 21.1 3 20 3Z"/>
                                    </svg>
                                </div>
                                <span class="flex-1 text-right text-[13px] font-semibold" style="color: rgba(255,255,255,0.90);">اتاق مشاوره</span>
                            </a>

                            <!-- برنامه درسی -->
                            <a href="{{ route('client.profile.plan') }}" wire:navigate
                               x-on:click="servicesOpen = false"
                               class="popup-item-row">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(52,211,153,0.22);">
                                    <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                    </svg>
                                </div>
                                <span class="flex-1 text-right text-[13px] font-semibold" style="color: rgba(255,255,255,0.90);">برنامه درسی</span>
                            </a>

                            <!-- گزارش -->
                            <a href="{{ route('client.profile.report') }}" wire:navigate
                               x-on:click="servicesOpen = false"
                               class="popup-item-row">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(251,191,36,0.22);">
                                    <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21.93 6.76L18.56 20.29C18.32 21.3 17.42 22 16.38 22H3.24C1.73 22 .65 20.52 1.1 19.07L5.31 5.55C5.6 4.61 6.47 3.96 7.45 3.96H19.75C20.7 3.96 21.49 4.54 21.82 5.34C22.01 5.77 22.05 6.26 21.93 6.76Z"/>
                                        <path d="M16 22H20.78C22.07 22 23.08 20.91 22.99 19.62L22 6"/>
                                    </svg>
                                </div>
                                <span class="flex-1 text-right text-[13px] font-semibold" style="color: rgba(255,255,255,0.90);">گزارش درسی</span>
                            </a>

                            <!-- آزمون -->
                            <a href="{{ route('client.profile.typed-exam.list') }}" wire:navigate
                               x-on:click="servicesOpen = false"
                               class="popup-item-row">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(248,113,113,0.22);">
                                    <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </div>
                                <span class="flex-1 text-right text-[13px] font-semibold" style="color: rgba(255,255,255,0.90);">آزمون</span>
                            </a>

                            <!-- ساعت مطالعه -->
                            <a href="{{ route('client.profile.studySession') }}" wire:navigate
                               x-on:click="servicesOpen = false"
                               class="popup-item-row">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(56,189,248,0.22);">
                                    <svg class="w-[17px] h-[17px]" viewBox="0 0 20 20" fill="none" stroke="#38bdf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="10" cy="10" r="8"/>
                                        <path d="M10 5v5h4"/>
                                    </svg>
                                </div>
                                <span class="flex-1 text-right text-[13px] font-semibold" style="color: rgba(255,255,255,0.90);">ساعت مطالعه</span>
                            </a>

                        </div>

                        <!-- tail -->
                        <div class="popup-tail"></div>

                    </div>

                    <!-- Bottom Navigation Bar with notch -->
                    <nav class="nav-notch-bar pb-[max(env(safe-area-inset-bottom),12px)]">
                        <!-- Glass background layer (has the notch clip) -->
                        <div class="nav-notch-bg"></div>

                        <div class="relative flex items-center justify-around h-16 px-6 max-w-lg mx-auto">

                            <!-- داشبورد (icon + active SVG dot) -->
                            <a href="{{ route('client.profile.dashboard') }}" wire:navigate
                               class="flex flex-col items-center justify-center gap-1 min-w-[48px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.dashboard', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9.02 2.84016L3.63 7.04016C2.73 7.74016 2 9.23016 2 10.3602V17.7702C2 20.0902 3.89 21.9902 6.21 21.9902H17.79C20.11 21.9902 22 20.0902 22 17.7802V10.5002C22 9.29016 21.19 7.74016 20.2 7.05016L14.02 2.72016C12.62 1.74016 10.37 1.79016 9.02 2.84016Z"/>
                                    <path d="M12 17.9902V14.9902"/>
                                </svg>
                                <svg x-show="isActive('{{ route('client.profile.dashboard', [], false) }}')" x-cloak
                                     class="nav-active-dot" viewBox="0 0 6 6" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="3" cy="3" r="3"/>
                                </svg>
                                <span x-show="!isActive('{{ route('client.profile.dashboard', [], false) }}')"
                                      class="nav-item-dot-placeholder"></span>
                            </a>

                            <!-- اطلاع رسانی (icon + active SVG dot) -->
                            <a href="{{ route('client.profile.notification') }}" wire:navigate
                               class="flex flex-col items-center justify-center gap-1 min-w-[48px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.notification', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <div class="relative">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z"/>
                                    </svg>
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                            <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white font-bold text-[8px]"
                                                  x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                                        </span>
                                    </template>
                                </div>
                                <svg x-show="isActive('{{ route('client.profile.notification', [], false) }}')" x-cloak
                                     class="nav-active-dot" viewBox="0 0 6 6" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="3" cy="3" r="3"/>
                                </svg>
                                <span x-show="!isActive('{{ route('client.profile.notification', [], false) }}')"
                                      class="nav-item-dot-placeholder"></span>
                            </a>

                            <!-- سرویس‌ها (Center Button — floats above notch) -->
                            <button type="button"
                                    x-on:click="servicesOpen = !servicesOpen"
                                    class="relative flex items-center justify-center services-btn mb-[env(safe-area-inset-bottom)]" style="margin-top: -5rem;">
                                <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center shadow-lg shadow-primary/40 transition-all duration-300 services-circle"
                                     :class="servicesOpen ? 'scale-110' : ''">
                                    <img src="/client/assets/images/favicon.svg"
                                         alt="سرویس‌ها"
                                         class="w-10 h-10 transition-transform duration-300"
                                         :class="servicesOpen ? 'rotate-45' : ''"
                                         style="filter: brightness(0) invert(1);">
                                </div>
                            </button>

                            <!-- تیکت و پشتیبانی (icon + active SVG dot) -->
                            <a href="{{ route('client.profile.ticket') }}" wire:navigate
                               class="flex flex-col items-center justify-center gap-1 min-w-[48px] py-2 px-1 rounded-xl transition-all"
                               :class="isActive('{{ route('client.profile.ticket', [], false) }}') ? 'text-primary' : 'text-muted hover:text-foreground'">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M17.98 10.79V14.79C17.98 15.05 17.97 15.3 17.94 15.54C17.71 18.24 16.12 19.58 13.19 19.58H12.79C12.54 19.58 12.3 19.7 12.15 19.9L10.95 21.5C10.42 22.21 9.56 22.21 9.03 21.5L7.82999 19.9C7.69999 19.73 7.41 19.58 7.19 19.58H6.79001C3.60001 19.58 2 18.79 2 14.79V10.79C2 7.86001 3.35001 6.27001 6.04001 6.04001C6.28001 6.01001 6.53001 6 6.79001 6H13.19C16.38 6 17.98 7.60001 17.98 10.79Z"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21.98 6.79001V10.79C21.98 13.73 20.63 15.31 17.94 15.54C17.97 15.3 17.98 15.05 17.98 14.79V10.79C17.98 7.60001 16.38 6 13.19 6H6.79004C6.53004 6 6.28004 6.01001 6.04004 6.04001C6.27004 3.35001 7.86004 2 10.79 2H17.19C20.38 2 21.98 3.60001 21.98 6.79001Z"
                                          stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M13.4955 13.25H13.5045" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.9955 13.25H10.0045" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.4955 13.25H6.5045" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg x-show="isActive('{{ route('client.profile.ticket', [], false) }}')" x-cloak
                                     class="nav-active-dot" viewBox="0 0 6 6" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="3" cy="3" r="3"/>
                                </svg>
                                <span x-show="!isActive('{{ route('client.profile.ticket', [], false) }}')"
                                      class="nav-item-dot-placeholder"></span>
                            </a>

                            <!-- آچار فرانسه (icon + active SVG dot) -->
                            <a href="#"
                               class="flex flex-col items-center justify-center gap-1 min-w-[48px] py-2 px-1 rounded-xl transition-all text-muted hover:text-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                    <path d="M1 19.894a3.1 3.1 0 0 0 3.098 3.102 3.149 3.149 0 0 0 2.232-.933l10.268-11.938a1.451 1.451 0 0 1 .535-.343.898.898 0 0 1 .088 0 3.932 3.932 0 0 0 3.668-.573 6.235 6.235 0 0 0 2.106-3.958.621.621 0 0 0-.108-.442l-.113-.141-.11-.06a.647.647 0 0 0-.704.06l-2.88 2.239-1.244-.927-.6-1.531 2.889-2.245a.652.652 0 0 0 .237-.644l-.045-.17-.073-.096a.638.638 0 0 0-.42-.241 6.047 6.047 0 0 0-4.32 1.032 4.209 4.209 0 0 0-1.222 4.789 6.976 6.976 0 0 1-.44.593L1.91 17.697A3.085 3.085 0 0 0 1 19.895z"/>
                                    <path d="M4.1 21h.8A1.101 1.101 0 0 0 6 19.9v-.8A1.101 1.101 0 0 0 4.9 18h-.8A1.101 1.101 0 0 0 3 19.1v.8A1.101 1.101 0 0 0 4.1 21z"/>
                                </svg>
                                <span class="nav-item-dot-placeholder"></span>
                            </a>

                        </div>
                    </nav>
                </div>

                <!-- Spacer for bottom navigation -->
                <div class="md:hidden h-[calc(5rem+env(safe-area-inset-bottom))]"></div>

                @script
                <script>
                    (function() {
                        function applyNotch() {
                            var bg = document.querySelector('.nav-notch-bg');
                            if (!bg) return;
                            var w = bg.offsetWidth;
                            var h = bg.offsetHeight;
                            var r = 24; // border-radius
                            var cx = w / 2;
                            var notchR = 48; // radius of the circular notch
                            var notchDepth = 32; // how deep the notch goes
                            var x1 = cx - notchR - 6;
                            var x2 = cx + notchR + 6;
                            // SVG path: full rect with rounded corners except notch cut at top center
                            var path =
                                'M' + r + ',0 ' +
                                'L' + x1 + ',0 ' +
                                'Q' + (cx - notchR + 4) + ',0 ' + (cx - notchR + 8) + ',' + (notchDepth / 2) + ' ' +
                                'A' + notchR + ',' + notchR + ' 0 0 0 ' + (cx + notchR - 8) + ',' + (notchDepth / 2) + ' ' +
                                'Q' + (cx + notchR - 4) + ',0 ' + x2 + ',0 ' +
                                'L' + (w - r) + ',0 ' +
                                'Q' + w + ',0 ' + w + ',' + r + ' ' +
                                'L' + w + ',' + (h - r) + ' ' +
                                'Q' + w + ',' + h + ' ' + (w - r) + ',' + h + ' ' +
                                'L' + r + ',' + h + ' ' +
                                'Q0,' + h + ' 0,' + (h - r) + ' ' +
                                'L0,' + r + ' ' +
                                'Q0,0 ' + r + ',0 Z';
                            bg.style.clipPath = 'path("' + path + '")';
                        }
                        document.addEventListener('DOMContentLoaded', applyNotch);
                        window.addEventListener('resize', applyNotch);
                        // also run after Livewire navigation
                        document.addEventListener('livewire:navigated', applyNotch);
                        // run immediately in case DOM is already ready
                        if (document.readyState !== 'loading') applyNotch();
                    })();
                </script>
                @endscript
            </div>
        @endif
    @endif
</div>
