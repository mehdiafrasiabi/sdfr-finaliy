<div>
    <h1 class="text-xl font-bold mb-4">وضعیت تحصیلی دانش‌آموزان</h1>

    {{-- خلاصه‌ی طبقه‌بندی --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        @foreach(['A' => 'emerald', 'B' => 'blue', 'C' => 'amber', 'D' => 'rose'] as $star => $color)
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
                <div class="text-xs text-slate-500">طبقه {{ $star }}</div>
                <div class="text-2xl font-bold mt-1 text-{{ $color }}-500">{{ $starCounts[$star] }}</div>
            </div>
        @endforeach
    </div>

    {{-- فیلترها --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <select wire:model.live="gradeFilter" class="rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
            <option value="">همه‌ی پایه‌ها</option>
            <option value="10">پایه ۱۰</option>
            <option value="11">پایه ۱۱</option>
            <option value="12">پایه ۱۲</option>
        </select>
        <select wire:model.live="starFilter" class="rounded-lg border-slate-300 dark:bg-slate-700 dark:border-slate-600 text-sm">
            <option value="">همه‌ی طبقه‌ها</option>
            <option value="A">طبقه A</option>
            <option value="B">طبقه B</option>
            <option value="C">طبقه C</option>
            <option value="D">طبقه D</option>
        </select>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 dark:bg-slate-700 text-xs">
            <tr>
                <th class="px-3 py-2 text-right">دانش‌آموز</th>
                <th class="px-3 py-2 text-right">پایه</th>
                <th class="px-3 py-2 text-right">رشته</th>
                <th class="px-3 py-2 text-center">طبقه‌بندی</th>
                <th class="px-3 py-2 text-center">میانگین نمره</th>
                <th class="px-3 py-2 text-right">وضعیت مدرسه</th>
                <th class="px-3 py-2 text-center">جزئیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($students ?? [] as $s)
                @php $pi = $s->user?->personalInformation; @endphp
                <tr class="border-t border-slate-200 dark:border-slate-700">
                    <td class="px-3 py-2">{{ $s->user?->name ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $s->grade ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $s->field ?: '—' }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="text-xs px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-600">{{ $s->star ?? '—' }}</span>
                    </td>
                    <td class="px-3 py-2 text-center">{{ $averages[$s->id] ?? '—' }}</td>
                    <td class="px-3 py-2">
                        @if($pi?->is_graduate)
                            <span class="text-xs text-slate-500">فارغ‌التحصیل</span>
                        @elseif($pi && !$pi->attends_school)
                            <span class="text-xs text-rose-500">مدرسه نمی‌رود</span>
                        @else
                            <span class="text-xs text-emerald-500">در حال تحصیل</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-center">
                        <a href="{{ route('school-manager.student.detail', $s->id) }}" class="text-blue-500 hover:underline">مشاهده</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-6 text-slate-400">دانش‌آموزی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($students)
        <div class="mt-4">{{ $students->links() }}</div>
    @endif
</div>
