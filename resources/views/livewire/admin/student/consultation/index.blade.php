<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">دانش آموزان /</span>
        اتاق مشاوره
    </h4>
    <!-- DataTable direct html : by byteMaster at 2024-02-01 -->
    <div class="card">
        <h5 class="card-header"></h5>
        <div class="card-datatable table-responsive pt-0">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>دانش آموز</th>
                    <th>پدر</th>
                    <th>مادر</th>
                    <th>پایه + رشته</th>
                    <th>جزییات</th>
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
                            <div>
                                @if($student->user->personalInformation->grade == 12)
                                    دوازدهم
                                @elseif($student->user->personalInformation->grade == 11)
                                    یازدهم
                                @elseif($student->user->personalInformation->grade == 10)
                                    دهم
                                @endif

                            </div>
                            <div>
                                @if($student->user->personalInformation->field == 'math')
                                    ریاضی
                                @elseif($student->user->personalInformation->field == 'experimental')
                                    تجربی
                                @elseif($student->user->personalInformation->field == 'human')
                                    انسانی
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{route('admin.student.advising-sessions.create',$student->payment->order->user->id)}}" class="btn btn-sm btn-icon item-edit waves-effect waves-light">
                                <i
                                    class="text-primary ti ti-pencil">

                                </i>
                            </a>
                        </td>
                    </tr>
                @empty
                    Not Found
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
