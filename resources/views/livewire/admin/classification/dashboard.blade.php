<div>
    <div>

        <div class="row">

            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                    <h4 class="mb-sm-0">داشبورد طبقه‌بندی دروس</h4>

                    <div class="page-title-right">

                        <ol class="breadcrumb m-0">

                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">داشبورد</a></li>

                            <li class="breadcrumb-item active">طبقه‌بندی</li>

                        </ol>

                    </div>

                </div>

            </div>

        </div>


        <!-- Stats Cards -->

        <div class="row mb-4">

            <div class="col-xl-4 col-md-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1 overflow-hidden">

                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">پروژه‌های در
                                    انتظار</p>

                            </div>

                        </div>

                        <div class="d-flex align-items-end justify-content-between mt-4">

                            <div>

                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">

                                    <span class="counter-value"
                                          data-target="{{ $upcomingProjects }}">{{ $upcomingProjects }}</span>

                                </h4>

                                <span class="badge bg-info-subtle text-info">در انتظار شروع</span>

                            </div>

                            <div class="avatar-sm flex-shrink-0">

                            <span class="avatar-title bg-info-subtle rounded fs-3">

                                <i class="ri-time-line text-info"></i>

                            </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-4 col-md-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1 overflow-hidden">

                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">پروژه‌های تمام‌شده</p>

                            </div>

                        </div>

                        <div class="d-flex align-items-end justify-content-between mt-4">

                            <div>

                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">

                                    <span class="counter-value"
                                          data-target="{{ $endedProjects }}">{{ $endedProjects }}</span>

                                </h4>

                                <span class="badge bg-success-subtle text-success">پایان یافته</span>

                            </div>

                            <div class="avatar-sm flex-shrink-0">

                            <span class="avatar-title bg-success-subtle rounded fs-3">

                                <i class="ri-checkbox-circle-line text-success"></i>

                            </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-xl-4 col-md-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1 overflow-hidden">

                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">روز تا پروژه بعدی</p>

                            </div>

                        </div>

                        <div class="d-flex align-items-end justify-content-between mt-4">

                            <div>

                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">

                                    @if($daysToNext !== null)

                                        <span class="counter-value"
                                              data-target="{{ $daysToNext }}">{{ $daysToNext }}</span>

                                    @else

                                        <span class="text-muted">-</span>

                                    @endif

                                </h4>

                                <span class="badge bg-warning-subtle text-warning">روز</span>

                            </div>

                            <div class="avatar-sm flex-shrink-0">

                            <span class="avatar-title bg-warning-subtle rounded fs-3">

                                <i class="ri-calendar-event-line text-warning"></i>

                            </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Projects List -->

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header d-flex align-items-center justify-content-between">

                        <h5 class="card-title mb-0">لیست پروژه‌ها</h5>

                        <div class="search-box">

                            <input type="text" wire:model.live.debounce.300ms="search"

                                   class="form-control search" placeholder="جستجو...">

                            <i class="ri-search-line search-icon"></i>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle mb-0">

                                <thead class="table-light">

                                <tr>

                                    <th style="width: 50px;">ردیف</th>

                                    <th>نام پروژه</th>

                                    <th style="width: 140px;">تاریخ شروع</th>

                                    <th style="width: 140px;">تاریخ پایان</th>

                                    <th style="width: 100px;">وضعیت</th>

                                    <th style="width: 120px;">عملیات</th>

                                </tr>

                                </thead>

                                <tbody>

                                @forelse($projects as $project)

                                    <tr>

                                        <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>

                                        <td>

                                            <div class="fw-medium">{{ $project->name }}</div>

                                            @if($project->description)

                                                <small
                                                    class="text-muted">{{ Str::limit($project->description, 50) }}</small>

                                            @endif

                                        </td>

                                        <td class="text-nowrap small">

                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}

                                            <br>

                                            <span class="text-muted">{{ $project->start_at->format('H:i') }}</span>

                                        </td>

                                        <td class="text-nowrap small">

                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}

                                            <br>

                                            <span class="text-muted">{{ $project->end_at->format('H:i') }}</span>

                                        </td>

                                        <td>

                                            @if($project->status === 'active')

                                                <span class="badge bg-success">در حال اجرا</span>

                                            @elseif($project->status === 'upcoming')

                                                <span class="badge bg-info">در انتظار</span>

                                            @else

                                                <span class="badge bg-secondary">پایان یافته</span>

                                            @endif

                                        </td>

                                        <td>

                                            <a href="{{ route('admin.classification.students', $project->id) }}"

                                               class="btn btn-sm btn-soft-primary">

                                                <i class="ri-group-line me-1"></i>

                                                دانش‌آموزان

                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center py-4">

                                            <div class="text-muted">

                                                <i class="ri-folder-open-line fs-1 d-block mb-2"></i>

                                                هیچ پروژه‌ای یافت نشد

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>


                        <div class="mt-3">

                            {{ $projects->links('layouts.admin.pagination') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
