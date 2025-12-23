<div class="max-w-7xl space-y-14 px-4 mx-auto">
    @push('link')
        <link rel="stylesheet" href="/client/assets/css/apexcharts.css"/>
        <style>
            /* Modal Wrapper با Backdrop بهتر */
            .welcome-modal-wrapper {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                background: rgba(0, 0, 0, 0.75);
                backdrop-filter: blur(8px);
                transition: opacity 300ms ease;
                overflow-y: auto;
            }

            .welcome-modal-wrapper.hidden {
                display: none;
            }

            /* کارت مودال با طراحی بهتر */
            .welcome-modal-card {
                position: relative;
                width: min(600px, 100%);
                max-height: 90vh;
                overflow-y: auto;
                border-radius: 24px;
                background: linear-gradient(145deg, #1a1f35, #141829);
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
                transform: scale(0.95) translateY(20px);
                opacity: 0;
                transition: all 400ms cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .welcome-modal-card.show {
                transform: scale(1) translateY(0);
                opacity: 1;
            }

            /* Scrollbar سفارشی */
            .welcome-modal-card::-webkit-scrollbar {
                width: 8px;
            }

            .welcome-modal-card::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
            }

            .welcome-modal-card::-webkit-scrollbar-thumb {
                background: rgba(96, 93, 255, 0.5);
                border-radius: 10px;
            }

            .welcome-modal-card::-webkit-scrollbar-thumb:hover {
                background: rgba(96, 93, 255, 0.7);
            }

            /* Confetti بهبود یافته */
            .welcome-confetti {
                position: absolute;
                inset: 0;
                pointer-events: none;
                overflow: hidden;
                border-radius: 24px;
            }

            .welcome-confetti span {
                position: absolute;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.4);
            }

            .welcome-confetti span.animate {
                animation: confetti-pop 1.2s ease-out forwards;
                animation-delay: calc(var(--i) * 40ms);
            }

            @keyframes confetti-pop {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0.4) rotate(0deg);
                }
                40% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1.3) rotate(180deg);
                }
                100% {
                    opacity: 0;
                    transform: translate(
                        calc(-50% + (var(--tx) * 1px)),
                        calc(-50% + (var(--ty) * 1px))
                    ) scale(0.6) rotate(360deg);
                }
            }

            /* Menu با Gradient زیباتر */
            .welcome-header {
                background: linear-gradient(135deg, rgba(96, 93, 255, 0.15), rgba(173, 99, 246, 0.15));
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            /* آیکون با انیمیشن */
            .welcome-icon {
                position: relative;
                overflow: hidden;
            }

            .welcome-icon::before {
                content: '';
                position: absolute;
                inset: -50%;
                background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
                animation: shine 3s infinite;
            }

            @keyframes shine {
                0%, 100% {
                    transform: translateX(-100%) translateY(-100%) rotate(45deg);
                }
                50% {
                    transform: translateX(100%) translateY(100%) rotate(45deg);
                }
            }

            /* لیست ویژگی‌ها */
            .feature-list {
                background: rgba(96, 93, 255, 0.05);
                border: 1px solid rgba(96, 93, 255, 0.15);
                border-radius: 16px;
                padding: 1.25rem;
                margin: 1.25rem 0;
            }

            .feature-list li {
                position: relative;
                padding-right: 1.5rem;
                margin-bottom: 0.75rem;
                line-height: 1.6;
            }

            .feature-list li:last-child {
                margin-bottom: 0;
            }

            .feature-list li::before {
                content: '✨';
                position: absolute;
                right: 0;
                top: 0;
            }

            /* دکمه بهبود یافته */
            .welcome-button {
                position: relative;
                background: linear-gradient(135deg, #8b5cf6, #6366f1);
                color: #fff;
                padding: 1rem 2rem;
                border: none;
                border-radius: 16px;
                font-weight: 700;
                font-size: 1rem;
                letter-spacing: -0.01em;
                cursor: pointer;
                overflow: hidden;
                transition: all 250ms cubic-bezier(0.34, 1.56, 0.64, 1);
                box-shadow: 0 12px 35px rgba(99, 102, 241, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }

            .welcome-button::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transform: translateX(-100%);
                transition: transform 500ms;
            }

            .welcome-button:hover::before {
                transform: translateX(100%);
            }

            .welcome-button:hover {
                transform: translateY(-2px) scale(1.02);
                box-shadow: 0 16px 45px rgba(99, 102, 241, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }

            .welcome-button:active {
                transform: translateY(1px) scale(0.98);
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }

            /* بهبود Typography */
            .welcome-title {
                background: linear-gradient(135deg, #fff, #e0e7ff);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* لینک تلگرام */
            .telegram-link {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                color: #ef4444;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border-radius: 12px;
                background: rgba(239, 68, 68, 0.1);
                transition: all 200ms;
            }

            .telegram-link:hover {
                background: rgba(239, 68, 68, 0.2);
                transform: translateX(-4px);
            }

            /* Responsive بهتر */
            @media (max-width: 640px) {
                .welcome-modal-card {
                    border-radius: 20px;
                    margin: 1rem;
                }

                .welcome-button {
                    padding: 0.875rem 1.5rem;
                    font-size: 0.9rem;
                }

                .feature-list {
                    padding: 1rem;
                    font-size: 0.875rem;
                }
            }

            /* Badge برای نسخه */
            .version-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: rgba(96, 93, 255, 0.15);
                border: 1px solid rgba(96, 93, 255, 0.3);
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                color: #a5b4fc;
            }
        </style>
    @endpush
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">


        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- end user:info -->

            <!-- user:menus -->

            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">
                <!-- notification:alert:box -->

                @if($student && $unreadNotificationsCount > 0)

                    <a href="{{ route('client.profile.notification') }}"

                       class="block bg-gray-800 dark:bg-gray-900 rounded-2xl p-4 transition-all hover:bg-gray-700 dark:hover:bg-gray-800 group">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex items-center justify-center w-10 h-10 bg-green-500 rounded-full">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor" class="w-5 h-5 text-white">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>

                                    </svg>

                                </div>

                                <div class="text-white">

                                    <div class="font-bold text-sm flex items-center gap-2">

                                        [مشاهده پیام ها]

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="2" stroke="currentColor"
                                             class="w-4 h-4 group-hover:-translate-x-1 transition-transform">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>

                                        </svg>

                                    </div>

                                    <div class="text-gray-300 text-xs mt-1">

                                        شما <span
                                            class="font-bold text-green-400">{{ $unreadNotificationsCount }}</span> پیام
                                        خوانده نشده در بخش دریافتی دارید

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                @endif

                <!-- end notification:alert:box -->


                <!-- statistics:items:wrapper -->
                <div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-8">
                    <!-- statistics:item -->

                    <!-- end statistics:item -->

                    <!-- statistics:item -->
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">

                                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                 <path stroke-linecap="round" stroke-linejoin="round"
                                                       d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">پشتیبان من </span>

                            @if($supporterStudent)
                                <span class="font-bold text-sm text-foreground line-clamp-1">
                                {{ $supporterStudent->name }}
                                </span>
                            @else
                                <span class="font-bold text-sm text-foreground line-clamp-1">
                             تعیین نشده است
                                </span>
                            @endif


                        </div>
                    </div>
                    <!-- end statistics:item -->
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">
                                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor"
                                                class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                  d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">مشاور من </span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-sm text-foreground">
                                     @if($supporterStudent)
                                        <span class="font-bold text-sm text-foreground line-clamp-1">
                                {{ $supporterStudent->name }}
                                </span>
                                    @else
                                        <span class="font-bold text-sm text-foreground line-clamp-1">
                             تعیین نشده است
                                </span>
                                    @endif
                                </span>
                                <span class="text-xs text-muted"></span>
                            </div>
                        </div>
                    </div>
                    <!-- statistics:item -->
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                   <span
                                       class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                  d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">سطح آموزشی</span>
                            <span
                                class="font-bold text-sm text-foreground line-clamp-1">بزودی ...</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">

                         <span
                             class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-yellow-500">
                                       <span
                                           class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-yellow-500">
                                           <img src="/client/assets/images/coin.png" alt="coin" class="w-5 h-5">
                                    </span>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">سکه</span>
                            <span class="font-bold text-sm text-foreground line-clamp-1">بزودی ...</span>
                        </div>
                    </div>
                    <!-- end statistics:item -->

                    <!-- statistics:item -->

                    <!-- end statistics:item -->
                </div>
                <!-- end statistics:wrapper -->

                <!-- section:learning-courses -->
                <div class="space-y-5">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">داشبورد</div>
                    </div>
                    <!-- end section:title -->

                    <!-- section:learning-courses:slider -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-right">

                            <thead
                                class="text-xs text-muted uppercase bg-background border-b border-border">
                            <tr>
                                <th class="whitespace-nowrap p-5">مقام</th>
                                <th class="whitespace-nowrap p-5">دانش آموز</th>
                                <th class="whitespace-nowrap p-5">ساعت مطالعه</th>
                                <th class="whitespace-nowrap p-5">تعداد تست ها</th>
                                <th class="whitespace-nowrap p-5">میانگین درصد آزمون</th>
                            </tr>
                            </thead>


                            <tbody>
                            <tr class="odd:bg-secondary even:bg-background">
                                <td class="p-5">
                                    <div class="font-black text-sm text-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px"
                                             viewBox="0 0 128 128" aria-hidden="true" role="img"
                                             class="iconify iconify--noto w-5 h-5" preserveAspectRatio="xMidYMid meet">
                                            <path
                                                d="M69.09 4.24c-1.08.96-9.48 17.63-9.48 17.63l-6.25 25.21l24.32-2.23S97.91 7.23 98.32 6.36c.73-1.58 1.12-2.23-1.67-2.23c-2.79-.01-26.55-.79-27.56.11z"
                                                fill="#176cc7"/>
                                            <path
                                                d="M81.68 43.29c-1.21-.65-36.85-1.21-37.69 0c-.76 1.1-.65 6.13-.28 6.78c.37.65 12.35 6.22 12.35 6.22l-.01 2.03s.66 1.59 7.34 1.59s7.37-1.35 7.37-1.35l.06-2.05s10.49-5.24 11.04-5.7c.56-.47 1.03-6.87-.18-7.52zM70.7 51.62s-.03-1.4-.72-1.75c-.69-.35-11.8-.29-12.74-.24c-.94.05-.94 1.73-.94 1.73l-7.6-3.7v-.74l28.3.2l.05.84l-6.35 3.66z"
                                                fill="#fcc417"/>
                                            <path
                                                d="M59.26 51.17c-.94 0-1.48.98-1.48 2.67c0 1.58.54 2.91 1.73 2.81c.98-.08 1.32-1.58 1.23-2.91c-.09-1.58-.29-2.57-1.48-2.57z"
                                                fill="#fdffff"/>
                                            <path
                                                d="M28.98 90.72c0 23.96 21.66 34.63 36.06 34.12c15.88-.57 34.9-12.95 33.75-35.81C97.7 67.37 79.48 57.1 63.7 57.21c-18.34.13-34.72 12.58-34.72 33.51z"
                                                fill="#fcc417"/>
                                            <path
                                                d="M64.53 120.67c-.25 0-.51 0-.76-.01c-7.5-.25-14.91-3.41-20.33-8.66c-5.8-5.62-8.98-13.22-8.94-21.39c.09-19.95 17.53-29.2 29.36-29.2h.1c16.03.07 29.19 12.53 29.56 29.42c.16 7.52-2.92 15.41-8.96 21.35c-5.64 5.53-13.12 8.49-20.03 8.49zm-.69-55.94c-10.61 0-26.3 8.68-26.34 25.88c-.03 12.86 9.93 26.08 26.52 26.64c6.32.2 12.83-2.22 18.09-7.39c5.46-5.37 8.53-12.29 8.42-18.99c-.24-14.53-12.12-26.09-26.54-26.15c-.04 0-.12.01-.15.01z"
                                                fill="#fa912c"/>
                                            <path
                                                d="M57.82 60.61c-.69-.95-8.51-.77-15.9 6.45c-7.13 6.97-7.9 13.54-6.53 13.92c1.55.43 3.44-6.53 9.97-12.38c6-5.36 13.84-6.1 12.46-7.99z"
                                                fill="#fefffa"/>
                                            <path
                                                d="M88.07 86.48c-2.41.34.09 7.56-5.5 15.64c-4.85 7.01-10.35 9.55-9.71 11.09c.86 2.06 9.67-3.07 13.75-11.43c3.7-7.57 3.26-15.56 1.46-15.3z"
                                                fill="#fefffa"/>
                                            <path
                                                d="M55.85 77.02c-.52.77-.05 7.52.26 7.82c.6.6 5.16-1.55 5.16-1.55l-.17 18.05s-3.35-.04-3.7.09c-.69.26-.6 7.22-.09 7.56s14.18.52 14.7-.17c.52-.69.39-6.78.15-7.06c-.43-.52-3.7-.31-3.7-.31s.28-26.58.19-27.43s-1.03-1.38-2.15-1.12s-10.32 3.62-10.65 4.12z"
                                                fill="#fa912c"/>
                                            <path
                                                d="M25.51 3.72c-.63.58 23.46 43.48 23.46 43.48s4.04.52 13.06.6s13.49-.52 13.49-.52S56.79 4.15 55.67 3.72c-.55-.22-7.97-.3-15.22-.38c-7.26-.09-14.34-.18-14.94.38z"
                                                fill="#2e9df4"/>
                                        </svg>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">مهدی افراسیابی گولک</span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">156 ساعت </span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">1569 تست </span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">70%</span>
                                    </div>
                                </td>


                            </tr>
                            <tr class="odd:bg-secondary even:bg-background">
                                <td class="p-5">
                                    <div class="font-black text-sm text-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px"
                                             viewBox="0 0 128 128" aria-hidden="true" role="img"
                                             class="iconify iconify--noto w-5 h-5" preserveAspectRatio="xMidYMid meet">
                                            <path
                                                d="M69.09 4.24c-1.08.96-9.48 17.63-9.48 17.63l-6.25 25.21l24.32-2.23S97.91 7.23 98.32 6.36c.73-1.58 1.12-2.23-1.67-2.23c-2.79-.01-26.55-.79-27.56.11z"
                                                fill="#176cc7"/>
                                            <path
                                                d="M81.68 43.29c-1.21-.65-36.85-1.21-37.69 0c-.76 1.1-.33 6.87-.04 7.56c.52 1.2 12.03 6.43 12.03 6.43l-.22 2.38s.94.24 7.63.24s8.01-.34 8.01-.34l.02-2.15s10.36-5.04 10.88-5.74c.44-.58.59-7.73-.62-8.38zm-10.61 9.12s.33-1.47-.36-1.81c-.69-.35-12.53-.19-13.47-.14c-.94.05-.94 1.73-.94 1.73l-7.6-4.53v-.74l28.3.2l.05.84l-5.98 4.45z"
                                                fill="#cecdd2"/>
                                            <path
                                                d="M59.26 51.17c-.94 0-1.48.98-1.48 2.67c0 1.58.54 2.91 1.73 2.81c.98-.08 1.32-1.58 1.23-2.91c-.09-1.58-.29-2.57-1.48-2.57z"
                                                fill="#fdffff"/>
                                            <path
                                                d="M28.97 91.89c0 23.96 22.05 34.13 36.46 33.7c16.79-.5 34.51-13.24 33.36-36.1C97.7 67.83 79.33 58.2 63.55 58.31c-18.34.14-34.58 12.65-34.58 33.58z"
                                                fill="#cecdd2"/>
                                            <path
                                                d="M64.53 121.13c-.25 0-.51 0-.76-.01c-7.5-.25-14.91-3.41-20.33-8.66c-5.8-5.62-8.98-13.22-8.94-21.39c.09-19.95 17.53-29.2 29.36-29.2h.1c16.03.07 29.19 12.53 29.56 29.42c.16 7.52-2.92 15.41-8.96 21.35c-5.64 5.53-13.12 8.49-20.03 8.49zm-.69-55.94c-10.61 0-26.3 8.68-26.34 25.88c-.03 12.86 9.93 26.08 26.52 26.64c6.32.2 12.83-2.22 18.09-7.39c5.46-5.37 8.53-12.29 8.42-18.99c-.26-14.53-12.14-26.09-26.56-26.16c-.02 0-.1.02-.13.02z"
                                                fill="#9b9b9d"/>
                                            <path
                                                d="M58.09 61.47c-.69-.95-7.76-.68-15.37 5.87c-7.56 6.51-8.69 13.71-7.33 14.09c1.55.43 3.44-6.53 9.97-12.38c6-5.35 14.1-5.69 12.73-7.58z"
                                                fill="#fefffa"/>
                                            <path
                                                d="M87.88 87.72c-2.41.34.09 7.56-5.5 15.64c-4.85 7.01-10.35 9.55-9.71 11.09c.86 2.06 9.67-3.07 13.75-11.43c3.69-7.56 3.25-15.55 1.46-15.3z"
                                                fill="#fefffa"/>
                                            <path
                                                d="M25.51 3.72c-.63.58 23.46 43.48 23.46 43.48s4.04.52 13.06.6s13.49-.52 13.49-.52S56.79 4.15 55.67 3.72c-.55-.22-7.97-.3-15.22-.38c-7.26-.09-14.34-.18-14.94.38z"
                                                fill="#2e9df4"/>
                                            <path
                                                d="M56.85 86.35c1.04.01 1.97-1.4 2.83-2.26c1.83-1.84 3.75-3.3 5.94-1.32C71 87.66 60.2 92.62 56.1 99.4c-3.06 5.06-3.68 8.95-2.83 9.99s21.54.99 21.82.47c.28-.52.57-7.45.09-7.78s-10.65-.14-10.65-.14s.85-1.98 4.34-5c3.83-3.31 6.9-7.86 6.08-13.24c-1.7-11.12-12.9-11.53-17.75-7.66c-4.73 3.77-3.71 10.27-.35 10.31z"
                                                fill="#9b9b9d"/>
                                        </svg>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">ارش حلیمی</span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">148 ساعت </span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">1329 تست </span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">67%</span>
                                    </div>
                                </td>


                            </tr>
                            <tr class="odd:bg-secondary even:bg-background">
                                <td class="p-5">
                                    <div class="font-black text-sm text-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" height="800px" width="800px"
                                             version="1.1" id="Layer_1" viewBox="0 0 300.439 300.439"
                                             class="w-5 h-5"
                                             xml:space="preserve">
<g>
    <path style="fill:#BF392C;" d="M276.967,0h-84.498L70.415,178.385h84.498L276.967,0z"/>
    <path style="fill:#E2574C;" d="M23.472,0h84.498l122.053,178.385h-84.498L23.472,0z"/>
    <path style="fill:#ED9D5D;"
          d="M154.914,93.887c57.271,0,103.276,46.005,103.276,103.276s-46.005,103.276-103.276,103.276   S51.638,254.434,51.638,197.163S97.643,93.887,154.914,93.887z"/>
    <path style="fill:#D58D54;"
          d="M154.914,122.053c-41.31,0-75.11,33.799-75.11,75.11s33.799,75.11,75.11,75.11   s75.11-33.799,75.11-75.11S196.224,122.053,154.914,122.053z M154.914,253.495c-30.983,0-56.332-25.35-56.332-56.332   s25.35-56.332,56.332-56.332s56.332,25.35,56.332,56.332S185.896,253.495,154.914,253.495z"/>
</g>
</svg>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">علیرضا بخشی</span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">112 ساعت </span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">1169 تست </span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white">62%</span>
                                    </div>
                                </td>


                            </tr>

                            </tbody>

                        </table>

                    </div>
                    <!-- end section:learning-courses:slider -->
                </div>
                <!-- end section:learning-courses -->


            </div>

        </div>
    </div>


</div>
