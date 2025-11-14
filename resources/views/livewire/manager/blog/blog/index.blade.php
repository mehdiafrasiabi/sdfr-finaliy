<div>
    @push('link')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 300px; /* یا هر عرضی که می‌خوای */
            }
        </style>
    @endpush
    <div class="card">
        <div class="card-header border-0">
            <div class="row g-4">
                <div class="col-sm-auto">
                    <div class="search-box ms-2">
                        <select wire:model.live.debounce.350ms="status" class="form-select w-auto">
                            <option value="all">همه وضعیت‌ها</option>
                            <option value="pending">در انتظار تایید</option>
                            <option value="completed">تایید شده</option>
                            <option value="rejected">رد شده</option>
                        </select>

                    </div>
                </div>
                <div class="col-sm">
                    <div class="d-flex justify-content-sm-end">
                        <div class="search-box ms-2">
                            <input type="text" wire:model.live.debounce.350ms="search" name="search" class="form-control" id="searchProductList"
                                   placeholder="جستجوی محصولات براساس نام و کد">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div> <div class="d-flex justify-content-sm-end">

                    </div>
                </div>
                @if(session()->has('success'))

                    <div class="alert alert-icon-left alert-light-success alert-dismissible fade show mb-4" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <svg data-bs-dismiss="alert"> ...</svg>
                        </button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-check-square">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <strong>پیغام !</strong>
                        {{ session()->get('success') }}
                    </div>

                @endif
            </div>
        </div>

        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-body active fw-semibold" data-bs-toggle="tab" href="#productnav-published"
                               role="tab" aria-selected="false" tabindex="-1">منتشر شد<span
                                    class="badge bg-danger-subtle text-danger align-middle rounded-pill ms-1">5</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-body fw-semibold" data-bs-toggle="tab" href="#productnav-draft"
                               role="tab" aria-selected="false" tabindex="-1">پیش نویس</a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto">
                    <div id="selection-element">
                        <div class="my-n1 d-flex align-items-center text-muted">انتخاب کنید
                            <div id="select-content" class="text-body fw-semibold px-1"></div>
                            نتیجه
                            <button type="button" class="btn btn-link link-danger p-0 ms-3" data-bs-toggle="modal"
                                    data-bs-target="#removeItemModal">حذف
                                کنید
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end card header -->
        <div class="card-body">

            <div class="tab-content text-muted">
                <!-- end tab pane -->

                <div class="tab-pane active" id="productnav-published" role="tabpanel">
                    <div id="table-product-list-published" class="table-card gridjs-border-none">
                        <div role="complementary" class="gridjs gridjs-container" style="width: 100%;">
                            <div class="gridjs-wrapper" style="height: auto;">
                                <table role="grid" class="gridjs-table" style="height: auto;">
                                    <thead class="gridjs-thead">
                                    <tr class="gridjs-tr">
                                        <th data-column-id="#" class="gridjs-th gridjs-th-sort text-muted" tabindex="0"
                                            style="width: 40px;">
                                            <div class="gridjs-th-content">#</div>
                                        </th>
                                        <th data-column-id="product" class="gridjs-th gridjs-th-sort text-muted"
                                            tabindex="0" style="width: 360px;">
                                            <div class="gridjs-th-content">محصول</div>

                                        </th>

                                        <th data-column-id="rating" class="gridjs-th gridjs-th-sort text-muted"
                                            tabindex="0" style="width: 105px;">
                                            <div class="gridjs-th-content">دسته بندی</div>

                                        </th>
                                        <th data-column-id="rating" class="gridjs-th gridjs-th-sort text-muted"
                                            tabindex="0" style="width: 105px;">
                                            <div class="gridjs-th-content">وضعیت</div>

                                        </th>
                                        <th data-column-id="action" class="gridjs-th gridjs-th-sort text-muted"
                                            tabindex="0" style="width: 80px;">
                                            <div class="gridjs-th-content"></div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="gridjs-tbody">
                                    @forelse($blogs as $blog)
                                        <tr class="gridjs-tr">
                                            <td data-column-id="#" class="gridjs-td">
                                                {{$loop->iteration + $blogs->firstItem() - 1}}

                                            </td>
                                            <td data-column-id="product" class="gridjs-td">
                                                <span>
                                                    <div
                                                        class="d-flex align-items-center">
                                                        <div
                                                            class="flex-shrink-0 me-3">
                                                            <div
                                                                class="avatar-sm bg-light rounded p-1">
                                                                <img
                                                                    @foreach($blog->images as $image)
                                                                    src="{{ asset('/blogs/'.$blog->id.'/photo/'.$image->path) }}"
                                                                    alt="{{$blog->title}}"
                                                                    class="img-fluid">
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="flex-grow-1"><h5 class="fs-14 mb-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-body"> {{$blog->title}}</a></h5><p
                                                                class="text-muted mb-0">کد محصول : <span
                                                                    class="fw-medium"> {{$blog->blog_code}}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </span>
                                            </td>
                                            <td data-column-id="rating" class="gridjs-td"><span><span
                                                        class="badge bg-light text-body fs-12 fw-medium"><i
                                                            class="mdi mdi-star text-warning me-1"></i>{{@$blog->category->name}}</span></span>
                                            </td>
                                            <td data-column-id="rating" class="gridjs-td"><span><span
                                                        class="badge bg-light text-body fs-12 fw-medium"><i
                                                            class="mdi mdi-star text-primary me-1 "></i>{{@$blog->status}}</span></span>
                                            </td>


                                            <td data-column-id="action" class="gridjs-td"><span><div class="dropdown"><button
                                                            class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="ri-more-fill"></i></button><ul
                                                            class="dropdown-menu dropdown-menu-end">
                                                            <li><a
                                                                    class="dropdown-item"
                                                                    href="{{ route('manager.blog.show', $blog->id) }}">
                                                                    <i
                                                                        class="ri-eye-fill align-bottom me-2 text-muted"></i> مشاهده</a></li>

                                                            <li
                                                                class="dropdown-divider"></li><li><a
                                                                    wire:confirm="آیا مطمئن هستید؟"
                                                                    class="dropdown-item remove-list" href="#"
                                                                    data-id="1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#removeItemModal"><i
                                                                        class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> حذف</a></li></ul></div></span>
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
                            <div class="gridjs-footer">
                                <div class="gridjs-pagination">
                                    {{$blogs->links('layouts.manager.pagination')}}
                                </div>
                            </div>
                            <div id="gridjs-temp" class="gridjs-temp"></div>
                        </div>
                    </div>
                </div>
                <!-- end tab pane -->

                <div class="tab-pane" id="productnav-draft" role="tabpanel">
                    <div class="py-4 text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                   colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px">
                        </lord-icon>
                        <h5 class="mt-4">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                    </div>
                </div>
                <!-- end tab pane -->
            </div>
            <!-- end tab content -->

        </div>
        <!-- end card body -->
    </div>
    <!-- end card -->
</div>
