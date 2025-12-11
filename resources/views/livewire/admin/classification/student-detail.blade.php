<div>
    <div>

        <div class="row">

            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                    <h4 class="mb-sm-0">جزئیات طبقه‌بندی</h4>

                    <div class="page-title-right">

                        <ol class="breadcrumb m-0">

                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">داشبورد</a></li>

                            <li class="breadcrumb-item"><a href="{{ route('admin.classification.dashboard') }}">طبقه‌بندی</a>
                            </li>

                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.classification.students', $project->id) }}">{{ $project->name }}</a>
                            </li>

                            <li class="breadcrumb-item active">جزئیات</li>

                        </ol>

                    </div>

                </div>

            </div>

        </div>


        <!-- Student Info Card -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="avatar-md flex-shrink-0">

                            <span class="avatar-title rounded-circle bg-primary fs-4">

                                {{ mb_substr($personalInfo->name ?? $user->name ?? 'N', 0, 1) }}

                            </span>

                            </div>

                            <div class="flex-grow-1 ms-3">

                                <h5 class="mb-1">{{ $personalInfo->name ?? $user->name ?? '-' }}</h5>

                                <div class="d-flex flex-wrap gap-2 text-muted">

                                <span>

                                    <i class="ri-phone-line me-1"></i>

                                    {{ $user->mobile ?? '-' }}

                                </span>

                                    @if($personalInfo)

                                        <span class="vr mx-2"></span>

                                        <span>

                                        <i class="ri-graduation-cap-line me-1"></i>

                                        پایه {{ $gradeNames[$personalInfo->grade] ?? $personalInfo->grade }}

                                    </span>

                                        <span class="vr mx-2"></span>

                                        <span>

                                        <i class="ri-book-line me-1"></i>

                                        رشته {{ $fieldNames[$personalInfo->field] ?? $personalInfo->field }}

                                    </span>

                                    @endif

                                </div>

                            </div>

                            <div>

                            <span class="badge bg-success fs-6">

                                <i class="ri-check-line me-1"></i>

                                ثبت شده

                            </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Tags Filter -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex flex-wrap gap-2">

                            @foreach($availableTags as $tag)

                                <button wire:click="selectTag('{{ $tag['id'] }}')"

                                        class="btn {{ $activeTag === $tag['id'] ? 'btn-primary' : 'btn-soft-primary' }}">

                                    {{ $tag['label'] }}

                                </button>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Subjects List -->

        <div class="row">

            <div class="col-12">

                @forelse($subjects as $subject)

                    <div class="card mb-3">

                        <div class="card-header bg-light">

                            <h5 class="card-title mb-0">

                                <i class="ri-book-2-line text-primary me-2"></i>

                                {{ $subject['name'] }}

                            </h5>

                        </div>

                        <div class="card-body">

                            @foreach($subject['chapters'] as $chapter)

                                <div class="mb-4">

                                    <h6 class="text-muted mb-3">

                                        <i class="ri-folder-line me-1"></i>

                                        {{ $chapter['name'] }}

                                    </h6>


                                    <div class="table-responsive">

                                        <table class="table table-bordered table-sm mb-0">

                                            <thead class="table-light">

                                            <tr>

                                                <th style="width: 50px;">ردیف</th>

                                                <th>مبحث</th>

                                                <th style="width: 100px;">امتیاز</th>

                                                <th style="width: 150px;">سطح تسلط</th>

                                            </tr>

                                            </thead>

                                            <tbody>

                                            @foreach($chapter['topics'] as $index => $topic)

                                                @php

                                                    $rating = $ratings[$topic['id']] ?? null;

                                                @endphp

                                                <tr>

                                                    <td>{{ $index + 1 }}</td>

                                                    <td>{{ $topic['name'] }}</td>

                                                    <td>

                                                        @if($rating)

                                                            <div class="d-flex gap-1">

                                                                @for($i = 1; $i <= 8; $i++)

                                                                    <span
                                                                        class="badge {{ $rating >= $i ? 'bg-' . $this->getRatingColor($rating) : 'bg-light text-muted' }}"

                                                                        style="width: 12px; height: 12px; padding: 0; border-radius: 50%;"></span>

                                                                @endfor

                                                            </div>

                                                        @else

                                                            <span class="text-muted">-</span>

                                                        @endif

                                                    </td>

                                                    <td>

                                                        @if($rating)

                                                            <span
                                                                class="badge bg-{{ $this->getRatingColor($rating) }}-subtle text-{{ $this->getRatingColor($rating) }}">

                                                                {{ $this->getRatingLabel($rating) }}

                                                            </span>

                                                        @else

                                                            <span class="text-muted">امتیازی ثبت نشده</span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="card">

                        <div class="card-body text-center py-5">

                            <i class="ri-book-open-line fs-1 text-muted d-block mb-3"></i>

                            <p class="text-muted mb-0">یکی از دسته‌بندی‌ها را انتخاب کنید</p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>


        <!-- Loading -->

        <div wire:loading.flex
             class="position-fixed top-0 start-0 w-100 h-100 justify-content-center align-items-center"

             style="background: rgba(0,0,0,0.3); z-index: 9999;">

            <div class="bg-white rounded p-4 d-flex align-items-center gap-3">

                <div class="spinner-border text-primary" role="status"></div>

                <span>در حال بارگذاری...</span>

            </div>

        </div>

    </div>
</div>
