<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">دانش آموزان /</span>
        کل دانش آموزان
    </h4>
    <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center"></div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <div class="btn-group">
                                <button
                                    class="btn btn-secondary buttons-collection dropdown-toggle btn-label-primary me-2 waves-effect waves-light"
                                    tabindex="0" aria-controls="DataTables_Table_1" type="button" aria-haspopup="dialog"
                                    aria-expanded="false">
                                    <span><i class="ti ti-file-export me-sm-1"></i>
                                        <span
                                            class="d-none d-sm-inline-block">گرفتن خروجی
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="DataTables_Table_1_length">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                        <div id="DataTables_Table_1_filter" class="dataTables_filter">
                            <label>جستجو:<input type="search"
                                                class="form-control"
                                                wire:model.live.debounce.350ms="search"
                                                placeholder="جستجو ..."
                                                aria-controls="DataTables_Table_1">
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>دانش آموز</th>
                    <th>پدر</th>
                    <th>مادر</th>
                    <th>پایه + رشته</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td> {{$loop->iteration + $students->firstItem() - 1}}</td>

                        <td>
                            <div class="d-flex justify-content-left align-items-center">
                                <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                        <img src="/admin/assets/img/icons/brands/html-label.png"
                                             alt="Avatar" class="rounded-circle">
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span
                                        class="text-truncate fw-medium">{{$student->user->personalInformation->name }}</span>
                                    <small
                                        class="text-truncate text-muted">29
                                        {{$student->payment->order->user->mobile}}
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

                    </tr>
                @empty
                    <h5 class="text-danger"> وجود ندارد </h5>
                @endforelse

                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-5">
            </div>
            <div class="col-sm-12 col-md-7">
                <div>
                    {{ $students->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>
    </div>


    @push('script')
        <!-- Page JS -->
        <script src="/admin/assets/js/tables-datatables-basic.js"></script>
    @endpush
</div>
