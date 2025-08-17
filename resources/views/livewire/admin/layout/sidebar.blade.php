<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{route('admin.dashboard.index')}}">
                        <img src="/admin/src/assets/img/favicon.svg" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{route('admin.dashboard.index')}}" class="nav-link"> SDFR </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="profile-info">
            <div class="user-info">
                <div class="profile-img">
                    <img src="/admin/src/assets/img/profile-30.png" alt="avatar">
                </div>
                <div class="profile-content">
                    <h6 class="">{{\Illuminate\Support\Facades\Auth::user()->name}}</h6>
                    <p class="">

                        @foreach (\Illuminate\Support\Facades\Auth::user()->getRoleNames() as $role)
                            {{ $role }}
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu active">
                <a href="#dashboard" data-bs-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>داشبورد</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-bs-parent="#accordionExample">
                    <li class="active">
                        <a href="{{route('admin.dashboard.index')}}"> صفحه اصلی </a>
                    </li>

                </ul>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>خدمات</span>
                </div>
            </li>
            <li class="menu">
                <a href="#students" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>دانش اموزان</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                {{--student--}}
                <ul class="collapse submenu list-unstyled" id="students" data-bs-parent="#accordionExample">
                    @canany(['view_students_for_academic_support','view students with support info'])
                        <li>
                            <a href="{{route('admin.student.index')}}">دانش آموزان من</a>
                        </li>
                    @endcanany

                    @can('upload weekly program')
                        <li>
                            <a href="{{route('admin.student.plan.index')}}"> برنامه درسی </a>
                        </li>
                    @endcan

                    @canany(['view_students_for_academic_support','view student reports with support info'])
                        <li>
                            <a href="{{route('admin.student.reportDailyActivities.index')}}">گزارش</a>
                        </li>
                    @endcanany

                    @can('view_report_monthlies_for_academic_support')

                        <li>
                            <a href="{{route('admin.student.reportStudent.index')}}">کارنامه وضعیت</a>
                        </li>
                    @endcan
                    @can('view_report_calling_for_academic_support')

                        <li>
                            <a href="{{route('admin.student.reportCalling.index')}}">ثبت مستندات تماس</a>
                        </li>
                    @endcan
                        @canany(['view_students_for_academic_support','view student reports with support info'])

                        <li>
                            <a href="{{route('admin.student.studySession.index')}}">میزان مطالعه دانش آموز</a>
                        </li>
                        @endcanany
                        @canany(['view_students_for_academic_support','view student reports with support info'])

                        <li>
                            <a href="{{route('admin.advising-sessions.index')}}">مشاوره فردی</a>
                        </li>
                        @endcanany
                </ul>


            </li>

            {{--reportStudent--}}
            @canany(['view_students_for_academic_support'])
                <li class="menu">
                    <a href="#reportStudentDay" data-bs-toggle="collapse" aria-expanded="false"
                       class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="feather feather-file">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <span>گزارش روزانه</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="reportStudentDay"
                        data-bs-parent="#accordionExample">
                        <li>
                            <a href="{{ route('admin.reportStudentDay', ['status' => 'all']) }}"> همه </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reportStudentDay', ['status' => 'pending']) }}"> در انتظار
                                تایید </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reportStudentDay', ['status' => 'completed']) }}"> تایید
                                شده </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reportStudentDay', ['status' => 'rejected']) }}"> رد
                                شده </a>
                        </li>
                    </ul>
                </li>
            @endcanany



            {{--Exam--}}
            @canany(['view_exams_for_academic_support','view exams'])
                <li class="menu">
                    <a href="{{route('admin.student.exam.index')}}" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-edit">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <span>آزمون ها</span>
                        </div>
                    </a>
                </li>
            @endcanany
            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>ارسال</span>
                </div>
            </li>
            {{--notification(elan) --}}
            <li class="menu">
                <a href="{{route('admin.student.notification')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-send">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <span>ارسال اعلان</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{route('admin.blog.index')}}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-pen-tool">
                            <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                            <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                            <path d="M2 2l7.586 7.586"></path>
                            <circle cx="11" cy="11" r="2"></circle>
                        </svg>
                        <span>بلاگ ها</span>
                    </div>
                </a>
            </li>
            {{--reportCalling --}}
            <li class="menu">
                <a href="#ecommerce" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-hard-drive">
                            <line x1="22" y1="12" x2="2" y2="12"></line>
                            <path
                                d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                            <line x1="6" y1="16" x2="6.01" y2="16"></line>
                            <line x1="10" y1="16" x2="10.01" y2="16"></line>
                        </svg>
                        <span>ارسال مستندات به مدیر</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="ecommerce" data-bs-parent="#accordionExample">
                    <li>
                        <a href="{{route('admin.sendFile.ReportStudentStudy')}}"> گزارشات تماس </a>
                    </li>
                    <li>
                        <a href="{{route('admin.sendFile.ReportStudentStudy')}}"> گزارشات وضعیت دانش اموزان </a>
                    </li>
                </ul>
            </li>


        </ul>

    </nav>


</div>
