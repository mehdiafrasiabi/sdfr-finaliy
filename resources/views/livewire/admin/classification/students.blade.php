<div>

    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">دانش‌آموزان - {{ $project->name }}</h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">داشبورد</a></li>

                        <li class="breadcrumb-item"><a href="{{ route('admin.classification.dashboard') }}">طبقه‌بندی</a></li>

                        <li class="breadcrumb-item active">دانش‌آموزان</li>

                    </ol>

                </div>

            </div>

        </div>

    </div>



    <!-- Stats -->

    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm flex-shrink-0">

                            <span class="avatar-title bg-success-subtle rounded-circle fs-3">

                                <i class="ri-check-double-line text-success"></i>

                            </span>

                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-0">ارسال کرده</p>

                            <h4 class="mb-0">{{ $submittedCount }}</h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="avatar-sm flex-shrink-0">

                            <span class="avatar-title bg-warning-subtle rounded-circle fs-3">

                                <i class="ri-time-line text-warning"></i>

                            </span>

                        </div>

                        <div class="flex-grow-1 ms-3">

                            <p class="text-muted mb-0">ارسال نکرده</p>

                            <h4 class="mb-0">{{ $totalStudents - $submittedCount }}</h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- Students List -->

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <h5 class="card-title mb-0">لیست دانش‌آموزان</h5>

                    <div class="search-box">

                        <input type="text" wire:model.live.debounce.300ms="search"

                               class="form-control search" placeholder="جستجو نام یا موبایل...">

                        <i class="ri-search-line search-icon"></i>

                    </div>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th style="width: 50px;">ردیف</th>

                                <th>نام دانش‌آموز</th>

                                <th style="width: 120px;">موبایل</th>

                                <th style="width: 100px;">پایه</th>

                                <th style="width: 100px;">رشته</th>

                                <th style="width: 120px;">وضعیت</th>

                                <th style="width: 100px;">عملیات</th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($students as $student)

                                @php

                                    $personalInfo = $student->user->personalInformation ?? null;

                                    $isSubmitted = isset($submissions[$student->user_id]) && $submissions[$student->user_id];

                                    $gradeNames = ['10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم'];

                                    $fieldNames = ['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'];

                                @endphp

                                <tr>

                                    <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="avatar-xs flex-shrink-0 me-2">

                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">

                                                        {{ mb_substr($student->user->name ?? 'N', 0, 1) }}

                                                    </span>

                                            </div>

                                            <div>

                                                <div class="fw-medium">{{ $student->user->name ?? '-' }}</div>

                                                @if($personalInfo)

                                                    <small class="text-muted">{{ $personalInfo->name }}</small>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                    <td dir="ltr" class="text-start">{{ $student->user->mobile ?? '-' }}</td>

                                    <td>

                                        @if($personalInfo)

                                            <span class="badge bg-primary-subtle text-primary">

                                                    {{ $gradeNames[$personalInfo->grade] ?? $personalInfo->grade }}

                                                </span>

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($personalInfo)

                                            <span class="badge bg-info-subtle text-info">

                                                    {{ $fieldNames[$personalInfo->field] ?? $personalInfo->field }}

                                                </span>

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($isSubmitted)

                                            <span class="badge bg-success">

                                                    <i class="ri-check-line me-1"></i>

                                                    ارسال شده

                                                </span>

                                        @else

                                            <span class="badge bg-warning text-dark">

                                                    <i class="ri-time-line me-1"></i>

                                                    ارسال نشده

                                                </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($isSubmitted)

                                            <a href="{{ route('admin.classification.detail', ['project' => $project->id, 'user' => $student->user_id]) }}"

                                               class="btn btn-sm btn-soft-info">

                                                <i class="ri-eye-line"></i>

                                                جزئیات

                                            </a>

                                        @else

                                            <button class="btn btn-sm btn-soft-secondary" disabled>

                                                <i class="ri-eye-off-line"></i>

                                            </button>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-4">

                                        <div class="text-muted">

                                            <i class="ri-user-search-line fs-1 d-block mb-2"></i>

                                            هیچ دانش‌آموزی یافت نشد

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>



                    <div class="mt-3">

                        {{ $students->links('layouts.admin.pagination') }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
