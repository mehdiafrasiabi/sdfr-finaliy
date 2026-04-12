<div>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">پاسخ‌های دانش‌آموزان: {{ $exam->title }}</h4>
                <a href="{{ route('admin.essay-exams.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if($assignments->isEmpty())
                    <p class="text-muted mb-0 text-center py-4">هیچ دانش‌آموزی به این آزمون اختصاص داده نشده.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>دانش‌آموز</th>
                                <th>بازه زمانی</th>
                                <th>وضعیت</th>
                                <th>نمره</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignments as $a)
                                <tr>
                                    <td>{{ $a->student?->user?->name ?? '—' }}</td>
                                    <td class="small">
                                        @if($a->time)
                                            {{ verta($a->time->start_at)->format('Y/m/d H:i') }}
                                            تا
                                            {{ verta($a->time->end_at)->format('Y/m/d H:i') }}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'pending' => ['secondary', 'در انتظار'],
                                                'in_progress' => ['warning', 'در حال آزمون'],
                                                'submitted' => ['info', 'آماده تصحیح'],
                                                'graded' => ['success', 'تصحیح شده'],
                                            ];
                                            [$cls, $label] = $statusMap[$a->status] ?? ['secondary', $a->status];
                                        @endphp
                                        <span class="badge bg-{{ $cls }}">{{ $label }}</span>
                                    </td>
                                    <td>
                                        @if($a->latestAttempt?->total_score !== null)
                                            {{ number_format($a->latestAttempt->total_score, 2) }} / {{ number_format($exam->total_score, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if($a->latestAttempt)
                                            <a href="{{ route('admin.essay-exams.attempt.review', ['attemptId' => $a->latestAttempt->id]) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="ti ti-edit"></i> تصحیح / مشاهده
                                            </a>
                                        @else
                                            <span class="text-muted small">هنوز شروع نشده</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

