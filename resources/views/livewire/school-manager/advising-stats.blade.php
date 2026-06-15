<div>
    <h1 class="text-xl font-bold mb-4">آمار جلسات مشاوره</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">کل جلسات</div>
            <div class="text-2xl font-bold mt-1">{{ $summary['total'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">برگزارشده</div>
            <div class="text-2xl font-bold mt-1 text-emerald-500">{{ $summary['held'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">غیبت دانش‌آموز</div>
            <div class="text-2xl font-bold mt-1 text-amber-500">{{ $summary['student_absent'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">غیبت مشاور</div>
            <div class="text-2xl font-bold mt-1 text-rose-500">{{ $summary['advisor_absent'] }}</div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">
        <div class="px-4 py-2 text-sm font-bold border-b border-slate-200 dark:border-slate-700">تفکیک به‌ازای دانش‌آموز</div>
        <table class="w-full text-sm">
            <thead class="bg-slate-100 dark:bg-slate-700 text-xs">
            <tr>
                <th class="px-3 py-2 text-right">دانش‌آموز</th>
                <th class="px-3 py-2 text-center">کل</th>
                <th class="px-3 py-2 text-center">برگزارشده</th>
                <th class="px-3 py-2 text-center">غیبت دانش‌آموز</th>
                <th class="px-3 py-2 text-center">غیبت مشاور</th>
            </tr>
            </thead>
            <tbody>
            @forelse($perStudent as $row)
                <tr class="border-t border-slate-200 dark:border-slate-700">
                    <td class="px-3 py-2">
                        @if($row['student'])
                            <a href="{{ route('school-manager.student.detail', $row['student']->id) }}" class="hover:text-blue-500">
                                {{ $row['student']->user?->name ?? '—' }}
                            </a>
                        @else — @endif
                    </td>
                    <td class="px-3 py-2 text-center">{{ $row['total'] }}</td>
                    <td class="px-3 py-2 text-center text-emerald-500">{{ $row['held'] }}</td>
                    <td class="px-3 py-2 text-center text-amber-500">{{ $row['student_absent'] }}</td>
                    <td class="px-3 py-2 text-center text-rose-500">{{ $row['advisor_absent'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-6 text-slate-400">جلسه‌ای ثبت نشده.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
