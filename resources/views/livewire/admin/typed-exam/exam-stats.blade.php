<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">آمار آزمون</h4>
                            <p class="text-muted mb-0">{{ $exam->title }}</p>
                        </div>
                        <a href="{{ route('admin.typed-exams.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-right me-1"></i>
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row 1: Average & Top Students -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-3">میانگین درصد کل آزمون</h6>
                        <div class="display-4 text-primary fw-bold">{{ $averageScore }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">سه نفر برتر آزمون</h6>
                    </div>
                    <div class="card-body p-0">
                        @if(empty($topStudents))
                            <div class="text-center py-4 text-muted">
                                هنوز شرکت‌کننده‌ای ثبت نشده است
                            </div>
                        @else
                            <table class="table table-sm mb-0">
                                <thead>
                                <tr>
                                    <th>رتبه</th>
                                    <th>نام</th>
                                    <th>درصد</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($topStudents as $student)
                                    <tr>
                                        <td>
                                            @if($student['rank'] === 1)
                                                <span class="badge bg-warning text-dark">🥇</span>
                                            @elseif($student['rank'] === 2)
                                                <span class="badge bg-secondary">🥈</span>
                                            @else
                                                <span class="badge bg-danger">🥉</span>
                                            @endif
                                        </td>
                                        <td>{{ $student['name'] }}</td>
                                        <td><span class="badge bg-primary">{{ $student['score'] }}%</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Row 2: Charts -->
        <div class="row mb-4">
            <!-- Bar Chart: Score Distribution -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">توزیع نمرات</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="scoreDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Donut Chart: Answer Stats -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">آمار پاسخ‌ها</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="answerStatsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row 3: Per-Question Stats -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">آمار هر سوال</h5>
                    </div>
                    <div class="card-body">
                        @if(empty($questionStats))
                            <div class="text-center py-5 text-muted">
                                هنوز پاسخی ثبت نشده است
                            </div>
                        @else
                            @foreach($questionStats as $index => $qs)
                                <div class="question-stat-box border rounded p-4 mb-4">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <!-- Question -->
                                            <div class="d-flex gap-2 mb-3">
                                                <span class="badge bg-primary">سوال {{ $index + 1 }}</span>
                                                <span class="badge bg-secondary">کد: {{ $qs['code'] }}</span>
                                                <span class="badge bg-info">{{ $qs['subject'] }}</span>
                                            </div>
                                            <!-- Question Body (Image or Text) -->
                                            <div class="question-body mb-3">
                                                @if(!empty($qs['question_image_url']))
                                                    <img src="{{ $qs['question_image_url'] }}"
                                                         alt="تصویر سوال {{ $index + 1 }}"
                                                         class="img-fluid rounded"
                                                         style="max-width: 100%; max-height: 250px; object-fit: contain;">
                                                @elseif(!empty($qs['body']))
                                                    {!! $qs['body'] !!}
                                                @else
                                                    <div class="text-muted text-center py-3">
                                                        <i class="ti ti-photo-off"></i>
                                                        <small>تصویر موجود نیست</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <hr>
                                            <!-- Options (Simple numbered) -->
                                            @foreach([1, 2, 3, 4] as $optNum)
                                                @php $isCorrect = ($qs['correct_option'] ?? null) === $optNum; @endphp
                                                <div
                                                    class="option-item p-2 mb-1 rounded {{ $isCorrect ? 'bg-success-subtle border border-success' : 'bg-light' }}">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span
                                                            class="badge {{ $isCorrect ? 'bg-success' : 'bg-secondary' }}"
                                                            style="width: 25px;">{{ $optNum }}</span>
                                                        <div class="flex-grow-1">گزینه {{ $optNum }}</div>
                                                        @if($isCorrect)
                                                            <i class="ti ti-check text-success"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-5">
                                            <!-- Stats -->
                                            <div class="row">
                                                <div class="col-6">
                                                    <canvas id="questionChart{{ $qs['id'] }}" height="150"></canvas>
                                                </div>
                                                <div class="col-6">
                                                    <table class="table table-sm">
                                                        <thead>
                                                        <tr>
                                                            <th>گزینه</th>
                                                            <th>تعداد</th>
                                                            <th>درصد</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach([1, 2, 3, 4] as $optNum)
                                                            @php $stat = $qs['stats']['option_' . $optNum]; @endphp
                                                            <tr>
                                                                <td>گزینه {{ $optNum }}</td>
                                                                <td>{{ $stat['count'] }}</td>
                                                                <td>{{ $stat['percent'] }}%</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr class="table-secondary">
                                                            <td>بدون پاسخ</td>
                                                            <td>{{ $qs['stats']['unanswered']['count'] }}</td>
                                                            <td>{{ $qs['stats']['unanswered']['percent'] }}%</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Score Distribution Bar Chart
                const scoreRanges = @json($scoreRanges);
                new Chart(document.getElementById('scoreDistributionChart'), {
                    type: 'bar',
                    data: {
                        labels: scoreRanges.map(r => r.label),
                        datasets: [{
                            label: 'تعداد دانش‌آموزان',
                            data: scoreRanges.map(r => r.count),
                            backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#198754'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {legend: {display: false}},
                        scales: {y: {beginAtZero: true, ticks: {stepSize: 1}}}
                    }
                });
                // Answer Stats Donut Chart
                const answerStats = @json($answerStats);
                new Chart(document.getElementById('answerStatsChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['صحیح', 'غلط', 'بدون پاسخ'],
                        datasets: [{
                            data: [answerStats.correct, answerStats.wrong, answerStats.unanswered],
                            backgroundColor: ['#198754', '#dc3545', '#6c757d'],
                        }]
                    },
                    options: {responsive: true}
                });
                // Per-Question Donut Charts
                const questionStats = @json($questionStats);
                questionStats.forEach(qs => {
                    const canvas = document.getElementById('questionChart' + qs.id);
                    if (canvas) {
                        new Chart(canvas, {
                            type: 'doughnut',
                            data: {
                                labels: ['گزینه ۱', 'گزینه ۲', 'گزینه ۳', 'گزینه ۴', 'بدون پاسخ'],
                                datasets: [{
                                    data: [
                                        qs.stats.option_1.count,
                                        qs.stats.option_2.count,
                                        qs.stats.option_3.count,
                                        qs.stats.option_4.count,
                                        qs.stats.unanswered.count
                                    ],
                                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {legend: {position: 'bottom', labels: {font: {size: 10}}}}
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</div>
