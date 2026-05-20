<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">دانش‌آموزان مدرسه: {{ $school->name }}</h4>
        <a href="{{ route('admin.school-supporter.schools') }}" class="btn btn-sm btn-outline-secondary">بازگشت به مدارس</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <input wire:model.live.debounce.500ms="search" type="text"
                       class="form-control" placeholder="جستجوی نام، کدملی یا موبایل">
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>کدملی</th>
                        <th>موبایل</th>
                        <th>پایه/رشته</th>
                        <th>تماس‌های این ماه</th>
                        <th>اقدامات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $s)
                        @php $count = $contactCounts[$s->id] ?? 0; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->user?->name ?? '---' }}</td>
                            <td>{{ $s->national_code ?? '---' }}</td>
                            <td>{{ $s->user?->mobile ?? '---' }}</td>
                            <td>{{ $s->grade ?? '—' }} / {{ $s->field ?? '—' }}</td>
                            <td>
                                @if($count < 2)
                                    <span class="badge bg-danger">{{ $count }}/2 ⚠️ نیاز به تماس</span>
                                @else
                                    <span class="badge bg-success">{{ $count }}/2 ✓</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.school-supporter.student.reports', [$school->id, $s->id]) }}"
                                   class="btn btn-sm btn-soft-info">گزارش‌ها</a>
                                <a href="{{ route('admin.school-supporter.student.contacts', [$school->id, $s->id]) }}"
                                   class="btn btn-sm btn-soft-warning">تماس‌ها</a>
                                <a href="{{ route('admin.school-supporter.student.grades', [$school->id, $s->id]) }}"
                                   class="btn btn-sm btn-soft-success">نمرات</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">دانش‌آموزی یافت نشد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $students->links() }}
        </div>
    </div>
</div>
