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
            <!-- Dashboards -->
            <li class="menu-item">
                <a class="menu-link menu-toggle" href="javascript:void(0);">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div data-i18n="Dashboards">داشبورد‌ها</div>
                    <div class="badge bg-primary rounded-pill ms-auto">5</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.dashboard.index')}}">
                            <div>داشبورد</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text" data-i18n="Apps & Pages">برنامه‌ها و صفحات</span>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="app-chat.html">
                    <i class="menu-icon tf-icons ti ti-messages"></i>
                    <div>پیامرسان</div>
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="{{route('admin.todo')}}">
                    <i class="menu-icon tf-icons ti ti-messages"></i>
                    <div>وظایف</div>
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="{{route('admin.student.reportCalling.index')}}">
                    <i class="menu-icon tf-icons ti ti-messages"></i>
                    <div>مستندات تماس</div>
                </a>
            </li>

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
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.index')}}">
                            <div>دانش آموزان کل</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.plan.index')}}">
                            <div>برنامه درسی</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.reportStudent.index')}}">
                            <div>کارنامه وضعیت</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.studySession.index')}}">
                            <div>ساعت مطالعه دانش آموزان</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.advising-sessions')}}">
                            <div>جلسه مشاوره (بزودی)</div>
                        </a>
                    </li>

                </ul>
            </li>
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
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.reportDailyActivities.index')}}">
                            <div>گزارش جامع</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('admin.reportStudentDay', ['status' => 'pending']) }}">
                            <div>در انتظار تایید</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('admin.reportMissing') }}">
                            <div>ارسال نشده</div>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="menu-item">
                <a class="menu-link menu-toggle" href="javascript:void(0);">
                    <i class="menu-icon  ti ti-align-box-left-bottom"></i>
                    <div>آزمون ها</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.exam.index')}}">
                            <div>اختصاص آژمون</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{route('admin.student.exam.students')}}">
                            <div>برگزاری</div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
