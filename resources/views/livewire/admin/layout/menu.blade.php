<div>

    <aside class="layout-menu menu-vertical menu bg-menu-theme" id="layout-menu">

        <div class="app-brand demo">

            <a class="app-brand-link" href="#">

                <span class="app-brand-text demo menu-text fw-bold">SDFR</span>

            </a>

            <a class="layout-menu-toggle menu-link text-large ms-auto" href="javascript:void(0);">

                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>

                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>

            </a>

        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">


            {{-- داشبورد --}}

            @can('admin.dashboard.view')

                <li class="menu-item">

                    <a class="menu-link menu-toggle" href="javascript:void(0);">

                        <i class="menu-icon tf-icons ti ti-smart-home"></i>

                        <div data-i18n="Dashboards">داشبورد‌ها</div>

                    </a>

                    <ul class="menu-sub">

                        <li class="menu-item">

                            <a class="menu-link" href="{{route('admin.dashboard.index')}}">

                                <div>داشبورد</div>

                            </a>

                        </li>

                    </ul>

                </li>

            @endcan



            <!-- برنامه‌ها و صفحات -->

            <li class="menu-header small text-uppercase">

                <span class="menu-header-text" data-i18n="Apps & Pages">برنامه‌ها و صفحات</span>

            </li>


            {{-- دانش آموزان --}}

            @canany(['admin.students.view', 'admin.student-plan.view', 'admin.report-status.view', 'admin.study-session.view', 'admin.advising-session.view'])

                <li class="menu-item">

                    <a class="menu-link menu-toggle" href="javascript:void(0);">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                             class="icon icon-tabler icons-tabler-outline icon-tabler-school menu-icon">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>

                            <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"/>

                            <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"/>

                        </svg>

                        <div>دانش آموزان</div>

                    </a>

                    <ul class="menu-sub">

                        @can('admin.students.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.student.index')}}">

                                    <div>دانش آموزان کل</div>

                                </a>

                            </li>

                        @endcan

                        @can('admin.student-plan.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.student.plan.index')}}">

                                    <div>برنامه درسی</div>

                                </a>

                            </li>

                        @endcan

                        @can('admin.report-status.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.student.reportStudent.index')}}">

                                    <div>کارنامه وضعیت</div>

                                </a>

                            </li>

                        @endcan

                        @can('admin.study-session.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.student.studySession.index')}}">

                                    <div>ساعت مطالعه دانش آموزان</div>

                                </a>

                            </li>

                        @endcan

                        @can('admin.advising-session.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.advising-sessions')}}">

                                    <div>اتاق مشاوره</div>

                                </a>

                            </li>

                        @endcan

                    </ul>

                </li>

            @endcanany



            {{-- گزارش جامع --}}

            @canany(['admin.daily-activities.view', 'admin.daily-report.view', 'admin.report-missing.view'])

                <li class="menu-item">

                    <a class="menu-link menu-toggle" href="javascript:void(0);">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"

                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                             class="icon icon-tabler icons-tabler-outline icon-tabler-report-search menu-icon">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>

                            <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"/>

                            <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2"/>

                            <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/>

                            <path d="M8 11h4"/>

                            <path d="M8 15h3"/>

                            <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0"/>

                            <path d="M18.5 19.5l2.5 2.5"/>

                        </svg>

                        <div>گزارش جامع</div>

                    </a>

                    <ul class="menu-sub">

                        @can('admin.daily-activities.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.student.reportDailyActivities.index')}}">

                                    <div>گزارش جامع</div>

                                </a>

                            </li>

                        @endcan

                        @can('admin.daily-report.view')

                            <li class="menu-item">

                                <a class="menu-link"
                                   href="{{ route('admin.reportStudentDay', ['status' => 'pending']) }}">

                                    <div>در انتظار تایید</div>

                                </a>

                            </li>

                        @endcan

                        @can('admin.report-missing.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{ route('admin.reportMissing') }}">

                                    <div>ارسال نشده</div>

                                </a>

                            </li>

                        @endcan

                    </ul>

                </li>

            @endcanany



            {{-- آزمون‌های تایپی --}}

            @canany(['admin.typed-exams.view', 'admin.typed-exams.assign', 'admin.typed-exams.stats'])

                <li class="menu-item">

                    <a class="menu-link menu-toggle" href="javascript:void(0);">

                        <i class="menu-icon ti ti-file-text"></i>

                        <div>آزمون‌های تایپی</div>

                    </a>

                    <ul class="menu-sub">

                        @can('admin.typed-exams.view')

                            <li class="menu-item">

                                <a class="menu-link" href="{{route('admin.typed-exams.index')}}">

                                    <div>لیست آزمون‌ها</div>

                                </a>

                            </li>

                        @endcan

                    </ul>

                </li>

            @endcanany



            {{-- طبقه‌بندی دروس --}}

            @can('admin.classification.view')

                <li class="menu-item">

                    <a class="menu-link menu-toggle" href="javascript:void(0);">

                        <i class="menu-icon ti ti-category"></i>

                        <div>طبقه‌بندی دروس</div>

                    </a>

                    <ul class="menu-sub">

                        <li class="menu-item">

                            <a class="menu-link" href="{{route('admin.classification.dashboard')}}">

                                <div>داشبورد</div>

                            </a>

                        </li>

                    </ul>

                </li>

            @endcan



            {{-- اعلان‌ها --}}

            @can('admin.notification.send')

                <li class="menu-item">

                    <a class="menu-link" href="{{route('admin.student.notification')}}">

                        <i class="menu-icon ti ti-bell"></i>

                        <div>ارسال اعلان</div>

                    </a>

                </li>

            @endcan


        </ul>

    </aside>

</div>
