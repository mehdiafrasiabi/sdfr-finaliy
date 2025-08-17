@php
    $studentId = auth()->user()->student->id;
    $attempt = \App\Models\ExamAttemp::where('exam_id', $exam->id)
                ->where('student_id', $studentId)
                ->first();
@endphp

<div class="p-4 rounded-xl shadow bg-white border">
    <div class="flex justify-between">
        <div>
            <h2 class="text-lg font-bold text-success">{{ $exam->title }}</h2>
            <p class="text-xs text-muted mt-3">
                مدت زمان: {{ $exam->duration_minutes }} دقیقه
            </p>
        </div>
        <div class="flex flex-col items-end gap-2 text-primary">
            @if ($attempt && $attempt->submitted_at)
                <span class="text-sm text-gray-600">پایان یافته</span>
                <a wire:navigate href="{{ route('client.profile.exam.result', $exam->id) }}"
                   class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-green-500/20 rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                    مشاهده نتیجه
                </a>
            @else
                <span class="text-sm text-gray-600">در انتظار شروع</span>
                <button wire:click="confirmEntry({{ $exam->id }})"
                        class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                    شروع
                    آزمون
                </button>
            @endif
        </div>
    </div>
</div>
