<div class="px-4 py-6 max-w-5xl mx-auto">
    <div class="bg-secondary rounded-2xl p-5 mb-6">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-xl font-bold text-foreground">سلام {{ $student->user?->name }} 👋</h1>
                <p class="text-sm text-muted mt-1">
                    مدرسه: <span class="font-medium text-foreground">{{ $student->school?->name ?? '—' }}</span>
                    <span class="mx-2">•</span>
                    پایه: <span class="font-medium text-foreground">{{ $student->grade ?? '—' }}</span>
                    <span class="mx-2">•</span>
                    رشته: <span class="font-medium text-foreground">{{ $student->field ?? '—' }}</span>
                </p>
                <p class="text-xs text-muted mt-1">
                    پشتیبان مدرسه‌ی شما: <span class="font-semibold text-foreground">{{ $student->schoolSupporter?->name ?? '— هنوز تعیین نشده —' }}</span>
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
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-secondary rounded-2xl p-4">
            <div class="text-xs text-muted">گزارش‌های ثبت‌شده</div>
            <div class="text-2xl font-bold text-foreground mt-1">{{ $reportStats->total ?? 0 }}</div>
            <div class="text-[11px] text-muted mt-1">
                <span class="text-emerald-500">تأیید: {{ $reportStats->approved ?? 0 }}</span>
                <span class="mx-1">•</span>
                <span class="text-amber-500">در انتظار: {{ $reportStats->pending ?? 0 }}</span>
                <span class="mx-1">•</span>
                <span class="text-rose-500">رد: {{ $reportStats->rejected ?? 0 }}</span>
            </div>
        </div>
        <div class="bg-secondary rounded-2xl p-4">
            <div class="text-xs text-muted">مجموع زمان مطالعه</div>
            <div class="text-2xl font-bold text-foreground mt-1">
                {{ intdiv((int)($reportStats->total_study ?? 0), 60) }}<span class="text-sm text-muted">س</span>
                {{ (int)($reportStats->total_study ?? 0) % 60 }}<span class="text-sm text-muted">د</span>
            </div>
        </div>
        <div class="bg-secondary rounded-2xl p-4">
            <div class="text-xs text-muted">موبایل غیرمفید</div>
            <div class="text-2xl font-bold text-foreground mt-1">
                {{ intdiv((int)($reportStats->total_mobile ?? 0), 60) }}<span class="text-sm text-muted">س</span>
                {{ (int)($reportStats->total_mobile ?? 0) % 60 }}<span class="text-sm text-muted">د</span>
            </div>
        </div>
        <div class="bg-secondary rounded-2xl p-4">
            <div class="text-xs text-muted">نمرات / تماس‌ها</div>
            <div class="text-lg font-bold text-foreground mt-1">{{ $gradesCount }} <span class="text-xs text-muted">نمره</span></div>
            <div class="text-xs text-muted mt-1">{{ $contactsCount }} تماس با اولیا</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-secondary rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-foreground">گزارش‌های اخیر</h2>
                <a wire:navigate href="{{ route('client.profile.school.report.index') }}"
                   class="text-xs text-primary hover:underline">همه</a>
            </div>
            @forelse($recentReports as $r)
                @php
                    $cls = match($r->status){
                        'approved' => 'bg-emerald-500',
                        'rejected' => 'bg-rose-500',
                        default    => 'bg-amber-500',
                    };
                @endphp
                <div class="flex items-center justify-between bg-background rounded-xl px-3 py-2 mb-2">
                    <div class="flex items-center gap-2 text-sm text-foreground">
                        <span class="w-2 h-2 rounded-full {{ $cls }}"></span>
                        <span>{{ jalali($r->report_date)->format('Y/m/d') }}</span>
                    </div>
                    <div class="text-xs text-muted">
                        {{ $r->parts_count }} پارت
                        <span class="mx-1">•</span>
                        {{ $r->total_study_minutes }} دقیقه
                    </div>
                </div>
            @empty
                <div class="text-center text-muted text-sm py-4">هنوز گزارشی ثبت نکرده‌اید.</div>
            @endforelse
        </div>

        <div class="bg-secondary rounded-2xl p-5">
            <h2 class="text-sm font-bold text-foreground mb-3">نمرات اخیر</h2>
            @forelse($recentGrades as $g)
                <div class="flex items-center justify-between bg-background rounded-xl px-3 py-2 mb-2">
                    <div class="text-sm text-foreground">
                        {{ $g->subject?->name ?? '—' }}
                        @if($g->chapter)
                            <span class="text-xs text-muted">/ {{ $g->chapter->name }}</span>
                        @endif
                    </div>
                    <div class="text-sm font-bold text-foreground">
                        {{ $g->score }}<span class="text-xs text-muted">/{{ $g->scale }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted text-sm py-4">هنوز نمره‌ای ثبت نشده.</div>
            @endforelse
        </div>
    </div>
</div>
