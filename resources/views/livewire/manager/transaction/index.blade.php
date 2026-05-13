<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">لیست احراز هویت</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input wire:model.live.debounce.500ms="search" type="text"
                                               class="form-control search" placeholder="جستجو...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">شماره سفارش</th>
                                    <th class="sort" data-sort="customer_name">نام ونام خانوادگی</th>
                                    <th class="sort" data-sort="phone">تلفن</th>
                                    <th class="sort" data-sort="action">وضعیت پرداخت</th>
                                    <th class="sort" data-sort="status">مبلغ نهایی</th>
                                    <th class="sort" data-sort="status">شماره مرجع درگاه</th>
                                    <th class="sort" data-sort="status">شماره کارت پرداختی</th>
                                    <th class="sort" data-sort="date">تاریخ ثبت سفارش</th>
                                    <th class="sort" data-sort="date">سفارش</th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($transactions as $item)
                                    <tr>
                                        <th scope="row">
                                            {{$loop->iteration + $transactions->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">{{$item->order_number}}</td>
                                        <td class="phone">{{@$item->enrollment->user->name}}</td>
                                        <td class="phone"> {{@$item->enrollment->user->mobile}}</td>

                                        <td>
                                            <span
                                                class=" badge badge-light-{{@$item->statusPaymentColor}} text-start action-delete">
                                                {{@$item->status=='pending' ? 'درحال پردازش' : ''}}
                                                {{@$item->status=='success' ? 'موفق' : ''}}
                                                {{@$item->status=='failed' ? 'ناموفق' : ''}}
                                            </span>
                                        </td>
                                        <td class="phone"> {{number_format($item->amount)}}تومان</td>
                                        <td class="phone"> {{$item->refNumber}}</td>
                                        <td class="phone"> {{$item->cardNumber}}</td>

                                        <td class="time">{{jalali($item->created_at)->format('%d %B %Y | H:i')}}</td>
                                        <td class="time">
                                            {{ $item->enrollment?->user?->name ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                       colors="primary:#121331,secondary:#08a88a"
                                                       style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                            <p class="text-muted mb-0">ما سفارش ها را جستجو کرده ایم، هیچ احرازی برای
                                                جستجوی شما پیدا نکردیم.</p>
                                        </div>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{$transactions->links('layouts.admin.pagination')}}
                            </div>
                        </div>
                    </div>
                    <!-- Modal: مشاهده عکس احراز هویت (سلفی) -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->

    </div>
</div>

