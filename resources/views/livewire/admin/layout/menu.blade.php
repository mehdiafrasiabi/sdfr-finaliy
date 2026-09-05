<div>
    @php
        $admin = auth('admin')->user();
        $generalAdminMenu = $admin?->hasRole('super admin') || $admin?->hasRole('educational-manager') || $admin?->hasRole('مشاور تحصیلی');
        // مدیر آموزشی فقط زمانی منوی دانش‌آموزان را می‌بیند که هم‌زمان مشاور تحصیلی هم باشد.
        $studentMenu = $admin?->hasRole('مشاور تحصیلی') || $admin?->hasRole('super admin');
        $educationalManagerActive = request()->routeIs('admin.educational-manager.*');
        $acquisitionMenu = $admin?->hasRole('site acquisition') || $admin?->hasRole('مشاور جذب تلفنی') || $admin?->hasRole('super admin');
        $unifiedAcquisitionRole = $admin?->hasRole('site acquisition') || $admin?->hasRole('مشاور جذب تلفنی') || $admin?->hasRole('super admin');
        $examMonitorTrialId = null;

        if ($unifiedAcquisitionRole) {
            $examMonitorTrialId = \App\Models\TrialWeek::query()
                ->whereHas('student.examSchedules', fn ($scheduleQuery) => $scheduleQuery
                    ->whereNotNull('weekly_program_id')
                    ->whereNotNull('program_built_at'))
                ->when(! $admin?->hasRole('super admin'), fn ($query) => $query->where('acquisition_supporter_id', $admin->id))
                ->latest()
                ->value('id');
        }

        $examMonitorHref = $examMonitorTrialId
            ? route('admin.trial-acquisition.exam-monitor', $examMonitorTrialId)
            : route('admin.trial-acquisition.exam-program-students');
    @endphp
        <!-- begin::NexLink Sidebar Menu -->
    <aside class="app-menubar-tabs" id="appMenubar">
        <div class="app-navbar-brand">
            <a class="navbar-brand-logo" href="{{route('admin.dashboard.index')}}">
                <img alt="NexLink Admin Dashboard Logo" src="/admin/assets/images/logo.svg"/>
            </a>
        </div>
        <div class="app-navbar-tabs" data-simplebar="">
            <ul aria-orientation="vertical" class="nav" id="appMenubarTabs" role="tablist">
                @if($admin?->hasRole('school-manager') || $admin?->hasRole('super admin'))
                    <li class="nav-item" data-bs-placement="right" data-bs-title="پنل مدیر مدرسه" data-bs-toggle="tooltip">
                        <a aria-controls="schoolManagerTab" aria-selected="false"
                           class="menu-link {{ $admin?->hasRole('school-manager') && !$admin?->hasRole('super admin') ? 'active' : '' }}"
                           data-bs-toggle="tab" href="#schoolManagerTab" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="menu-icon">
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                            </svg>
                        </a>
                    </li>
                @endif


                @if($generalAdminMenu || $acquisitionMenu)
                    @if($generalAdminMenu)
                    <li class="nav-item" data-bs-placement="right" data-bs-title="داشبورد" data-bs-toggle="tooltip">
                        <a aria-controls="dashboardTab" aria-selected="true"
                           class="menu-link {{ ((($admin?->hasRole('site acquisition') || $admin?->hasRole('مشاور جذب تلفنی')) && !$admin?->hasRole('super admin')) || request()->routeIs('admin.trial-acquisition.*') || request()->routeIs('admin.phone-acquisition.*') || request()->routeIs('admin.notifications.index') || $educationalManagerActive) ? '' : 'active' }}"
                           data-bs-toggle="tab" href="#dashboardTab" role="tab">
                            <svg fill="none" height="24" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    opacity="0.5" stroke="var(--bs-heading-color)" stroke-width="2">
                                </path>
                                <path d="M12 15V18" stroke="var(--bs-heading-color)" stroke-linecap="round"
                                      stroke-width="2">
                                </path>
                            </svg>
                        </a>
                    </li>
                    @if($studentMenu)
                    <li class="nav-item" data-bs-placement="right" data-bs-title="دانش آموزان" data-bs-toggle="tooltip">
                        <a aria-controls="appsTab" aria-selected="false" class="menu-link" data-bs-toggle="tab"
                           href="#appsTab" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="icon icon-tabler icons-tabler-outline icon-tabler-school menu-icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                            </svg>
                        </a>
                    </li>
                    @endif
                    @endif

                        @if($acquisitionMenu)
                            <li class="nav-item" data-bs-placement="right" data-bs-title="مشاوره جذب" data-bs-toggle="tooltip">
                                <a aria-controls="acquisitionTab" aria-selected="false"
                                   class="menu-link {{ (($admin?->hasRole('site acquisition') || $admin?->hasRole('مشاور جذب تلفنی')) && !$admin?->hasRole('super admin')) ? 'active' : '' }} {{ request()->routeIs('admin.trial-acquisition.*') || request()->routeIs('admin.phone-acquisition.*') || request()->routeIs('admin.notifications.index') ? 'active' : '' }}"
                                   data-bs-toggle="tab" href="#acquisitionTab" role="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="menu-icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                        <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                        <path d="M15 5a4 4 0 0 1 0 6"></path>
                                        <path d="M17 3a8 8 0 0 1 0 10"></path>
                                    </svg>
                                </a>
                            </li>
                        @endif


                    @if($generalAdminMenu)
                    <li class="nav-item" data-bs-placement="right" data-bs-title="{{ $admin?->hasRole('educational-manager') && !$admin?->hasRole('super admin') ? 'مدیر آموزشی' : 'مدیریت ادمین‌ها' }}"
                        data-bs-toggle="tooltip">
                        <a aria-controls="adminUsersTab" aria-selected="{{ $educationalManagerActive ? 'true' : 'false' }}" class="menu-link {{ $educationalManagerActive ? 'active' : '' }}" data-bs-toggle="tab"
                           href="#adminUsersTab" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="icon icon-tabler icons-tabler-outline icon-tabler-user-shield menu-icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2"></path>
                                <path d="M12 11a4 4 0 1 0 0 -8a4 4 0 0 0 0 8z"></path>
                                <path d="M22 16c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5z"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-placement="right" data-bs-title="همکاری با مدارس"
                        data-bs-toggle="tooltip">
                        <a aria-controls="adminUsersTab" aria-selected="false" class="menu-link" data-bs-toggle="tab"
                           href="#cooperationSchoolTab" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="icon icon-tabler icons-tabler-outline icon-tabler-user-shield menu-icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2"></path>
                                <path d="M12 11a4 4 0 1 0 0 -8a4 4 0 0 0 0 8z"></path>
                                <path d="M22 16c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5z"></path>
                            </svg>
                        </a>
                    </li>
                    @endif
                @endif
            </ul>
        </div>
        <div class="app-tab-content">
            <div class="app-side-brands">
                <a class="navbar-brand-text" href="{{route('admin.dashboard.index')}}">
                    NexLink
                </a>
            </div>
            <div class="app-content-inner">
                <div class="tab-content" id="appMenubarTabsContent">
                    @if($admin?->hasRole('school-manager') || $admin?->hasRole('super admin'))
                        <div class="tab-pane fade {{ $admin?->hasRole('school-manager') && !$admin?->hasRole('super admin') ? 'show active' : '' }}"
                             id="schoolManagerTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    <li class="menu-heading">
                                        <span class="menu-label">پنل مدیر مدرسه</span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.dashboard') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.dashboard') }}" role="button">
                                            <i class="fi fi-rr-dashboard"></i>
                                            <span class="menu-label">داشبورد</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.student.index') ? 'active' : '' }}"
                                           href="{{ route('admin.student.index') }}" role="button">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                            <span class="menu-label">دانش‌آموزان مدرسه</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.academic-status') || request()->routeIs('admin.school-manager.student.progress') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.academic-status') }}" role="button">
                                            <i class="fi fi-rr-chart-histogram"></i>
                                            <span class="menu-label">وضعیت تحصیلی</span>
                                        </a>
                                    </li>

                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.report-cards') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.report-cards') }}" role="button">
                                            <i class="fi fi-rr-trophy"></i>
                                            <span class="menu-label">کارنامه ماهانه</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.classification-insights') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.classification-insights') }}" role="button">
                                            <i class="fi fi-rr-megaphone"></i>
                                            <span class="menu-label">تحلیل دبیران</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.exam-stats') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.exam-stats') }}" role="button">
                                            <i class="fi fi-rr-stats"></i>
                                            <span class="menu-label">آمار آزمون‌ها</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.call-report') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.call-report') }}" role="button">
                                            <i class="fi fi-rr-phone-call"></i>
                                            <span class="menu-label">گزارش تماس‌ها</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.school-manager.advising') ? 'active' : '' }}"
                                           href="{{ route('admin.school-manager.advising') }}" role="button">
                                            <i class="fi fi-rr-comments"></i>
                                            <span class="menu-label">آمار جلسات مشاوره</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                    @if($acquisitionMenu)
                        <div class="tab-pane fade {{ ((($admin?->hasRole('site acquisition') || $admin?->hasRole('مشاور جذب تلفنی')) && !$admin?->hasRole('super admin')) || request()->routeIs('admin.trial-acquisition.*') || request()->routeIs('admin.phone-acquisition.*') || request()->routeIs('admin.notifications.index')) ? 'show active' : '' }}"
                             id="acquisitionTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    <li class="menu-heading">
                                        <span class="menu-label">مشاوره جذب</span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}"
                                           href="{{ route('admin.notifications.index') }}">
                                            <i class="fi fi-rr-bell"></i>
                                            <span class="menu-label">اعلانات من</span>
                                            @if(($adminUnreadNotificationsCount ?? 0) > 0)
                                                <span class="badge rounded-pill bg-danger ms-auto">
                                                    {{ $adminUnreadNotificationsCount > 99 ? '99+' : $adminUnreadNotificationsCount }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    @if($unifiedAcquisitionRole)
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.dashboard') ? 'active' : '' }}"
                                           href="{{ route('admin.trial-acquisition.dashboard') }}">
                                            <i class="fi fi-rr-dashboard"></i>
                                            <span class="menu-label">داشبورد</span>
                                        </a>
                                    </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.my-students') ? 'active' : '' }}"
                                               href="{{ route('admin.trial-acquisition.my-students') }}">
                                                <i class="fi fi-rr-users-alt"></i>
                                                <span class="menu-label">دانش‌آموزان من</span>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.temporary-no-interest') ? 'active' : '' }}"
                                               href="{{ route('admin.trial-acquisition.temporary-no-interest') }}">
                                                <i class="fi fi-rr-clock-three"></i>
                                                <span class="menu-label">عدم تمایل موقت</span>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.definitive-no-interest') ? 'active' : '' }}"
                                               href="{{ route('admin.trial-acquisition.definitive-no-interest') }}">
                                                <i class="fi fi-rr-ban"></i>
                                                <span class="menu-label">عدم تمایل قطعی</span>
                                            </a>
                                        </li>
                                        <li class="menu-heading">
                                            <span class="menu-label">یک هفته آزمایشی</span>
                                        </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.index') ? 'active' : '' }}"
                                           href="{{ route('admin.trial-acquisition.index') }}">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                            <span class="menu-label">دانش‌آموزان یک هفته آزمایشی</span>
                                        </a>
                                    </li>

                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.monitor') ? 'active' : '' }}"
                                           href="{{ route('admin.trial-acquisition.monitor') }}">
                                            <i class="fi fi-rr-chart-histogram"></i>
                                            <span class="menu-label">رصد دانش‌آموزان یک هفته آزمایشی</span>
                                        </a>
                                    </li>
                                        <li class="menu-heading">
                                            <span class="menu-label">بازه امتحانات </span>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.exam-program-students') ? 'active' : '' }}"
                                               href="{{ route('admin.trial-acquisition.exam-program-students') }}">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <span class="menu-label">دانش‌آموزان برنامه امتحانی</span>
                                            </a>
                                        </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.trial-acquisition.exam-monitor') ? 'active' : '' }}"
                                           href="{{ $examMonitorHref }}">
                                            <i class="fi fi-rr-chart-histogram"></i>
                                            <span class="menu-label">رصد دانش‌آموزان بازه امتحانات</span>
                                        </a>
                                    </li>

                                    @endif
                                    @if($admin?->hasRole('مشاور جذب تلفنی') || $admin?->hasRole('super admin'))
                                    <li><div class="menu-divider"></div></li>
                                    <li class="menu-heading">
                                        <span class="menu-label">جذب تلفنی</span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.dashboard') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.dashboard') }}">
                                            <i class="fi fi-rr-dashboard"></i>
                                            <span class="menu-label">داشبورد</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.queue') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.queue') }}">
                                            <i class="fi fi-rr-phone-call"></i>
                                            <span class="menu-label">صف تماس‌های من</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.needs-follow-up-acquisition') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.needs-follow-up-acquisition') }}">
                                            <i class="fi fi-rr-refresh"></i>
                                            <span class="menu-label">نیاز پیگیری مجدد تلفنی</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.needs-follow-up-registration') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.needs-follow-up-registration') }}">
                                            <i class="fi fi-rr-user"></i>
                                            <span class="menu-label">ارسال لینک بدون نتیجه</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.disinterest-temporary') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.disinterest-temporary') }}">
                                            <i class="fi fi-rr-clock-three"></i>
                                            <span class="menu-label">عدم تمایل موقت</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.disinterest-definitive') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.disinterest-definitive') }}">
                                            <i class="fi fi-rr-ban"></i>
                                            <span class="menu-label">عدم تمایل قطعی</span>
                                        </a>
                                    </li>

                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.phone-acquisition.receipts') ? 'active' : '' }}"
                                           href="{{ route('admin.phone-acquisition.receipts') }}">
                                            <i class="fi fi-rr-receipt"></i>
                                            <span class="menu-label">رسیدهای شارژ</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                    @if($generalAdminMenu)
                        @php
                            $acquisitionActive = ((($admin?->hasRole('site acquisition') || $admin?->hasRole('مشاور جذب تلفنی')) && !$admin?->hasRole('super admin'))
                                || request()->routeIs('admin.trial-acquisition.*')
                                || request()->routeIs('admin.phone-acquisition.*')
                                || request()->routeIs('admin.notifications.index'));
                        @endphp
                        <div class="tab-pane fade {{ ($acquisitionActive || $educationalManagerActive) ? '' : 'show active' }}" id="dashboardTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    <li class="menu-heading">
                                       <span class="menu-label">
                                        داشبورد ها
                                       </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.dashboard.index')}}" role="button">
                                            <i class="fi fi-rr-house-blank">
                                            </i>
                                            <span class="menu-label">
                                           داشبورد
                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        @if($studentMenu)
                        <div class="tab-pane fade" id="appsTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    <li class="menu-heading">
                                   <span class="menu-label">
                                    دانش آموزان
                                   </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.student.index')}}">
                                            <i class="fi fi-rr-comment">
                                            </i>
                                            <span class="menu-label">
                                                 اطلاعات دانش آموزان
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.student.reportDailyActivities.index')}}">
                                            <i class="fi fi-rs-usd-circle">
                                            </i>
                                            <span class="menu-label">
                                             گزارش درسی جامع
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.student.studySession.index')}}">
                                            <i class="fi fi-rr-calendar">
                                            </i>
                                            <span class="menu-label">
                                             ساعت مطالعه جامع
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-heading">
                                   <span class="menu-label">
                                  اتاق مشاوره
                                   </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.advising-sessions')}}">
                                            <i class="fi fi-rr-calendar">
                                            </i>
                                            <span class="menu-label">
                                            جلسه مشاوره
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.classification.dashboard')}}">
                                            <i class="fi fi-rr-flux-capacitor"></i>
                                            <span class="menu-label">
                                           طبقه بندی دروس
                                        </span>
                                        </a>
                                    </li>
                                    @if((bool) \App\Models\GeneralSetting::query()->value('smart_report_card_enabled'))
                                        <li class="menu-item">
                                            <a class="menu-link" href="{{route('admin.student.smartReportCard.index')}}">
                                                <i class="fi fi-rr-file-medical-alt">
                                                </i>
                                                <span class="menu-label">
                                                 کارنامه هوشمند
                                            </span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.contact-documentation.index')}}">
                                            <i class="fi fi-rr-phone-call">
                                            </i>
                                            <span class="menu-label">
                                            مستندات تماس
                                        </span>
                                        </a>
                                    </li>

                                    <li class="menu-heading">
                                   <span class="menu-label">
                                  آزمون ها
                                   </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.typed-exams.index')}}">
                                            <i class="fi fi-rr-unlock">
                                            </i>
                                            <span class="menu-label">
                                              آزمون تستی
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.typed-exams.my-exams')}}">
                                            <i class="fi fi-rr-edit">
                                            </i>
                                            <span class="menu-label">
                                              آزمون‌های اختصاصی من
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="{{route('admin.essay-exams.index')}}">
                                            <i class="fi fi-rr-unlock">
                                            </i>
                                            <span class="menu-label">
                                               آزمون تشریحی
                                        </span>
                                        </a>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                        @endif

                        <div class="tab-pane fade {{ $educationalManagerActive ? 'show active' : '' }}" id="adminUsersTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    {{-- مدیریت کارکنان فقط برای سوپرادمین؛ برای مدیر آموزشیِ خالص پنهان است --}}
                                    @if(auth('admin')->user()?->hasRole('trial-supporter') || auth('admin')->user()?->hasRole('super admin'))
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.trial-week.*') ? 'active' : '' }}"
                                               href="{{ route('admin.trial-week.index') }}">
                                                <i class="fi fi-rr-bolt"></i>
                                                <span class="menu-label">دانش‌آموزان آزمایشی</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li><div class="menu-divider"></div></li>
                                    @if($admin?->hasRole('educational-manager') || $admin?->hasRole('super admin'))
                                    <li class="menu-heading">
                                        <span class="menu-label">مدیر آموزشی</span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.incomplete-registrations') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.incomplete-registrations') }}">
                                            <i class="fi fi-rr-user-time"></i>
                                            <span class="menu-label">ثبت‌نام‌های ناقص</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.leave') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.leave') }}">
                                            <i class="fi fi-rr-calendar-clock"></i>
                                            <span class="menu-label">تایید مرخصی مشاوران</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.advisor-change-requests') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.advisor-change-requests') }}">
                                            <i class="fi fi-rr-exchange"></i>
                                            <span class="menu-label">درخواست جابجایی مشاور</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.advisor-onboarding-approvals') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.advisor-onboarding-approvals') }}">
                                            <i class="fi fi-rr-shield-check"></i>
                                            <span class="menu-label">تایید لینک گروه بله</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.phone-acquisition.dashboard') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.phone-acquisition.dashboard') }}">
                                            <i class="fi fi-rr-dashboard"></i>
                                            <span class="menu-label">داشبورد جامع جذب</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.acquisition.details') && request()->route('channel') === 'phone' ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.acquisition.details', ['channel' => 'phone', 'segment' => 'all']) }}">
                                            <i class="fi fi-rr-phone-call"></i>
                                            <span class="menu-label">جزئیات جذب تلفنی</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.acquisition.details') && request()->route('channel') === 'trial' ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.acquisition.details', ['channel' => 'trial', 'segment' => 'all']) }}">
                                            <i class="fi fi-rr-rocket-lunch"></i>
                                            <span class="menu-label">جزئیات جذب آزمایشی</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.phone-acquisition.leads') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.phone-acquisition.leads') }}">
                                            <i class="fi fi-rr-list"></i>
                                            <span class="menu-label">شماره‌های جذب تلفنی</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.phone-acquisition.assign') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.phone-acquisition.assign') }}">
                                            <i class="fi fi-rr-share"></i>
                                            <span class="menu-label">اختصاص شماره به مشاور</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.phone-acquisition.history*') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.phone-acquisition.history') }}">
                                            <i class="fi fi-rr-time-past"></i>
                                            <span class="menu-label">تاریخچهٔ تماس‌ها</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.phone-acquisition.goals') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.phone-acquisition.goals') }}">
                                            <i class="fi fi-rr-target"></i>
                                            <span class="menu-label">هدف‌گذاری ثبت‌نام</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link {{ request()->routeIs('admin.educational-manager.phone-acquisition.receipts') ? 'active' : '' }}"
                                           href="{{ route('admin.educational-manager.phone-acquisition.receipts') }}">
                                            <i class="fi fi-rr-receipt"></i>
                                            <span class="menu-label">رسیدهای شارژ</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($admin?->hasRole('مشاور تحصیلی') || $admin?->hasRole('super admin'))
                                    <li class="menu-heading"><span class="menu-label">مشاور</span></li>

                                        <li class="menu-item">
                                            <a class="menu-link" href="{{route('admin.student.notification')}}">
                                                <i class="fi fi-rr-circle-user"></i>
                                                <span class="menu-label">
                                                    اعلان در پنل دانش آموز
                                                </span>
                                            </a>
                                        </li>
                                        @if($admin?->hasRole('مشاور تحصیلی') || $admin?->hasRole('super admin'))
                                            <li class="menu-item">
                                                <a class="menu-link {{ request()->routeIs('admin.consultant.chat*') ? 'active' : '' }}" href="{{route('admin.consultant.chats')}}">
                                                    <i class="fi fi-rr-comment-alt">
                                                    </i>
                                                    <span class="menu-label">
                                            چت با دانش‌آموز
                                        </span>
                                                    <livewire:admin.consultant.chat.unread-badge />
                                                </a>
                                            </li>

                                        @endif
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.consultant.leave') ? 'active' : '' }}"
                                               href="{{ route('admin.consultant.leave') }}">
                                                <i class="fi fi-rr-calendar-clock"></i>
                                                <span class="menu-label">مرخصی من</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                            <div class="tab-pane fade" id="cooperationSchoolTab" role="tabpanel" tabindex="0">
                                <nav class="app-navbar" data-simplebar="">
                                    <ul class="side-menubar">
                                        <li class="menu-heading">
                                            <span class="menu-label">مدیر آموزشی</span>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.consultant.grades') ? 'active' : '' }}" href="{{route('admin.consultant.grades')}}">
                                                <i class="fi fi-rr-edit">
                                                </i>
                                                <span class="menu-label">
                                           ثبت نمره
                                        </span>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.consultant.grades') ? 'active' : '' }}" href="{{route('admin.consultant.grades')}}">
                                                <i class="fi fi-rr-edit">
                                                </i>
                                                <span class="menu-label">
                                            کارنامه مدرسه
                                        </span>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('admin.consultant.emergency-calls') ? 'active' : '' }}" href="{{route('admin.consultant.emergency-calls')}}">
                                                <i class="fi fi-rr-siren-on">
                                                </i>
                                                <span class="menu-label">
                                            تماس اورژانسی
                                        </span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        <div class="tab-pane fade" id="formElementsTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    <li class="menu-heading">
		   <span class="menu-label">
			fontawsome
		   </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/form-elements.html">
                                            <i class="fi fi-rr-form">
                                            </i>
                                            <span class="menu-label">
			 عناصر فرم
			</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/form-floating.html">
                                            <i class="fi fi-rr-form">
                                            </i>
                                            <span class="menu-label">
			 فرم شناور
			</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/form-input-group.html">
                                            <i class="fi fi-rr-form">
                                            </i>
                                            <span class="menu-label">
			 گروه ورودی فرم
			</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/form-layout.html">
                                            <i class="fi fi-rr-form">
                                            </i>
                                            <span class="menu-label">
			 طرح بندی فرم
			</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/form-validation.html">
                                            <i class="fi fi-rr-form">
                                            </i>
                                            <span class="menu-label">
			 اعتبار سنجی فرم
			</span>
                                        </a>
                                    </li>
                                    <li class="menu-heading">
		   <span class="menu-label">
			پلاگین های فرم ها
		   </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/persian-datepicker.html">
                                            <i class="fi fi-rr-calendar-lines">
                                            </i>
                                            <span class="menu-label">
			 Persian Datepicker
			</span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="forms/tagify.html">
                                            <i class="fi fi-rr-tags">
                                            </i>
                                            <span class="menu-label">
			 تگیفای
			</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="menu-divider">
                                        </div>
                                    </li>
                                    <li class="menu-heading">
                                    <span class="menu-label">
                                        جدول
                                    </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="table/tables-basic.html">
                                            <i class="fi fi-rr-table-list">
                                            </i>
                                            <span class="menu-label">
                                             جدول
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="table/tables-datatable.html">
                                            <i class="fi fi-rr-table">
                                            </i>
                                            <span class="menu-label">
                                             جدول داده
                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="tab-pane fade" id="chartsTab" role="tabpanel" tabindex="0">
                            <nav class="app-navbar" data-simplebar="">
                                <ul class="side-menubar">
                                    <li class="menu-heading">
                                    <span class="menu-label">
                                        نمودارها
                                    </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="chart/apexchart.html">
                                            <i class="fi fi-br-chart-histogram">
                                            </i>
                                            <span class="menu-label">
                                             apexchart
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="chart/chartjs.html">
                                            <i class="fi fi-rr-chart-pie-alt">
                                            </i>
                                            <span class="menu-label">
                                                 نمودار JS
                                        </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="menu-divider">
                                        </div>
                                    </li>
                                    <li class="menu-heading">
                                   <span class="menu-label">
                                    نقشه ها
                                   </span>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="maps/jsvectormap.html">
                                            <i class="fi fi-rr-marker">
                                            </i>
                                            <span class="menu-label">
                                             نقشه برداری JS
                                        </span>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a class="menu-link" href="maps/leaflet.html">
                                            <i class="fi fi-rr-map-marker">
                                            </i>
                                            <span class="menu-label">
                                             جزوه
                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </aside>
    <!-- end::NexLink Sidebar Menu -->
    <div aria-hidden="true" aria-labelledby="addCustomerModal" class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        مشتری جدید
                    </h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                نام مشتری
                            </label>
                            <input class="form-control" placeholder="نام کامل را وارد کنید" type="text"/>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                آدرس ایمیل
                            </label>
                            <input class="form-control" placeholder="ایمیل را وارد کنید" type="text"/>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                شماره تلفن
                            </label>
                            <input class="form-control" placeholder="به عنوان مثال 09010010011" type="text"/>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                شرکت
                            </label>
                            <input class="form-control" placeholder="نام شرکت" type="text"/>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                کشور
                            </label>
                            <select class="form-select">
                                <option value="">
                                    کشور را انتخاب کنید
                                </option>
                                <option value="US">
                                    ایالات متحده آمریکا
                                </option>
                                <option value="UK">
                                    انگلستان
                                </option>
                                <option value="IN">
                                    هند
                                </option>
                                <option value="CA">
                                    کانادا
                                </option>
                                <option value="DE">
                                    آلمان
                                </option>
                                <option value="FR">
                                    فرانسه
                                </option>
                                <option value="JP">
                                    ژاپن
                                </option>
                                <option value="BR">
                                    برزیل
                                </option>
                                <option value="EG">
                                    مصر
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                نوع مشتری
                            </label>
                            <select class="form-select">
                                <option value="">
                                    نوع را انتخاب کنید
                                </option>
                                <option value="Lead">
                                    لید
                                </option>
                                <option value="Prospect">
                                    مشتری
                                </option>
                                <option value="Client">
                                    چشم انداز
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                وضعیت حساب
                            </label>
                            <select class="form-select">
                                <option value="">
                                    وضعیت را انتخاب کنید
                                </option>
                                <option value="Active">
                                    فعال
                                </option>
                                <option value="Inactive">
                                    غیر فعال
                                </option>
                                <option value="Blocked">
                                    مسدود شده است
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">
                                تاریخ عضویت
                            </label>
                            <input type="text" class="form-control p-date-only" placeholder="انتخاب تاریخ"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal" type="button">
                        لغو کنید
                    </button>
                    <button class="btn btn-primary ms-2" type="button">
                        مشتری اضافه کنید
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
