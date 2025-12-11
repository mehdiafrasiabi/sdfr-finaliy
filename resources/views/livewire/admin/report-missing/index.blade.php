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
                    <div class="col-md-8 mt-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end">
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

        <div class="card">
            <div class="card-header">
                @if($missingDays instanceof \Illuminate\Pagination\LengthAwarePaginator && $missingDays->count())
                    <div>
                        <div >

                            @if(!$filtersSubmitted)
                                <div class="py-10 text-center text-gray-500">
                                    <p class="text-sm">برای مشاهده روزهای بدون گزارش، لطفاً فیلترها را تنظیم کرده و روی دکمه «ثبت فیلترها» کلیک کنید.</p>
                                </div>
                            @else

                                <div class="card-datatable table-responsive pt-0">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>دانش‌آموز</th>
                                            <th>ماه</th>
                                            <th>روز</th>
                                            <th>وضعیت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($missingDays as $index => $missingDay)
                                            <tr class="border-b border-gray-100 dark:border-[#172036]">
                                                <td>{{ $missingDays->firstItem() + $index }}</td>
                                                <td>{{ $missingDay['student_name'] }}</td>
                                                <td>{{ $missingDay['month_name'] }}</td>
                                                <td>{{ $missingDay['day_formatted'] }} -- {{ $missingDay['weekday'] }}</td>
                                                <td>
                                                    @if(($missingDay['status_type'] ?? 'not_sent') === 'rejected')
                                                        <span class="badge bg-label-danger">رد شده</span>
                                                    @else
                                                        <span class="badge bg-label-warning">ارسال نشده</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            ...
                                        @endforelse
                                        </tbody>

                                    </table>
                                </div>

                                <div class="mt-5">
                                    {{ $missingDays->links('layouts.admin.pagination') }}
                                </div>
                            @endif


                        </div>
                    </div>

                @else
                    <p class="text-center text-gray-500 mt-4">
                        لطفاً فیلترها را انتخاب و ثبت کنید.
                    </p>
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
                    @this.set('selectedStudentId', value);
                    });
                }

                document.addEventListener('livewire:init', () => {
                    initStudentSelect2();

                    // وقتی Livewire رندر می‌کند، دوباره Select2 را روی این input ست کن
                    Livewire.hook('morph.updated', ({ el }) => {
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
