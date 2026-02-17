<div>
    <!-- begin::NexLink Sidebar Menu -->
    <aside class="app-menubar-tabs" id="appMenubar">
        <div class="app-navbar-brand">
            <a class="navbar-brand-logo" href="{{route('admin.dashboard.index')}}">
                <img alt="NexLink Admin Dashboard Logo" src="/admin/assets/images/logo.svg"/>
            </a>
        </div>
        <div class="app-navbar-tabs" data-simplebar="">
            <ul aria-orientation="vertical" class="nav" id="appMenubarTabs" role="tablist">
                <li class="nav-item" data-bs-placement="right" data-bs-title="داشبورد" data-bs-toggle="tooltip">
                    <a aria-controls="dashboardTab" aria-selected="true" class="menu-link active" data-bs-toggle="tab"
                       href="#dashboardTab" role="tab">
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
                <li class="nav-item" data-bs-placement="right" data-bs-title="گزارش جامع" data-bs-toggle="tooltip">
                    <a aria-controls="pagesTab" aria-selected="false" class="menu-link" data-bs-toggle="tab"
                       href="#pagesTab" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-report-search menu-icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                            <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2"></path>
                            <path
                                d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                            <path d="M8 11h4"></path>
                            <path d="M8 15h3"></path>
                            <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0"></path>
                            <path d="M18.5 19.5l2.5 2.5"></path>
                        </svg>
                    </a>
                </li>
                <li class="nav-item-hr">
                </li>
                <li class="nav-item" data-bs-placement="right" data-bs-title="آزمون" data-bs-toggle="tooltip">
                    <a aria-controls="authenticationTab" aria-selected="false" class="menu-link" data-bs-toggle="tab"
                       href="#authenticationTab" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-test-pencil menu-icon">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>

                            <!-- Paper -->
                            <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h8"></path>
                            <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2"></path>

                            <!-- Header clip -->
                            <path
                                d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>

                            <!-- Lines -->
                            <path d="M8 11h4"></path>
                            <path d="M8 15h3"></path>

                            <!-- Pencil -->
                            <path d="M14 16l4 -4a2 2 0 0 1 3 3l-4 4l-4 1l1 -4z"></path>
                            <path d="M18 12l3 3"></path>

                        </svg>


                    </a>
                </li>
                <li class="nav-item" data-bs-placement="right" data-bs-title="طبقه بندی" data-bs-toggle="tooltip">
                    <a aria-controls="componentsTab" aria-selected="false" class="menu-link" data-bs-toggle="tab"
                       href="#componentsTab" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-category-folder menu-icon">

                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>

                            <!-- Folder -->
                            <path
                                d="M4 6a2 2 0 0 1 2 -2h4l2 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z"></path>

                            <!-- List inside folder -->
                            <path d="M8 12h8"></path>
                            <path d="M8 15h6"></path>
                            <path d="M8 18h4"></path>

                        </svg>

                    </a>
                </li>
                <li class="nav-item-hr">
                </li>
                <li class="nav-item" data-bs-placement="right" data-bs-title="اعلانات"
                    data-bs-toggle="tooltip">
                    <a aria-controls="extendedTab" aria-selected="false" class="menu-link" data-bs-toggle="tab"
                       href="#extendedTab" role="tab">
                        <svg fill="none" height="25" viewBox="0 0 24 25" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.7491 10.2096V9.50497C18.7491 5.63623 15.7274 2.5 12 2.5C8.27256 2.5 5.25087 5.63623 5.25087 9.50497V10.2096C5.25087 11.0552 5.00972 11.8818 4.5578 12.5854L3.45036 14.3095C2.43882 15.8843 3.21105 18.0249 4.97036 18.5229C9.57274 19.8257 14.4273 19.8257 19.0296 18.5229C20.789 18.0249 21.5612 15.8843 20.5496 14.3095L19.4422 12.5854C18.9903 11.8818 18.7491 11.0552 18.7491 10.2096Z"
                                stroke="var(--bs-heading-color)" stroke-width="2">
                            </path>
                            <path
                                d="M7.5 19.5C8.15503 21.2478 9.92246 22.5 12 22.5C14.0775 22.5 15.845 21.2478 16.5 19.5"
                                opacity="0.5" stroke="var(--bs-heading-color)" stroke-linecap="round" stroke-width="2">
                            </path>
                            <path d="M12 6.5V10.5" opacity="0.5" stroke="var(--bs-heading-color)" stroke-linecap="round"
                                  stroke-width="2">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="nav-item-hr">
                </li>
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
                    <div class="tab-pane fade show active" id="dashboardTab" role="tabpanel" tabindex="0">
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
                                           داشبورد پیش فرض
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
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
                                                 همه دانش آموزان
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{route('admin.student.studySession.index')}}">
                                        <i class="fi fi-rr-calendar">
                                        </i>
                                        <span class="menu-label">
                                            ساعت مطالعه دانش آموزان
                                        </span>
                                    </a>
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
                                <li>
                                    <div class="menu-divider">
                                    </div>
                                </li>

                            </ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" id="pagesTab" role="tabpanel" tabindex="0">
                        <nav class="app-navbar" data-simplebar="">
                            <ul class="side-menubar">
                                <li class="menu-heading">
                                       <span class="menu-label">
                                        گزارش جامع
                                       </span>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{route('admin.student.reportDailyActivities.index')}}">
                                        <i class="fi fi-rs-usd-circle">
                                        </i>
                                        <span class="menu-label">
                                             گزارش جامع
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link"
                                       href="{{ route('admin.reportStudentDay', ['status' => 'pending']) }}">
                                        <i class="fi fi-rs-usd-circle">
                                        </i>
                                        <span class="menu-label">
                                             در انتظار تایید
                                        </span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('admin.reportMissing') }}">
                                        <i class="fi fi-rs-usd-circle">
                                        </i>
                                        <span class="menu-label">
                                             ارسال نشده
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="menu-divider"></div>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{route('admin.contact-documentation.index')}}">
                                        <i class="fi fi-rr-phone-call">
                                        </i>
                                        <span class="menu-label">
                                            مستندات تماس
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" id="authenticationTab" role="tabpanel" tabindex="0">
                        <nav class="app-navbar" data-simplebar="">
                            <ul class="side-menubar">
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
                                             لیست آزمون
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="menu-divider">
                                    </div>
                                </li>


                            </ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" id="componentsTab" role="tabpanel" tabindex="0">
                        <nav class="app-navbar" data-simplebar="">
                            <ul class="side-menubar">
                                <li class="menu-heading">
                                   <span class="menu-label">
                                    طبقه بندی
                                   </span>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="{{route('admin.classification.dashboard')}}">
                                        <i class="fi fi-rr-flux-capacitor"></i>
                                        <span class="menu-label">
                                             داشبورد
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" id="extendedTab" role="tabpanel" tabindex="0">
                        <nav class="app-navbar" data-simplebar="">
                            <ul class="side-menubar">
                                <li class="menu-heading">
		   <span class="menu-label">
			اعلانات
		   </span>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{route('admin.student.notification')}}">
                                        <i class="fi fi-rr-circle-user">
                                        </i>
                                        <span class="menu-label">
                                             ارسال اعلان
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
