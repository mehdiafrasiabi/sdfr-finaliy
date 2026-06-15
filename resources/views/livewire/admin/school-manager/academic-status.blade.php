<div class="container-fluid">
    <h4 class="mb-3">وضعیت تحصیلی دانش‌آموزان</h4>

    <div class="row g-3 mb-4">
        @foreach(['A' => 'success', 'B' => 'primary', 'C' => 'warning', 'D' => 'danger'] as $star => $color)
            <div class="col-md-3 col-6">
                <div class="card text-center"><div class="card-body py-3">
                    <div class="text-muted small">طبقه {{ $star }}</div>
                    <h3 class="mb-0 mt-1 text-{{ $color }}">{{ $starCounts[$star] }}</h3>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="d-flex flex-wrap gap-2 mb-3">
        <select wire:model.live="gradeFilter" class="form-select form-select-sm" style="max-width: 180px;">
            <option value="">همه‌ی پایه‌ها</option>
            <option value="10">پایه ۱۰</option>
            <option value="11">پایه ۱۱</option>
            <option value="12">پایه ۱۲</option>
        </select>
        <select wire:model.live="starFilter" class="form-select form-select-sm" style="max-width: 180px;">
            <option value="">همه‌ی طبقه‌ها</option>
            <option value="A">طبقه A</option>
            <option value="B">طبقه B</option>
            <option value="C">طبقه C</option>
            <option value="D">طبقه D</option>
        </select>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>دانش‌آموز</th>
                    <th>پایه</th>
                    <th>رشته</th>
                    <th class="text-center">طبقه‌بندی</th>
                    <th class="text-center">میانگین نمره</th>
                    <th>وضعیت مدرسه</th>
                    <th class="text-center">پیشرفت</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $s)
                    @php $pi = $s->user?->personalInformation; @endphp
                    <tr>
                        <td>{{ $s->user?->name ?? '—' }}</td>
                        <td>{{ $s->grade ?? '—' }}</td>
                        <td>{{ $s->field ?: '—' }}</td>
                        <td class="text-center"><span class="badge bg-secondary">{{ $s->star ?? '—' }}</span></td>
                        <td class="text-center">{{ $averages[$s->id] ?? '—' }}</td>
                        <td>
                            @if($pi?->is_graduate)
                                <span class="text-muted small">فارغ‌التحصیل</span>
                            @elseif($pi && !$pi->attends_school)
                                <span class="text-danger small">مدرسه نمی‌رود</span>
                            @else
                                <span class="text-success small">در حال تحصیل</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.school-manager.student.progress', $s->id) }}" class="btn btn-sm btn-outline-info">مشاهده</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">دانش‌آموزی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $students->links() }}</div>
</div>
