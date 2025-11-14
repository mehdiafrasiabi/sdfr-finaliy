<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">لیست تیکت</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:%20void(0);">تیکت</a></li>
                        <li class="breadcrumb-item active">لیست تیکت</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">کل تیکت ها</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                                            data-target="{{ $totalTickets }}">{{ $totalTickets }}</span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">
                                                    <i class="ri-ticket-2-line"></i>
                                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> <!-- end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">تیکت های در انتظار پاسخ</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                                            data-target="{{ $waitingTickets }}">{{ $waitingTickets }}</span>
                            </h2>

                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">
                                                    <i class="mdi mdi-timer-sand"></i>
                                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">تیکت های بسته</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                                            data-target="{{ $closedTickets }}">{{ $closedTickets }}</span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">
                                                    <i class="mdi mdi-send-lock"></i>
                                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-3 col-sm-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">تیکت های پاسخ داده شده</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                                            data-target="{{$answeredTickets}}">{{$answeredTickets}}</span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">
                                                    <i class="mdi mdi-send-check"></i>
                                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="ticketsList">

                <div class="card-body border border-dashed border-end-0 border-start-0">

                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-12">
                            <div class="search-box">
                                <input wire:model.live.debounce.350ms="search" type="text"
                                       class="form-control search bg-light border-light"
                                       placeholder="جستجوی عنوان تیکت">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-4">
                            <div class="input-light">
                                <div class="choices" tabindex="0" role="listbox">
                                    <div>
                                        <select class=" choices__inner mb-3" wire:model.live.debounce.500ms="status">
                                            <option value="">وضعیت تیکت</option>
                                            <option value="waiting">در انتظار پاسخ ادمین</option>
                                            <option value="answered">پاسخ داده شده</option>
                                            <option value="closed">بسته شده</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-4">
                            <div class="input-light">
                                <div class="choices" tabindex="0" role="listbox">
                                    <div>
                                        <select class=" choices__inner mb-3" wire:model.live.debounce.500ms="priority">
                                            <option value="">اولویت تیکت</option>
                                            <option value="low">کم</option>
                                            <option value="medium">متوسط</option>
                                            <option value="high">فوری</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-1 col-sm-4 choices">
                            <select class="choices__inner mb-3" wire:model.live.debounce.500ms="department">
                                <option value="">دپارتمان</option>
                                @foreach($departments as $dep)
                                    <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0" id="ticketTable">
                            <thead>
                            <tr>
                                <th scope="col" style="width: 40px;">
                                    #
                                </th>
                                <th class="sort" data-sort="tasks_name">عنوان</th>
                                <th class="sort" data-sort="client_name">مشتری</th>
                                <th class="sort" data-sort="assignedto">دپارتمان</th>
                                <th class="sort" data-sort="create_date">تاریخ ثبت</th>
                                <th class="sort" data-sort="status">وضعیت</th>
                                <th class="sort" data-sort="priority">اولویت</th>
                                <th class="sort" data-sort="action">اقدام</th>
                            </tr>
                            </thead>
                            <tbody class="list form-check-all" id="ticket-list-data">
                            @forelse($tickets as $ticket)
                                <tr>
                                    <th scope="row">
                                        {{$loop->iteration + $tickets->firstItem() - 1}}

                                    </th>

                                    <td class="tasks_name">{{$ticket->title}}</td>
                                    <td class="client_name">{{$ticket->user->name}}</td>
                                    <td class="assignedto">{{ $ticket->department->name }}</td>
                                    <td class="create_date">{{jalali($ticket->created_at)->format('%d %B %Y | H:i')}}</td>
                                    <td class="status">
                                        @if($ticket->status=='waiting')
                                            <span class="badge bg-warning-subtle text-warning text-uppercase">
                                                در انتظار پاسخ ادمین
                                            </span>
                                        @elseif($ticket->status=='answered')
                                            <span class="badge bg-success-subtle text-success text-uppercase">
                                             پاسخ داده شده
                                            </span>
                                        @elseif($ticket->status=='closed')
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">
                                             بسته شده
                                            </span>
                                        @elseif($ticket->status=='referred')
                                            <span class="badge bg-primary-subtle text-primary text-uppercase">
                                             ارجاع داده شد
                                            </span>
                                        @endif

                                    </td>
                                    <td class="priority">

                                        @if($ticket->priority=='low')
                                            <span class="badge bg-primary-subtle text-primary text-uppercase">
                                                کم
                                            </span>
                                        @elseif($ticket->priority=='medium')
                                            <span class="badge bg-warning-subtle text-warning text-uppercase">
                                            متوسط
                                            </span>
                                        @elseif($ticket->priority=='high')
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">
                                            فوری
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('manager.ticket.show',$ticket->id)}}">
                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i>مشاهده
                                            کنید
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr class="noresult" style="display: block;">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                   colors="primary:#121331,secondary:#08a88a"
                                                   style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>

                                    </div>
                                </tr>

                            @endforelse
                            </tbody>

                        </table>


                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                           colors="primary:#121331,secondary:#08a88a"
                                           style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                <p class="text-muted mb-0">ما  تیکت را جستجو کرده ایم، هیچ تیکتی برای
                                    جستجوی شما پیدا نکردیم.</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <div class="pagination-wrap hstack gap-2">
                            {{$tickets->links('layouts.manager.pagination')}}

                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                               colors="primary:#405189,secondary:#f06548"
                                               style="width:90px;height:90px"></lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4>می خواهید سفارشی را حذف کنید؟</h4>
                                        <p class="text-muted fs-14 mb-4">حذف سفارش شما تمام اطلاعات شما را از پایگاه
                                            داده ما حذف می کند.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                    id="deleteRecord-close" data-bs-dismiss="modal"><i
                                                    class="ri-close-line me-1 align-middle"></i>بستن
                                            </button>
                                            <button class="btn btn-danger" id="delete-record">بله حذفش کن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end modal -->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
