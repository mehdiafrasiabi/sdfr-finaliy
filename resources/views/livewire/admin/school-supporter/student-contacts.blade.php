<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">تماس‌های اولیا: {{ $student->user?->name }}</h4>
        <a href="{{ route('admin.school-supporter.students', $school->id) }}" class="btn btn-sm btn-outline-secondary">بازگشت</a>
    </div>

    @if($monthCount < 2)
        <div class="alert alert-warning">
            ⚠️ این ماه فقط <strong>{{ $monthCount }}</strong> تماس ثبت شده است. حداقل ۲ تماس در هر ماه با اولیای دانش‌آموز ضروری است.
        </div>
    @else
        <div class="alert alert-success">
            ✓ این ماه <strong>{{ $monthCount }}</strong> تماس ثبت شده است.
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header"><strong>ثبت تماس جدید</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">تماس با</label>
                    <select wire:model="contacted_with" class="form-select">
                        <option value="father">پدر</option>
                        <option value="mother">مادر</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">یادداشت</label>
                    <input type="text" wire:model="notes" class="form-control" maxlength="2000">
                </div>
            </div>
            <div class="text-end mt-3">
                <button wire:click="save" class="btn btn-primary">
                    <span wire:loading.remove>ثبت تماس</span>
                    <span wire:loading>در حال ثبت...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>تاریخچه تماس‌ها</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>تاریخ</th>
                        <th>تماس با</th>
                        <th>یادداشت</th>
                        <th>ثبت‌کننده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($contacts as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ jalali($c->contacted_at)->format('Y/m/d H:i') }}</td>
                            <td>{{ $c->contacted_with === 'father' ? 'پدر' : 'مادر' }}</td>
                            <td>{{ $c->notes ?? '—' }}</td>
                            <td>{{ $c->admin?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">تماسی ثبت نشده.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $contacts->links() }}
        </div>
    </div>
</div>
