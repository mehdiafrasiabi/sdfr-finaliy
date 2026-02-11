<div>
    @push('link')
        <!-- Vendors CSS -->
        <link href="/admin/assets/vendor/libs/node-waves/node-waves.css" rel="stylesheet"/>
        <link href="/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet"/>
        <link href="/admin/assets/vendor/libs/typeahead-js/typeahead.css" rel="stylesheet"/>
        <link href="/admin/assets/vendor/libs/select2/select2.css" rel="stylesheet"/>
        <link href="/admin/assets/vendor/libs/tagify/tagify.css" rel="stylesheet"/>
        <link href="/admin/assets/vendor/libs/bootstrap-select/bootstrap-select.css" rel="stylesheet"/>
        <link href="/admin/assets/vendor/libs/typeahead-js/typeahead.css" rel="stylesheet"/>
        <!-- Page CSS -->
        <!-- Helpers -->
        <script src="/admin/assets/vendor/js/helpers.js"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="/admin/assets/vendor/js/template-customizer.js"></script>
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="/admin/assets/js/config.js"></script>
    @endpush
    <div class="card">
        <div class="card-header">
            <div class="row g-3 align-items-end">
                {{-- دانش‌آموزان با Select2 --}}
                <div class="col-md-4" wire:ignore>
                    <label class="form-label mb-1">دانش‌آموزان</label>
                    <select id="student-select" class="form-select select2" wire:model.live="selectedStudentId">
                        <option value="">همه دانش‌آموزان</option>
                        @forelse($studentOptions as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->personalInformation->name ?? $student->user->name ?? '---' }}
                            </option>
                        @empty
                            <option value="" disabled>دانش‌آموزی یافت نشد</option>
                        @endforelse
                    </select>
                </div>

                {{-- دکمه انتخاب همه (هنوز نگه می‌داریم برای راحتی) --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="selectAllStudents" class="btn btn-outline-secondary w-100" type="button">
                        همه دانش‌آموزان
                    </button>
                </div>

                {{-- ماه --}}
                <div class="col-md-3">
                    <label class="form-label mb-1">ماه</label>
                    <select class="form-select" wire:model.live="selectedMonthKey">
                        <option value="">همه ماه‌ها</option>
                        @foreach($monthOptions as $monthKey => $monthTitle)
                            <option value="{{ $monthKey }}">{{ $monthTitle }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- دکمه همه ماه‌ها --}}
                <div class="col-md-3 d-flex align-items-end">
                    <button wire:click="selectAllMonths" class="btn btn-outline-secondary w-100" type="button">
                        همه ماه‌ها
                    </button>
                </div>

                {{-- وضعیت گزارش --}}
                <div class="col-md-4 mt-3">
                    <label class="form-label mb-1">وضعیت گزارش</label>
                    <select class="form-select" wire:model.live="selectedStatus">
                        <option value="not_sent">ارسال نشده</option>
                        <option value="rejected">رد شده</option>
                        <option value="both">نمایش هر دو</option>
                    </select>
                </div>

                {{-- دکمه جست‌وجو و خلاصه فیلترها --}}
                <div
                    class="col-md-8 mt-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end">
                    <div class="mb-2 mb-md-0">
                        <p class="mb-1 small text-muted">
                            ماه فعال:
                            <span class="badge bg-label-primary">
                            @if($filtersSubmitted)
                                    {{ $appliedMonthKey ? ($monthOptions[$appliedMonthKey] ?? $appliedMonthKey) : 'همه ماه‌ها' }}
                                @else
                                    انتخاب نشده
                                @endif
                        </span>
                            |
                            دانش‌آموز:
                            <span class="badge bg-label-primary">
                            @if($filtersSubmitted)
                                    {{ $appliedStudentName }}
                                @else
                                    انتخاب نشده
                                @endif
                        </span>
                            |
                            وضعیت:
                            <span class="badge bg-label-info">
                            @if($filtersSubmitted)
                                    @switch($appliedStatus)
                                        @case('rejected') رد شده @break
                                        @case('both') هر دو (ارسال نشده و رد شده) @break
                                        @default ارسال نشده
                                    @endswitch
                                @else
                                    پیش‌فرض (ارسال نشده)
                                @endif
                        </span>
                        </p>
                    </div>

                    <button wire:click="submitFilters"
                            wire:loading.attr="disabled"
                            class="btn btn-primary"
                            type="button">
                        <span wire:loading.remove>جست‌وجو</span>
                        <span wire:loading>در حال بروزرسانی...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <hr>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bx bx-calendar-x text-danger" style="font-size: 24px;"></i>
                        روزهای بدون گزارش و گزارش‌های رد شده
                    </h5>
                    @if($filtersSubmitted && $missingDays instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <span class="badge bg-primary">{{ $missingDays->total() }} مورد</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(!$filtersSubmitted)
                    <div class="text-center py-5">
                        <svg width="96" height="96" fill="currentColor" viewBox="0 0 16 16" class="text-muted mb-3">
                            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                        </svg>
                        <h5 class="mt-3 mb-2">برای شروع فیلترها را انتخاب کنید</h5>
                        <p class="text-muted">برای مشاهده روزهای بدون گزارش و گزارش‌های رد شده، لطفاً فیلترها را تنظیم کرده و روی دکمه «جست‌وجو» کلیک کنید.</p>
                    </div>
                @elseif($missingDays instanceof \Illuminate\Pagination\LengthAwarePaginator && $missingDays->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>دانش‌آموز</th>
                                <th class="text-center">ماه</th>
                                <th class="text-center">تاریخ</th>
                                <th class="text-center">روز هفته</th>
                                <th class="text-center">وضعیت</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($missingDays as $index => $missingDay)
                                <tr class="{{ ($missingDay['status_type'] ?? 'not_sent') === 'rejected' ? 'table-danger' : 'table-warning' }}">
                                    <td class="text-center fw-bold">{{ $missingDays->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bx bx-user-circle text-primary" style="font-size: 20px;"></i>
                                            <span class="fw-medium">{{ $missingDay['student_name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $missingDay['month_name'] }}</span>
                                    </td>
                                    <td class="text-center" dir="ltr">
                                        <strong>{{ $missingDay['day_formatted'] }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $missingDay['weekday'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if(($missingDay['status_type'] ?? 'not_sent') === 'rejected')
                                            <span class="badge bg-danger d-inline-flex align-items-center gap-1">
                                            <i class="bx bx-x-circle"></i>
                                            رد شده
                                        </span>
                                        @else
                                            <span class="badge bg-warning text-dark d-inline-flex align-items-center gap-1">
                                            <i class="bx bx-error"></i>
                                            ارسال نشده
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer bg-transparent border-top mt-3 pt-3">
                        {{ $missingDays->links('layouts.admin.pagination') }}
                    </div>
            @else
                    <div class="text-center py-5">
                        <svg width="96" height="96" fill="currentColor" viewBox="0 0 16 16" class="text-success mb-3">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <h5 class="mt-3 mb-2 text-success">عالی!</h5>
                        <p class="text-muted">برای فیلترهای انتخاب شده هیچ روز بدون گزارش یا گزارش رد شده‌ای یافت نشد.</p>
                    </div>
            @endif

        </div>
    </div>

    @push('script')
        <script src="/admin/assets/vendor/libs/select2/select2.js"></script>
        <script src="/admin/assets/vendor/libs/select2/i18n/fa.js"></script>
        <script src="/admin/assets/vendor/libs/tagify/tagify.js"></script>
        <script src="/admin/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
        <script src="/admin/assets/vendor/libs/bootstrap-select/i18n/defaults-fa_IR.js"></script>
        <script src="/admin/assets/vendor/libs/typeahead-js/typeahead.js"></script>
        <script src="/admin/assets/vendor/libs/bloodhound/bloodhound.js"></script>
        <!-- Main JS -->
        <script src="/admin/assets/js/main.js"></script>
        <!-- Page JS -->
        <script src="/admin/assets/js/forms-selects.js"></script>
        <script src="/admin/assets/js/forms-tagify.js"></script>
        <script src="/admin/assets/js/forms-typeahead.js"></script>

        {{-- Select2 + Livewire --}}
        <script>
            function initStudentSelect2() {
                const $select = $('#student-select');
                if (!$select.length) return;

                // اگر از قبل مقداردهی شده، ریستش کن
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.off('change').select2('destroy');
                }

                $select.select2({
                    placeholder: 'انتخاب دانش‌آموز',
                    allowClear: true,
                    dir: 'rtl',
                    width: '100%',
                    language: 'fa'
                });

                $select.on('change', function (e) {
                    const value = $(this).val() || null;
                @this.set('selectedStudentId', value)
                    ;
                });
            }

            document.addEventListener('livewire:init', () => {
                initStudentSelect2();

                // وقتی Livewire رندر می‌کند، دوباره Select2 را روی این input ست کن
                Livewire.hook('morph.updated', ({el}) => {
                    if (el.id === 'student-select') {
                        initStudentSelect2();
                    }
                });

                // برای سینک شدن مقدار از سمت PHP به Select2 (مثلاً وقتی selectAllStudents می‌زنی)
                Livewire.on('refreshStudentSelect', (value) => {
                    const $select = $('#student-select');
                    if ($select.length && $select.hasClass('select2-hidden-accessible')) {
                        $select.val(value ?? '').trigger('change.select2');
                    }
                });
            });
        </script>
    @endpush

</div>
