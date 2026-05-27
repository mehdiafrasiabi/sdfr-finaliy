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

    <div class="space-y-4">
        @foreach ($assessments as $assessment)
            @php $attempt = $attempts->get($assessment->id); @endphp

            <details class="bg-base-100 border border-base-300 rounded-xl group" open>
                <summary class="cursor-pointer p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold">{{ $assessment->name_fa }}</h3>
                        <p class="text-xs text-base-content/60 mt-1">{{ $assessment->kind_label }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($attempt && $attempt->status === \App\Models\StudentAssessmentAttempt::STATUS_COMPLETED)
                            <span class="badge badge-success">تکمیل شده</span>
                        @elseif ($attempt)
                            <span class="badge badge-warning">{{ $attempt->answered_count }} / {{ $assessment->questions->count() }}</span>
                        @else
                            <span class="badge badge-ghost">شروع نشده</span>
                        @endif
                    </div>
                </summary>

                <div class="p-4 border-t border-base-300">
                    @if (! $attempt)
                        <p class="text-sm text-base-content/60">هنوز شروع نشده است.</p>
                    @else
                        @php $answersByQ = $attempt->answers->keyBy('question_id'); @endphp
                        <div class="space-y-3">
                            @foreach ($assessment->questions as $q)
                                @php $answer = $answersByQ->get($q->id); @endphp
                                <div class="bg-base-200 rounded-lg p-3">
                                    <p class="text-sm font-medium mb-2">{{ $loop->iteration }}. {{ $q->question_text_fa }}</p>
                                    @if (! $answer)
                                        <p class="text-xs text-error">— بدون پاسخ —</p>
                                    @elseif ($q->isMultiSelect())
                                        @php
                                            $selectedIds = $answer->selected_options ?? [];
                                            $selectedLabels = $q->options->whereIn('id', $selectedIds)->pluck('label_fa')->all();
                                        @endphp
                                        <ul class="text-sm text-base-content/80 list-disc pr-5">
                                            @foreach ($selectedLabels as $l)
                                                <li>{{ $l }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-primary font-medium">
                                            {{ $answer->option?->label_fa ?? ($answer->free_value ?? '—') }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </details>
        @endforeach
    </div>
</div>
