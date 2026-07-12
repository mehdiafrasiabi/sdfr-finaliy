<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}">
                        <i class="fi fi-rr-home"></i>
                        صفحه اصلی
                    </a>
                </li>
                <li aria-current="page" class="breadcrumb-item active">
                    کارنامه هوشمند
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="card-title mb-0">
                        کارنامه هوشمند دانش‌آموزان
                    </h6>
                </div>

                <div class="card-body p-0 pb-2">
                    <div class="dt-container dt-bootstrap5 dt-empty-footer">
                        <div class="row mt-2 justify-content-between mx-2 py-2">
                            <div class="d-md-flex justify-content-end align-items-center col-12">
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    style="max-width: 240px;"
                                    wire:model.live.debounce.350ms="search"
                                    placeholder="جستجوی نام دانش‌آموز"
                                />
                            </div>
                        </div>

                        <div class="row mt-2 justify-content-between ">
                            <div class="d-md-flex justify-content-between align-items-center col-12 col-md">
                                <table class="table display" style="width: 100%;">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>دانش‌آموز</th>
                                        <th>موبایل</th>
                                        <th>پایه و رشته</th>
                                        <th>ماه‌های فعال برای دانش‌آموز</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($students as $student)
                                        @php
                                            $personalInfo = $student->user->personalInformation ?? null;
                                            $activeCount = $activeCardCounts[$student->id] ?? 0;
                                            $gradeLabel = match((int)($personalInfo->grade ?? 0)) {
                                                10 => 'دهم',
                                                11 => 'یازدهم',
                                                12 => 'دوازدهم',
                                                default => '-',
                                            };
                                            $fieldLabel = match($personalInfo->field ?? '') {
                                                'math' => 'ریاضی',
                                                'experimental' => 'تجربی',
                                                'human' => 'انسانی',
                                                default => '',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration + $students->firstItem() - 1 }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">{{ $personalInfo->name ?? '-' }} {{ $personalInfo->name_full ?? '' }}</span>
                                                    <small class="text-muted">{{ $student->user->mobile ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>{{ $student->user->mobile ?? '-' }}</td>
                                            <td>{{ $gradeLabel }} {{ $fieldLabel }}</td>
                                            <td>
                                                <span class="badge bg-{{ $activeCount > 0 ? 'success' : 'secondary' }}-subtle text-{{ $activeCount > 0 ? 'success' : 'secondary' }}">
                                                    {{ $activeCount }} ماه
                                                </span>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm"
                                                   href="{{ route('admin.student.smartReportCard.detail', $student->user_id) }}">
                                                    <i class="fi fi-rr-eye me-1"></i>
                                                    مشاهده کارنامه
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-danger text-center py-4">
                                                دانش‌آموزی یافت نشد
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="px-3">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
