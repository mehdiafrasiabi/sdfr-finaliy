
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
                    گزارشات دانش آموزان
                </li>
            </ol>
        </nav>
    </div>

    {{-- ───────────── داشبورد جدید ───────────── --}}
    <div class="row g-3 mb-4">
        {{-- آمار کلی --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header">آمار امروز ({{ \Morilog\Jalali\Jalalian::forge('today')->format('Y/m/d') }})</div>
                <div class="card-body">
                    <p>گزارش ارسال شده: <span class="fw-bold text-success">{{ $dashboardStats['reportsSentToday'] ?? 0 }} نفر</span></p>
                    <p class="mb-0">عدم ارسال گزارش: <span class="fw-bold text-danger">{{ $dashboardStats['reportsNotSentToday'] ?? 0 }} نفر</span></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header">آمار کلی گزارشات</div>
                <div class="card-body">
                    <p>گزارشات جبرانی: <span class="fw-bold">{{ $dashboardStats['compensatoryReportsCount'] ?? 0 }}</span></p>
                    <p>در انتظار تایید: <span class="fw-bold">{{ $dashboardStats['pendingReportsCount'] ?? 0 }}</span></p>
                    <p class="mb-0">میانگین رضایت: <span class="fw-bold">{{ $dashboardStats['averageRating'] ?? 0 }} از ۵</span></p>
                </div>
            </div>
        </div>

        {{-- آمار تست --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header">آمار تست‌ها</div>
                <div class="card-body">
                    @if(isset($dashboardStats['testStats']))
                        <p>کل تست‌ها: <span class="fw-bold">{{ $dashboardStats['testStats']['planned'] ?? 0 }}</span></p>
                        <p>انجام شده: <span class="fw-bold text-success">{{ $dashboardStats['testStats']['completed'] ?? 0 }}</span></p>
                        <p class="mb-0">انجام نشده: <span class="fw-bold text-danger">{{ $dashboardStats['testStats']['not_completed'] ?? 0 }}</span></p>
                    @else
                        <p class="text-muted">داده‌ای برای نمایش وجود ندارد.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- میانگین ارسال هفتگی --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-header">میانگین ارسال هفتگی</div>
                <div class="card-body">
                    @if(!empty($dashboardStats['dayOfWeekStats']))
                        @foreach($dashboardStats['dayOfWeekStats'] as $day => $percent)
                            <div class="d-flex justify-content-between">
                                <span>{{ $day }}:</span>
                                <span class="fw-bold">{{ $percent }}%</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">داده‌ای برای نمایش وجود ندارد.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- بهترین و بدترین دانش آموزان --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header text-success">دانش آموزان با بهترین عملکرد (۳۰ روز اخیر)</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($dashboardStats['bestPerformers'] ?? [] as $student)
                            <li class="list-group-item">{{ $student->user?->personalInformation?->name ?? $student->user?->name }}</li>
                        @empty
                            <li class="list-group-item text-muted">موردی یافت نشد.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header text-danger">دانش آموزان با ضعیف‌ترین عملکرد (۳۰ روز اخیر)</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($dashboardStats['worstPerformers'] ?? [] as $student)
                             <li class="list-group-item">{{ $student->user?->personalInformation?->name ?? $student->user?->name }}</li>
                        @empty
                            <li class="list-group-item text-muted">موردی یافت نشد.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>


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
                                        <th data-dt-column="0">#</th>
                                        <th data-dt-column="1">دانش آموز</th>
                                        <th data-dt-column="2" class="text-center">ارسال شده</th>
                                        <th data-dt-column="3" class="text-center">ارسال نشده</th>
                                        <th data-dt-column="4" class="text-center">جبرانی</th>
                                        <th data-dt-column="5"></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                {{$loop->iteration + $students->firstItem() - 1}}
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            @php
                                                                $profile = $student->user->profile ?? null;
                                                                $personalInfo = $student->user->personalInformation ?? null;
                                                            @endphp
                                                            @if($profile && $profile->picture)
                                                                <img
                                                                    src="{{ asset('user/img/' . $student->user->id . '/' . $profile->picture) }}"
                                                                    alt="{{ $personalInfo->name ?? '' }}"
                                                                    class="rounded-circle"
                                                                    style="width:40px;height:40px;object-fit:cover;"
                                                                />
                                                           @else
                                                                <span
                                                                    class="avatar-title rounded-circle bg-secondary-subtle text-secondary fs-4">
                                                                    <i class="fi fi-rr-user"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column">
                                                          <span class="text-truncate fw-medium">
                                                           {{ $personalInfo->name ?? $student->user->name ?? '-' }}
                                                          </span>
                                                        <small class="text-truncate text-muted">
                                                            {{ $student->user->mobile ?? '' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>


                                            {{-- تعداد گزارشات ارسال شده --}}
                                            <td class="text-center">
                                                <span class="badge bg-success fs-6">
                                                    {{ $sentCounts[$student->id] ?? 0 }}
                                                </span>
                                            </td>

                                            {{-- تعداد گزارشات ارسال نشده --}}
                                            <td class="text-center">
                                                @php $ns = $notSentCounts[$student->id] ?? 0; @endphp
                                                <span class="badge {{ $ns > 0 ? 'bg-danger' : 'bg-secondary' }} fs-6">
                                                    {{ $ns }}
                                                </span>
                                            </td>

                                            {{-- تعداد گزارشات جبرانی --}}
                                            <td class="text-center">
                                                <span class="badge bg-warning text-white fs-6">
                                                    {{ $compensatoryCounts[$student->id] ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group float-start">
                                                    <a class="dropdown-item" href="{{ route('admin.student.reportDailyActivities.detail', $student->user_id) }}">
                                                        <i class="fi fi-br-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-danger text-center">
                                                وجود ندارد
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>

                                </table>


                            </div>
                        </div>

                        <!-- Bottom Pagination -->
                        <div class="row mt-2 justify-content-between">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                            </div>

                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
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
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,.5);" wire:click.self="closeExportModal">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">خروجی اکسل گزارشات دانش آموزان</h5>
                        <button type="button" class="btn-close" wire:click="closeExportModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">نوع خروجی</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="report_export_all" value="all" wire:model.live="exportTarget">
                                    <label class="form-check-label" for="report_export_all">همه دانش‌آموزان</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="report_export_selected" value="selected" wire:model.live="exportTarget">
                                    <label class="form-check-label" for="report_export_selected">چند دانش‌آموز انتخابی</label>
                                </div>
                            </div>
                        </div>

                        @if($exportTarget === 'selected')
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="report_selected_students">انتخاب دانش‌آموزان</label>
                                <select id="report_selected_students" class="form-select" multiple wire:model.live="selectedStudentIds" style="min-height: 180px;">
                                    @foreach($exportStudents as $exportStudent)
                                        <option value="{{ $exportStudent->id }}">
                                            {{ trim(($exportStudent->user?->personalInformation?->name ?? '') . ' ' . ($exportStudent->user?->personalInformation?->name_full ?? '')) ?: '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedStudentIds') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">از تاریخ</label>
                                <div wire:ignore>
                                    <input type="text" id="report_export_start" data-jdp data-jdp-only-date class="form-control" placeholder="1404/01/01" autocomplete="off" readonly>
                                </div>
                                @error('exportStartDate') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">تا تاریخ</label>
                                <div wire:ignore>
                                    <input type="text" id="report_export_end" data-jdp data-jdp-only-date class="form-control" placeholder="1404/01/30" autocomplete="off" readonly>
                                </div>
                                @error('exportEndDate') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeExportModal">انصراف</button>
                        <button type="button" class="btn btn-success" wire:click="exportExcel">دریافت خروجی اکسل</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        if (typeof jalaliDatepicker !== 'undefined') {
            jalaliDatepicker.startWatch({
                time: false,
                autoClose: true,
                changeMonth: true,
                changeYear: true,
                showTodayBtn: true,
                todayBtnText: 'امروز',
                zIndex: 2000

            });
        }

        const bindReportExportDates = () => {
            const start = document.getElementById('report_export_start');
            const end = document.getElementById('report_export_end');

            if (start) {
                start.value = $wire.exportStartDate || '';
                start.onchange = (e) => $wire.set('exportStartDate', e.target.value);
            }
            if (end) {
                end.value = $wire.exportEndDate || '';
                end.onchange = (e) => $wire.set('exportEndDate', e.target.value);
            }
        };

        $wire.$watch('exportModalOpen', (open) => {
            if (open) {
                setTimeout(bindReportExportDates, 50);
            }
        });
    </script>
    @endscript

</div>
