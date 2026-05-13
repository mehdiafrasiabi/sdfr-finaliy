<div dir="rtl" class="container py-4">
    <h3 class="mb-4">اختصاص پشتیبان جذب به دانش‌آموزان جدید trial</h3>

    <div class="card">
        <table class="table mb-0">
            <thead class="table-light">
            <tr>
                <th>دانش‌آموز</th>
                <th>موبایل</th>
                <th>پایه</th>
                <th>تاریخ ثبت‌نام</th>
                <th>پشتیبان جذب</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($trials as $t)
                <tr>
                    <td>{{ $t->user->name ?? '—' }}</td>
                    <td>{{ $t->user->mobile ?? '—' }}</td>
                    <td>{{ $t->grade_label }}</td>
                    <td>{{ $t->created_at->format('Y-m-d') }}</td>
                    <td>
                        <select wire:model="assignments.{{ $t->id }}" class="form-select form-select-sm">
                            <option value="">انتخاب کنید</option>
                            @foreach ($supporters as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><button wire:click="assign({{ $t->id }})" class="btn btn-sm btn-primary">اختصاص</button></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">دانش‌آموز در انتظار وجود ندارد.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $trials->links() }}</div>
</div>
