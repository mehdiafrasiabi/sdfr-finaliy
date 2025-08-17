<div class="row layout-spacing layout-top-spacing" id="cancel-row">

    <div class="col-lg-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                @canany(['view_students_for_academic_support','view students with support info'])

                    <div id="style-2_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="dt--top-section">
                            <div class="row">

                                <div
                                    class="col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3">
                                    <div id="style-2_filter" class="dataTables_filter"><label>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-search">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg>
                                            <input type="text" wire:model.live.debounce.350ms="search"
                                                   class="form-control"
                                                   id="input-search"
                                                   placeholder="نام دانش آموز">
                                        </label>
                                    </div>

                                </div>
                                <button wire:click="exportExcel" class="btn btn-outline-success" wire:loading.attr="disabled">
                                    <span wire:loading>در حال تهیه...</span>
                                    <span wire:loading.remove>خروجی اکسل</span>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">

                            <table id="style-2" class="table style-2 dt-table-hover dataTable no-footer" role="grid"
                                   aria-describedby="style-2_info" style="width: 1444px;">
                                <thead>
                                <tr role="row">
                                    <th class="checkbox-column dt-no-sorting sorting_asc" rowspan="1" colspan="1"
                                        aria-label=" Record Id " style="width: 70px;">
                                        #
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="style-2" rowspan="1" colspan="1"
                                        aria-label="First Name: activate to sort column ascending" style="width: 138px;">
                                        دانش آموز
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="style-2" rowspan="1" colspan="1"
                                        aria-label="Last Name: activate to sort column ascending" style="width: 135px;">
                                        تلفن همراه
                                    </th>

                                    <th class="sorting" tabindex="0" aria-controls="style-2" rowspan="1" colspan="1"
                                        aria-label="Mobile No.: activate to sort column ascending" style="width: 166px;">
                                        پدر
                                    </th>
                                    <th class="text-center sorting" tabindex="0" aria-controls="style-2" rowspan="1"
                                        colspan="1" aria-label="Image: activate to sort column ascending"
                                        style="width: 87px;">
                                        مادر
                                    </th>
                                    <th class="text-center sorting" tabindex="0" aria-controls="style-2" rowspan="1"
                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                        style="width: 134px;">تعداد امتیازات
                                    </th>
                                    <th class="text-center sorting" tabindex="0" aria-controls="style-2" rowspan="1"
                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                        style="width: 134px;">سطح آموزشی
                                    </th>
                                    <th class="text-center dt-no-sorting sorting" tabindex="0" aria-controls="style-2"
                                        rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending"
                                        style="width: 92px;">عملیات
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($students as $student)

                                    <tr role="row" class="odd">
                                        <td class=" sorting_1">
                                            {{$loop->iteration + $students->firstItem() - 1}}
                                        </td>
                                        <td>{{$student->user->personalInformation->name }}</td>
                                        <td>{{$student->payment->order->user->mobile}}</td>
                                        <td>{{$student->user->personalInformation->father_mobile }}</td>
                                        <td>{{$student->user->personalInformation->mother_mobile }}</td>

                                        <td class="text-center"><span class="shadow-none badge badge-primary">بزودی</span></td>

                                        <td>
                                            @can('view personal_information')
                                                <select
                                                    wire:confirm="آیا از انتخاب خود برای تغییر سطح آموزشی اطمینان دارید ؟"
                                                    wire:change="changeStatus({{$student->id}},$event.target.value)"
                                                    class="form-select rounded-pill mb-3  text-{{$student->statusColor}}">
                                                    <option value="A" {{$student->star=='A' ? 'selected' :''}}>
                                                        A
                                                    </option>
                                                    <option value="B" {{$student->star=='B' ? 'selected' :''}}>
                                                        B
                                                    </option>
                                                    <option value="C" {{$student->star=='C' ? 'selected' :''}}>
                                                        C
                                                    </option>
                                                    <option value="D" {{$student->star=='D' ? 'selected' :''}}>
                                                        D
                                                    </option>
                                                </select>

                                            @else
                                                <div class="alert alert-danger">عدم دسترسی!!</div>
                                            @endcan
                                        </td>
                                        <td class="text-center">



                                            <a href="{{route('admin.student.meetGoogle',$student->payment->order->user->id)}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-video">
                                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="dt--bottom-section d-sm-flex justify-content-sm-between text-center">

                            <div class="dt--pagination">
                                <div class="dataTables_paginate paging_simple_numbers" id="style-2_paginate">
                                    {{$students ->links('layouts.admin.pagination')}}

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-icon-left alert-light-danger alert-dismissible fade show mb-4" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <svg data-bs-dismiss="alert"> ...</svg>
                        </button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-check-square">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <strong></strong>
                        شما به این قسمت دسترسی ندارید !!!
                    </div>
                @endcanany
            </div>
        </div>
    </div>
</div>
