<div>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">داشبورد طبقه‌بندی دروس</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">داشبورد</a></li>
                            <li class="breadcrumb-item active">طبقه‌بندی</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student Analysis Dashboard --}}
        @if(!empty($studentDashboardData) && !isset($studentDashboardData['error']))
            <div class="row mb-4">
                 <div class="col-12">
                    <div class="alert alert-info">
                        تحلیل دانش‌آموزان بر اساس پروژه: <strong>{{ $studentDashboardData['projectName'] }}</strong>
                    </div>
                </div>
                <!-- Participation & Scores -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">آمار مشارکت و امتیازات</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>مشارکت دانش‌آموزان</h6>
                                    <p>انجام داده: <span class="fw-bold text-success">{{ $studentDashboardData['classifiedStudentsCount'] }} نفر</span></p>
                                    <p>انجام نداده: <span class="fw-bold text-danger">{{ $studentDashboardData['unclassifiedStudentsCount'] }} نفر</span></p>
                                    <hr>
                                    <h6>پروژه بعدی</h6>
                                     @if($studentDashboardData['nextClassificationDate'])
                                        <p>شروع: <span class="fw-bold">{{ \Morilog\Jalali\Jalalian::fromCarbon($studentDashboardData['nextClassificationDate'])->format('Y/m/d') }}</span></p>
                                     @else
                                        <p class="text-muted">پروژه بعدی تعریف نشده.</p>
                                     @endif
                                </div>
                                <div class="col-md-6">
                                    <h6>توزیع امتیازات</h6>
                                    <ul class="list-unstyled">
                                        @forelse($studentDashboardData['ratingDistribution'] as $rating => $count)
                                            <li><span class="fw-bold">امتیاز {{ $rating }}:</span> {{ $count }} مورد</li>
                                        @empty
                                            <li class="text-muted">هنوز امتیازی ثبت نشده.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strengths & Weaknesses -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">نقاط قوت و ضعف دانش‌آموزان</h5>
                        </div>
                        <div class="card-body">
                           <div class="row">
                               <div class="col-md-6">
                                   <h6 class="text-success">نقاط قوت (بیشترین امتیاز A)</h6>
                                   <ul class="list-group list-group-flush">
                                       @forelse($studentDashboardData['strengths'] as $item)
                                           <li class="list-group-item">{{ $item['name'] }} <span class="badge bg-success float-end">{{$item['A']}}</span></li>
                                       @empty
                                           <li class="list-group-item text-muted">موردی یافت نشد.</li>
                                       @endforelse
                                   </ul>
                               </div>
                               <div class="col-md-6">
                                   <h6 class="text-danger">نقاط ضعف تخصصی (بیشترین امتیاز C)</h6>
                                   <ul class="list-group list-group-flush">
                                       @forelse($studentDashboardData['weaknesses'] as $item)
                                           <li class="list-group-item">{{ $item['name'] }} <span class="badge bg-danger float-end">{{$item['C']}}</span></li>
                                       @empty
                                            <li class="list-group-item text-muted">موردی یافت نشد.</li>
                                       @endforelse
                                   </ul>
                                    <h6 class="text-warning mt-3">نقاط ضعف عمومی (بیشترین امتیاز C)</h6>
                                   <ul class="list-group list-group-flush">
                                       @forelse($studentDashboardData['generalWeaknesses'] as $item)
                                           <li class="list-group-item">{{ $item['name'] }} <span class="badge bg-warning float-end">{{$item['C']}}</span></li>
                                       @empty
                                            <li class="list-group-item text-muted">موردی یافت نشد.</li>
                                       @endforelse
                                   </ul>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($studentDashboardData['error']))
             <div class="alert alert-warning">{{ $studentDashboardData['error'] }}</div>
        @endif


        <!-- Projects List (Original Content) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">لیست پروژه‌ها</h5>
                        <div class="search-box">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   class="form-control search" placeholder="جستجو...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">ردیف</th>
                                    <th>نام پروژه</th>
                                    <th style="width: 140px;">تاریخ شروع</th>
                                    <th style="width: 140px;">تاریخ پایان</th>
                                    <th style="width: 100px;">وضعیت</th>
                                    <th style="width: 120px;">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $project->name }}</div>
                                            @if($project->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-nowrap small">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                                            <br>
                                            <span class="text-muted">{{ $project->start_at->format('H:i') }}</span>
                                        </td>
                                        <td class="text-nowrap small">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                                            <br>
                                            <span class="text-muted">{{ $project->end_at->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            @if($project->status === 'active')
                                                <span class="badge bg-success">در حال اجرا</span>
                                            @elseif($project->status === 'upcoming')
                                                <span class="badge bg-info">در انتظار</span>
                                            @else
                                                <span class="badge bg-secondary">پایان یافته</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.classification.students', $project->id) }}"
                                               class="btn btn-sm btn-soft-primary">
                                                <i class="fi fi-br-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-folder-open-line fs-1 d-block mb-2"></i>
                                                هیچ پروژه‌ای یافت نشد
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $projects->links('layouts.admin.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
