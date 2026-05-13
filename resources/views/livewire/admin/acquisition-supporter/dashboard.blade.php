<div dir="rtl" class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>دانش‌آموزان جذبی شما</h3>
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="جستجو نام یا موبایل...">
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>نام دانش‌آموز</th>
                    <th>موبایل</th>
                    <th>پایه/رشته</th>
                    <th>وضعیت trial</th>
                    <th>تماس بعدی</th>
                    <th>هشدار</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($trials as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->user->name ?? '—' }}</td>
                        <td>{{ $t->user->mobile ?? '—' }}</td>
                        <td>{{ $t->grade_label }} / {{ $t->field_label }}</td>
                        <td><span class="badge bg-{{ $t->status_color }}">{{ $t->status_label }}</span></td>
                        <td>
                            @php
                                $label = match($meta[$t->id]['next']) {
                                    'initial' => 'تماس اولیه',
                                    'secondary' => 'تماس ثانویه (سررسید!)',
                                    'side' => 'تماس جانبی',
                                    default => '—',
                                };
                            @endphp
                            <span class="badge bg-{{ $meta[$t->id]['next'] === 'secondary' ? 'warning' : 'info' }}">{{ $label }}</span>
                        </td>
                        <td>
                            @if ($meta[$t->id]['inactive'])
                                <span class="badge bg-danger">⚠ ۳ روز فعالیت ندارد</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.acquisition-supporter.student', $t->id) }}" class="btn btn-sm btn-primary">مشاهده</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">دانش‌آموزی به شما اختصاص داده نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $trials->links() }}</div>
</div>
