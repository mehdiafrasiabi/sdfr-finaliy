<div dir="rtl" class="container py-4">
    <h3 class="mb-4">اختصاص مشاور تحصیلی به دانش‌آموزان خریدار</h3>

    <div class="card">
        <table class="table mb-0">
            <thead class="table-light">
            <tr>
                <th>دانش‌آموز</th>
                <th>موبایل</th>
                <th>تاریخ پرداخت</th>
                <th>مشاور</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($students as $st)
                <tr>
                    <td>{{ $st->user->name ?? '—' }}</td>
                    <td>{{ $st->user->mobile ?? '—' }}</td>
                    <td>{{ $st->payment?->updated_at?->format('Y-m-d') ?? '—' }}</td>
                    <td>
                        <select wire:model="assignments.{{ $st->id }}" class="form-select form-select-sm">
                            <option value="">انتخاب کنید</option>
                            @foreach ($advisors as $a)
                                <option value="{{ $a->id }}">{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><button wire:click="assign({{ $st->id }})" class="btn btn-sm btn-primary">اختصاص</button></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">دانش‌آموزی منتظر مشاور نیست.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $students->links() }}</div>
</div>
