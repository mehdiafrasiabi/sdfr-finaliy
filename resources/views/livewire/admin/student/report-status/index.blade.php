
<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard.index')}}">
                        <i class="fi fi-rr-home">
                        </i>
                        صفحه اصلی
                    </a>
                </li>
                <li aria-current="page" class="breadcrumb-item active">

                </li>
            </ol>
        </nav>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">
                        کارنامه وضعیت
                    </h6>
                </div>

                <div class="card-body p-0 pb-2">
                    <div id="dt_basic_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">

                        <!-- Top Controls -->
                        <div class="row mt-2 justify-content-between mx-2 py-2">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                                <div class="dt-length">
                                    <label for="dt-length-0"> در هرصفحه</label>:
                                    <select
                                        aria-controls="dt_basic"
                                        class="form-select form-select-sm"
                                        id="dt-length-0"
                                    >
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>

                                </div>
                            </div>

                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
                                <div >
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="search"
                                        wire:model.live.debounce.350ms="search"
                                        name="search"
                                        placeholder="جستجو"
                                    />
                                    <label for="dt-search-0"></label>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="row mt-2 justify-content-between ">
                            <div
                                class="d-md-flex justify-content-between align-items-center col-12  col-md">
                                <table class="table display"  style="width: 100%;">


                                    <thead class="table-light">
                                    <tr>
                                        <th data-dt-column="0">#</th>
                                        <th data-dt-column="1">دانش آموز</th>
                                        <th data-dt-column="2">پدر</th>
                                        <th data-dt-column="3">مادر</th>
                                        <th data-dt-column="4">پایه + رشته</th>
                                        <th data-dt-column="5"></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                {{$loop->iteration + $students->firstItem() - 1}}
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            <img
                                                                src="/admin/assets/images/avatar/avatar2.webp"
                                                                alt="Avatar"
                                                                class="rounded-circle"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column">
                                                            <span class="text-truncate fw-medium">
                                                                {{$student->user->personalInformation->name}}
                                                            </span>
                                                        <small class="text-truncate text-muted">
                                                            29 {{$student->payment->order->user->mobile}}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                {{$student->user->personalInformation->father_mobile}}
                                            </td>

                                            <td>
                                                {{$student->user->personalInformation->mother_mobile}}
                                            </td>

                                            <td>
                                                @if($student->user->personalInformation->grade == 12)
                                                    دوازدهم
                                                @elseif($student->user->personalInformation->grade == 11)
                                                    یازدهم
                                                @elseif($student->user->personalInformation->grade == 10)
                                                    دهم
                                                @endif

                                                @if($student->user->personalInformation->field == 'math')
                                                    ریاضی
                                                @elseif($student->user->personalInformation->field == 'experimental')
                                                    تجربی
                                                @elseif($student->user->personalInformation->field == 'human')
                                                    انسانی
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group float-end">
                                                    <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                                        <i class="fi fi-rr-menu-dots">
                                                        </i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('admin.student.reportStudent.detail',$student->payment->order->user->id)}}">
                                                                ایجاد کارنامه وضعیت
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-danger text-center">
                                                وجود ندارد
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>

                                </table>


                            </div>
                        </div>

                        <!-- Bottom Pagination -->
                        <div class="row mt-2 justify-content-between">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                            </div>

                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
                                <div class="dt-paging">
                                    {{ $students->links('layouts.admin.pagination') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
