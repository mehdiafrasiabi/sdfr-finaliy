<div class="p-6" dir="rtl">

    <div class="mb-4">
        <a href="{{ route('manager.students-assessments.index') }}" class="text-sm text-base-content/70 hover:text-primary">
            ← بازگشت به داشبورد
        </a>
    </div>

    <div class="bg-base-100 rounded-xl border border-base-300 p-5 mb-6">
        <h1 class="text-xl font-bold">{{ $user->name }}</h1>
        <div class="text-sm text-base-content/70 mt-2 flex gap-4 flex-wrap">
            <span>موبایل: <code>{{ $user->mobile }}</code></span>
            @if ($user->trialWeek)
                <span>پایه: {{ $user->trialWeek->grade_label }}</span>
                @if ($user->trialWeek->grade != 9)
                    <span>رشته: {{ $user->trialWeek->field_label }}</span>
                @endif
            @endif
        </div>
    </div>

    {{-- بنرهای flag سراسری --}}
    @foreach ($globalFlagBanners as $flag => $banner)
        @php
            $bg = $banner['severity'] === 'critical' ? 'bg-error/10 border-error text-error'
                : ($banner['severity'] === 'warning' ? 'bg-warning/10 border-warning text-warning-content' : 'bg-info/10 border-info text-info');
        @endphp
        <div class="border-r-4 {{ $bg }} rounded-lg p-4 mb-3">
            <div class="font-bold">{{ $banner['title'] }}</div>
            <div class="text-sm mt-1 text-base-content/80">{{ $banner['text'] }}</div>
        </div>
    @endforeach

    {{-- بخش تست‌های دانش‌آموز --}}
    <h2 class="text-lg font-bold mt-6 mb-3">آزمون‌های دانش‌آموز</h2>
    <div class="space-y-4">
        @foreach ($studentAssessments as $assessment)
            @php
                $attempt = $studentAttempts->get($assessment->id);
                $interpretation = $interpretations[$assessment->id] ?? [];
            @endphp

            <details class="bg-base-100 border border-base-300 rounded-xl" open>
                <summary class="cursor-pointer p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold">{{ $assessment->name_fa }}</h3>
                        <p class="text-xs text-base-content/60 mt-1">{{ $assessment->kind_label }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($attempt && $attempt->status === \App\Models\StudentAssessmentAttempt::STATUS_COMPLETED)
                            <span class="badge badge-success">تکمیل‌شده</span>
                        @elseif ($attempt)
                            <span class="badge badge-warning">{{ $attempt->answered_count }} / {{ $assessment->questions->count() }}</span>
                        @else
                            <span class="badge badge-ghost">شروع نشده</span>
                        @endif
                    </div>
                </summary>

                <div class="p-4 border-t border-base-300 space-y-4">
                    {{-- کارت پروفایل تفسیر‌شده --}}
                    @if (! empty($interpretation['mbti']) && ! empty($interpretation['mbti']['type']))
                        @php $m = $interpretation['mbti']; @endphp
                        <div class="bg-primary/5 border border-primary/30 rounded-xl p-4">
                            <div class="flex items-baseline gap-3 mb-2">
                                <span class="text-2xl font-bold text-primary">{{ $m['type'] }}</span>
                                <span class="font-medium">{{ $m['title'] }}</span>
                            </div>
                            <p class="text-sm text-base-content/80 mb-2">{{ $m['description'] }}</p>
                            <p class="text-sm bg-base-100 rounded-lg p-2 leading-7"><strong>توصیه‌ی مطالعه:</strong> {{ $m['study_tip'] }}</p>
                            <div class="grid grid-cols-4 gap-2 mt-3 text-xs">
                                @foreach (['EI', 'SN', 'TF', 'JP'] as $pair)
                                    @php [$a, $b] = str_split($pair); @endphp
                                    <div class="bg-base-100 rounded p-2">
                                        <div class="flex justify-between">
                                            <span>{{ $a }}: {{ $m['axes'][$a] ?? 0 }}</span>
                                            <span>{{ $b }}: {{ $m['axes'][$b] ?? 0 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (! empty($interpretation['vark']) && ! empty($interpretation['vark']['profile']))
                        @php $v = $interpretation['vark']; @endphp
                        <div class="bg-primary/5 border border-primary/30 rounded-xl p-4">
                            <div class="flex items-baseline gap-3 mb-3">
                                <span class="text-2xl font-bold text-primary">{{ $v['profile'] }}</span>
                                <span class="text-sm text-base-content/70">{{ $v['is_multimodal'] ? '(چندوجهی)' : '' }}</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($v['modalities'] as $mod)
                                    <div class="bg-base-100 rounded-lg p-3 {{ $mod['dominant'] ? 'border-2 border-primary' : 'border border-base-300' }}">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-bold">{{ $mod['title'] }}</span>
                                            <span class="text-sm text-primary">{{ $mod['percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-base-300 rounded-full h-1.5 mb-2 overflow-hidden">
                                            <div class="bg-primary h-1.5" style="width: {{ $mod['percent'] }}%"></div>
                                        </div>
                                        <p class="text-xs leading-6 text-base-content/80">{{ $mod['tip'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (! empty($interpretation['custom']) && ! empty($interpretation['custom']['facets']))
                        @php $c = $interpretation['custom']; @endphp
                        <div class="bg-primary/5 border border-primary/30 rounded-xl p-4">
                            <div class="flex items-baseline justify-between mb-3">
                                <span class="font-bold">پروفایل تفسیر‌شده</span>
                                <span class="text-sm text-base-content/70">امتیاز کلی: {{ $c['overall_percent'] }}% ({{ $c['overall_level'] }})</span>
                            </div>
                            <div class="space-y-2">
                                @foreach ($c['facets'] as $facet => $f)
                                    @php
                                        $color = match($f['level']) {
                                            'high' => 'bg-error',
                                            'medium' => 'bg-warning',
                                            default => 'bg-success',
                                        };
                                    @endphp
                                    <div class="bg-base-100 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-sm">{{ $f['label'] }}</span>
                                            <span class="text-xs">{{ $f['percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-base-300 rounded-full h-2 mb-2 overflow-hidden">
                                            <div class="{{ $color }} h-2" style="width: {{ $f['percent'] }}%"></div>
                                        </div>
                                        <p class="text-xs leading-6 text-base-content/80">{{ $f['text'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            @if (! empty($c['flags']))
                                <div class="mt-3 space-y-2">
                                    @foreach ($c['flags'] as $flag => $info)
                                        @php $bg = $info['severity'] === 'critical' ? 'bg-error/10 border-error' : 'bg-warning/10 border-warning'; @endphp
                                        <div class="border-r-4 {{ $bg }} rounded p-2">
                                            <div class="font-bold text-sm">{{ $info['title'] }}</div>
                                            <p class="text-xs">{{ $info['text'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- پاسخ‌های خام --}}
                    @if ($attempt)
                        @php $answersByQ = $attempt->answers->keyBy('question_id'); @endphp
                        <div>
                            <h4 class="font-bold mb-2 text-sm">پاسخ‌های دانش‌آموز:</h4>
                            <div class="space-y-2">
                                @foreach ($assessment->questions as $q)
                                    @php $ans = $answersByQ->get($q->id); @endphp
                                    <div class="bg-base-200 rounded-lg p-2">
                                        <p class="text-sm">{{ $loop->iteration }}. {{ $q->question_text_fa }}</p>
                                        @if (! $ans)
                                            <p class="text-xs text-error mt-1">— بدون پاسخ —</p>
                                        @elseif ($q->isMultiSelect())
                                            @php
                                                $selectedIds = $ans->selected_options ?? [];
                                                $selectedLabels = $q->options->whereIn('id', $selectedIds)->pluck('label_fa')->all();
                                            @endphp
                                            <ul class="text-xs text-base-content/80 list-disc pr-5 mt-1">
                                                @foreach ($selectedLabels as $l)
                                                    <li>{{ $l }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-xs text-primary font-medium mt-1">{{ $ans->option?->label_fa ?? $ans->free_value ?? '—' }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-base-content/60">هنوز شروع نشده.</p>
                    @endif
                </div>
            </details>
        @endforeach
    </div>
</div>
