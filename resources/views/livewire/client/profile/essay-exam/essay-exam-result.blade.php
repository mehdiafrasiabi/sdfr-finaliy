<div class="max-w-5xl mx-auto px-4 space-y-5" dir="rtl">
    @php
        $exam = $attempt->assignment->essayExam;
        $scoresByQ = $attempt->questionScores->keyBy('essay_exam_question_id');
        $isGraded = $attempt->status === 'graded';
    @endphp

    <div class="bg-secondary border border-border rounded-2xl p-5 space-y-2">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <h1 class="font-bold text-foreground text-lg">{{ $exam->title }}</h1>
            <x-ui.button href="{{ route('client.profile.typed-exam.list') }}" variant="secondary-outline" icon="chevron-right" size="sm">
                بازگشت به لیست آزمون‌ها
            </x-ui.button>
        </div>
        @if($isGraded)
            <div class="flex items-center gap-3 flex-wrap">
                <x-ui.status-badge status="paid" label="تصحیح شده"/>
                <span class="font-bold text-foreground">
                    نمره شما: {{ number_format($attempt->total_score ?? 0, 2) }} / {{ number_format($exam->total_score, 2) }}
                </span>
            </div>
        @else
            <x-ui.status-badge status="pending" label="ارسال شده — در انتظار تصحیح"/>
        @endif
    </div>

    @if($isGraded)
        <!-- Per-question scores -->
        <div class="bg-secondary border border-border rounded-2xl p-5">
            <h3 class="font-bold text-foreground mb-3">نمرات هر سوال</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($exam->questions as $q)
                    @php
                        $given = $scoresByQ->get($q->id)?->score ?? 0;
                        $ratio = (float)$q->score > 0 ? ((float)$given / (float)$q->score) : 0;
                        $color = $ratio >= 0.75 ? 'success' : ($ratio >= 0.4 ? 'warning' : 'error');
                    @endphp
                    <div class="p-3 bg-background rounded-xl border border-border text-center">
                        <div class="text-xs text-muted">سوال {{ $q->question_number }}</div>
                        <div class="font-bold text-foreground mt-1">
                            <span class="text-{{ $color }}">{{ number_format($given, 2) }}</span>
                            <span class="text-muted text-xs">/ {{ number_format($q->score, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($attempt->consultant_message)
            <div class="bg-info/10 border border-info/20 rounded-2xl p-5">
                <h3 class="font-bold text-info mb-2">پیام مشاور</h3>
                <p class="text-sm text-foreground leading-7 whitespace-pre-line">{{ $attempt->consultant_message }}</p>
            </div>
        @endif
    @endif

    <div class="bg-secondary border border-border rounded-2xl p-5">
        <h3 class="font-bold text-foreground mb-3">برگه‌های ارسال‌شده شما</h3>
        @if($attempt->uploads->count())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($attempt->uploads as $up)
                    <a href="{{ $up->url }}" target="_blank" class="block">
                        <img src="{{ $up->url }}" class="w-full h-40 object-cover rounded-lg border border-border hover:scale-105 transition">
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-muted">تصویری ثبت نشده.</p>
        @endif
    </div>
</div>
