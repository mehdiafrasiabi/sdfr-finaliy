<div>
    @php
        $admin = auth('admin')->user();
        $adminName = $admin?->name ?? 'ادمین';
        $adminEmail = $admin?->email ?? '-';
        $roleMap = [
            'super admin' => 'مدیر کل',
            'مشاور تحصیلی' => 'مشاور تحصیلی',
            'academic support' => 'پشتیبان تحصیلی',
            'academic_advisor' => 'مشاور تحصیلی',
            'product admin' => 'مدیر محصولات',
            'order admin' => 'مدیر سفارشات',
            'payment admin' => 'مدیر پرداخت',
            'user admin' => 'مدیر کاربران',
            'story admin' => 'مدیر استوری',
            'student admin' => 'مدیر دانش‌آموزان',
            'map admin' => 'مدیر نقشه',
            'contactUs admin' => 'مدیر تماس با ما',
            'payment_method admin' => 'مدیر درگاه پرداخت',
        ];
        $adminRoleKey = $admin?->roles?->first()?->name;
        $adminRole = $roleMap[$adminRoleKey] ?? ($adminRoleKey ?: 'مدیر');
    @endphp

        <!-- begin::NexLink Page Header -->
    <header class="app-header">
        <div class="app-header-inner">
            <button aria-label="app toggler" class="app-toggler" type="button">
                <svg fill="none" height="16" viewbox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg" style="transform: rotate(180deg);">
                    <path d="M7.66699 12.6668L3.66699 8.00016L7.66699 3.3335" stroke="#1C274C" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75">
                    </path>
                    <path d="M12.667 12.6668L8.66699 8.00016L12.667 3.3335" opacity="0.5" stroke="#1C274C" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75">
                    </path>
                </svg>
            </button>
            <div class="app-header-start d-none d-md-flex">
                <form action="#" class="d-flex align-items-center h-100 w-lg-250px w-xxl-300px position-relative">
                    <button class="btn btn-sm border-0 position-absolute start-0 ms-3 p-0" type="button">
                        <i class="fi fi-rr-search">
                        </i>
                    </button>
                    <input class="form-control form-control-fill ps-5" data-bs-target="#searchResultsModal" data-bs-toggle="modal" placeholder="هر چیزی را جستجو کنید" type="text"/>
                </form>
            </div>
            <div class="app-header-end">
                <div class="px-lg-4 px-2 ps-0 d-flex align-items-center">
                    <a class="theme-btn" href="javascript:void(0);">
                        <svg class="icon-light" fill="none" height="21" viewbox="0 0 20 21" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.1663 10.5002C14.1663 12.8013 12.3008 14.6668 9.99967 14.6668C7.69849 14.6668 5.83301 12.8013 5.83301 10.5002C5.83301 8.19898 7.69849 6.3335 9.99967 6.3335C12.3008 6.3335 14.1663 8.19898 14.1663 10.5002Z" fill="var(--bs-heading-color)">
                            </path>
                            <path clip-rule="evenodd" d="M10.0003 1.5415C10.3455 1.5415 10.6253 1.82133 10.6253 2.1665V3.83317C10.6253 4.17834 10.3455 4.45817 10.0003 4.45817C9.65516 4.45817 9.37532 4.17834 9.37532 3.83317V2.1665C9.37532 1.82133 9.65516 1.5415 10.0003 1.5415ZM1.04199 10.4998C1.04199 10.1547 1.32182 9.87484 1.66699 9.87484H3.33366C3.67883 9.87484 3.95866 10.1547 3.95866 10.4998C3.95866 10.845 3.67883 11.1248 3.33366 11.1248H1.66699C1.32182 11.1248 1.04199 10.845 1.04199 10.4998ZM16.042 10.4998C16.042 10.1547 16.3218 9.87484 16.667 9.87484H18.3337C18.6788 9.87484 18.9587 10.1547 18.9587 10.4998C18.9587 10.845 18.6788 11.1248 18.3337 11.1248H16.667C16.3218 11.1248 16.042 10.845 16.042 10.4998ZM10.0003 16.5415C10.3455 16.5415 10.6253 16.8213 10.6253 17.1665V18.8332C10.6253 19.1783 10.3455 19.4582 10.0003 19.4582C9.65516 19.4582 9.37532 19.1783 9.37532 18.8332V17.1665C9.37532 16.8213 9.65516 16.5415 10.0003 16.5415Z" fill="var(--bs-heading-color)" fill-rule="evenodd">
                            </path>
                            <g opacity="0.5">
                                <path d="M3.05729 3.59633C3.29021 3.34158 3.68554 3.32389 3.94029 3.55681L5.79198 5.24979C6.04673 5.48271 6.06442 5.87804 5.8315 6.13279C5.59858 6.38754 5.20325 6.40524 4.94851 6.17232L3.09683 4.47933C2.84208 4.24642 2.82438 3.85108 3.05729 3.59633Z" fill="var(--bs-heading-color)">
                                </path>
                                <path d="M16.9428 3.59633C17.1758 3.85108 17.1581 4.24642 16.9033 4.47933L15.0516 6.17232C14.7968 6.40524 14.4015 6.38754 14.1686 6.13279C13.9357 5.87804 13.9534 5.48271 14.2082 5.24979L16.0598 3.55681C16.3146 3.32389 16.7099 3.34158 16.9428 3.59633Z" fill="var(--bs-heading-color)">
                                </path>
                                <path d="M14.188 14.6874C14.4321 14.4433 14.8277 14.4434 15.0718 14.6875L16.9235 16.5394C17.1676 16.7835 17.1676 17.1792 16.9235 17.4232C16.6794 17.6673 16.2837 17.6673 16.0396 17.4232L14.1879 15.5713C13.9438 15.3272 13.9439 14.9315 14.188 14.6874Z" fill="var(--bs-heading-color)">
                                </path>
                                <path d="M5.81235 14.6875C6.05643 14.9315 6.05643 15.3272 5.81235 15.5713L3.9605 17.4231C3.71642 17.6672 3.32069 17.6672 3.07662 17.4231C2.83253 17.179 2.83253 16.7834 3.07662 16.5393L4.92847 14.6874C5.17254 14.4434 5.56828 14.4434 5.81235 14.6875Z" fill="var(--bs-heading-color)">
                                </path>
                            </g>
                        </svg>
                        <div class="theme-toggle">
                        </div>
                        <svg class="icon-dark" fill="none" height="21" viewbox="0 0 20 21" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5835 2.4225C16.4495 2.08117 15.9681 2.08117 15.834 2.4225L15.4754 3.33523C15.4345 3.43944 15.3523 3.52193 15.2485 3.56303L14.339 3.92301C13.999 4.05762 13.999 4.54067 14.339 4.67529L15.2485 5.03527C15.3523 5.07637 15.4345 5.15886 15.4754 5.26306L15.834 6.1758C15.9681 6.51712 16.4495 6.51712 16.5835 6.17581L16.9422 5.26306C16.9831 5.15886 17.0653 5.07637 17.1691 5.03527L18.0785 4.67529C18.4186 4.54067 18.4186 4.05762 18.0785 3.92301L17.1691 3.56303C17.0653 3.52193 16.9831 3.43944 16.9422 3.33523L16.5835 2.4225Z" fill="var(--bs-heading-color)">
                            </path>
                            <path d="M13.3609 7.27454C13.2267 6.93323 12.7455 6.93323 12.6113 7.27454L12.4806 7.60733C12.4396 7.71154 12.3575 7.79403 12.2536 7.83513L11.9221 7.96638C11.582 8.10099 11.582 8.58404 11.9221 8.71866L12.2536 8.8499C12.3575 8.89098 12.4396 8.97348 12.4806 9.07773L12.6113 9.41048C12.7455 9.75182 13.2267 9.75182 13.3609 9.41048L13.4916 9.07773C13.5326 8.97348 13.6147 8.89098 13.7186 8.8499L14.0501 8.71866C14.3902 8.58404 14.3902 8.10099 14.0501 7.96638L13.7186 7.83513C13.6147 7.79403 13.5326 7.71154 13.4916 7.60733L13.3609 7.27454Z" fill="var(--bs-heading-color)">
                            </path>
                            <path d="M10.0003 18.8332C14.6027 18.8332 18.3337 15.1022 18.3337 10.4998C18.3337 10.1143 17.7557 10.0505 17.5563 10.3805C16.6077 11.9503 14.8849 12.9998 12.917 12.9998C9.92541 12.9998 7.50032 10.5748 7.50032 7.58317C7.50032 5.61521 8.54982 3.89238 10.1197 2.9438C10.4497 2.7444 10.3859 2.1665 10.0003 2.1665C5.39795 2.1665 1.66699 5.89746 1.66699 10.4998C1.66699 15.1022 5.39795 18.8332 10.0003 18.8332Z" fill="var(--bs-heading-color)" opacity="0.5">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="vr my-3">
                </div>
                <div class="d-flex align-items-center gap-sm-2 gap-0 px-lg-4 px-sm-2 px-1">
                    <div class="dropdown text-end">
                        <button aria-expanded="true" class="btn btn-icon btn-action-gray rounded-circle waves-effect waves-light" data-bs-auto-close="outside" data-bs-toggle="dropdown" type="button">
                            <svg fill="none" height="25" viewbox="0 0 24 25" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.7491 10.2096V9.50497C18.7491 5.63623 15.7274 2.5 12 2.5C8.27256 2.5 5.25087 5.63623 5.25087 9.50497V10.2096C5.25087 11.0552 5.00972 11.8818 4.5578 12.5854L3.45036 14.3095C2.43882 15.8843 3.21105 18.0249 4.97036 18.5229C9.57274 19.8257 14.4273 19.8257 19.0296 18.5229C20.789 18.0249 21.5612 15.8843 20.5496 14.3095L19.4422 12.5854C18.9903 11.8818 18.7491 11.0552 18.7491 10.2096Z" stroke="var(--bs-heading-color)" stroke-width="2">
                                </path>
                                <path d="M7.5 19.5C8.15503 21.2478 9.92246 22.5 12 22.5C14.0775 22.5 15.845 21.2478 16.5 19.5" opacity="0.5" stroke="var(--bs-heading-color)" stroke-linecap="round" stroke-width="2">
                                </path>
                                <path d="M12 6.5V10.5" opacity="0.5" stroke="var(--bs-heading-color)" stroke-linecap="round" stroke-width="2">
                                </path>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg-end p-0 w-300px mt-2">
                            <div class="px-3 py-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    هشدارهای جدید
                                    <span class="badge badge-sm rounded-pill bg-primary ms-2">
			9
		   </span>
                                </h6>
                                <i class="bi bi-x-lg cursor-pointer">
                                </i>
                            </div>
                            <div class="p-2" data-simplebar="" style="height: 300px;">
                                <ul class="list-group list-group-hover list-group-smooth list-group-unlined">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs avatar-status-success rounded-circle me-1">
                                            <img alt=""  src="/admin/assets/images/avatar/avatar2.webp"/>
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                باربد باباخانی
                                            </h6>
                                            <small class="text-body d-block">
                                                نیاز به به روز رسانی جزئیات
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                7 ساعت قبل
                                            </small>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs bg-success rounded-circle text-white">
                                            D
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                تیم طراحی
                                            </h6>
                                            <small class="text-body d-block">
                                                پوشه مشترک خود را بررسی کنید.
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                6 ساعت پیش
                                            </small>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs bg-dark rounded-circle text-white">
                                            <i class="fi fi-rr-lock">
                                            </i>
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                به روز رسانی امنیتی
                                            </h6>
                                            <small class="text-body d-block">
                                                رمز عبور با موفقیت تنظیم شد.
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                5 ساعت قبل
                                            </small>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs bg-info rounded-circle text-white">
                                            <i class="fi fi-rr-shopping-cart">
                                            </i>
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                فاکتور شماره 1432
                                            </h6>
                                            <small class="text-body d-block">
                                                مبلغ پرداخت شده است: 899.00 دلار
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                5 ساعت قبل
                                            </small>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs bg-danger rounded-circle text-white">
                                            آر
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                باربد باباخانی
                                            </h6>
                                            <small class="text-body d-block">
                                                شما را به Dashboard Analytics اضافه کرد
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                5 ساعت قبل
                                            </small>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs avatar-status-success rounded-circle me-1">
                                            <img alt=""  src="/admin/assets/images/avatar/avatar3.webp"/>
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                شیرین رضایی
                                            </h6>
                                            <small class="text-body d-block">
                                                اکنون می توانید "گزارش" را مشاهده کنید.
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                4 ساعت پیش
                                            </small>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="avatar avatar-xs avatar-status-danger rounded-circle me-1">
                                            <img alt=""  src="/admin/assets/images/avatar/avatar5.webp"/>
                                        </div>
                                        <div class="ms-2 me-auto">
                                            <h6 class="mb-0">
                                                مهسا رهنما
                                            </h6>
                                            <small class="text-body d-block">
                                                @Isabella لطفا بررسی کنید.
                                            </small>
                                            <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">
                                                2 ساعت پیش
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="p-2">
                                <a class="btn w-100 btn-primary waves-effect waves-light" href="javascript:void(0);">
                                    مشاهده تمام اعلان ها
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vr my-3">
                </div>
                <div class="dropdown text-end ms-sm-3 ms-2 ms-lg-4">
                    <a aria-expanded="true" class="d-flex align-items-center py-2" data-bs-auto-close="outside" data-bs-toggle="dropdown" href="#">
                        <div class="text-end me-2 d-none d-lg-inline-block">
                            <div class="fw-bold text-dark">
                                  {{ $adminName }}
                            </div>
                            <small class="text-body d-block lh-sm">
                                <i class="fi fi-rr-angle-down text-3xs me-1">
                                </i>
                                {{ $adminRole }}
                            </small>
                        </div>
                        <div class="avatar avatar-sm rounded-circle avatar-status-success">
                            <img alt=""  src="/admin/assets/images/avatar/avatar1.webp"/>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end w-225px mt-1">
                        <li class="d-flex align-items-center p-2">
                            <div class="avatar avatar-sm rounded-circle">
                                <img alt=""  src="/admin/assets/images/avatar/avatar1.webp"/>
                            </div>
                            <div class="ms-2">
                                <div class="fw-bold text-dark">
                                      {{ $adminName }}
                                </div>
                                <small class="text-body d-block lh-sm">
                                    {{ $adminEmail }}
                                </small>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1">
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                <i class="fi fi-rr-user scale-1x">
                                </i>
                                مشاهده نمایه
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                <i class="fi fi-rr-note scale-1x">
                                </i>
                                وظیفه من
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                <i class="fi fi-rr-settings scale-1x">
                                </i>
                                تنظیمات حساب
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1">
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="{{route('admin.logout')}}" target="_blank">
                                <i class="fi fi-sr-exit scale-1x">
                                </i>
                                از سیستم خارج شوید
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- end::NexLink Page Header -->
    <div aria-hidden="true" class="modal fade" id="searchResultsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-1 px-3">
                    <form action="#" class="d-flex align-items-center position-relative w-100">
                        <button class="btn btn-sm border-0 position-absolute start-0 p-0 text-sm" type="button">
                            <i class="fi fi-rr-search">
                            </i>
                        </button>
                        <input class="form-control form-control-lg ps-4 border-0 shadow-none" id="searchInput" placeholder="هر چیزی را جستجو کنید" type="text"/>
                    </form>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                    </button>
                </div>
                <div class="modal-body pb-2" data-simplebar="" style="height: 300px;">
                    <div id="recentlyResults">
		<span class="text-uppercase text-2xs fw-semibold text-muted d-block mb-2">
		 اخیرا جستجو شده:
		</span>
                        <ul class="list-inline search-list">
                            <li>
                                <a class="search-item" href="#">
                                    <i class="fi fi-rr-apps">
                                    </i>
                                    داشبورد
                                </a>
                            </li>
                            <li>
                                <a class="search-item" href="#">
                                    <i class="fi fi-rr-comment">
                                    </i>
                                    گفتگو
                                </a>
                            </li>

                            <li>
                                <a class="search-item" href="#">
                                    <i class="fi fi-rr-envelope">
                                    </i>
                                    ایمیل
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="searchContainer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
