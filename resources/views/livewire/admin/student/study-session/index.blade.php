<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard.index')}}">
                        <i class="fi fi-rr-home">
                        </i>
                        صفحه اصلی
                    </a>
                </li>
                <li aria-current="page" class="breadcrumb-item active">
                    میزان ساعت مطالعه
                </li>
            </ol>
        </nav>
    </div>

    {{-- ───────────── داشبورد جدید ───────────── --}}
    @if(!empty($dashboardData))
        <div class="row g-3 mb-4">
            {{-- آمار کلی ساعت مطالعه --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="card-header">آمار کلی ساعت مطالعه</div>
                    <div class="card-body">
                        <p>کل برنامه‌ریزی شده: <span class="fw-bold">{{ $dashboardData['totalPlannedHours'] ?? 0 }} ساعت</span></p>
                        <p>کل مطالعه شده: <span class="fw-bold text-success">{{ $dashboardData['totalStudiedHours'] ?? 0 }} ساعت</span></p>
                        <p class="mb-0">کل جبرانی: <span class="fw-bold text-info">{{ $dashboardData['totalCompensatoryHours'] ?? 0 }} ساعت</span></p>
                    </div>
                </div>
            </div>

            {{-- آمار پارت‌ها و امتیاز --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="card-header">آمار پارت‌ها</div>
                    <div class="card-body">
                        <p>کل پارت‌های برنامه‌ریزی شده: <span class="fw-bold">{{ $dashboardData['totalPartsPlanned'] ?? 0 }}</span></p>
                        <p>انجام شده: <span class="fw-bold text-success">{{ $dashboardData['totalPartsDone'] ?? 0 }}</span></p>
                        <p>انجام نشده: <span class="fw-bold text-danger">{{ $dashboardData['totalPartsNotDone'] ?? 0 }}</span></p>
                        <p class="mb-0">میانگین امتیاز پارت: <span class="fw-bold">{{ $dashboardData['averagePartRating'] ?? 0 }}/10</span></p>
                    </div>
                </div>
            </div>

            {{-- آمار تست‌ها --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="card-header">آمار تست‌ها</div>
                    <div class="card-body">
                        <p>کل تست‌های برنامه‌ریزی شده: <span class="fw-bold">{{ $dashboardData['totalTestsPlanned'] ?? 0 }}</span></p>
                        <p>انجام شده: <span class="fw-bold text-success">{{ $dashboardData['totalTestsDone'] ?? 0 }}</span></p>
                        <p class="mb-0">انجام نشده: <span class="fw-bold text-danger">{{ $dashboardData['totalTestsNotDone'] ?? 0 }}</span></p>
                    </div>
                </div>
            </div>

            {{-- میانگین مطالعه --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="card-header">عملکرد میانگین</div>
                    <div class="card-body">
                        <p class="mb-0">میانگین ساعت مطالعه روزانه هر دانش‌آموز: <span class="fw-bold">{{ $dashboardData['averageDailyStudyHours'] ?? 0 }} ساعت</span></p>
                    </div>
                </div>
            </div>

            {{-- بهترین و بدترین دانش آموزان --}}
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header text-success">دانش آموزان با بهترین عملکرد (بیش از 80%)</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($dashboardData['bestPerformers'] ?? [] as $student)
                                <li class="list-group-item">{{ $student->user?->personalInformation?->name ?? $student->user?->name }}</li>
                            @empty
                                <li class="list-group-item text-muted">موردی یافت نشد.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header text-danger">دانش آموزان با ضعیف‌ترین عملکرد (کمتر از 30%)</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($dashboardData['worstPerformers'] ?? [] as $student)
                                 <li class="list-group-item">{{ $student->user?->personalInformation?->name ?? $student->user?->name }}</li>
                            @empty
                                <li class="list-group-item text-muted">موردی یافت نشد.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- دروس فراری و پارت‌های تقلب --}}
            <div class="col-lg-4">
                 <div class="card h-100">
                    <div class="card-header text-warning">آنالیز کیفی</div>
                    <div class="card-body">
                        <h6>پارت‌های تقلب شده:</h6>
                        <ul class="list-group list-group-flush mb-3">
                           @forelse($dashboardData['cheatedPartsDetails'] ?? [] as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $detail['student_name'] }}
                                    <span class="badge bg-danger rounded-pill">{{ $detail['count'] }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">موردی یافت نشد.</li>
                            @endforelse
                        </ul>

                        <h6>بیشترین دروس فراری:</h6>
                        <ul class="list-group list-group-flush">
                           @forelse($dashboardData['fugitiveLessons'] ?? [] as $lesson => $count)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $lesson }}
                                    <span class="badge bg-secondary rounded-pill">{{ $count }} نفر</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">موردی یافت نشد.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">
                      لیست دانش آموزان
                    </h6>
                </div>

                <div class="card-body p-0 pb-2">
                    <div id="dt_basic_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">

                        <!-- Top Controls -->
                        <div class="row mt-2 justify-content-between mx-2 py-2">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                                <div class="dt-length">
                                    <label for="dt-length-0"> در هرصفحه</label>:
                                    <select
                                        aria-controls="dt_basic"
                                        class="form-select form-select-sm"
                                        id="dt-length-0"
                                        wire:model.live="perPage"
                                    >
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>

                                </div>
                            </div>

                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto gap-2">
                                <button type="button" class="btn btn-success btn-sm" wire:click="openExportModal">
                                    خروجی اکسل
                                </button>
                                <div>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="search"
                                        wire:model.live.debounce.350ms="search"
                                        name="search"
                                        placeholder="جستجو"
                                    />
                                    <label for="dt-search-0"></label>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="row mt-2 justify-content-between ">
                            <div
                                class="d-md-flex justify-content-between align-items-center col-12  col-md">
                                <table class="table display"  style="width: 100%;">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>دانش آموز</th>
                                        <th>ساعت مطالعه (عادی + جبرانی)</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>{{ $loop->iteration + $students->firstItem() - 1 }}</td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            @if($student->user?->profile?->picture)
                                                                <img src="{{ asset('user/img/' . $student->user->id . '/' . $student->user->profile->picture) }}"
                                                                     alt="{{ $student->user->personalInformation->name ?? '' }}" class="rounded-circle"
                                                                     style="width:40px;height:40px;object-fit:cover;"/>
                                                            @else
                                                                <span class="avatar-title rounded-circle bg-secondary-subtle text-secondary fs-4">
                                                                    <i class="fi fi-rr-user"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-truncate fw-medium">{{ $student->user?->personalInformation?->name ?? $student->user->name ?? '-' }}</span>
                                                        <small class="text-truncate text-muted">{{ $student->user->mobile ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $studentTotalDisplays[$student->id] ?? '00:00+00:00' }}</td>
                                            <td>
                                                <div class="btn-group float-end">
                                                    <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                                        <i class="fi fi-rr-menu-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.student.studySession.detail', $student->user_id) }}">
                                                                مشاهده جزئیات
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-danger text-center">وجود ندارد</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Bottom Pagination -->
                        <div class="row mt-2 justify-content-between">
                            <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto"></div>
                            <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
                                <div class="dt-paging">
                                    {{ $students->links('layouts.admin.pagination') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($exportModalOpen)
    {{-- Modal content is unchanged --}}
    @endif

    @script
    <script>
        // Script content is unchanged
    </script>
    @endscript
</div>
