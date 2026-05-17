<div class="px-4 py-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold text-foreground">گزارش‌های مطالعه‌ی من</h1>
            <p class="text-sm text-muted mt-1">
                مدرسه: <span class="font-medium">{{ $student->school?->name ?? '—' }}</span>
                <span class="mx-2">|</span>
                پشتیبان مدرسه: <span class="font-medium">{{ $student->schoolSupporter?->name ?? '—' }}</span>
            </p>
        </div>
        <a wire:navigate href="{{ route('client.profile.school.report.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary text-primary-foreground hover:opacity-90 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="text-sm font-semibold">ثبت گزارش جدید</span>
        </a>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @forelse($reports as $report)
        <div class="bg-secondary rounded-2xl p-4 mb-3 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <div class="text-sm font-semibold text-foreground">
                        تاریخ: {{ jalali($report->report_date)->format('Y/m/d') }}
                    </div>
                    @php
                        $statusColor = match($report->status) {
                            'approved' => 'bg-emerald-500 text-white',
                            'rejected' => 'bg-rose-500 text-white',
                            default    => 'bg-amber-500 text-white',
                        };
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full {{ $statusColor }}">
                        {{ $report->status_label }}
                    </span>
                </div>
                <div class="text-xs text-muted">
                    {{ $report->parts_count }} پارت
                    <span class="mx-1">•</span>
                    مطالعه {{ $report->total_study_minutes }} دقیقه
                    <span class="mx-1">•</span>
                    موبایل {{ $report->total_mobile_minutes }} دقیقه
                </div>
            </div>
            @if($report->advisor_comment)
                <div class="mt-3 px-3 py-2 rounded-lg bg-background text-sm text-foreground">
                    <span class="text-xs text-muted">نظر پشتیبان:</span>
                    {{ $report->advisor_comment }}
                </div>
            @endif
        </div>
    @empty
        <div class="bg-secondary rounded-2xl p-8 text-center text-muted">
            هنوز گزارشی ثبت نکرده‌اید. روی «ثبت گزارش جدید» کلیک کنید.
        </div>
    @endforelse

    <div class="mt-4">{{ $reports->links() }}</div>
</div>
