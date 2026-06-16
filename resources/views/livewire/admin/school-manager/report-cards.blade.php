<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h4 class="mb-0">کارنامهٔ ماهانه و رتبه‌بندی</h4>
        <div class="d-flex gap-2">
            <button wire:click="exportGrades" wire:loading.attr="disabled" wire:target="exportGrades" class="btn btn-sm btn-outline-success">
                <span wire:loading.remove wire:target="exportGrades">اکسل نمرات</span>
                <span wire:loading wire:target="exportGrades">...</span>
            </button>
            <button wire:click="exportRanking" wire:loading.attr="disabled" wire:target="exportRanking" class="btn btn-sm btn-outline-primary">
                <span wire:loading.remove wire:target="exportRanking">اکسل رتبه‌بندی</span>
                <span wire:loading wire:target="exportRanking">...</span>
            </button>
        </div>
    </div>

    {{-- فیلترها --}}
    <div class="card mb-3"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">سال</label>
                <select wire:model.live="jalaliYear" class="form-select form-select-sm">
                    @foreach($yearOptions as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ماه</label>
                <select wire:model.live="jalaliMonth" class="form-select form-select-sm">
                    @foreach($monthNames as $num => $name)<option value="{{ $num }}">{{ $name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">پایه</label>
                <select wire:model.live="gradeFilter" class="form-select form-select-sm">
                    <option value="">همه‌ی پایه‌ها</option>
                    <option value="10">پایه ۱۰</option>
                    <option value="11">پایه ۱۱</option>
                    <option value="12">پایه ۱۲</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">رشته</label>
                <select wire:model.live="fieldFilter" class="form-select form-select-sm">
                    <option value="">همه‌ی رشته‌ها</option>
                    @foreach($fieldLabels as $slug => $label)<option value="{{ $slug }}">{{ $label }}</option>@endforeach
                </select>
            </div>
        </div>
    </div></div>

    @forelse($groups as $key => $group)
        @php
            $first = $group->first();
            $fieldLabel = $first['field_label'];
        @endphp
        <div class="card mb-3">
            <div class="card-header">
                <strong>پایه {{ $first['grade'] }} — {{ $fieldLabel }}</strong>
                <span class="text-muted small">({{ $group->count() }} دانش‌آموز)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:70px">رتبه</th>
                        <th>دانش‌آموز</th>
                        <th class="text-center">معدل کل</th>
                        <th class="text-center" style="width:120px">جزئیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($group as $student)
                        <tr>
                            <td class="text-center">
                                @if($student['rank'])<span class="badge bg-primary">{{ $student['rank'] }}</span>@else<span class="text-muted">—</span>@endif
                            </td>
                            <td>{{ $student['name'] }}</td>
                            <td class="text-center"><strong>{{ $student['overall'] ?? '—' }}</strong></td>
                            <td class="text-center">
                                <button wire:click="toggleStudent({{ $student['student_id'] }})" class="btn btn-sm btn-outline-secondary">
                                    {{ $expandedStudentId === $student['student_id'] ? 'بستن' : 'نمرات دروس' }}
                                </button>
                            </td>
                        </tr>
                        @if($expandedStudentId === $student['student_id'])
                            <tr>
                                <td colspan="4" class="bg-light">
                                    @if($student['subjects']->isEmpty())
                                        <div class="text-muted small py-2">برای این ماه نمره‌ای ثبت نشده است.</div>
                                    @else
                                        <table class="table table-sm mb-0">
                                            <thead>
                                            <tr>
                                                <th>درس</th>
                                                <th class="text-center">فعالیت کلاسی</th>
                                                <th class="text-center">امتحان</th>
                                                <th class="text-center">معدل</th>
                                                <th>نظر دبیر</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($student['subjects'] as $subject)
                                                <tr>
                                                    <td>{{ $subject['subject'] }}</td>
                                                    <td class="text-center">{{ $subject['class_activity'] ?? '—' }}</td>
                                                    <td class="text-center">{{ $subject['exam'] ?? '—' }}</td>
                                                    <td class="text-center"><strong>{{ $subject['average'] ?? '—' }}</strong></td>
                                                    <td class="small text-muted">{{ $subject['teacher_comment'] ?: '—' }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center text-muted py-4">
            برای این ماه/فیلتر نمره‌ای ثبت نشده است.
        </div></div>
    @endforelse
</div>
