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
                    <button type="button" class="btn btn-primary btn-sm" wire:click="openBulkModal">
                        <i class="fi fi-rr-users me-1"></i>
                        فعال‌سازی گروهی
                    </button>
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
                                        <th>کارنامه‌های فعال</th>
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
                                                    <i class="fi fi-rr-edit me-1"></i>
                                                    مدیریت کارنامه
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

    {{-- ==================== Bulk Activation Modal ==================== --}}
    @if($bulkModalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width:780px;">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fi fi-rr-users"></i>
                            فعال‌سازی گروهی کارنامه هوشمند
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeBulkModal"></button>
                    </div>

                    {{-- Step indicator --}}
                    <div class="px-3 py-2 border-bottom bg-light">
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge rounded-pill {{ $bulkStep === 1 ? 'bg-primary' : 'bg-success' }}">1</span>
                            <span class="{{ $bulkStep === 1 ? 'fw-bold text-primary' : 'text-success' }}">انتخاب دانش‌آموزان</span>
                            <div class="flex-fill mx-2" style="border-top:1px dashed #ccc;"></div>
                            <span class="badge rounded-pill {{ $bulkStep === 2 ? 'bg-primary' : 'bg-secondary' }}">2</span>
                            <span class="{{ $bulkStep === 2 ? 'fw-bold text-primary' : 'text-muted' }}">انتخاب سال و ماه‌ها</span>
                        </div>
                    </div>

                    <div class="modal-body p-0">

                        {{-- ============ Step 1: Pick Students ============ --}}
                        @if($bulkStep === 1)
                            <div class="p-3 border-bottom bg-light">
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fi fi-rr-search"></i></span>
                                    <input type="text"
                                           class="form-control"
                                           wire:model.live.debounce.300ms="bulkStudentSearch"
                                           placeholder="جستجوی دانش‌آموز..." />
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="selectAllBulkStudents">
                                        انتخاب همه
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="clearBulkStudents">
                                        لغو انتخاب
                                    </button>
                                </div>
                            </div>

                            @if(count($bulkSelectedStudents) > 0)
                                <div class="p-2 px-3 bg-primary-subtle border-bottom">
                                    <span class="badge bg-primary rounded-pill me-1">{{ count($bulkSelectedStudents) }}</span>
                                    دانش‌آموز انتخاب شده
                                </div>
                            @endif

                            <div style="max-height: 420px; overflow-y: auto;">
                                @forelse($bulkStudents as $mStudent)
                                    @php
                                        $mProfile   = $mStudent->user->profile ?? null;
                                        $mInfo      = $mStudent->user->personalInformation ?? null;
                                        $isSelected = in_array($mStudent->id, $bulkSelectedStudents);
                                    @endphp
                                    <div wire:click="toggleBulkStudent({{ $mStudent->id }})"
                                         class="d-flex align-items-center p-3 border-bottom {{ $isSelected ? 'bg-primary-subtle' : '' }}"
                                         style="cursor:pointer; transition: background 0.15s;">
                                        <div class="me-3">
                                            <div class="form-check mb-0">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       style="width:18px;height:18px;"
                                                       @checked($isSelected)
                                                       onclick="return false;" />
                                            </div>
                                        </div>
                                        <div class="me-3">
                                            @if($mProfile && $mProfile->picture)
                                                <img src="{{ asset('user/img/' . $mStudent->user->id . '/' . $mProfile->picture) }}"
                                                     class="rounded-circle"
                                                     style="width:38px;height:38px;object-fit:cover;" />
                                            @else
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                                     style="width:38px;height:38px;font-size:1rem;">
                                                    <i class="fi fi-rr-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $mInfo->name ?? '-' }} {{ $mInfo->name_full ?? '' }}</div>
                                            <small class="text-muted">{{ $mStudent->user->mobile ?? '' }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-muted small">دانش‌آموزی یافت نشد.</div>
                                @endforelse
                            </div>
                        @endif

                        {{-- ============ Step 2: Pick Year + Months ============ --}}
                        @if($bulkStep === 2)
                            <div class="p-3 border-bottom bg-primary-subtle d-flex align-items-center gap-2 flex-wrap">
                                <i class="fi fi-rr-users text-primary"></i>
                                <span class="fw-bold text-primary">{{ count($bulkSelectedStudents) }}</span>
                                <span>دانش‌آموز انتخاب شده‌اند.</span>
                            </div>

                            {{-- Action mode --}}
                            <div class="p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="fw-semibold">عملیات:</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" wire:click="$set('bulkActivate', true)" @checked($bulkActivate) id="bulkActOn">
                                        <label class="form-check-label" for="bulkActOn">فعال‌سازی</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" wire:click="$set('bulkActivate', false)" @checked(!$bulkActivate) id="bulkActOff">
                                        <label class="form-check-label" for="bulkActOff">غیرفعال‌سازی</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Year --}}
                            <div class="p-3 border-bottom">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-semibold ms-2">سال:</span>
                                    @for($y = $currentJalaliYear - 1; $y <= $currentJalaliYear + 1; $y++)
                                        <button type="button"
                                                wire:click="changeBulkYear({{ $y }})"
                                                class="btn btn-sm {{ $bulkSelectedYear === $y ? 'btn-primary' : 'btn-outline-primary' }}">
                                            {{ $y }}
                                        </button>
                                    @endfor
                                </div>
                            </div>

                            {{-- Months --}}
                            <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <span class="fw-semibold">ماه‌ها:</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="toggleAllBulkMonths">
                                    {{ count($bulkSelectedMonths) === 12 ? 'لغو همه' : 'انتخاب همه' }}
                                </button>
                            </div>

                            <div class="row g-2 p-3">
                                @foreach($monthNames as $num => $name)
                                    @php
                                        $isSelected = in_array($num, $bulkSelectedMonths);
                                        $days = $num <= 6 ? 31 : 30;
                                        $start = sprintf('%04d/%02d/01', $bulkSelectedYear, $num);
                                        $end = sprintf('%04d/%02d/%02d', $bulkSelectedYear, $num, $days);
                                    @endphp
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div wire:click="toggleBulkMonth({{ $num }})"
                                             class="p-2 border rounded-3 text-center {{ $isSelected ? 'border-primary bg-primary-subtle' : 'bg-light' }}"
                                             style="cursor: pointer; transition: all .15s;">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="fw-bold small">{{ $name }}</span>
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       style="width:16px;height:16px;"
                                                       @checked($isSelected)
                                                       onclick="return false;" />
                                            </div>
                                            <div class="text-muted" style="font-size: 10.5px;">
                                                {{ $start }} - {{ $end }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-top">
                        @if($bulkStep === 1)
                            <button type="button" class="btn btn-outline-secondary" wire:click="closeBulkModal">
                                انصراف
                            </button>
                            <button type="button" class="btn btn-primary"
                                    wire:click="goToBulkStep(2)"
                                @disabled(empty($bulkSelectedStudents))>
                                ادامه
                                <i class="fi fi-rr-arrow-left ms-1"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-secondary" wire:click="goToBulkStep(1)">
                                <i class="fi fi-rr-arrow-right me-1"></i>
                                بازگشت
                            </button>
                            <button type="button"
                                    class="btn {{ $bulkActivate ? 'btn-success' : 'btn-danger' }}"
                                    wire:click="submitBulk"
                                    wire:loading.attr="disabled"
                                    wire:target="submitBulk"
                                @disabled(empty($bulkSelectedMonths))>
                                <span wire:loading.remove wire:target="submitBulk">
                                    {{ $bulkActivate ? 'فعال‌سازی' : 'غیرفعال‌سازی' }}
                                    ({{ count($bulkSelectedStudents) * count($bulkSelectedMonths) }} کارنامه)
                                </span>
                                <span wire:loading wire:target="submitBulk">در حال انجام...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
