<div class="max-w-7xl space-y-8 px-4 mx-auto">
    @push('link')
        <link rel="stylesheet" href="/client/assets/css/apexcharts.css"/>
        <style>
            /* ==================== Variables ==================== */
            :root {
                --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --card-bg-light: rgba(255, 255, 255, 0.95);
                --card-bg-dark: rgba(30, 41, 59, 0.8);
                --border-light: rgba(226, 232, 240, 0.8);
                --border-dark: rgba(148, 163, 184, 0.15);
                --hover-shadow: 0 10px 40px rgba(102, 126, 234, 0.15);
            }

            /* ==================== Base Styles ==================== */
            .dashboard-container {
                animation: fadeIn 0.6s ease-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* ==================== Card Styles ==================== */
            .dashboard-card {
                background: var(--card-bg-light);
                border: 1px solid var(--border-light);
                border-radius: 16px;
                padding: 1.5rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                backdrop-filter: blur(10px);
            }

            .dark .dashboard-card {
                background: var(--card-bg-dark);
                border-color: var(--border-dark);
            }

            .dashboard-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--hover-shadow);
            }

            /* ==================== Section Title ==================== */
            .section-title {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
            }

            .section-title-dots {
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .section-title-dots .dot {
                border-radius: 50%;
                background: currentColor;
            }

            .section-title-dots .dot-1 {
                width: 4px;
                height: 4px;
            }

            .section-title-dots .dot-2 {
                width: 8px;
                height: 8px;
            }

            /* ==================== Profile Cards ==================== */
            .profile-card {
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                border: 1px solid rgba(102, 126, 234, 0.2);
                border-radius: 16px;
                padding: 1.5rem;
                text-align: center;
                transition: all 0.3s ease;
            }

            .profile-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
                animation: rotate 20s linear infinite;
            }

            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .profile-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 30px rgba(102, 126, 234, 0.2);
            }

            .profile-avatar {
                position: relative;
                width: 64px;
                height: 64px;
                margin: 0 auto 1rem;
                border-radius: 50%;
                background: var(--primary-gradient);
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            }

            .profile-avatar svg {
                width: 32px;
                height: 32px;
                color: white;
            }

            /* ==================== Today's Program ==================== */
            .program-list {
                max-height: 400px;
                overflow-y: auto;
                padding-right: 0.5rem;
            }

            .program-list::-webkit-scrollbar {
                width: 6px;
            }

            .program-list::-webkit-scrollbar-track {
                background: rgba(148, 163, 184, 0.1);
                border-radius: 10px;
            }

            .program-list::-webkit-scrollbar-thumb {
                background: rgba(102, 126, 234, 0.5);
                border-radius: 10px;
            }

            .program-list::-webkit-scrollbar-thumb:hover {
                background: rgba(102, 126, 234, 0.7);
            }

            .program-item {
                background: rgba(148, 163, 184, 0.05);
                border: 1px solid rgba(148, 163, 184, 0.1);
                border-radius: 12px;
                padding: 1rem;
                margin-bottom: 0.75rem;
                transition: all 0.3s ease;
            }

            .dark .program-item {
                background: rgba(148, 163, 184, 0.08);
                border-color: rgba(148, 163, 184, 0.15);
            }

            .program-item:hover {
                background: rgba(102, 126, 234, 0.08);
                border-color: rgba(102, 126, 234, 0.3);
                transform: translateX(-4px);
            }

            .program-item:last-child {
                margin-bottom: 0;
            }

            .program-badge {
                display: inline-block;
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                font-weight: 600;
                border-radius: 8px;
                background: rgba(102, 126, 234, 0.1);
                color: #667eea;
                border: 1px solid rgba(102, 126, 234, 0.2);
            }

            /* ==================== Progress Bar ==================== */
            .progress-container {
                width: 100%;
                height: 12px;
                background: rgba(148, 163, 184, 0.15);
                border-radius: 10px;
                overflow: hidden;
                position: relative;
            }

            .dark .progress-container {
                background: rgba(148, 163, 184, 0.1);
            }

            .progress-bar {
                height: 100%;
                background: var(--primary-gradient);
                border-radius: 10px;
                transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .progress-bar::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                animation: shimmer 2s infinite;
            }

            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }

            .progress-bar-extra {
                height: 100%;
                background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
                border-radius: 0 10px 10px 0;
                transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* ==================== Stats Grid ==================== */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .stat-card {
                background: rgba(102, 126, 234, 0.05);
                border: 1px solid rgba(102, 126, 234, 0.15);
                border-radius: 16px;
                padding: 1.5rem;
                transition: all 0.3s ease;
            }

            .dark .stat-card {
                background: rgba(102, 126, 234, 0.08);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                background: rgba(102, 126, 234, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .stat-icon svg {
                width: 24px;
                height: 24px;
                color: #667eea;
            }

            /* ==================== Empty State ==================== */
            .empty-state {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 3rem 1rem;
                text-align: center;
            }

            .empty-state svg {
                width: 80px;
                height: 80px;
                opacity: 0.3;
                margin-bottom: 1rem;
            }

            /* ==================== Days Grid ==================== */
            .days-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .day-circle {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.875rem;
                font-weight: 700;
                transition: all 0.3s ease;
            }

            .day-circle.completed {
                background: var(--primary-gradient);
                color: white;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }

            .day-circle.pending {
                background: rgba(148, 163, 184, 0.1);
                color: rgba(148, 163, 184, 0.5);
                border: 2px dashed rgba(148, 163, 184, 0.2);
            }

            /* ==================== Notification Alert ==================== */
            .notification-alert {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(52, 211, 153, 0.1) 100%);
                border: 1px solid rgba(16, 185, 129, 0.3);
                border-radius: 16px;
                padding: 1rem 1.5rem;
                transition: all 0.3s ease;
            }

            .notification-alert:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(52, 211, 153, 0.15) 100%);
            }

            /* ==================== Responsive ==================== */
            @media (max-width: 768px) {
                .dashboard-card {
                    padding: 1rem;
                }

                .profile-avatar {
                    width: 56px;
                    height: 56px;
                }

                .profile-avatar svg {
                    width: 28px;
                    height: 28px;
                }

                .program-list {
                    max-height: 300px;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .day-circle {
                    width: 32px;
                    height: 32px;
                    font-size: 0.75rem;
                }

                .section-title {
                    font-size: 1.125rem;
                }
            }

            @media (max-width: 640px) {
                .dashboard-card {
                    border-radius: 12px;
                }

                .profile-card {
                    padding: 1rem;
                }

                .stat-card {
                    padding: 1rem;
                }

                .days-grid {
                    gap: 0.375rem;
                }

                .day-circle {
                    width: 28px;
                    height: 28px;
                    font-size: 0.7rem;
                }
            }
        </style>
    @endpush

    <div class="dashboard-container">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <!-- Sidebar -->
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-8">
                    <!-- Notification Alert -->
                    @if($student && $unreadNotificationsCount > 0)
                        <a wire:navigate
                           href="{{ route('client.profile.notification') }}"
                           class="notification-alert block group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-500/20 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-foreground flex items-center gap-2">
                                            مشاهده پیام‌ها
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                            </svg>
                                        </div>
                                        <div class="text-muted text-xs mt-1">
                                            شما <span class="font-bold text-green-600">{{ $unreadNotificationsCount }}</span> پیام خوانده نشده دارید
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif

                    <!-- Dashboard Title -->
                    <div class="section-title">
                        <div class="section-title-dots">
                            <div class="dot dot-1"></div>
                            <div class="dot dot-2"></div>
                        </div>
                        <h2 class="font-black text-foreground text-xl">داشبورد</h2>
                    </div>

                    <!-- Support & Advisor Cards -->
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-6">
                        <!-- پشتیبان من -->
                        <div class="profile-card">
                            <h3 class="font-bold text-base text-foreground mb-4 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                پشتیبان من
                            </h3>
                            <div class="profile-avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            @if($supporterStudent)
                                <span class="font-bold text-base text-foreground">{{ $supporterStudent->name }}</span>
                            @else
                                <span class="text-sm text-muted">تعیین نشده است</span>
                            @endif
                        </div>

                        <!-- مشاور من -->
                        <div class="profile-card">
                            <h3 class="font-bold text-base text-foreground mb-4 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                </svg>
                                مشاور من
                            </h3>
                            <div class="profile-avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 01.672 0 41.059 41.059 0 018.198 5.424.75.75 0 01-.254 1.285 31.372 31.372 0 00-7.86 3.83.75.75 0 01-.84 0 31.508 31.508 0 00-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 013.305-2.033.75.75 0 00-.714-1.319 37 37 0 00-3.446 2.12A2.216 2.216 0 006 9.393v.38a31.293 31.293 0 00-4.28-1.746.75.75 0 01-.254-1.285 41.059 41.059 0 018.198-5.424zM6 11.459a29.848 29.848 0 00-2.455-1.158 41.029 41.029 0 00-.39 3.114.75.75 0 00.419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 101.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 013.095 2.348.75.75 0 00.992 0 26.547 26.547 0 015.93-3.95.75.75 0 00.42-.739 41.053 41.053 0 00-.39-3.114 29.925 29.925 0 00-5.199 2.801 2.25 2.25 0 01-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 01-1.084 3.45 26.503 26.503 0 00-1.281-.78A5.487 5.487 0 006 12v-.54z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @if($advisorStudent)
                                <span class="font-bold text-base text-foreground">{{ $advisorStudent->name }}</span>
                            @else
                                <span class="text-sm text-muted">تعیین نشده است</span>
                            @endif
                        </div>
                    </div>

                    <!-- Today's Program -->
                    <div class="dashboard-card">
                        <div class="section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-foreground">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <h3 class="font-bold text-lg text-foreground">برنامه امروز من</h3>
                        </div>

                        @if(count($todayProgram) > 0)
                            <div class="program-list">
                                @foreach($todayProgram as $index => $part)
                                    <div class="program-item">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="flex items-center justify-center w-8 h-8 bg-foreground/10 rounded-lg flex-shrink-0 font-bold text-sm">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="flex-1 space-y-2">
                                                    <h4 class="font-bold text-foreground text-sm">{{ $part->lesson->name ?? 'درس' }}</h4>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        @if($part->ccSubject)
                                                            <span class="program-badge">{{ $part->ccSubject->name }}</span>
                                                        @endif
                                                        @if($part->ccChapter)
                                                            <span class="program-badge">{{ $part->ccChapter->name }}</span>
                                                        @endif
                                                        <span class="program-badge">{{ $part->part_type_label }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end text-left space-y-1 flex-shrink-0">
                                                <div class="flex items-center gap-1 text-sm font-bold text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $part->duration_hours }} ساعت
                                                </div>
                                                @if($part->test_count)
                                                    <span class="text-xs text-muted">{{ $part->test_count }} تست</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-muted">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <p class="text-muted font-semibold">برنامه‌ای برای امروز تعریف نشده است</p>
                                <p class="text-sm text-muted mt-2">منتظر برنامه جدید از مشاور خود باشید</p>
                            </div>
                        @endif
                    </div>
                    <!-- Class Schedule Section (برنامه کلاسی) -->
                    @if($classSchedule)
                        <div class="dashboard-card">
                            <div class="section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-foreground">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <h3 class="font-bold text-lg text-foreground">برنامه کلاسی من</h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 text-xs rounded-full font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    نهایی شده
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                    <tr>
                                        <th class="text-right py-2 px-3 font-bold text-foreground border-b border-border" style="min-width: 80px;">روز</th>
                                        @for($p = 1; $p <= 5; $p++)
                                            <th class="text-center py-2 px-3 font-bold text-foreground border-b border-border">پارت {{ $p }}</th>
                                        @endfor
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @for($d = 0; $d < 7; $d++)
                                        <tr class="{{ in_array($d, [5, 6]) ? 'opacity-60' : '' }}">
                                            <td class="py-2 px-3 font-bold text-foreground border-b border-border/50">
                                                {{ \App\Models\ClassSchedule::getDayName($d) }}
                                            </td>
                                            @for($p = 1; $p <= 5; $p++)
                                                @php
                                                    $cPart = $classSchedule->parts->where('day_of_week', $d)->where('part_order', $p)->first();
                                                @endphp
                                                <td class="text-center py-2 px-3 border-b border-border/50">
                                                    @if($cPart)
                                                        <span class="inline-block px-2 py-1 rounded-lg text-xs font-semibold"
                                                              style="background: rgba(102, 126, 234, 0.1); color: #667eea; border: 1px solid rgba(102, 126, 234, 0.2);">
                                                            {{ $cPart->lesson_name }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <!-- TO DO Section -->
                    <div class="dashboard-card">
                        <div class="section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-foreground">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="font-bold text-lg text-foreground">TO DO</h3>
                        </div>

                        <div class="stats-grid">
                            <!-- ساعت مطالعه -->
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-foreground">ساعت مطالعه</h4>
                                    <span class="font-bold text-lg" style="color: #667eea;">{{ $studyHoursProgress['total_hours'] }} ساعت</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="progress-container">
                                        <div class="flex">
                                            <div class="progress-bar" style="width: {{ $studyHoursProgress['percentage'] }}%"></div>
                                            @if($studyHoursProgress['extra_percentage'] > 0)
                                                <div class="progress-bar-extra" style="width: {{ min($studyHoursProgress['extra_percentage'], 50) }}%"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-muted">{{ $studyHoursProgress['completed_hours'] }} از {{ $studyHoursProgress['total_hours'] }} ساعت</span>
                                        <span class="font-bold" style="color: #667eea;">{{ round($studyHoursProgress['percentage']) }}%</span>
                                    </div>
                                    @if($studyHoursProgress['extra_hours'] > 0)
                                        <div class="flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-lg" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                                            </svg>
                                            {{ $studyHoursProgress['extra_hours'] }} ساعت مطالعه اضافی! عالی هستی 🎉
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ارسال گزارش -->
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-foreground">ارسال گزارش</h4>
                                    <span class="font-bold text-lg" style="color: #667eea;">{{ $reportProgress['submitted_days'] }}/{{ $reportProgress['total_days'] }}</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ $reportProgress['percentage'] }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-muted">{{ $reportProgress['submitted_days'] }} روز ثبت شده</span>
                                        <span class="font-bold" style="color: #667eea;">{{ round($reportProgress['percentage']) }}%</span>
                                    </div>
                                    <div class="days-grid">
                                        @for($i = 0; $i < 7; $i++)
                                            <div class="day-circle {{ $i < $reportProgress['submitted_days'] ? 'completed' : 'pending' }}">
                                                {{ $i + 1 }}
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
