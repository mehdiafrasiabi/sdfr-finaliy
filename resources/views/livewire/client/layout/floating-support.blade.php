<div>
    @if($settings && $settings->floating_support_enabled)
        @php
            $supportWidgetId = 'floating-support-' . uniqid();

            $mobile   = $settings->floating_mobile ? trim($settings->floating_mobile) : null;
            $phone    = $settings->floating_phone ? trim($settings->floating_phone) : null;

            $telegramRaw = $settings->floating_telegram ? trim($settings->floating_telegram) : null;
            $telegramUser = $telegramRaw ? ltrim($telegramRaw, '@') : null;
            $telegramUrl = $telegramRaw
                ? (str_starts_with($telegramRaw, 'http') ? $telegramRaw : ('https://t.me/' . $telegramUser))
                : null;

            $whatsappDigits = $settings->floating_whatsapp
                ? preg_replace('/\D+/', '', $settings->floating_whatsapp)
                : null;

            $hasAny = $mobile || $phone || $telegramUrl || $whatsappDigits;
        @endphp

        @if($hasAny)
            <div
                data-support-widget
                class="fixed bottom-6 right-6 z-50"
            >
                <!-- Popup Menu -->
                <div
                    id="{{ $supportWidgetId }}-menu"
                    data-support-menu
                    dir="ltr"
                    role="menu"
                    aria-hidden="true"
                    class="absolute bottom-20 right-0 w-64 max-w-[calc(100vw-3rem)]
                           rounded-2xl bg-white/95 dark:bg-gray-900/90 backdrop-blur
                           border border-gray-100 dark:border-gray-800
                           shadow-2xl p-3 mb-2
                           origin-bottom-right
                           transition-all duration-200 ease-out motion-reduce:transition-none
                           invisible opacity-0 translate-y-4 pointer-events-none"
                >
                    <div class="space-y-2">
                        @if($mobile)
                            <a
                                href="tel:{{ $mobile }}"
                                role="menuitem"
                                class="group grid grid-cols-[2.5rem_1fr_2.5rem] items-center
                                       rounded-xl bg-gray-50 hover:bg-gray-100
                                       dark:bg-gray-800/60 dark:hover:bg-gray-800
                                       px-3 py-3 transition-colors
                                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60"
                            >
                                <span class="flex items-center justify-center">
                                    <!-- Mobile icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2"
                                         class="w-5 h-5 text-gray-700 dark:text-gray-200">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5A2.25 2.25 0 0 0 8.25 22.5h7.5A2.25 2.25 0 0 0 18 20.25V3.75A2.25 2.25 0 0 0 15.75 1.5H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                                    </svg>
                                </span>

                                <span dir="rtl"
                                      class="text-center text-sm font-medium text-gray-800 dark:text-gray-100">
                                    تلفن همراه
                                </span>

                                <span aria-hidden="true"></span>
                            </a>
                        @endif

                        @if($phone)
                            <a
                                href="tel:{{ $phone }}"
                                role="menuitem"
                                class="group grid grid-cols-[2.5rem_1fr_2.5rem] items-center
                                       rounded-xl bg-gray-50 hover:bg-gray-100
                                       dark:bg-gray-800/60 dark:hover:bg-gray-800
                                       px-3 py-3 transition-colors
                                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60"
                            >
                                <span class="flex items-center justify-center">
                                    <!-- Phone icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2"
                                         class="w-5 h-5 text-gray-700 dark:text-gray-200">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25A2.25 2.25 0 0 0 21.75 19.5v-1.372
                                                 c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-1.097 1.46
                                                 a1.125 1.125 0 0 1-.57.411A13.168 13.168 0 0 1 6.87 11.526a1.125 1.125 0 0 1 .411-.57l1.46-1.097
                                                 c.362-.271.527-.733.417-1.173L8.052 4.263c-.125-.501-.575-.852-1.091-.852H5.625
                                                 A3.375 3.375 0 0 0 2.25 6.75Z"/>
                                    </svg>
                                </span>

                                <span dir="rtl"
                                      class="text-center text-sm font-medium text-gray-800 dark:text-gray-100">
                                    تلفن ثابت
                                </span>

                                <span aria-hidden="true"></span>
                            </a>
                        @endif

                        @if($telegramUrl)
                            <a
                                href="{{ $telegramUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                role="menuitem"
                                class="group grid grid-cols-[2.5rem_1fr_2.5rem] items-center
                                       rounded-xl bg-gray-50 hover:bg-gray-100
                                       dark:bg-gray-800/60 dark:hover:bg-gray-800
                                       px-3 py-3 transition-colors
                                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60"
                            >
                                <span class="flex items-center justify-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-sky-500">
                                        <!-- Telegram icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="currentColor" class="w-4 h-4 text-white">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                                        </svg>
                                    </span>
                                </span>

                                <span dir="rtl"
                                      class="text-center text-sm font-medium text-gray-800 dark:text-gray-100">
                                    پشتیبانی تلگرام
                                </span>

                                <span aria-hidden="true"></span>
                            </a>
                        @endif

                        @if($whatsappDigits)
                            <a
                                href="https://wa.me/{{ $whatsappDigits }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                role="menuitem"
                                class="group grid grid-cols-[2.5rem_1fr_2.5rem] items-center
                                       rounded-xl bg-gray-50 hover:bg-gray-100
                                       dark:bg-gray-800/60 dark:hover:bg-gray-800
                                       px-3 py-3 transition-colors
                                       focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60"
                            >
                                <span class="flex items-center justify-center">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-500">
                                        <!-- WhatsApp icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"  class="w-4 h-4 text-white ">
                                            <title>whatsapp</title>
                                            <path
                                                d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-0 0-0.001 0-0.001 0-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353h-0zM22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z"/>
                                            </svg>
                                    </span>
                                </span>

                                <span dir="rtl"
                                      class="text-center text-sm font-medium text-gray-800 dark:text-gray-100">
                                    پشتیبانی واتساپ
                                </span>

                                <span aria-hidden="true"></span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Floating Button -->
                <div class="relative">
                    <!-- Glow -->
                    <div
                        class="absolute inset-0 rounded-full bg-blue-500/30 animate-ping motion-reduce:animate-none pointer-events-none"></div>
                    <div
                        class="absolute inset-0 rounded-full bg-blue-400/20 animate-pulse motion-reduce:animate-none scale-110 pointer-events-none"></div>

                    <button
                        type="button"
                        data-support-toggle
                        aria-controls="{{ $supportWidgetId }}-menu"
                        aria-expanded="false"
                        aria-label="پشتیبانی"
                        class="relative flex items-center justify-center w-14 h-14 rounded-full
                               bg-blue-600 hover:bg-blue-700
                               shadow-lg transition-transform duration-200 hover:scale-105
                               focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-300 dark:focus-visible:ring-blue-800"
                    >
                        <!-- Headset icon (closed) -->
                        <svg data-support-icon="open" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 0 1 16 0"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 12v6a2 2 0 0 0 2 2h1v-8H6a2 2 0 0 0-2 2Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 12v6a2 2 0 0 1-2 2h-1v-8h1a2 2 0 0 1 2 2Z"/>
                        </svg>

                        <!-- Close icon (open state) -->
                        <svg data-support-icon="close" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             class="w-7 h-7 text-white hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            @once
                @push('script')
                    <script>
                        (() => {
                            const prefersReducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches;
                            const TRANSITION_MS = prefersReducedMotion ? 0 : 200;

                            const widgets = Array.from(document.querySelectorAll('[data-support-widget]'));
                            if (!widgets.length) return;

                            const setIcons = (widget, open) => {
                                const iconOpen = widget.querySelector('[data-support-icon="open"]');
                                const iconClose = widget.querySelector('[data-support-icon="close"]');
                                if (iconOpen) iconOpen.classList.toggle('hidden', open);
                                if (iconClose) iconClose.classList.toggle('hidden', !open);
                            };

                            const openWidget = (widget) => {
                                const menu = widget.querySelector('[data-support-menu]');
                                const btn = widget.querySelector('[data-support-toggle]');
                                if (!menu || !btn) return;

                                widget.dataset.open = 'true';
                                btn.setAttribute('aria-expanded', 'true');
                                menu.setAttribute('aria-hidden', 'false');
                                setIcons(widget, true);

                                // Make it visible first, then animate in
                                menu.classList.remove('invisible', 'pointer-events-none');
                                requestAnimationFrame(() => {
                                    menu.classList.remove('opacity-0', 'translate-y-4');
                                    menu.classList.add('opacity-100', 'translate-y-0');
                                });
                            };

                            const closeWidget = (widget) => {
                                const menu = widget.querySelector('[data-support-menu]');
                                const btn = widget.querySelector('[data-support-toggle]');
                                if (!menu || !btn) return;

                                widget.dataset.open = 'false';
                                btn.setAttribute('aria-expanded', 'false');
                                menu.setAttribute('aria-hidden', 'true');
                                setIcons(widget, false);

                                menu.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                                menu.classList.remove('opacity-100', 'translate-y-0');

                                window.setTimeout(() => {
                                    if (widget.dataset.open === 'false') {
                                        menu.classList.add('invisible');
                                    }
                                }, TRANSITION_MS);
                            };

                            const toggleWidget = (widget) => {
                                const isOpen = widget.dataset.open === 'true';
                                if (isOpen) closeWidget(widget);
                                else openWidget(widget);
                            };

                            // Init
                            widgets.forEach((widget) => {
                                widget.dataset.open = 'false';

                                const btn = widget.querySelector('[data-support-toggle]');
                                const menu = widget.querySelector('[data-support-menu]');

                                if (menu) {
                                    menu.classList.add('invisible', 'opacity-0', 'translate-y-4', 'pointer-events-none');
                                    menu.classList.remove('opacity-100', 'translate-y-0');
                                    menu.setAttribute('aria-hidden', 'true');

                                    // Close when clicking any link inside menu
                                    menu.querySelectorAll('a').forEach(a => {
                                        a.addEventListener('click', () => closeWidget(widget));
                                    });
                                }

                                if (btn) {
                                    btn.setAttribute('aria-expanded', 'false');
                                    btn.addEventListener('click', (e) => {
                                        e.stopPropagation();

                                        // Close other widgets if any
                                        widgets.forEach(w => {
                                            if (w !== widget && w.dataset.open === 'true') closeWidget(w);
                                        });

                                        toggleWidget(widget);
                                    });
                                }

                                setIcons(widget, false);
                            });

                            // Click outside => close
                            document.addEventListener('click', (e) => {
                                widgets.forEach((widget) => {
                                    if (widget.dataset.open !== 'true') return;
                                    if (!widget.contains(e.target)) closeWidget(widget);
                                });
                            });

                            // ESC => close
                            document.addEventListener('keydown', (e) => {
                                if (e.key !== 'Escape') return;
                                widgets.forEach((widget) => {
                                    if (widget.dataset.open === 'true') closeWidget(widget);
                                });
                            });
                        })();
                    </script>
                @endpush
            @endonce
        @endif
    @endif
</div>
