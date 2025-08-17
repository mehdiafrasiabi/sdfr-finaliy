<div>
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <!-- Dark Logo-->
            <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="/manager/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                <span class="logo-lg">
                        <img src="/manager/assets/images/logo-dark.png" alt="" height="17">
                    </span>
            </a>
            <!-- Light Logo-->
            <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="/manager/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                <span class="logo-lg">
                        <img src="/manager/assets/images/logo-light.png" alt="" height="17">
                    </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title">
                        <span data-key="t-menu">منو</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.dashboard.analytics') ? 'active' : '' }}" href="#dashboard" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="dashboard">
                            <i class="ri-dashboard-2-line"></i>
                            <span data-key="t-base-ui">پیشخوان</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="dashboard">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{ route('manager.dashboard.analytics') }}" class="nav-link"
                                               data-key="t-basic">تجزیه و تحلیل</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.dashboard.crm')}}" class="nav-link"
                                               data-key="t-basic">CRM</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.blog.exampleQuestion') ? 'active' : '' }}" href="#dashboard" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="dashboard">
                            <i class="ri-dashboard-2-line"></i>
                            <span data-key="t-base-ui">بلاگ سایت</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="dashboard">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{route('manager.blog.index')}}" class="nav-link"
                                               data-key="t-basic">وبلاگ ها</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.blog.exampleQuestion')}}" class="nav-link"
                                               data-key="t-basic">نمونه سوالات امتحانی</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.category.index') ? 'active' : '' }}"
                           href="{{route('manager.category.index')}}" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-apps-2-line"></i> <span data-key="t-dashboards">دسته بندی</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.product.index') ? 'active' : '' }}"
                           href="{{route('manager.product.index')}}" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class=" ri-archive-line"></i> <span data-key="t-dashboards">محصولات</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.coupon') ? 'active' : '' }}"
                           href="{{route('manager.coupon')}}" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class=" ri-percent-line"></i> <span data-key="t-dashboards">کد تخفیف</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.order.index') ? 'active' : '' }}" href="#order" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="order">
                            <i class=" ri-list-ordered"></i>
                            <span data-key="t-base-ui">سفارشات</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="order">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a class="text-warning nav-link" data-key="t-basic" href="{{route('manager.order.index')}}?status=all"> کل

                                                سفارشات </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="text-secondary nav-link" data-key="t-basic" href="{{route('manager.order.index')}}?status=pending">
                                                درانتظار </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="text-info nav-link"  data-key="t-basic"href="{{route('manager.order.index')}}?status=processing">
                                                درحال پردازش </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="text-success nav-link" data-key="t-basic" href="{{route('manager.order.index')}}?status=completed">
                                                کامل شده </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="text-danger nav-link" data-key="t-basic" href="{{route('manager.order.index')}}?status=cancelled">
                                                لغوشده </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.transaction') ? 'active' : '' }}" href="#transaction" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="transaction">
                            <i class=" ri-bank-card-2-line"></i>
                            <span data-key="t-base-ui">تراکنش ها</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="transaction">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{route('manager.transaction')}}?status=all" class="nav-link text-info" data-key="t-basic">لیست تراکنش ها</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.transaction')}}?status=completed" class="nav-link text-success"
                                               data-key="t-basic">موفق</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.transaction')}}?status=cancelled" class="nav-link text-danger"
                                               data-key="t-basic">نا موفق</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.user') ? 'active' : '' }}" href="#sidebarUI" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="sidebarUI">
                            <i class="ri-user-6-line"></i>
                            <span data-key="t-base-ui">کاربران</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{ route('manager.user') }}" class="nav-link"
                                               data-key="t-basic">مشتربان</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.adminManage')}}" class="nav-link"
                                               data-key="t-basic">ادمین ها</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.student') ? 'active' : '' }}" href="#student" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="student">
                            <i class=" ri-team-line"></i>
                            <span data-key="t-base-ui">دانش اموزان و پشتیبانان</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="student">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{ route('manager.student') }}" class="nav-link"
                                               data-key="t-basic">کل دانش اموزان</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.studentAssign')}}" class="nav-link"
                                               data-key="t-basic">نیاز به انتخاب پشتیبان</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.supporters')}}" class="nav-link"
                                               data-key="t-basic">پشتیبان ها</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.exam.index') ? 'active' : '' }}" href="{{route('manager.exam.index')}}" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class=" ri-archive-fill"></i> <span data-key="t-dashboards">آزمون ها </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.document') ? 'active' : '' }}" href="#document" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="document">
                            <i class="  ri-file-line"></i>
                            <span data-key="t-base-ui">مستندات</span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="document">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{ route('manager.document.contact') }}" class="nav-link"
                                               data-key="t-basic">مستندات تماس</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.document.reportStudentStudy')}}" class="nav-link"
                                               data-key="t-basic"> بایگانی گزارش درسی دانش اموزان</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>


                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.story') ? 'active' : '' }}" href="{{route('manager.story')}}" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class=" ri-youtube-line"></i> <span data-key="t-dashboards">استوری ها</span>
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.map') ? 'active' : '' }}" href="#map" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="map">
                            <i class="ri-map-pin-line"></i>
                            <span data-key="t-base-ui">مپ </span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="map">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{route('manager.map.country')}}" class="nav-link " data-key="t-basic">کشور ها</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.map.state')}}" class="nav-link "
                                               data-key="t-basic">استان ها</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.map.city')}}" class="nav-link "
                                               data-key="t-basic">شهر ها</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.paymentMethod') ? 'active' : '' }}" href="{{route('manager.paymentMethod')}}" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class=" ri-product-hunt-line"></i> <span data-key="t-dashboards">درگاه های پرداخت</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.ticket.index') ? 'active' : '' }}" href="#ticket" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="ticket">
                            <i class=" ri-ticket-2-line"></i>
                            <span data-key="t-base-ui">تیکت و دپارتمان </span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="ticket">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{route('manager.department')}}" class="nav-link " data-key="t-basic">افزودن دپارتمان</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('manager.ticket.index')}}" class="nav-link " data-key="t-basic">تیکت ها</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                    </li>
                    <li class="nav-item ">
                        <a class="nav-link menu-link {{ request()->routeIs('manager.setting') ? 'active' : '' }}" href="#cotactUs" data-bs-toggle="collapse" role="button"
                           aria-expanded="false" aria-controls="cotactUs">
                            <i class=" ri-settings-2-line"></i>
                            <span data-key="t-base-ui">تنظیمات </span>
                        </a>
                        <div class="collapse menu-dropdown mega-dropdown-menu" id="cotactUs">
                            <div class="row">
                                <div class="col-lg-4">
                                    <ul class="nav nav-sm flex-column">

                                        <li class="nav-item">
                                            <a href="{{route('manager.setting.contactUs')}}" class="nav-link " data-key="t-basic">ارتباط با ما </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
    </div>
</div>
