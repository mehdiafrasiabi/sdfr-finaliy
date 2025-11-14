<div class="container mt-4">
    <h2 class="mb-4">زمان مطالعه <span class="text-primary">{{ $studentName }}</span></h2>

    <div class="row g-3">

        <!-- امروز -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-center border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">امروز</h5>
                    <p class="card-text display-6">{{ $studyTime['today'] }}</p>
                </div>
            </div>
        </div>

        <!-- این هفته -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-center border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">این هفته</h5>
                    <p class="card-text display-6">{{ $studyTime['week'] }}</p>
                </div>
            </div>
        </div>

        <!-- این ماه -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-center border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">این ماه</h5>
                    <p class="card-text display-6">{{ $studyTime['month'] }}</p>
                </div>
            </div>
        </div>

        <!-- کل زمان -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-center border-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">کل زمان</h5>
                    <p class="card-text display-6">{{ $studyTime['total'] }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- یادداشت‌ها -->
    <div class="mt-5">
        <h4>یادداشت‌های مطالعه</h4>
        <ul class="list-group">
            @foreach($studySessions as $session)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div><strong>شروع:</strong> {{ $session->started_at ? $session->started_at->format('Y-m-d H:i') : '-' }}</div>
                        <div><strong>پایان:</strong> {{ $session->ended_at ? $session->ended_at->format('Y-m-d H:i') : '-' }}</div>
                        <div><strong>مدت:</strong>
                            {{ sprintf('%02d:%02d:%02d', floor($session->duration_seconds/3600), floor(($session->duration_seconds%3600)/60), $session->duration_seconds%60) }}
                        </div>
                        @if($session->note)
                            <div class="mt-1"><strong>یادداشت:</strong> {{ $session->note }}</div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
