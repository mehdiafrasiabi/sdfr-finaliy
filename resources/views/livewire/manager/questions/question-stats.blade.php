<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title mb-0">آمار سوالات</h4>
                        <a href="{{ route('manager.questions.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-right me-1"></i>
                            بازگشت به بانک سوالات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-filter me-1"></i>
                            فیلترها (اختیاری — برای محدودکردن دامنه آمار)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4 col-lg-3">
                                <label class="form-label">دوره تحصیلی</label>
                                <select wire:model.live="filterEducationLevel" class="form-select">
                                    <option value="">همه</option>
                                    @foreach($educationLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($showFieldFilter)
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label">رشته</label>
                                    <select wire:model.live="filterField"
                                            class="form-select" {{ empty($fields) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($fields as $field)
                                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-4 col-lg-3">
                                <label class="form-label">پایه</label>
                                <select wire:model.live="filterGrade"
                                        class="form-select" {{ empty($grades) ? 'disabled' : '' }}>
                                    <option value="">همه</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <label class="form-label">درس</label>
                                <select wire:model.live="filterSubject"
                                        class="form-select" {{ empty($subjects) ? 'disabled' : '' }}>
                                    <option value="">همه</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($filterEducationLevel || $filterGrade || $filterField || $filterSubject)
                            <div class="mt-3">
                                <button wire:click="clearFilters" class="btn btn-outline-secondary btn-sm">
                                    <i class="ti ti-x me-1"></i>
                                    پاک کردن فیلترها
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- View Mode Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="btn-group flex-wrap" role="group">
                    <button type="button" wire:click="$set('viewMode', 'all')"
                            class="btn {{ $viewMode === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        همه فصل‌ها و مباحث
                    </button>
                    <button type="button" wire:click="$set('viewMode', 'chapters_empty')"
                            class="btn {{ $viewMode === 'chapters_empty' ? 'btn-danger' : 'btn-outline-danger' }}">
                        فصل‌های بدون سوال
                        <span class="badge bg-white text-danger ms-1">{{ $chaptersEmptyCount }}</span>
                    </button>
                    <button type="button" wire:click="$set('viewMode', 'topics_empty')"
                            class="btn {{ $viewMode === 'topics_empty' ? 'btn-danger' : 'btn-outline-danger' }}">
                        مبحث‌های بدون سوال
                        <span class="badge bg-white text-danger ms-1">{{ $topicsEmptyCount }}</span>
                    </button>
                    <button type="button" wire:click="$set('viewMode', 'fewest')"
                            class="btn {{ $viewMode === 'fewest' ? 'btn-warning' : 'btn-outline-warning' }}">
                        کمترین تعداد سوال
                    </button>
                    <button type="button" wire:click="$set('viewMode', 'most')"
                            class="btn {{ $viewMode === 'most' ? 'btn-success' : 'btn-outline-success' }}">
                        بیشترین تعداد سوال
                    </button>
                </div>
            </div>
        </div>

        @if($viewMode === 'all')
            <div class="row">
                <div class="col-12">
                    @if(!$showHierarchical)
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-list-tree text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">
                                    برای دیدن تعداد سوالِ هر فصل و مبحث به‌تفکیک، بالا یک درس انتخاب کنید.
                                    برای دیدن فصل‌ها/مباحث بدون سوال یا مرتب‌سازی بر اساس تعداد سوال در کل بانک،
                                    از دکمه‌های بالا استفاده کنید — نیازی به انتخاب درس ندارند.
                                </p>
                            </div>
                        </div>
                    @else
                        @forelse($chapters as $chapter)
                            <div class="card mb-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span class="fw-bold">
                                        <i class="ti ti-folder me-1"></i>
                                        {{ $chapter->name }}
                                    </span>
                                    <span class="badge bg-{{ $chapter->total_count === 0 ? 'danger' : 'dark' }}">
                                        مجموع: {{ $chapter->total_count }} سوال
                                    </span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 align-middle">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted">سوالات جامع فصل</td>
                                                <td style="width: 120px;">
                                                    <span class="badge bg-{{ $chapter->comprehensive_count === 0 ? 'secondary' : 'info' }}">
                                                        {{ $chapter->comprehensive_count }} سوال
                                                    </span>
                                                </td>
                                                <td style="width: 160px;" class="text-end">
                                                    <a href="{{ route('manager.questions.form') }}?chapter_id={{ $chapter->id }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-plus me-1"></i>
                                                        افزودن سوال
                                                    </a>
                                                </td>
                                            </tr>
                                            @forelse($chapter->topics as $topic)
                                                <tr>
                                                    <td>{{ $topic->name }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $topic->question_count === 0 ? 'secondary' : 'success' }}">
                                                            {{ $topic->question_count }} سوال
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('manager.questions.form') }}?chapter_id={{ $chapter->id }}&topic_id={{ $topic->id }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-plus me-1"></i>
                                                            افزودن سوال
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-muted small">این فصل مبحثی ثبت‌شده ندارد.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="card">
                                <div class="card-body text-center py-5 text-muted">
                                    فصلی برای این درس یافت نشد.
                                </div>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        @elseif($viewMode === 'chapters_empty')
            <div class="row">
                <div class="col-12">
                    @if($pagedChaptersEmpty->isEmpty())
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-circle-check text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">فصل بدون سوالی (در این فیلتر) پیدا نشد.</p>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>مسیر</th>
                                            <th>فصل</th>
                                            <th style="width: 160px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pagedChaptersEmpty as $chapter)
                                            <tr>
                                                <td class="small text-muted">
                                                    {{ $chapter->subject->grade->educationLevel->name ?? '-' }}
                                                    »
                                                    {{ $chapter->subject->grade->name ?? '-' }}
                                                    @if($chapter->subject->field)
                                                        » {{ $chapter->subject->field->name }}
                                                    @endif
                                                    » {{ $chapter->subject->name ?? '-' }}
                                                </td>
                                                <td class="fw-semibold">{{ $chapter->name }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('manager.questions.form') }}?chapter_id={{ $chapter->id }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-plus me-1"></i>
                                                        افزودن سوال
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pagedChaptersEmpty->links('layouts.manager.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- topics_empty | fewest | most --}}
            <div class="row">
                <div class="col-12">
                    @if($pagedBuckets->isEmpty())
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-circle-check text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">موردی (در این فیلتر) پیدا نشد.</p>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>مسیر</th>
                                            <th>مبحث / نوع</th>
                                            <th style="width: 120px;">تعداد سوال</th>
                                            <th style="width: 160px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pagedBuckets as $bucket)
                                            @php($chapter = $bucket['chapter'])
                                            @php($topic = $bucket['topic'])
                                            <tr>
                                                <td class="small text-muted">
                                                    {{ $chapter->subject->grade->educationLevel->name ?? '-' }}
                                                    »
                                                    {{ $chapter->subject->grade->name ?? '-' }}
                                                    @if($chapter->subject->field)
                                                        » {{ $chapter->subject->field->name }}
                                                    @endif
                                                    » {{ $chapter->subject->name ?? '-' }}
                                                    » {{ $chapter->name }}
                                                </td>
                                                <td class="fw-semibold">
                                                    {{ $bucket['label'] }}
                                                    @if($bucket['type'] === 'comprehensive')
                                                        <span class="badge bg-info ms-1">جامع</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $bucket['count'] === 0 ? 'secondary' : 'dark' }}">
                                                        {{ $bucket['count'] }} سوال
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('manager.questions.form') }}?chapter_id={{ $chapter->id }}{{ $topic ? '&topic_id='.$topic->id : '' }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-plus me-1"></i>
                                                        افزودن سوال
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pagedBuckets->links('layouts.manager.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
