<div>
    <div class="row">
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-body p-4">
                    <div>
                        <div class="table-responsive">
                            <table class="table mb-0 table-borderless">
                                <tbody>
                                <tr>
                                    <th><span class="fw-medium">نام:</span></th>
                                    <td>{{$student->personalInformation->name}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">موبایل:</span></th>
                                    <td>{{$student->user->mobile}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">ایمیل</span></th>
                                    <td>{{$student->user->email??'---'}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">مشاور</span></th>
                                    <td>{{$student->advisor->name??'---'}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">تلفن مشاور</span></th>
                                    <td>{{$student->advisor->mobile??'---'}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">

                        <span style="color: red">لیست برنامه های مشاوره ای دریافتی</span>
                        : {{$student->personalInformation->name}}</h4>
                    <div class="d-flex justify-content-end mb-3">
                        <button wire:click="exportBarnamehs" class="btn btn-outline-primary">دانلود برنامه‌های مشاوره‌ای</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3"></div>
                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">عنوان</th>
                                    <th class="sort" data-sort="phone">برنامه مشاوره ای</th>
                                    <th class="sort" data-sort="action">تاریخ ثبت</th>
                                    <th class="sort" data-sort="phone">وضعیت مشاهده</th>
                                    <th class="sort" data-sort="phone">تاریخ مشاهده</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($details  as $detail)
                                    <tr>
                                        <th scope="row">
                                            {{$loop->iteration + $details ->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">{{ $detail->title ?? '-' }}</td>
                                        <td class="phone"><a href="{{public_path('students'.$detail->student_id.'/plan/'.$detail->barnameh)}}" download>مشاهده</a></td>
                                        <td class="phone">{{jalali($detail->created_at)->format('%d %B %Y | H:i')}}</td>
                                        <td class="phone">
                                            @if ($detail->views->isNotEmpty())
                                                <span class="text-success font-bold">دیده شده</span>
                                            @else
                                                <span class="text-danger font-bold">دیده نشده</span>
                                            @endif
                                        </td>
                                        <td class="phone">
                                            @if ($detail->views->isNotEmpty())
                                                <span class="text-success font-bold">
                                                {{jalali($detail->created_at)->format('%d %B %Y | H:i')}}
                                            </span>
                                            @else
                                                <span class="text-danger font-bold">---</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                            <p class="text-muted mb-0">ما برنامه مشاوره ای دانش اموز ها را جستجو کرده ایم، هیچ برنامه مشاوره ای دانش آموز برای جستجوی شما پیدا نکردیم.</p>
                                        </div>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{$details ->links('layouts.manager.pagination')}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <span style="color: #00ad62">لیست کارنامه وضعیت ماهانه</span> :


                        {{$student->personalInformation->name}}</h4>

                </div>

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">

                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">عنوان</th>
                                    <th class="sort" data-sort="phone">کارنامه وضعیت</th>
                                    <th class="sort" data-sort="action">تاریخ ثبت</th>


                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($reportMonthlies  as $report)
                                    <tr>
                                        <th scope="row">
                                            {{$loop->iteration + $reportMonthlies ->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">{{ $report->title ?? '-' }}</td>
                                        <td class="phone"><a href="{{public_path('students/'.$report->student_id.'/report_monthly/'.$report->report)}}" download>مشاهده</a></td>
                                        <td class="phone">{{jalali($report->created_at)->format('%d %B %Y | H:i')}}</td>

                                    </tr>
                                @empty
                                    <tr class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                            <p class="text-muted mb-0">ما برنامه مشاوره ای دانش اموز ها را جستجو کرده ایم، هیچ برنامه مشاوره ای دانش آموز برای جستجوی شما پیدا نکردیم.</p>
                                        </div>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{$reportMonthlies ->links('layouts.manager.pagination')}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <span style="color: #00ad62">لیست  گزارش های روزانه</span> :


                        {{$student->personalInformation->name}}</h4>
                    <div class="d-flex justify-content-end mb-3">
                        <button wire:click="exportReportDaily" class="btn btn-outline-success">دانلود گزارش های دانش آموز</button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">


                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">توضیحات</th>
                                    <th class="sort" data-sort="phone">رضایت از خود</th>
                                    <th class="sort" data-sort="phone">وضعیت</th>
                                    <th class="sort" data-sort="phone">فایل</th>
                                    <th class="sort" data-sort="action">تاریخ ثبت</th>


                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($reportDaily  as $item)
                                    <tr>
                                        <th scope="row">
                                            {{$loop->iteration + $reportDaily ->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">
                                            <p class="wrap-text">{{ $item->description ?? '-' }}</p>
                                        </td>
                                        <td class="customer_name">
                                          <span class="badge bg-warning-subtle text-warning-emphasis">
                                                امتیاز: {{ $item->complacent ?? '--' }} / 10
                                            </span>
                                        </td>
                                        <td class="customer_name">
                                            @if($item->status == 'pending')
                                                <span  class="text-warning-emphasis wrap-text">در انتظار تایید مشاور</span>
                                            @elseif($item->status == 'completed')
                                                <span class="text-success wrap-text ">تایید شده</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="text-danger-emphasis wrap-text ">رد شده</span>
                                            @endif
                                        </td>
                                        <td class="phone">
                                            @if(isset($item->report_file))
                                                <a href="{{asset('students/reportsDaily/'.$item->student_id).'/'.$item->report_file}}" download>مشاهده</a>
                                            @else
                                                فایلی برای مشاهده وجود ندارد
                                            @endif
                                        </td>
                                        <td class="phone">{{jalali($item->created_at)->format('%d %B %Y | H:i')}}</td>

                                    </tr>
                                @empty
                                    <tr class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                            <p class="text-muted mb-0">ما برنامه مشاوره ای دانش اموز ها را جستجو کرده ایم، هیچ برنامه مشاوره ای دانش آموز برای جستجوی شما پیدا نکردیم.</p>
                                        </div>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{$reportDaily ->links('layouts.manager.pagination')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('style')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 500px;
            }
        </style>
    @endpush
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/manager/assets/libs/prismjs/prism.js"></script>
        <script src="/manager/assets/libs/list.js/list.min.js"></script>
        <script src="/manager/assets/libs/list.pagination.js/list.pagination.min.js"></script>
        <script src="/manager/assets/js/pages/listjs.init.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Livewire.on('open-selfie-modal', () => {
                    new bootstrap.Modal(document.getElementById('selfieModal')).show();
                });
                Livewire.on('open-national-modal', () => {
                    new bootstrap.Modal(document.getElementById('nationalCardModal')).show();
                });
            });
        </script>
    @endpush
</div>
