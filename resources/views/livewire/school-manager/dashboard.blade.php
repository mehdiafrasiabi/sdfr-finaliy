<div>
    <h1 class="text-xl font-bold mb-4">داشبورد مدرسه: {{ $school?->name ?? '—' }}</h1>
    <p class="text-sm text-slate-500 mb-6">کد مدرسه: {{ $school?->code ?? '—' }}</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">دانش‌آموزان</div>
            <div class="text-2xl font-bold mt-1">{{ $stats['students'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">گزارش‌های ثبت‌شده</div>
            <div class="text-2xl font-bold mt-1">{{ $stats['reports'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">نمرات ثبت‌شده</div>
            <div class="text-2xl font-bold mt-1">{{ $stats['grades'] }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
            <div class="text-xs text-slate-500">تماس‌ها با اولیا</div>
            <div class="text-2xl font-bold mt-1">{{ $stats['contacts'] }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('school-manager.students') }}"
           class="inline-block px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">
            مشاهده‌ی همه‌ی دانش‌آموزان مدرسه
        </a>
    </div>
</div>
