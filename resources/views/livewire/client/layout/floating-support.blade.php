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
        @push('link')
            <style>
                /* قطره های خیلی ملایم زیر دکمه */
                .droplet {
                    position: absolute;
                    inset: -10px;
                    border-radius: 9999px;
                    background: rgba(59, 130, 246, 0.22); /* آبی ملایم */
                    filter: blur(10px);
                    transform: scale(0.88);
                    opacity: 0;
                }

                @keyframes dropletPulse {
                    0% {
                        transform: scale(0.88);
                        opacity: 0.0;
                    }
                    18% {
                        opacity: 0.18;
                    }
                    55% {
                        opacity: 0.12;
                    }
                    100% {
                        transform: scale(1.28);
                        opacity: 0.0;
                    }
                }

                .droplet-1 {
                    animation: dropletPulse 2.6s ease-in-out infinite;
                }

                .droplet-2 {
                    animation: dropletPulse 2.6s ease-in-out infinite 0.55s;
                    background: rgba(59, 130, 246, 0.16);
                }

                .droplet-3 {
                    animation: dropletPulse 2.6s ease-in-out infinite 1.10s;
                    background: rgba(59, 130, 246, 0.10);
                }

                /* وقتی باز شد: انیمیشن فریز بشه روی همون فریم */
                [data-support-widget][data-open="true"] [data-droplet] .droplet {
                    animation-play-state: paused;
                }

                /* notch مورب برای حالت X */
                [data-notch] {
                    width: 18px;
                    height: 18px;
                    background: #1D4ED8;
                    border-radius: 6px;
                    transform: rotate(45deg);
                    box-shadow: 0 10px 22px rgba(29, 78, 216, 0.20);
                }

                /* کاربران prefers-reduced-motion */
                @media (prefers-reduced-motion: reduce) {
                    .droplet-1, .droplet-2, .droplet-3 {
                        animation: none;
                        opacity: 0.12;
                        transform: scale(1.05);
                    }
                }
            </style>
        @endpush
        @if($hasAny)
            <div data-support-widget class="fixed bottom-6 right-6 z-50">
                <!-- Popup -->
                <div
                    id="{{ $supportWidgetId }}-menu"
                    data-support-menu
                    role="menu"
                    aria-hidden="true"
                    class="absolute bottom-[74px] right-0 w-[240px] max-w-[calc(100vw-3rem)]
         rounded-[16px] bg-white
         border border-black/5
         shadow-[0_18px_45px_rgba(0,0,0,0.12)]
         p-2.5
         origin-bottom-right
         transition duration-200 ease-out
         invisible opacity-0 translate-y-3 pointer-events-none"
                >

                    <div class="space-y-2">
                        @if($mobile)
                            <a
                                href="tel:{{ $mobile }}"
                                role="menuitem"
                                class="grid grid-cols-[1fr_16px_36px] items-center
                                   rounded-[12px]
                                   bg-[#F3F4F6] hover:bg-[#EDEFF3]
                                   px-3 py-[10px]
                                   transition
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/50"
                            >
                                   <span dir="rtl" class="text-center text-[13px] font-medium text-[#111827]">
                  تلفن همراه
                </span>
                                <span aria-hidden="true"></span>
                                <span class="flex items-center justify-center">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                               fill="none" stroke="currentColor" stroke-width="2"
                                               class="w-[18px] h-[18px] text-[#111827]">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5A2.25 2.25 0 0 0 8.25 22.5h7.5A2.25 2.25 0 0 0 18 20.25V3.75A2.25 2.25 0 0 0 15.75 1.5H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                                          </svg>
                             </span>


                            </a>
                        @endif

                        @if($phone)
                            <a
                                href="tel:{{ $phone }}"
                                role="menuitem"
                                class="grid grid-cols-[1fr_16px_36px] items-center
                                   rounded-[12px]
                                   bg-[#F3F4F6] hover:bg-[#EDEFF3]
                                   px-3 py-[10px]
                                   transition
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/50"
                            >
                                                                <span dir="rtl"
                                                                      class="text-center text-[13px] font-medium text-[#111827]">
                  تلفن ثابت
                </span>
                                <span aria-hidden="true"></span>
                                <span class="flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                       fill="none" stroke="currentColor" stroke-width="2"
                       class="w-[18px] h-[18px] text-[#111827]">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25A2.25 2.25 0 0 0 21.75 19.5v-1.372
                         c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-1.097 1.46
                         a1.125 1.125 0 0 1-.57.411A13.168 13.168 0 0 1 6.87 11.526a1.125 1.125 0 0 1 .411-.57l1.46-1.097
                         c.362-.271.527-.733.417-1.173L8.052 4.263c-.125-.501-.575-.852-1.091-.852H5.625
                         A3.375 3.375 0 0 0 2.25 6.75Z"/>
                  </svg>
                </span>


                            </a>
                        @endif

                        @if($telegramUrl)
                            <a
                                href="{{ $telegramUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                role="menuitem"
                                class="grid grid-cols-[1fr_16px_36px] items-center
                                   rounded-[12px]
                                   bg-[#F3F4F6] hover:bg-[#EDEFF3]
                                   px-3 py-[10px]
                                   transition
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/50"

                            >
                                <span dir="rtl" class="text-center text-[13px] font-medium text-[#111827]">
                  پشتیبانی تلگرام
                </span>
                                <span aria-hidden="true"></span>
                                <span class="flex items-center justify-center">
                                      <span
                                          class="inline-flex items-center justify-center w-[22px] h-[22px] rounded-full bg-[#2AABEE]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="currentColor" class="w-[13px] h-[13px] text-white">
                                          <path
                                              d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                                        </svg>
                                      </span>
                                    </span>


                            </a>
                        @endif

                        @if($whatsappDigits)
                            <a
                                href="https://wa.me/{{ $whatsappDigits }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                role="menuitem"
                                class="grid grid-cols-[1fr_16px_36px] items-center
                                   rounded-[12px]
                                   bg-[#F3F4F6] hover:bg-[#EDEFF3]
                                   px-3 py-[10px]
                                   transition
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/50">
                                     <span dir="rtl" class="text-center text-[13px] font-medium text-[#111827]">
                  پشتیبانی واتساپ
                </span>
                                <span aria-hidden="true"></span>
                                <span class="flex items-center justify-center">
                  <span class="inline-flex items-center justify-center w-[22px] h-[22px] rounded-full bg-[#22C55E]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-[13px] h-[13px] text-white"
                         fill="currentColor">
                      <path
                          d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353z"/>
                      <path
                          d="M22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z"/>
                    </svg>
                  </span>
                </span>


                            </a>
                        @endif
                    </div>
                </div>

                <!-- Floating Button -->
                <div class="relative">
                    <!-- ripple / droplet (فقط زیر دکمه) -->
                    <div data-droplet class="pointer-events-none absolute inset-0">
                        <span class="droplet droplet-1"></span>
                        <span class="droplet droplet-2"></span>
                        <span class="droplet droplet-3"></span>
                    </div>

                    <!-- notch (فقط وقتی بازه) -->
                    <span data-notch class="pointer-events-none absolute -top-1 right-1 hidden"></span>

                    <button
                        type="button"
                        data-support-toggle
                        aria-controls="{{ $supportWidgetId }}-menu"
                        aria-expanded="false"
                        aria-label="پشتیبانی"
                        class="relative flex items-center justify-center w-14 h-14 rounded-full
           bg-[#1D4ED8] hover:bg-[#1E40AF]
           shadow-[0_10px_22px_rgba(29,78,216,0.28)]
           transition duration-200 hover:scale-[1.03]
           focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-300/60"
                    >
                        <!-- Headset (closed) -->
                        <svg data-support-icon="open" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 0 1 16 0"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 12v6a2 2 0 0 0 2 2h1v-8H6a2 2 0 0 0-2 2Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 12v6a2 2 0 0 1-2 2h-1v-8h1a2 2 0 0 1 2 2Z"/>
                        </svg>

                        <!-- X (open state) -->
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
                            const TRANSITION_MS = 200;

                            const widgets = Array.from(document.querySelectorAll('[data-support-widget]'));
                            if (!widgets.length) return;

                            const setIcons = (widget, open) => {
                                const iconOpen = widget.querySelector('[data-support-icon="open"]');
                                const iconClose = widget.querySelector('[data-support-icon="close"]');
                                const notch = widget.querySelector('[data-notch]');

                                if (iconOpen) iconOpen.classList.toggle('hidden', open);
                                if (iconClose) iconClose.classList.toggle('hidden', !open);

                                if (notch) notch.classList.toggle('hidden', !open);
                            };


                            const openWidget = (widget) => {
                                const menu = widget.querySelector('[data-support-menu]');
                                const btn = widget.querySelector('[data-support-toggle]');
                                if (!menu || !btn) return;

                                widget.dataset.open = 'true';
                                btn.setAttribute('aria-expanded', 'true');
                                menu.setAttribute('aria-hidden', 'false');
                                setIcons(widget, true);

                                menu.classList.remove('invisible', 'pointer-events-none');
                                requestAnimationFrame(() => {
                                    menu.classList.remove('opacity-0', 'translate-y-3');
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

                                menu.classList.add('opacity-0', 'translate-y-3', 'pointer-events-none');
                                menu.classList.remove('opacity-100', 'translate-y-0');

                                window.setTimeout(() => {
                                    if (widget.dataset.open === 'false') menu.classList.add('invisible');
                                }, TRANSITION_MS);
                            };

                            const toggleWidget = (widget) => {
                                const isOpen = widget.dataset.open === 'true';
                                isOpen ? closeWidget(widget) : openWidget(widget);
                            };

                            widgets.forEach((widget) => {
                                widget.dataset.open = 'false';

                                const btn = widget.querySelector('[data-support-toggle]');
                                const menu = widget.querySelector('[data-support-menu]');

                                if (menu) {
                                    menu.classList.add('invisible', 'opacity-0', 'translate-y-3', 'pointer-events-none');
                                    menu.setAttribute('aria-hidden', 'true');

                                    menu.querySelectorAll('a').forEach(a => {
                                        a.addEventListener('click', () => closeWidget(widget));
                                    });
                                }

                                if (btn) {
                                    btn.setAttribute('aria-expanded', 'false');
                                    btn.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        widgets.forEach(w => {
                                            if (w !== widget && w.dataset.open === 'true') closeWidget(w);
                                        });
                                        toggleWidget(widget);
                                    });
                                }

                                setIcons(widget, false);
                            });

                            document.addEventListener('click', (e) => {
                                widgets.forEach((widget) => {
                                    if (widget.dataset.open !== 'true') return;
                                    if (!widget.contains(e.target)) closeWidget(widget);
                                });
                            });

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
