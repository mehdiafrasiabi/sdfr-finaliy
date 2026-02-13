<div>
    @push('link')

        <style>
            [x-cloak] {
                display: none !important;
            }

            .update-notif-logo-box {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 1.25rem;
                border-radius: 1.5rem;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(8px);
            }

            .update-notif-logo-pulse {
                animation: updateNotifPulse 10s ease-in-out infinite;
            }

            @keyframes updateNotifPulse {
                0%, 100% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgb(2, 9, 198);
                }
                50% {
                    transform: scale(1.05);
                    box-shadow: 0 0 30px 10px rgba(152, 149, 255, 0.2);
                }
            }

            .update-notif-list {
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 1rem;
                padding: 1.25rem;
            }

            .update-notif-list-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                color: rgba(255, 255, 255, 0.85);
                font-size: 0.9rem;
                font-weight: 500;
            }

            .update-notif-check {
                flex-shrink: 0;
                width: 1.75rem;
                height: 1.75rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }

            .update-notif-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.875rem 2.5rem;
                border-radius: 0.875rem;
                color: white;
                font-weight: 800;
                font-size: 1rem;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 20px rgba(81, 77, 204, 0.4);
            }

            .update-notif-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px rgba(81, 77, 204, 0.5);
            }

            .update-notif-btn:active {
                transform: translateY(0);
            }

            .update-notif-progress-track {
                width: 100%;
                height: 10px;
                background: rgba(255, 255, 255, 0.12);
                border-radius: 10px;
                overflow: hidden;
            }

            .update-notif-progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #9895FF, #514DCC, #9895FF);
                background-size: 200% 100%;
                border-radius: 10px;
                transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                animation: updateNotifShimmer 1.5s linear infinite;
            }

            @keyframes updateNotifShimmer {
                0% {
                    background-position: 200% 0;
                }
                100% {
                    background-position: -200% 0;
                }
            }

            /* انیمیشن‌های نرم‌تر برای transition بین مراحل */
            .step-transition-enter {
                animation: stepFadeInScale 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }

            .step-transition-leave {
                animation: stepFadeOutScale 0.5s cubic-bezier(0.4, 0, 1, 1) forwards;
            }

            @keyframes stepFadeInScale {
                0% {
                    opacity: 0;
                    transform: scale(0.85) translateY(20px);
                }
                100% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            @keyframes stepFadeOutScale {
                0% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
                100% {
                    opacity: 0;
                    transform: scale(0.95) translateY(-10px);
                }
            }
        </style>
    @endpush
    <div
        x-data="{
        show: false,
        step: 'info',
        progress: 0,
        userId: @js($userId),
        storageKey: '',

        init() {
            this.storageKey = 'sdfr_update_seen_v2_' + this.userId;
            if (!localStorage.getItem(this.storageKey)) {
                this.show = true;
                document.body.style.overflow = 'hidden';
            }
        },

        startUpdate() {
            this.step = 'progress';
            this.animateProgress();
        },

        animateProgress() {
            // مراحل پروگرس به ترتیب: [مقدار, مدت زمان به میلی‌ثانیه]
            const stages = [
                { target: 20, duration: 1500 },
                { target: 33, duration: 1300 },
                { target: 58, duration: 2000 },
                { target: 43, duration: 800 },  // کاهش پیدا می‌کنه
                { target: 68, duration: 1800 },
                { target: 83, duration: 1400 },
                { target: 100, duration: 1200 }
            ];

            let currentStage = 0;

            const runStage = () => {
                if (currentStage >= stages.length) {
                    setTimeout(() => {
                        this.step = 'welcome';
                    }, 400);
                    return;
                }

                const stage = stages[currentStage];
                const startValue = this.progress;
                const targetValue = stage.target;
                const duration = stage.duration;
                const startTime = performance.now();

                const animate = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const fraction = Math.min(elapsed / duration, 1);

                    // از easing function برای حرکت نرم‌تر استفاده می‌کنیم
                    const eased = this.easeInOutCubic(fraction);
                    this.progress = Math.round(startValue + (targetValue - startValue) * eased);

                    if (fraction < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        currentStage++;
                        // یک توقف کوتاه بین مراحل
                        setTimeout(runStage, 100);
                    }
                };

                requestAnimationFrame(animate);
            };

            runStage();
        },

        // تابع easing برای حرکت نرم‌تر
        easeInOutCubic(t) {
            return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
        },

        dismiss() {
            localStorage.setItem(this.storageKey, 'true');
            document.body.style.overflow = '';
            this.show = false;
        }
    }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[9999]"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md"></div>

        {{-- Content Container --}}
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4">

            {{-- Step 1: Info --}}
            <div
                x-show="step === 'info'"
                x-transition:enter="transition ease-out duration-600"
                x-transition:enter-start="opacity-0 scale-85 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
                class="w-full max-w-md mx-auto text-center"
            >
                {{-- Logo --}}
                <div class="flex justify-center mb-8">
                    <div class="update-notif-logo-box">
                        <img src="/client/assets/images/favicon.svg" class="w-20 h-20">
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="text-3xl md:text-3xl font-black text-white mb-6">
                    نسخه 2.1 SDFR
                </h1>

                {{-- Update List --}}
                <div class="update-notif-list mb-8 bg-secondary">
                    <ul class="space-y-3 text-right">
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4 ">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>بهبود رابط کاربری و طراحی جدید پنل</span>
                        </li>

                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>افزایش سرعت بارگذاری صفحات</span>
                        </li>

                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>به روزرسانی سیستم برنامه ریزی درسی</span>
                        </li>
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>به روزرسانی سیستم گزارش دهی</span>
                        </li>
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>بهبود جزییات کارنامه وضعیت تحصیلی</span>
                        </li>
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>به روزرسانی سیستم اطلاع رسانی و پیامکی</span>
                        </li>
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>هوشمند سازی و بهبود عملکرد ثبت ساعت مطالعه</span>
                        </li>
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>اضافه شدن سیستم طبقه بندی هوشمند مباحث</span>
                        </li>
                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>بهبود و هوشمند سازی سازی سیستم آزمون دهی</span>
                        </li>

                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>اضافه شدن کیف پول و پاداش به دانش آموزان</span>
                        </li>

                        <li class="update-notif-list-item">
                            <div class="update-notif-check bg-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span>رفع مشکلات و باگ‌های گزارش شده</span>
                        </li>
                    </ul>
                </div>

                {{-- Update Button --}}
                <button
                    @click="startUpdate()"
                    class="update-notif-btn bg-primary"
                >
                    شروع به روزرسانی
                </button>
            </div>

            {{-- Step 2: Progress --}}
            <div
                x-show="step === 'progress'"
                x-transition:enter="transition ease-out duration-600"
                x-transition:enter-start="opacity-0 scale-85 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-sm mx-auto text-center"
            >
                {{-- Logo --}}
                <div class="flex justify-center mb-8">
                    <div class="update-notif-logo-box">
                        <img src="/client/assets/images/favicon.svg" class="w-20 h-20">
                    </div>
                </div>

                <p class="text-white font-bold text-lg mb-6">در حال به روزرسانی پنل...</p>

                {{-- Progress Bar --}}
                <div class="update-notif-progress-track">
                    <div
                        class="update-notif-progress-fill"
                        :style="'width: ' + progress + '%'"
                    ></div>
                </div>

                <p class="text-white/70 text-sm mt-3 font-semibold" x-text="progress + '%'"></p>
            </div>

            {{-- Step 3: Welcome --}}
            <div
                x-show="step === 'welcome'"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-75 translate-y-12"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="w-full max-w-md mx-auto text-center"
            >
                {{-- Logo --}}
                <div class="flex justify-center mb-8">
                    <div class="update-notif-logo-box update-notif-logo-pulse">
                        <img src="/client/assets/images/favicon.svg" class="w-20 h-20">
                    </div>
                </div>

                <h1 class="text-2xl md:text-3xl font-black text-white mb-4">
                    پنل شما با موفقیت به روزرسانی شد.
                </h1>

                <p class="text-white/60 text-sm mb-8"></p>

                <button
                    @click="dismiss()"
                    class="update-notif-btn bg-primary"
                >
                    ورود به پنل
                </button>
            </div>
        </div>

    </div>
</div>
