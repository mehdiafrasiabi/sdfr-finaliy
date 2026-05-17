<div>
    <h1 class="text-xl font-bold mb-4">دانش‌آموزان مدرسه: {{ $school?->name }}</h1>

    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input wire:model.live.debounce.500ms="search" type="text"
                   class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-100"
                   placeholder="جستجوی نام، کدملی یا موبایل">
            <select wire:model.live="gradeFilter"
                    class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                <option value="">همه پایه‌ها</option>
                <option value="10">دهم</option>
                <option value="11">یازدهم</option>
                <option value="12">دوازدهم</option>
            </select>
        </div>
    </div>

    @if($students)
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 dark:bg-slate-700 text-xs">
            <tr>
                <th class="px-3 py-2 text-right">ردیف</th>
                <th class="px-3 py-2 text-right">نام</th>
                <th class="px-3 py-2 text-right">کدملی</th>
                <th class="px-3 py-2 text-right">موبایل</th>
                <th class="px-3 py-2 text-right">پایه/رشته</th>
                <th class="px-3 py-2 text-right">پشتیبان</th>
                <th class="px-3 py-2 text-right">جزئیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($students as $s)
                <tr class="border-t border-slate-200 dark:border-slate-700">
                    <td class="px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="px-3 py-2">{{ $s->user?->name ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $s->national_code ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $s->user?->mobile ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $s->grade }} / {{ $s->field }}</td>
                    <td class="px-3 py-2">{{ $s->schoolSupporter?->name ?? '—' }}</td>
                    <td class="px-3 py-2">
                        <a href="{{ route('school-manager.student.detail', $s->id) }}"
                           class="text-blue-500 hover:underline">مشاهده</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-6 text-slate-400">دانش‌آموزی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $students->links() }}</div>
    </div>
    @endif
</div>
