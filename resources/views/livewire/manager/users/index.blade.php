<div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">لیست مشتریان</h5>
                            </div>
                        </div>
                        <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))" class="tablelist-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">نام مشتری:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" placeholder="نام را وارد کنید" wire:model="name" name="name" class="form-control"
                                                id="name">
                                        @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">تلفن مشتری:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" placeholder="تلفن را وارد کنید" wire:model="mobile" name="mobile" class="form-control"
                                                id="mobile">
                                        @error('mobile') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-success" id="add-btn">
                                        <span >مشتری اضافه کنید</span>
                                    </button>
                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form>
                        <div class="row g-3">
                            <div class="col-xl-6">
                                <div class="search-box">
                                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control search"
                                           placeholder="جستجو بر اساس نام یا تلفن همراه یا ایمیل...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-1">
                            <table class="table align-middle" id="customerTable">
                                <thead class="table-light text-muted">
                                <tr>

                                    <th>#</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th >تلفن</th>
                                    <th>ایمیل</th>
                                    <th>تاریخ عضویت</th>
                                    <th>اقدام</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($users as $user)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                {{$loop->iteration + $users->firstItem() - 1}}

                                            </div>
                                        </th>
                                        <td class="customer_name">{{$user->name ?? '--'}}</td>
                                        <td class="phone">{{$user->mobile ?? '--'}}</td>
                                        <td class="email">{{$user->email ?? '--'}}</td>
                                        <td class="date">{{jalali($user->created_at)->format('%d %B %Y | H:i')}}</td>
                                        <td class="date">
                                            <a href="{{ route('manager.user.detail',$user->id) }}">
                                                مشاهده پروفایل
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                       colors="primary:#121331,secondary:#08a88a"
                                                       style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                            <p class="text-muted mb-0">ما بیش از 150 مشتری را جست&zwnj;وجو کرده&zwnj;ایم. هیچ
                                                مشتری برای جستجوی شما پیدا نکردیم.</p>
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
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{$users->links('layouts.admin.pagination')}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="showModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light p-3">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                        </div>
                        <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))" class="tablelist-form" autocomplete="off">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="customername-field" class="form-label">نام مشتری</label>
                                    <input type="text" id="customername-field" name="name" class="form-control" placeholder="نام را وارد کنید" wire:model="name">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>



                                <div class="mb-3">
                                    <label for="phone-field" class="form-label">تلفن</label>
                                    <input type="tel" maxlength="11" id="phone-field" class="form-control" name="mobile" placeholder="شماره تلفن را وارد کنید" wire:model="mobile">
                                    @error('mobile') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>


                            </div>
                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                                    <button type="submit" class="btn btn-success" id="add-btn">
                                        <span >مشتری اضافه کنید</span>
                                    </button>
                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!--end col-->
    </div>
    @push('script')
        <script src="/admin/assets/js/pages/ecommerce-customer-list.init.js"></script>
    @endpush

</div>
