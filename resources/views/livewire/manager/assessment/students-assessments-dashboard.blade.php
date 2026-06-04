<div class="p-6" dir="rtl">

    <h1 class="text-2xl font-bold mb-6">داشبورد آزمون‌های دانش‌آموزان</h1>

    <div class="bg-base-100 rounded-xl border border-base-300 p-4 mb-4 flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.400ms="search"
               placeholder="جستجو نام یا موبایل..."
               class="input input-bordered flex-1 min-w-[200px]" />
        <select wire:model.live="statusFilter" class="select select-bordered">
            <option value="">همه وضعیت‌ها</option>
            <option value="not_started">شروع نشده</option>
            <option value="in_progress">در حال انجام</option>
            <option value="completed">تکمیل‌شده</option>
        </select>
    </div>

    <div class="bg-base-100 rounded-xl border border-base-300 overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>دانش‌آموز</th>
                    <th>موبایل</th>
                    <th>پایه/رشته</th>
                    <th>پیشرفت دانش‌آموز</th>
                    <th>پاسخ والدین</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    @php
                        $badgeClass = match ($row->status) {
                            'completed'   => 'badge-success',
                            'in_progress' => 'badge-warning',
                            default       => 'badge-ghost',
                        };
                        $badgeLabel = match ($row->status) {
                            'completed'   => 'تکمیل‌شده',
                            'in_progress' => 'در حال انجام',
                            default       => 'شروع نشده',
                        };
                    @endphp
                    <tr>
                        <td class="font-medium">{{ $row->user->name }}</td>
                        <td><code class="text-xs">{{ $row->user->mobile }}</code></td>
                        <td>
                            @if ($row->user->trialWeek)
                                {{ $row->user->trialWeek->grade_label }}
                                @if ($row->user->trialWeek->grade != 9)
                                    / {{ $row->user->trialWeek->field_label }}
                                @endif
                            @endif
                        </td>
                        <td>{{ $row->completed }} / {{ $row->total }}</td>
                        <td>
                            @php
                                $parentBadge = match (true) {
                                    $row->parentCompleted >= $row->parentTotal && $row->parentTotal > 0 => 'badge-success',
                                    $row->parentCompleted > 0                                          => 'badge-warning',
                                    $row->parentTotal === 0                                            => 'badge-ghost',
                                    default                                                            => 'badge-error',
                                };
                            @endphp
                            <span class="badge {{ $parentBadge }}">{{ $row->parentCompleted }} / {{ $row->parentTotal }}</span>
                        </td>
                        <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                        <td>
                            <a href="{{ route('manager.students-assessments.show', $row->user->id) }}"
                               class="btn btn-xs btn-info">مشاهده پاسخ‌ها</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-8 text-base-content/60">دانش‌آموزی یافت نشد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $paginator->links() }}</div>
</div>
