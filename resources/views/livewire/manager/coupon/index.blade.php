<div>
    <div>
        <div class="col-xxl-6">
            @if (session()->has('success'))
                <div class="bg-green text-green p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">مدیریت کد های تخفیفی وبسایت </h4>

                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted">
                        <span class="bg-danger text-white">قبل از اقدام حتما مطالعه شود :</span>

                        <hr class="border-dashed">
                        1-کد تخفیفی شما باید بصورت کارکتر
                        <span class="text-danger">اینگلیسی</span>
                        باشد.
                            <hr>
                            2-اگر
                            <span class="text-danger">  نوع تخفیف</span>
                            <span class="text-success">نقدی</span>

                            باشد باید در
                            <span class="text-danger"> میزان تخفیف</span>
                            حتما میزان تخفیف را باید یک نرخ واحد مانند

                            <span class="text-success"> 500000</span>


                            نوشته شود اگر بصورت
                            <span class="text-danger"> درصدی</span>

                            باشد باید بین
                            <span class="text-success"> 1 تا 99</span>
                            انتخاب شود و سیستم بصورت خودکار درصد را محاسبه
                            خواهد کرد.
                            <hr>
                            3-در
                            <span class="text-danger"> انتخاب کاربر خصوصی</span>
                            اگر گزینه
                            <span class="text-success">برای همه کاربران</span>
                            باشد بصورت خودکار برای همه کاربران مجموعه انتخاب میشود و گزینه
                            <span class="text-danger">نوع کد</span>
                            باید
                            <span class="text-success">عمومی</span>
                            انتخاب شود در غیر اینصورت با
                            <span class="text-danger">انتخاب کاربر</span>
                            باید نوع کد
                            <span class="text-success">شخصی</span>
                            باشد
                    </p>

                    <hr class="border-dashed">
                    <div class="live-preview">
                        <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">کد تخفیف:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="code" name="code" class="form-control"
                                               placeholder="مثلا:SDFR" id="code">
                                        @error('code') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">نوع تخفیف:</label>
                                        <sup style="color: red">*</sup>
                                        <select name="type" wire:model="type" class="form-select mb-3">
                                            <option value="fixed" selected>نقدی</option>
                                            <option value="percent">درصدی</option>
                                        </select>

                                        @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="value" class="form-label">میزان تخفیف مد نظر:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="value" name="value" class="form-control"
                                               placeholder=" هزار تومان یا 5 درصد"
                                               id="value">
                                        @error('value') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="limit" class="form-label">تعداد استفاده از کد :</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="limit" name="limit" class="form-control"
                                               placeholder="55(نفر)"
                                               id="limit">
                                        @error('limit') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_purchase" class="form-label">حداقل میزان خرید :</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="min_purchase" name="min_purchase"
                                               class="form-control"
                                               placeholder="5000000(5میلیون تومان)"
                                               id="min_purchase">
                                        @error('min_purchase') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expires_at" class="form-label">تاریخ انقضا :</label>
                                        <sup style="color: red">*</sup>
                                        <input type="date" wire:model="expires_at" name="expires_at"
                                               class="form-control"
                                               placeholder=""
                                               id="expires_at">
                                        @error('expires_at') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">انتخاب کاربر خصوصی:</label>
                                        <sup style="color: red">*</sup>
                                        <select name="user_id" wire:model="user_id" class="form-select mb-3">
                                            <option value="" selected>برای همه کاربران</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('user_id') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    وضعیت کد:
                                    <sup style="color: red">*</sup>
                                    <div class="mb-3">
                                        <div class="form-check form-check-success form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active"
                                                   id="form-check-radio-success" wire:model="is_active" value="1">
                                            <label class="form-check-label" for="form-check-radio-success">
                                                فعال
                                            </label>
                                        </div>
                                        <div class="form-check form-check-danger form-check-inline">
                                            <input class="form-check-input" type="radio" name="0"
                                                   id="form-check-radio-danger" wire:model="is_active" value="0">
                                            <label class="form-check-label" for="form-check-radio-danger">
                                                غیر فعال
                                            </label>
                                        </div>
                                        @error('is_active') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="is_public" class="form-label">نوع کد :
                                        <sup style="color: red">*</sup>
                                    </label>
                                    <div class="form-check form-check-success form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_public"
                                               id="form-check-radio-success" wire:model="is_public" value="1">
                                        <label class="form-check-label" for="form-check-radio-success">
                                            عمومی
                                        </label>
                                    </div>
                                    <div class="form-check form-check-danger form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_public"
                                               id="form-check-radio-danger" wire:model="is_public" value="0">
                                        <label class="form-check-label" for="form-check-radio-danger">
                                            شخصی
                                        </label>
                                    </div>
                                    @error('is_public') <span
                                        class="text-danger text-sm">{{ $message }}</span> @enderror

                                </div>
                            </div>
                            <!--end col-->


                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <span wire:loading.remove>افزودن</span>
                                        <span wire:loading="">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                                 preserveAspectRatio="xMidYMid" width="30" height="30"
                                                 style="shape-rendering: auto; display: block; background: transparent;"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink"><g><circle
                                                        stroke-linecap="round" fill="none"
                                                        stroke-dasharray="50.26548245743669 50.26548245743669"
                                                        stroke="#ffffff"
                                                        stroke-width="8" r="32" cy="50" cx="50">
                                              <animateTransform values="0 50 50;360 50 50" keyTimes="0;1"
                                                                dur="0.6097560975609756s" repeatCount="indefinite"
                                                                type="rotate"
                                                                attributeName="transform"></animateTransform>
                                            </circle><g></g></g><!-- [ldio] generated by https://loading.io -->
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!--end col-->
                    </div>
                    <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">لیست کد های تخفیف</h5>
                </div>
                <div class="card-body">
                    <div id="alternative-pagination_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input wire:model.live.debounce.500ms="search" type="text"
                                               class="form-control search" placeholder="جستجو بر اساس نام کد">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive table-card mb-4">
                                <table id="alternative-pagination"
                                       class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline"
                                       style="width: 100%;" aria-describedby="alternative-pagination_info">
                                    <thead>
                                    <tr>
                                        <th tabindex="0"
                                            aria-controls="alternative-pagination" rowspan="1" colspan="1"
                                            style="width: 110.722px;" aria-sort="ascending"
                                            aria-label="شماره SR: activate to sort column descending">ردیف
                                        </th>
                                        <th tabindex="0" aria-controls="alternative-pagination"
                                            rowspan="1" colspan="1" style="width: 183.661px;"
                                            aria-label="کدتخفیف: activate to sort column ascending">کدتخفیف
                                        </th>
                                        <th>نوع
                                        </th>
                                        <th>میزان تخفیف
                                        </th>
                                        <th>تعداد
                                        </th>
                                        <th>حداقل خرید کاربر
                                        </th>
                                        <th>تاربخ انقضا
                                        </th>
                                        <th>وضعیت
                                        </th>
                                        <th>کاربر
                                        </th>
                                        <th>نوع کوپن
                                        </th>
                                        <th>اقدام
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($coupons as $coupon)
                                        <tr class="odd">
                                            <td class="dtr-control " tabindex="0">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{ $coupon->code }}</a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{$coupon->type=='fixed'?'نقدی':'درصدی'}}</a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">
                                                        @if($coupon->type=='fixed')
                                                            {{number_format($coupon->value)}}هزار تومان
                                                        @else
                                                            {{$coupon->value}}درصد
                                                        @endif
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{$coupon->limit}}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">  {{number_format($coupon->min_purchase)}}
                                                        تومان
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{jalali($coupon->expires_at)->format('%d %B %Y | h:i')}}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{$coupon->is_active=='1'?'فعال':'غیرفعال'}}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{ $coupon->user ? $coupon->user->name : 'عمومی' }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{$coupon->is_public=='1'?'عمومی':'خصوصی'}}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <button
                                                    wire:confirm="آیا از انتخاب خود برای حدف ارز اطمینان دارید؟"

                                                    wire:click="delete({{$coupon->id}})"
                                                    class="btn btn-sm btn-soft-danger">
                                                    <i class=" ri-delete-bin-6-line"></i>
                                                    حذف
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="noresult" style="display: block;">
                                            <div class="text-center">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                           trigger="loop"
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
                                        <p class="text-muted mb-0">ما همه دپارتمان را جستجو کرده ایم، هیچ
                                            دپارتمان برای
                                            جستجوی شما پیدا نکردیم.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="alternative-pagination_info" role="status"
                                     aria-live="polite">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div>
</div>

</div>
