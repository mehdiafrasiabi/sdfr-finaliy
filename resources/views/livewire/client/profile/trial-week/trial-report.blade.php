<div class="max-w-4xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    {{-- ═══════════ سربرگ ═══════════ --}}
    <div class="rounded-3xl border border-primary/30 bg-gradient-to-br from-primary/10 to-secondary p-7 mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary mb-4">
            <svg class="w-8 h-8 text-primary-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
            </svg>
        </div>
        <h1 class="text-2xl font-black text-foreground mb-2">کارنامه هفته آزمایشی</h1>
        <p class="text-sm text-muted leading-7 max-w-xl mx-auto">
            خلاصه‌ای از شخصیت، وضعیت درسی و برنامهٔ مطالعاتی ساخته‌شده برای تو.
        </p>
    </div>

    {{-- ═══════════ ۱) شخصیت ═══════════ --}}
    <section class="mb-6">
        <h2 class="font-black text-foreground mb-3 flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/15 text-primary text-sm font-black">۱</span>
            شخصیت‌شناسی
        </h2>

        @if(!empty($personality['vark']['profile']))
            <div class="rounded-2xl border border-border bg-background p-5 mb-3">
                <div class="font-bold text-foreground mb-3">سبک یادگیری (VARK): <span class="text-primary">{{ $personality['vark']['profile'] }}</span></div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($personality['vark']['modalities'] as $m)
                        <div class="rounded-xl border {{ $m['dominant'] ? 'border-primary/50 bg-primary/5' : 'border-border' }} p-2 text-center">
                            <div class="text-xs text-muted">{{ $m['title'] }}</div>
                            <div class="font-black text-foreground">{{ $m['percent'] }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach($personality['custom'] as $testName => $facets)
            @php
                $strengths = collect($facets)->filter(fn($f) => $f['level'] === 'high');
                $weaknesses = collect($facets)->filter(fn($f) => $f['level'] === 'low');
            @endphp
            <div class="rounded-2xl border border-border bg-background p-5 mb-3">
                <div class="font-bold text-foreground mb-3">{{ $testName }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-2">نقاط قوت</div>
                        @forelse($strengths as $f)
                            <div class="text-sm text-foreground mb-1.5">✅ {{ $f['label'] }} <span class="text-muted text-xs">({{ $f['percent'] }}%)</span></div>
                        @empty
                            <div class="text-xs text-muted">—</div>
                        @endforelse
                    </div>
                    <div>
                        <div class="text-xs font-bold text-amber-600 dark:text-amber-400 mb-2">نیازمند تقویت</div>
                        @forelse($weaknesses as $f)
                            <div class="text-sm text-foreground mb-1.5">⚠️ {{ $f['label'] }} <span class="text-muted text-xs">({{ $f['percent'] }}%)</span></div>
                        @empty
                            <div class="text-xs text-muted">—</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach

        @foreach($personality['flags'] as $flag)
            <div class="rounded-2xl border p-4 mb-3 {{ ($flag['severity'] ?? '') === 'critical' ? 'border-red-500/40 bg-red-500/10' : 'border-amber-500/40 bg-amber-500/10' }}">
                <div class="font-bold text-foreground text-sm">{{ $flag['title'] ?? '' }}</div>
                @if(!empty($flag['text']))
                    <p class="text-xs text-muted leading-6 mt-1">{{ $flag['text'] }}</p>
                @endif
            </div>
        @endforeach
    </section>

    {{-- ═══════════ ۲) طبقه‌بندی درس‌ها ═══════════ --}}
    <section class="mb-6">
        <h2 class="font-black text-foreground mb-3 flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/15 text-primary text-sm font-black">۲</span>
            تحلیل طبقه‌بندی درس‌ها
        </h2>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="rounded-2xl border border-red-500/30 bg-red-500/5 p-4 text-center">
                <div class="text-2xl font-black text-red-600 dark:text-red-400">{{ $classification['weak_count'] }}</div>
                <div class="text-xs text-muted mt-1">ضعیف</div>
            </div>
            <div class="rounded-2xl border border-amber-500/30 bg-amber-500/5 p-4 text-center">
                <div class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $classification['medium_count'] }}</div>
                <div class="text-xs text-muted mt-1">متوسط</div>
            </div>
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/5 p-4 text-center">
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $classification['strong_count'] }}</div>
                <div class="text-xs text-muted mt-1">قوی</div>
            </div>
        </div>

        @if(!empty($classification['subject_averages']))
            <div class="rounded-2xl border border-border bg-background p-5">
                <div class="text-sm font-bold text-foreground mb-3">میانگین به تفکیک درس</div>
                @foreach($classification['subject_averages'] as $name => $data)
                    <div class="flex items-center justify-between text-sm py-1.5 border-b border-border last:border-0">
                        <span class="text-foreground">{{ $name }}</span>
                        <span class="text-muted text-xs">{{ $data['label'] }} <span class="text-foreground font-bold">({{ $data['average'] }})</span></span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-border bg-background p-5 text-sm text-muted text-center">
                داده‌ای برای طبقه‌بندی ثبت نشده است.
            </div>
        @endif
    </section>

    {{-- ═══════════ ۳) خلاصه برنامه ═══════════ --}}
    <section class="mb-6">
        <h2 class="font-black text-foreground mb-3 flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/15 text-primary text-sm font-black">۳</span>
            خلاصه برنامه مطالعاتی
        </h2>

        @if($program)
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="rounded-2xl border border-border bg-background p-4 text-center">
                    <div class="text-2xl font-black text-primary">{{ $program->total_parts }}</div>
                    <div class="text-xs text-muted mt-1">پارت</div>
                </div>
                <div class="rounded-2xl border border-border bg-background p-4 text-center">
                    <div class="text-2xl font-black text-primary">{{ $program->total_hours }}</div>
                    <div class="text-xs text-muted mt-1">ساعت</div>
                </div>
                <div class="rounded-2xl border border-border bg-background p-4 text-center">
                    <div class="text-2xl font-black text-primary">{{ $program->total_tests }}</div>
                    <div class="text-xs text-muted mt-1">تست</div>
                </div>
            </div>

            <div class="rounded-2xl border border-border bg-background p-5">
                @foreach($program->getWeekDays() as $day)
                    @continue($day['parts']->isEmpty() && !$day['is_rest_day'])
                    <div class="py-2.5 border-b border-border last:border-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-foreground text-sm">{{ $day['name'] }} <span class="text-muted text-xs">{{ $day['jalali_short'] }}</span></span>
                            @if($day['is_rest_day'])
                                <span class="text-xs text-amber-600 dark:text-amber-400">روز استراحت</span>
                            @else
                                <span class="text-xs text-muted">{{ $day['total_hours'] }} ساعت</span>
                            @endif
                        </div>
                        @if(!$day['is_rest_day'])
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($day['parts'] as $part)
                                    <span class="text-xs bg-secondary border border-border rounded-lg px-2 py-1 text-foreground">
                                        {{ $part->lesson_name }} <span class="text-muted">({{ $part->duration_minutes }}د)</span>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-border bg-background p-5 text-sm text-muted text-center">
                هنوز برنامه‌ای ساخته نشده است.
            </div>
        @endif
    </section>

    <div class="text-center">
        <a wire:navigate href="{{ route('client.profile.trial.guide') }}" class="btn btn-soft">بازگشت به راهنما</a>
    </div>
</div>
