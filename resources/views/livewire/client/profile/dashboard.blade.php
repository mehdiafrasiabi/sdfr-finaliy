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
                box-shadow:
                    0 25px 60px rgba(0, 0, 0, 0.5),
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
                0%, 100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
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
                box-shadow:
                    0 12px 35px rgba(99, 102, 241, 0.4),
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
                box-shadow:
                    0 16px 45px rgba(99, 102, 241, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }

            .welcome-button:active {
                transform: translateY(1px) scale(0.98);
                box-shadow:
                    0 8px 25px rgba(99, 102, 241, 0.3),
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

        <div id="welcome-modal" class="welcome-modal-wrapper hidden opacity-0">
            <div class="welcome-modal-card">
                <!-- Confetti Effect -->
                <div class="welcome-confetti">
                    @for($i = 0; $i < 30; $i++)
                        <span style="
                            top: {{ rand(15, 85) }}%;
                            left: {{ rand(10, 90) }}%;
                            --tx: {{ rand(-80, 80) }};
                            --ty: {{ rand(50, 140) }};
                            --i: {{ $i }};
                            background: {{ ['#8b5cf6', '#6366f1', '#ec4899', '#f59e0b'][rand(0, 3)] }};
                        "></span>
                    @endfor
                </div>

                <!-- Menu Section -->
                <div class="welcome-header p-6 sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 text-right">
                            <div class="version-badge mb-3">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                </svg>
                                <span>نسخه 1.0.0.1</span>
                            </div>
                            <h2 class="welcome-title text-3xl sm:text-4xl font-black leading-tight mb-2">
                                SDFR آپدیت شد! 🎉
                            </h2>

                        </div>
                        <div class="welcome-icon w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-fuchsia-500/20 border border-white/10 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6 sm:p-8 space-y-6 text-right">
                    <!-- مقدمه -->
                    <p class="text-white text-base leading-relaxed">
                        با افتخار به اطلاع می‌رسانیم که پنل دانش‌آموزان با موفقیت به‌روزرسانی شد. این ارتقا گامی مهم در جهت بهبود کیفیت خدمات آموزشی و ایجاد تجربه‌ای کارآمدتر است.
                    </p>

                    <!-- لیست ویژگی‌ها -->
                    <div class="feature-list">
                        <h3 class="text-white font-bold text-lg mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            ویژگی‌های جدید
                        </h3>
                        <ul class="text-primary text-sm space-y-2">
                            <li>آپدیت بخش آزمون‌ها (کلید آزمون و نتایج)</li>
                            <li>تحلیل آزمون‌ها توسط هوش مصنوعی SDFR</li>
                            <li>بهبود بخش گزارش‌ها</li>
                            <li>سیستم امتیاز‌دهی ستاره‌ای</li>
                            <li>امکان ثبت نظر پس از مشاوره</li>
                            <li>اطلاع‌رسانی هوشمند برنامه‌ها</li>
                            <li>آپدیت ساعت مطالعه</li>
                            <li>بهبود‌های امنیتی و رفع باگ‌ها</li>
                        </ul>
                    </div>

                    <!-- پیام تشکر -->
                    <div class="bg-gradient-to-r from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 rounded-xl p-4">
                        <p class="text-success text-sm leading-relaxed">
                            از تلاش و همراهی تمامی دانش‌آموزان، اولیا و همکاران محترم که ما را در تحقق این بهبود یاری کردند، صمیمانه سپاسگزاریم.
                            <br>
                            <span class="text-indigo-300 font-semibold">آینده‌ای روشن و موفقیت‌آمیز برای شما آرزومندیم 🌟</span>
                        </p>
                    </div>

                    <!-- بخش پشتیبانی -->
                    <div class="border-t border-white/10 pt-5">
                        <p class="text-yellow-500 text-sm mb-3">
                            برای گزارش مشکلات، پیشنهادات و انتقادات(تلگرام):
                        </p>
                        <a href="https://t.me/SdfrWebApp" target="_blank" rel="noopener" class="telegram-link">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                            </svg>
                            <span>@SdfrWebApp</span>
                        </a>
                    </div>
                    <br>
                    <!-- دکمه -->
                    <div class="flex justify-center pt-2">
                        <button id="welcome-dismiss" type="button" class="welcome-button">
                            متوجه شدم، بریم شروع کنیم! 🚀
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- end user:info -->

            <!-- user:menus -->

            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">

                <!-- statistics:items:wrapper -->
                <div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-8" >
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
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full ">
                                        <img src="/client/assets/images/icon/medal.png" class="w-5 h-5">
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">سطح آموزشی</span>
                            <span
                                class="font-bold text-sm text-foreground line-clamp-1">سطح : {{$student->star ?? '--'}} </span>
                        </div>
                    </div>
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
                            <span class="font-bold text-xs text-muted line-clamp-1">سکه</span>
                            <span class="font-bold text-sm text-foreground line-clamp-1">بزودی 🔥</span>
                        </div>
                    </div>
                    <!-- end statistics:item -->

                    <!-- statistics:item -->

                    <!-- end statistics:item -->
                </div>
                <!-- end statistics:wrapper -->

                <!-- section:learning-courses -->
                <div class="space-y-5" >
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

                <div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-8  blur-container">
                    <div class="lg:col-span-2">
                        <!-- Returning Customer Rate -->
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                                <div class="trezo-card-title">
                                    <h5 class="font-black xs:text-2xl text-lg text-white">نمودار 1</h5>
                                </div>
                            </div>
                            <div class="trezo-card-content">
                                <div class="-mb-[15px] -mt-[5px] md:-mt-[22px] ltr:-ml-[10px] rtl:-mr-[10px]">
                                    <div id="ecommerceReturningCustomerRateChart" class="" style="min-height: 334px;">
                                        <div id="apexcharts2kbwt4uai"
                                             class="apexcharts-canvas apexcharts2kbwt4uai apexcharts-theme-"
                                             style="width: 353px; height: 319px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                                 xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="353"
                                                 height="319">
                                                <foreignObject x="0" y="0" width="353" height="319">
                                                    <div
                                                        class="apexcharts-legend apexcharts-align-center apx-legend-position-top"
                                                        xmlns="http://www.w3.org/1999/xhtml"
                                                        style="right: 0px; position: absolute; left: 0px; top: 4px; max-height: 159.5px;">
                                                        <div class="apexcharts-legend-series" rel="1"
                                                             seriesname="بارxپنجم" data:collapsed="false"
                                                             style="margin: 0px 8px;"><span
                                                                class="apexcharts-legend-marker" rel="1"
                                                                data:collapsed="false"
                                                                style="height: 14px; width: 14px; left: -2px; top: -0.5px;"><svg
                                                                    xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="100%" height="100%"><path d="M 0, 0
           m -6, 0
           a 6,6 0 1,0 12,0
           a 6,6 0 1,0 -12,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                                     stroke-linecap="butt"
                                                                                                     stroke-width="1"
                                                                                                     stroke-dasharray="0"
                                                                                                     cx="0" cy="0"
                                                                                                     shape="circle"
                                                                                                     class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                                     style="transform: translate(50%, 50%);"></path></svg></span><span
                                                                class="apexcharts-legend-text" rel="1" i="0"
                                                                data:default-text="%D8%A8%D8%A7%D8%B1%20%D9%BE%D9%86%D8%AC%D9%85"
                                                                data:collapsed="false"
                                                                style="color: rgb(100, 116, 139); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">بار پنجم</span>
                                                        </div>
                                                        <div class="apexcharts-legend-series" rel="2"
                                                             seriesname="بارxچهارم" data:collapsed="false"
                                                             style="margin: 0px 8px;"><span
                                                                class="apexcharts-legend-marker" rel="2"
                                                                data:collapsed="false"
                                                                style="height: 14px; width: 14px; left: -2px; top: -0.5px;"><svg
                                                                    xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="100%" height="100%"><path d="M 0, 0
           m -6, 0
           a 6,6 0 1,0 12,0
           a 6,6 0 1,0 -12,0" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                                     stroke-linecap="butt"
                                                                                                     stroke-width="1"
                                                                                                     stroke-dasharray="0"
                                                                                                     cx="0" cy="0"
                                                                                                     shape="circle"
                                                                                                     class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                                     style="transform: translate(50%, 50%);"></path></svg></span><span
                                                                class="apexcharts-legend-text" rel="2" i="1"
                                                                data:default-text="%D8%A8%D8%A7%D8%B1%20%DA%86%D9%87%D8%A7%D8%B1%D9%85"
                                                                data:collapsed="false"
                                                                style="color: rgb(100, 116, 139); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">بار چهارم</span>
                                                        </div>
                                                    </div>
                                                    <style type="text/css">
                                                        .apexcharts-flip-y {
                                                            transform: scaleY(-1) translateY(-100%);
                                                            transform-origin: top;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-flip-x {
                                                            transform: scaleX(-1);
                                                            transform-origin: center;
                                                            transform-box: fill-box;
                                                        }

                                                        .apexcharts-legend {
                                                            display: flex;
                                                            overflow: auto;
                                                            padding: 0 10px;
                                                        }

                                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                            flex-direction: column;
                                                        }

                                                        .apexcharts-legend-group {
                                                            display: flex;
                                                        }

                                                        .apexcharts-legend-group-vertical {
                                                            flex-direction: column-reverse;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
                                                            flex-wrap: wrap
                                                        }

                                                        .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                            flex-direction: column;
                                                            bottom: 0;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                            justify-content: flex-start;
                                                            align-items: flex-start;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                            justify-content: center;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                            justify-content: flex-end;
                                                            align-items: flex-end;
                                                        }

                                                        .apexcharts-legend-series {
                                                            cursor: pointer;
                                                            line-height: normal;
                                                            display: flex;
                                                            align-items: center;
                                                        }

                                                        .apexcharts-legend-text {
                                                            position: relative;
                                                            font-size: 14px;
                                                        }

                                                        .apexcharts-legend-text *, .apexcharts-legend-marker * {
                                                            pointer-events: none;
                                                        }

                                                        .apexcharts-legend-marker {
                                                            position: relative;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            cursor: pointer;
                                                            margin-right: 1px;
                                                        }

                                                        .apexcharts-legend-series.apexcharts-no-click {
                                                            cursor: auto;
                                                        }

                                                        .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
                                                            display: none !important;
                                                        }

                                                        .apexcharts-inactive-legend {
                                                            opacity: 0.45;
                                                        }

                                                    </style>
                                                </foreignObject>
                                                <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                                                      stroke-width="0" stroke="none" stroke-dasharray="0"
                                                      fill="#fefefe"></rect>
                                                <g class="apexcharts-datalabels-group"
                                                   transform="translate(0, 0) scale(1)"></g>
                                                <g class="apexcharts-datalabels-group"
                                                   transform="translate(0, 0) scale(1)"></g>
                                                <g class="apexcharts-yaxis" rel="0"
                                                   transform="translate(20.2064266204834, 0)">
                                                    <g class="apexcharts-yaxis-texts-g">
                                                        <text x="20" y="50" text-anchor="end" dominant-baseline="auto"
                                                              font-size="12px"
                                                              font-family="Helvetica, Arial, sans-serif"
                                                              font-weight="400" fill="#64748b"
                                                              class="apexcharts-text apexcharts-yaxis-label "
                                                              style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>100%</tspan>
                                                            <title>100%</title></text>
                                                        <text x="20" y="96.35859138412476" text-anchor="end"
                                                              dominant-baseline="auto" font-size="12px"
                                                              font-family="Helvetica, Arial, sans-serif"
                                                              font-weight="400" fill="#64748b"
                                                              class="apexcharts-text apexcharts-yaxis-label "
                                                              style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>80%</tspan>
                                                            <title>80%</title></text>
                                                        <text x="20" y="142.71718276824953" text-anchor="end"
                                                              dominant-baseline="auto" font-size="12px"
                                                              font-family="Helvetica, Arial, sans-serif"
                                                              font-weight="400" fill="#64748b"
                                                              class="apexcharts-text apexcharts-yaxis-label "
                                                              style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>60%</tspan>
                                                            <title>60%</title></text>
                                                        <text x="20" y="189.0757741523743" text-anchor="end"
                                                              dominant-baseline="auto" font-size="12px"
                                                              font-family="Helvetica, Arial, sans-serif"
                                                              font-weight="400" fill="#64748b"
                                                              class="apexcharts-text apexcharts-yaxis-label "
                                                              style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>40%</tspan>
                                                            <title>40%</title></text>
                                                        <text x="20" y="235.43436553649906" text-anchor="end"
                                                              dominant-baseline="auto" font-size="12px"
                                                              font-family="Helvetica, Arial, sans-serif"
                                                              font-weight="400" fill="#64748b"
                                                              class="apexcharts-text apexcharts-yaxis-label "
                                                              style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>20%</tspan>
                                                            <title>20%</title></text>
                                                        <text x="20" y="281.7929569206238" text-anchor="end"
                                                              dominant-baseline="auto" font-size="12px"
                                                              font-family="Helvetica, Arial, sans-serif"
                                                              font-weight="400" fill="#64748b"
                                                              class="apexcharts-text apexcharts-yaxis-label "
                                                              style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan>0%</tspan>
                                                            <title>0%</title></text>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-inner apexcharts-graphical"
                                                   transform="translate(50.2064266204834, 46)">
                                                    <defs>
                                                        <clipPath id="gridRectMask2kbwt4uai">
                                                            <rect width="292.7935733795166" height="231.7929569206238"
                                                                  x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0"
                                                                  stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectBarMask2kbwt4uai">
                                                            <rect width="298.7935733795166" height="237.7929569206238"
                                                                  x="-3" y="-3" rx="0" ry="0" opacity="1"
                                                                  stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                  fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="gridRectMarkerMask2kbwt4uai">
                                                            <rect width="302.7935733795166" height="241.7929569206238"
                                                                  x="-5" y="-5" rx="0" ry="0" opacity="1"
                                                                  stroke-width="0" stroke="none" stroke-dasharray="0"
                                                                  fill="#fff"></rect>
                                                        </clipPath>
                                                        <clipPath id="forecastMask2kbwt4uai"></clipPath>
                                                        <clipPath id="nonForecastMask2kbwt4uai"></clipPath>
                                                    </defs>
                                                    <line x1="0" y1="0" x2="0" y2="231.7929569206238" stroke="#b6b6b6"
                                                          stroke-dasharray="3" stroke-linecap="butt"
                                                          class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                          height="231.7929569206238" fill="#b1b9c4" filter="none"
                                                          fill-opacity="0.9" stroke-width="1"></line>
                                                    <g class="apexcharts-grid">
                                                        <g class="apexcharts-gridlines-horizontal">
                                                            <line x1="0" y1="46.35859138412476" x2="292.7935733795166"
                                                                  y2="46.35859138412476" stroke="#eceef2"
                                                                  stroke-dasharray="0" stroke-linecap="butt"
                                                                  class="apexcharts-gridline"></line>
                                                            <line x1="0" y1="92.71718276824951" x2="292.7935733795166"
                                                                  y2="92.71718276824951" stroke="#eceef2"
                                                                  stroke-dasharray="0" stroke-linecap="butt"
                                                                  class="apexcharts-gridline"></line>
                                                            <line x1="0" y1="139.07577415237427" x2="292.7935733795166"
                                                                  y2="139.07577415237427" stroke="#eceef2"
                                                                  stroke-dasharray="0" stroke-linecap="butt"
                                                                  class="apexcharts-gridline"></line>
                                                            <line x1="0" y1="185.43436553649903" x2="292.7935733795166"
                                                                  y2="185.43436553649903" stroke="#eceef2"
                                                                  stroke-dasharray="0" stroke-linecap="butt"
                                                                  class="apexcharts-gridline"></line>
                                                        </g>
                                                        <g class="apexcharts-gridlines-vertical"></g>
                                                        <line x1="0" y1="231.7929569206238" x2="292.7935733795166"
                                                              y2="231.7929569206238" stroke="transparent"
                                                              stroke-dasharray="0" stroke-linecap="butt"></line>
                                                        <line x1="0" y1="1" x2="0" y2="231.7929569206238"
                                                              stroke="transparent" stroke-dasharray="0"
                                                              stroke-linecap="butt"></line>
                                                    </g>
                                                    <g class="apexcharts-grid-borders">
                                                        <line x1="0" y1="0" x2="292.7935733795166" y2="0"
                                                              stroke="#eceef2" stroke-dasharray="0"
                                                              stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="231.7929569206238" x2="292.7935733795166"
                                                              y2="231.7929569206238" stroke="#eceef2"
                                                              stroke-dasharray="0" stroke-linecap="butt"
                                                              class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-line-series apexcharts-plot-series">
                                                        <g class="apexcharts-series" zIndex="0" seriesName="بارxپنجم"
                                                           data:longestSeries="true" rel="1" data:realIndex="0">
                                                            <path
                                                                d="M 0 69.53788707618713C 11.386416742536756 69.53788707618713 21.146202521853976 178.48057682888032 32.53261926439073 178.48057682888032C 43.919036006927485 178.48057682888032 53.67882178624471 139.07577415237427 65.06523852878146 139.07577415237427C 76.45165527131822 139.07577415237427 86.21144105063544 162.25506984443666 97.5978577931722 162.25506984443666C 108.98427453570895 162.25506984443666 118.74406031502618 88.08132362983704 130.13047705756293 88.08132362983704C 141.5168938000997 88.08132362983704 151.2766795794169 111.26061932189943 162.66309632195367 111.26061932189943C 174.0495130644904 111.26061932189943 183.80929884380765 23.179295692062396 195.1957155863444 23.179295692062396C 206.58213232888116 23.179295692062396 216.34191810819837 185.43436553649903 227.72833485073514 185.43436553649903C 239.11475159327188 185.43436553649903 248.87453737258912 92.71718276824953 260.26095411512586 92.71718276824953C 271.6473708576626 92.71718276824953 281.40715663697983 108.94268975269318 292.7935733795166 108.94268975269318"
                                                                fill="none" fill-opacity="1"
                                                                stroke="rgba(96,93,255,0.85)" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="2"
                                                                stroke-dasharray="0" class="apexcharts-line" index="0"
                                                                clip-path="url(#gridRectMask2kbwt4uai)"
                                                                pathTo="M 0 69.53788707618713C 11.386416742536756 69.53788707618713 21.146202521853976 178.48057682888032 32.53261926439073 178.48057682888032C 43.919036006927485 178.48057682888032 53.67882178624471 139.07577415237427 65.06523852878146 139.07577415237427C 76.45165527131822 139.07577415237427 86.21144105063544 162.25506984443666 97.5978577931722 162.25506984443666C 108.98427453570895 162.25506984443666 118.74406031502618 88.08132362983704 130.13047705756293 88.08132362983704C 141.5168938000997 88.08132362983704 151.2766795794169 111.26061932189943 162.66309632195367 111.26061932189943C 174.0495130644904 111.26061932189943 183.80929884380765 23.179295692062396 195.1957155863444 23.179295692062396C 206.58213232888116 23.179295692062396 216.34191810819837 185.43436553649903 227.72833485073514 185.43436553649903C 239.11475159327188 185.43436553649903 248.87453737258912 92.71718276824953 260.26095411512586 92.71718276824953C 271.6473708576626 92.71718276824953 281.40715663697983 108.94268975269318 292.7935733795166 108.94268975269318"
                                                                pathFrom="M 0 231.7929569206238 L 0 231.7929569206238 L 32.53261926439073 231.7929569206238 L 65.06523852878146 231.7929569206238 L 97.5978577931722 231.7929569206238 L 130.13047705756293 231.7929569206238 L 162.66309632195367 231.7929569206238 L 195.1957155863444 231.7929569206238 L 227.72833485073514 231.7929569206238 L 260.26095411512586 231.7929569206238 L 292.7935733795166 231.7929569206238"
                                                                fill-rule="evenodd"></path>
                                                            <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                               data:realIndex="0">
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 0, 69.53788707618713
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0" cx="0"
                                                                          cy="69.53788707618713" shape="circle"
                                                                          class="apexcharts-marker no-pointer-events wa71z2mf2f"
                                                                          rel="0" j="0" index="0"
                                                                          default-marker-size="4"></path>
                                                                    <path d="M 32.53261926439073, 178.48057682888032
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="32.53261926439073" cy="178.48057682888032"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events wgqickh3y"
                                                                          rel="1" j="1" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 65.06523852878146, 139.07577415237427
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="65.06523852878146" cy="139.07577415237427"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events w4sbbys83"
                                                                          rel="2" j="2" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 97.5978577931722, 162.25506984443666
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="97.5978577931722" cy="162.25506984443666"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events whyibaks7"
                                                                          rel="3" j="3" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 130.13047705756293, 88.08132362983704
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="130.13047705756293" cy="88.08132362983704"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events wmfpfjhnx"
                                                                          rel="4" j="4" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 162.66309632195367, 111.26061932189943
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="162.66309632195367"
                                                                          cy="111.26061932189943" shape="circle"
                                                                          class="apexcharts-marker no-pointer-events whakjnezs"
                                                                          rel="5" j="5" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 195.1957155863444, 23.179295692062396
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="195.1957155863444" cy="23.179295692062396"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events w68dvfccj"
                                                                          rel="6" j="6" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 227.72833485073514, 185.43436553649903
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="227.72833485073514"
                                                                          cy="185.43436553649903" shape="circle"
                                                                          class="apexcharts-marker no-pointer-events w634muqq9"
                                                                          rel="7" j="7" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 260.26095411512586, 92.71718276824953
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="260.26095411512586" cy="92.71718276824953"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events wu27vq5mz"
                                                                          rel="8" j="8" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 292.7935733795166, 108.94268975269318
           m -4, 0
           a 4,4 0 1,0 8,0
           a 4,4 0 1,0 -8,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="292.7935733795166" cy="108.94268975269318"
                                                                          shape="circle"
                                                                          class="apexcharts-marker no-pointer-events wq2gx09v1l"
                                                                          rel="9" j="9" index="0"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g class="apexcharts-series" zIndex="1" seriesName="بارxچهارم"
                                                           data:longestSeries="true" rel="2" data:realIndex="1">
                                                            <path
                                                                d="M 0 197.02401338253023C 11.386416742536756 197.02401338253023 21.146202521853976 97.35304190666201 32.53261926439073 97.35304190666201C 43.919036006927485 97.35304190666201 53.67882178624471 127.4861263063431 65.06523852878146 127.4861263063431C 76.45165527131822 127.4861263063431 86.21144105063544 143.71163329078675 97.5978577931722 143.71163329078675C 108.98427453570895 143.71163329078675 118.74406031502618 69.53788707618713 130.13047705756293 69.53788707618713C 141.5168938000997 69.53788707618713 151.2766795794169 115.8964784603119 162.66309632195367 115.8964784603119C 174.0495130644904 115.8964784603119 183.80929884380765 104.30683061428071 195.1957155863444 104.30683061428071C 206.58213232888116 104.30683061428071 216.34191810819837 92.71718276824953 227.72833485073514 92.71718276824953C 239.11475159327188 92.71718276824953 248.87453737258912 50.99445052253725 260.26095411512586 50.99445052253725C 271.6473708576626 50.99445052253725 281.40715663697983 139.07577415237427 292.7935733795166 139.07577415237427"
                                                                fill="none" fill-opacity="1"
                                                                stroke="rgba(173,99,246,0.85)" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="2"
                                                                stroke-dasharray="0" class="apexcharts-line" index="1"
                                                                clip-path="url(#gridRectMask2kbwt4uai)"
                                                                pathTo="M 0 197.02401338253023C 11.386416742536756 197.02401338253023 21.146202521853976 97.35304190666201 32.53261926439073 97.35304190666201C 43.919036006927485 97.35304190666201 53.67882178624471 127.4861263063431 65.06523852878146 127.4861263063431C 76.45165527131822 127.4861263063431 86.21144105063544 143.71163329078675 97.5978577931722 143.71163329078675C 108.98427453570895 143.71163329078675 118.74406031502618 69.53788707618713 130.13047705756293 69.53788707618713C 141.5168938000997 69.53788707618713 151.2766795794169 115.8964784603119 162.66309632195367 115.8964784603119C 174.0495130644904 115.8964784603119 183.80929884380765 104.30683061428071 195.1957155863444 104.30683061428071C 206.58213232888116 104.30683061428071 216.34191810819837 92.71718276824953 227.72833485073514 92.71718276824953C 239.11475159327188 92.71718276824953 248.87453737258912 50.99445052253725 260.26095411512586 50.99445052253725C 271.6473708576626 50.99445052253725 281.40715663697983 139.07577415237427 292.7935733795166 139.07577415237427"
                                                                pathFrom="M 0 231.7929569206238 L 0 231.7929569206238 L 32.53261926439073 231.7929569206238 L 65.06523852878146 231.7929569206238 L 97.5978577931722 231.7929569206238 L 130.13047705756293 231.7929569206238 L 162.66309632195367 231.7929569206238 L 195.1957155863444 231.7929569206238 L 227.72833485073514 231.7929569206238 L 260.26095411512586 231.7929569206238 L 292.7935733795166 231.7929569206238"
                                                                fill-rule="evenodd"></path>
                                                            <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                               data:realIndex="1">
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M -3.5555555555555554 193.46845782697469
           L 3.5555555555555554 193.46845782697469
           L 3.5555555555555554 200.57956893808577
           L -3.5555555555555554 200.57956893808577
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0" cx="0"
                                                                          cy="197.02401338253023" shape="square"
                                                                          class="apexcharts-marker no-pointer-events wp1pnyt25j"
                                                                          rel="0" j="0" index="1"
                                                                          default-marker-size="4"></path>
                                                                    <path d="M 28.977063708835175 93.79748635110646
           L 36.08817481994629 93.79748635110646
           L 36.08817481994629 100.90859746221757
           L 28.977063708835175 100.90859746221757
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="32.53261926439073" cy="97.35304190666201"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wk0pwcbs2f"
                                                                          rel="1" j="1" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 61.50968297322591 123.93057075078754
           L 68.62079408433702 123.93057075078754
           L 68.62079408433702 131.04168186189864
           L 61.50968297322591 131.04168186189864
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="65.06523852878146" cy="127.4861263063431"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wpafhxl1wj"
                                                                          rel="2" j="2" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 94.04230223761664 140.1560777352312
           L 101.15341334872775 140.1560777352312
           L 101.15341334872775 147.2671888463423
           L 94.04230223761664 147.2671888463423
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="97.5978577931722" cy="143.71163329078675"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wfvpnurwe"
                                                                          rel="3" j="3" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 126.57492150200737 65.98233152063158
           L 133.68603261311847 65.98233152063158
           L 133.68603261311847 73.09344263174269
           L 126.57492150200737 73.09344263174269
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="130.13047705756293" cy="69.53788707618713"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wpk0n8s45f"
                                                                          rel="4" j="4" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 159.10754076639813 112.34092290475634
           L 166.21865187750922 112.34092290475634
           L 166.21865187750922 119.45203401586745
           L 159.10754076639813 119.45203401586745
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="162.66309632195367" cy="115.8964784603119"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events w4txog9j1"
                                                                          rel="5" j="5" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 191.64016003078885 100.75127505872516
           L 198.75127114189993 100.75127505872516
           L 198.75127114189993 107.86238616983627
           L 191.64016003078885 107.86238616983627
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="195.1957155863444" cy="104.30683061428071"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wd8mcjraw"
                                                                          rel="6" j="6" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 224.1727792951796 89.16162721269397
           L 231.28389040629068 89.16162721269397
           L 231.28389040629068 96.27273832380509
           L 224.1727792951796 96.27273832380509
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="227.72833485073514" cy="92.71718276824953"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wthddyw6u"
                                                                          rel="7" j="7" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 256.7053985595703 47.43889496698169
           L 263.8165096706814 47.43889496698169
           L 263.8165096706814 54.55000607809281
           L 256.7053985595703 54.55000607809281
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="260.26095411512586" cy="50.99445052253725"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events wkrsoc0tk"
                                                                          rel="8" j="8" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                                <g class="apexcharts-series-markers"
                                                                   clip-path="url(#gridRectMarkerMask2kbwt4uai)">
                                                                    <path d="M 289.23801782396106 135.52021859681872
           L 296.34912893507214 135.52021859681872
           L 296.34912893507214 142.6313297079298
           L 289.23801782396106 142.6313297079298
           Z" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9" stroke-linecap="butt"
                                                                          stroke-width="0" stroke-dasharray="0"
                                                                          cx="292.7935733795166" cy="139.07577415237427"
                                                                          shape="square"
                                                                          class="apexcharts-marker no-pointer-events w72dzhyo7"
                                                                          rel="9" j="9" index="1"
                                                                          default-marker-size="4"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                        <g class="apexcharts-datalabels" data:realIndex="1"></g>
                                                    </g>
                                                    <line x1="0" y1="0" x2="292.7935733795166" y2="0" stroke="#b6b6b6"
                                                          stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                          class="apexcharts-ycrosshairs"></line>
                                                    <line x1="0" y1="0" x2="292.7935733795166" y2="0" stroke="#b6b6b6"
                                                          stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                          class="apexcharts-ycrosshairs-hidden"></line>
                                                    <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                        <g class="apexcharts-xaxis-texts-g"
                                                           transform="translate(0, -4)">
                                                            <text x="0" y="259.7929569206238" text-anchor="middle"
                                                                  dominant-baseline="auto" font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>فروردین</tspan>
                                                                <title>فروردین</title></text>
                                                            <text x="32.53261926439073" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan></tspan>
                                                                <title></title></text>
                                                            <text x="65.06523852878146" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>خرداد</tspan>
                                                                <title>خرداد</title></text>
                                                            <text x="97.59785779317221" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>تیر</tspan>
                                                                <title>تیر</title></text>
                                                            <text x="130.13047705756293" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>مرداد</tspan>
                                                                <title>مرداد</title></text>
                                                            <text x="162.66309632195367" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>شهریور</tspan>
                                                                <title>شهریور</title></text>
                                                            <text x="195.19571558634442" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan></tspan>
                                                                <title></title></text>
                                                            <text x="227.72833485073517" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>آبان</tspan>
                                                                <title>آبان</title></text>
                                                            <text x="260.2609541151259" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>آذر</tspan>
                                                                <title>آذر</title></text>
                                                            <text x="292.79357337951666" y="259.7929569206238"
                                                                  text-anchor="middle" dominant-baseline="auto"
                                                                  font-size="12px"
                                                                  font-family="Helvetica, Arial, sans-serif"
                                                                  font-weight="400" fill="#8695aa"
                                                                  class="apexcharts-text apexcharts-xaxis-label "
                                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                                <tspan>دی</tspan>
                                                                <title>دی</title></text>
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-yaxis-annotations"></g>
                                                    <g class="apexcharts-xaxis-annotations"></g>
                                                    <g class="apexcharts-point-annotations"></g>
                                                </g>
                                            </svg>
                                            <div class="apexcharts-tooltip apexcharts-theme-light">
                                                <div class="apexcharts-tooltip-title"
                                                     style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                                <div
                                                    class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                    style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                                            style="background-color: rgb(96, 93, 255);"></span>
                                                    <div class="apexcharts-tooltip-text"
                                                         style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                        <div class="apexcharts-tooltip-y-group"><span
                                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                                        <div class="apexcharts-tooltip-goals-group"><span
                                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                                class="apexcharts-tooltip-text-goals-value"></span>
                                                        </div>
                                                        <div class="apexcharts-tooltip-z-group"><span
                                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                                    style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                                            style="background-color: rgb(173, 99, 246);"></span>
                                                    <div class="apexcharts-tooltip-text"
                                                         style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                        <div class="apexcharts-tooltip-y-group"><span
                                                                class="apexcharts-tooltip-text-y-label"></span><span
                                                                class="apexcharts-tooltip-text-y-value"></span></div>
                                                        <div class="apexcharts-tooltip-goals-group"><span
                                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                                class="apexcharts-tooltip-text-goals-value"></span>
                                                        </div>
                                                        <div class="apexcharts-tooltip-z-group"><span
                                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                                <div class="apexcharts-xaxistooltip-text"
                                                     style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                            </div>
                                            <div
                                                class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                <div class="apexcharts-yaxistooltip-text"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="lg:col-span-2">
                        <!-- Order Summary -->
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                                <div class="trezo-card-title">
                                    <h5 class="font-black xs:text-2xl text-lg text-white">نمودار 2</h5>
                                </div>

                            </div>
                            <div class="trezo-card-content">
                                <div id="ecommerceOrderSummaryChart" class="" style="min-height: 323px;">
                                    <div id="apexcharts8db0ssht"
                                         class="apexcharts-canvas apexcharts8db0ssht apexcharts-theme-"
                                         style="width: 343px; height: 323px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                             xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="343"
                                             height="323">
                                            <foreignObject x="0" y="0" width="343" height="323">
                                                <div
                                                    class="apexcharts-legend apexcharts-align-center apx-legend-position-top"
                                                    xmlns="http://www.w3.org/1999/xhtml"
                                                    style="right: 0px; position: absolute; left: 0px; top: 4px; max-height: 143.5px;">
                                                    <div class="apexcharts-legend-series" rel="1" seriesname="تکمیلxشده"
                                                         data:collapsed="false" style="margin: 0px 8px;"><span
                                                            class="apexcharts-legend-marker" rel="1"
                                                            data:collapsed="false"
                                                            style="height: 14px; width: 14px; left: -2px; top: -0.5px;"><svg
                                                                xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                                height="100%"><path d="M 0, 0
           m -6, 0
           a 6,6 0 1,0 12,0
           a 6,6 0 1,0 -12,0" fill="#37d80a" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                    stroke-linecap="butt"
                                                                                    stroke-width="1"
                                                                                    stroke-dasharray="0" cx="0" cy="0"
                                                                                    shape="circle"
                                                                                    class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                    style="transform: translate(50%, 50%);"></path></svg></span><span
                                                            class="apexcharts-legend-text" rel="1" i="0"
                                                            data:default-text="%D8%AA%DA%A9%D9%85%DB%8C%D9%84%20%D8%B4%D8%AF%D9%87"
                                                            data:collapsed="false"
                                                            style="color: rgb(100, 116, 139); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">تکمیل شده</span>
                                                    </div>
                                                    <div class="apexcharts-legend-series" rel="2"
                                                         seriesname="سفارشxجدید" data:collapsed="false"
                                                         style="margin: 0px 8px;"><span class="apexcharts-legend-marker"
                                                                                        rel="2" data:collapsed="false"
                                                                                        style="height: 14px; width: 14px; left: -2px; top: -0.5px;"><svg
                                                                xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                                height="100%"><path d="M 0, 0
           m -6, 0
           a 6,6 0 1,0 12,0
           a 6,6 0 1,0 -12,0" fill="#605dff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                    stroke-linecap="butt"
                                                                                    stroke-width="1"
                                                                                    stroke-dasharray="0" cx="0" cy="0"
                                                                                    shape="circle"
                                                                                    class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                    style="transform: translate(50%, 50%);"></path></svg></span><span
                                                            class="apexcharts-legend-text" rel="2" i="1"
                                                            data:default-text="%D8%B3%D9%81%D8%A7%D8%B1%D8%B4%20%D8%AC%D8%AF%DB%8C%D8%AF"
                                                            data:collapsed="false"
                                                            style="color: rgb(100, 116, 139); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">تایید نشده</span>
                                                    </div>
                                                    <div class="apexcharts-legend-series" rel="3" seriesname="درxانتظار"
                                                         data:collapsed="false" style="margin: 0px 8px;"><span
                                                            class="apexcharts-legend-marker" rel="3"
                                                            data:collapsed="false"
                                                            style="height: 14px; width: 14px; left: -2px; top: -0.5px;"><svg
                                                                xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                                height="100%"><path d="M 0, 0
           m -6, 0
           a 6,6 0 1,0 12,0
           a 6,6 0 1,0 -12,0" fill="#ad63f6" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                                    stroke-linecap="butt"
                                                                                    stroke-width="1"
                                                                                    stroke-dasharray="0" cx="0" cy="0"
                                                                                    shape="circle"
                                                                                    class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                                    style="transform: translate(50%, 50%);"></path></svg></span><span
                                                            class="apexcharts-legend-text" rel="3" i="2"
                                                            data:default-text="%D8%AF%D8%B1%20%D8%A7%D9%86%D8%AA%D8%B8%D8%A7%D8%B1"
                                                            data:collapsed="false"
                                                            style="color: rgb(100, 116, 139); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">در انتظار</span>
                                                    </div>
                                                </div>
                                                <style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }

                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }

                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }

                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *, .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }

                                                </style>
                                            </foreignObject>
                                            <g class="apexcharts-inner apexcharts-graphical"
                                               transform="translate(0, 26)">
                                                <defs>
                                                    <clipPath id="gridRectMask8db0ssht">
                                                        <rect width="343" height="271" x="0" y="0" rx="0" ry="0"
                                                              opacity="1" stroke-width="0" stroke="none"
                                                              stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMask8db0ssht">
                                                        <rect width="349" height="277" x="-3" y="-3" rx="0" ry="0"
                                                              opacity="1" stroke-width="0" stroke="none"
                                                              stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMask8db0ssht">
                                                        <rect width="343" height="271" x="0" y="0" rx="0" ry="0"
                                                              opacity="1" stroke-width="0" stroke="none"
                                                              stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask8db0ssht"></clipPath>
                                                    <clipPath id="nonForecastMask8db0ssht"></clipPath>
                                                </defs>
                                                <g class="apexcharts-pie">
                                                    <g transform="translate(0, 0) scale(1)">
                                                        <circle r="82.0268292682927" cx="171.5" cy="135.5"
                                                                fill="transparent"></circle>
                                                        <g class="apexcharts-slices">
                                                            <g class="apexcharts-series apexcharts-pie-series"
                                                               seriesName="تکمیلxشده" rel="1" data:realIndex="0">
                                                                <path
                                                                    d="M 171.5 9.304878048780466 A 126.19512195121953 126.19512195121953 0 1 1 97.32436840582302 237.5939982657556 L 123.28583946378497 201.86109887274114 A 82.0268292682927 82.0268292682927 0 1 0 171.5 53.4731707317073 L 171.5 9.304878048780466 z "
                                                                    fill="rgba(55,216,10,1)" fill-opacity="1"
                                                                    stroke="#ffffff" stroke-opacity="1"
                                                                    stroke-linecap="butt" stroke-width="2"
                                                                    stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                                    index="0" j="0" data:angle="216" data:startAngle="0"
                                                                    data:strokeWidth="2" data:value="60"
                                                                    data:pathOrig="M 171.5 9.304878048780466 A 126.19512195121953 126.19512195121953 0 1 1 97.32436840582302 237.5939982657556 L 123.28583946378497 201.86109887274114 A 82.0268292682927 82.0268292682927 0 1 0 171.5 53.4731707317073 L 171.5 9.304878048780466 z "></path>
                                                            </g>
                                                            <g class="apexcharts-series apexcharts-pie-series"
                                                               seriesName="سفارشxجدید" rel="2" data:realIndex="1">
                                                                <path
                                                                    d="M 97.32436840582302 237.5939982657556 A 126.19512195121953 126.19512195121953 0 0 1 97.324368405823 33.40600173424443 L 123.28583946378495 69.13890112725888 A 82.0268292682927 82.0268292682927 0 0 0 123.28583946378497 201.86109887274114 L 97.32436840582302 237.5939982657556 z "
                                                                    fill="rgba(96,93,255,1)" fill-opacity="1"
                                                                    stroke="#ffffff" stroke-opacity="1"
                                                                    stroke-linecap="butt" stroke-width="2"
                                                                    stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                                    index="0" j="1" data:angle="108"
                                                                    data:startAngle="216" data:strokeWidth="2"
                                                                    data:value="30"
                                                                    data:pathOrig="M 97.32436840582302 237.5939982657556 A 126.19512195121953 126.19512195121953 0 0 1 97.324368405823 33.40600173424443 L 123.28583946378495 69.13890112725888 A 82.0268292682927 82.0268292682927 0 0 0 123.28583946378497 201.86109887274114 L 97.32436840582302 237.5939982657556 z "></path>
                                                            </g>
                                                            <g class="apexcharts-series apexcharts-pie-series"
                                                               seriesName="درxانتظار" rel="3" data:realIndex="2">
                                                                <path
                                                                    d="M 97.324368405823 33.40600173424443 A 126.19512195121953 126.19512195121953 0 0 1 171.47797479633175 9.30487997084208 L 171.48568361761565 53.473171981047344 A 82.0268292682927 82.0268292682927 0 0 0 123.28583946378495 69.13890112725888 L 97.324368405823 33.40600173424443 z "
                                                                    fill="rgba(173,99,246,1)" fill-opacity="1"
                                                                    stroke="#ffffff" stroke-opacity="1"
                                                                    stroke-linecap="butt" stroke-width="2"
                                                                    stroke-dasharray="0"
                                                                    class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                                    index="0" j="2" data:angle="36"
                                                                    data:startAngle="324" data:strokeWidth="2"
                                                                    data:value="10"
                                                                    data:pathOrig="M 97.324368405823 33.40600173424443 A 126.19512195121953 126.19512195121953 0 0 1 171.47797479633175 9.30487997084208 L 171.48568361761565 53.473171981047344 A 82.0268292682927 82.0268292682927 0 0 0 123.28583946378495 69.13890112725888 L 97.324368405823 33.40600173424443 z "></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line x1="0" y1="0" x2="343" y2="0" stroke="#b6b6b6"
                                                      stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                      class="apexcharts-ycrosshairs"></line>
                                                <line x1="0" y1="0" x2="343" y2="0" stroke="#b6b6b6"
                                                      stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                      class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g class="apexcharts-datalabels-group"
                                               transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-datalabels-group"
                                               transform="translate(0, 0) scale(1)"></g>
                                        </svg>
                                        <div class="apexcharts-tooltip apexcharts-theme-dark">
                                            <div
                                                class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                                style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                                        style="background-color: rgb(55, 216, 10);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                     style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                            <div
                                                class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                                style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                                        style="background-color: rgb(96, 93, 255);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                     style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                            <div
                                                class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-2"
                                                style="order: 3;"><span class="apexcharts-tooltip-marker"
                                                                        style="background-color: rgb(173, 99, 246);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                     style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-y-label"></span><span
                                                            class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span
                                                            class="apexcharts-tooltip-text-goals-label"></span><span
                                                            class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- لایه تار با دکمه بزودی -->

                    <div class="blur-overlay">
                        <img src="/client/soon2.png" alt="بزودی">
                    </div>
                </div>
            </div>

        </div>
    </div>

        @push('script')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('welcome-modal');
                    if (!modal) return;

                    const card = modal.querySelector('.welcome-modal-card');
                    const dots = Array.from(modal.querySelectorAll('.welcome-confetti span'));
                    const dismiss = document.getElementById('welcome-dismiss');
                    const storageKey = 'clientDashboardWelcomeSeen_v1.0.0.1';

                    const showModal = () => {
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden'; // قفل کردن اسکرول صفحه

                        requestAnimationFrame(() => {
                            modal.classList.remove('opacity-0');
                            card.classList.add('show');

                            // انیمیشن confetti
                            dots.forEach((dot, index) => {
                                setTimeout(() => {
                                    dot.classList.add('animate');
                                }, index * 30);
                            });
                        });
                    };

                    const hideModal = () => {
                        modal.classList.add('opacity-0');
                        card.classList.remove('show');

                        setTimeout(() => {
                            modal.classList.add('hidden');
                            document.body.style.overflow = ''; // بازگرداندن اسکرول
                        }, 350);
                    };

                    // نمایش مودال اگر قبلاً ندیده
                    if (!localStorage.getItem(storageKey)) {
                        setTimeout(showModal, 500); // تاخیر کوتاه برای بارگذاری کامل صفحه
                    }

                    // بستن مودال
                    dismiss?.addEventListener('click', () => {
                        localStorage.setItem(storageKey, 'true');
                        hideModal();
                    });

                    // بستن با کلیک روی backdrop
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            localStorage.setItem(storageKey, 'true');
                            hideModal();
                        }
                    });

                    // بستن با ESC
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                            localStorage.setItem(storageKey, 'true');
                            hideModal();
                        }
                    });
                });
            </script>
        @endpush
</div>
