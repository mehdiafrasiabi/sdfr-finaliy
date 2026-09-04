<div class="student-ui student-ui-auto-collapse">
    @include('livewire.admin.student._styles')
    <div class="max-w-7xl space-y-6 px-4 mx-auto">

        {{-- نوار بالای صفحه مخصوص مشاور --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2 text-sm text-muted">
                <span class="font-bold text-foreground">{{ $studentName }}</span>
                <span class="opacity-60">·</span>
                <span>{{ $currentLabel }}</span>
            </div>
            <a href="{{ route('admin.student.smartReportCard.detail', $userId) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-background border border-border rounded-full text-sm text-muted hover:text-foreground hover:border-primary/40 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                     stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
                بازگشت به فهرست ماه‌ها
            </a>
        </div>

        @include('livewire._partials.smart-report-card-content', [
            'columnClass' => 'space-y-8',
            'backUrl' => route('admin.student.smartReportCard.detail', $userId),
        ])
    </div>

    {{-- مودال جزئیات فصل‌ها — برای دانش‌آموزِ هدف (studentId از طریق رویداد ارسال می‌شود) --}}
    <livewire:client.profile.subject-detail-modal/>
</div>
