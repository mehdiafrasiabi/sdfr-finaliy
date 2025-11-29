<div>
    <div>
        <div class="col-xxl-6">
            @if(session()->has('message'))

                <div class="alert alert-success alert-dismissible alert-additional fade show" role="alert">
                    <div class="alert-body">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-user-smile-line label-icon"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading">ادمین شما با موفقیت ساخته شد!</h5>
                                <p class="mb-0">این رمز را در اختیار هیجکس به جز ادمین مربوطه ندهید !</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert-content">
                        <p class="mb-0">{{ session()->get('message') }} </p>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">مدیریت ادمین و پشتیبان های وبسایت </h4>

                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted">
                        <span class="bg-danger text-white">قبل از اقدام حتما مطالعه شود :</span>

                    <hr class="border-dashed">

                    </p>

                    <hr class="border-dashed">
                    <div class="live-preview">
                        <form  <form
                            wire:submit="{{ $isEditing ? 'update(Object.fromEntries(new FormData($event.target)))' : 'submit(Object.fromEntries(new FormData($event.target)))' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">نام و نام خانوادگی:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="name" name="name" class="form-control"
                                               placeholder="مثلا:مهدی آبان" id="name">
                                        @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">ایمیل:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="email" name="email" class="form-control"
                                               placeholder="مثلا:abbban,poshtibani@gmail.com" id="email">

                                        @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">تلفن همراه:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="mobile" name="mobile" class="form-control"
                                               placeholder="09940682693"
                                               id="mobile">
                                        @error('mobile')
                                        <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="national_code" class="form-label">کد ملی:</label>
                                        <sup style="color: red">*</sup>
                                        <input type="text" wire:model="national_code" name="national_code" class="form-control" id="national_code">
                                        @error('national_code') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label">کد پستی:</label>
                                        <sup style="color: red">*</sup>

                                        <input type="text" wire:model="postal_code" name="postal_code" class="form-control" id="postal_code">
                                        @error('postal_code') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">آدرس محل سکونت:</label>
                                        <sup style="color: red">*</sup>

                                        <textarea wire:model="address" name="address" class="form-control" id="address"></textarea>
                                        @error('address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3"
                                         x-data="{isUploading:false,progress:0 }"
                                         x-on:livewire-upload-start="isUploading=true"
                                         x-on:livewire-upload-finish="isUploading=false"
                                         x-on:livewire-upload-error="isUploading=false"
                                         x-on:livewire-upload-progress="progress=$event.detail.progress">
                                        <label for="document" class="form-label">مدرک تحصیلی (pdf یا تصویر):</label>
                                        <sup style="color: red">*</sup>

                                        <input type="file" wire:model="document" name="document" class="form-control" id="document" accept="application/pdf,image/*">
                                        <div x-show="isUploading" class="progress mt-3 ltr">
                                            <div class="progress-md progress-bar-striped  bg-success progress-bar-animated"
                                                 role="progressbar" x-bind:style="`width:${progress}%`" aria-valuenow="10"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        @error('document') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3"
                                         x-data="{isUploading:false,progress:0 }"
                                         x-on:livewire-upload-start="isUploading=true"
                                         x-on:livewire-upload-finish="isUploading=false"
                                         x-on:livewire-upload-error="isUploading=false"
                                         x-on:livewire-upload-progress="progress=$event.detail.progress">

                                        <label for="contract" class="form-label">آپلود قرارداد طرفین (pdf)</label>
                                        <sup style="color: red">*</sup>

                                        <input type="file" wire:model="contract" name="contract" class="form-control" id="contract" accept="application/pdf/*">
                                        <div x-show="isUploading" class="progress mt-3 ltr">
                                            <div class="progress-md progress-bar-striped  bg-success progress-bar-animated"
                                                 role="progressbar" x-bind:style="`width:${progress}%`" aria-valuenow="10"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        @error('contract') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="selectedRoles" class="form-label">انتخاب نقش:</label>
                                        <sup style="color: red">*</sup>
                                        <select name="selectedRoles" wire:model="selectedRoles" class="form-select mb-3"
                                                multiple>
                                            @foreach($roles as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('selectedRoles') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="selectedPermissions" class="form-label">انتخاب دسترسی:</label>
                                        <sup style="color: red">*</sup>
                                        <select name="selectedPermissions" wire:model="selectedPermissions"
                                                class="form-select mb-3" multiple>
                                            @foreach($permissions as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('selectedPermissions') <span
                                            class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!--end col-->


                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <span wire:loading.remove>{{ $isEditing ? 'بروزرسانی' : 'افزودن' }}</span>
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
                                    @if($isEditing)
                                        <button type="button" class="btn btn-soft-secondary me-2" wire:click="cancelEdit"
                                                wire:loading.attr="disabled">
                                            انصراف
                                        </button>
                                    @endif
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
                    <h5 class="card-title mb-0">لیست ادمین ها و پشتیبانی</h5>
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
                                            aria-label="کدتخفیف: activate to sort column ascending">نام و نام خانوادگی
                                        </th>
                                        <th>
                                            اطلاعات تماس
                                        </th>
                                        <th>
                                            نقش
                                        </th>
                                        <th>
                                            دسترسی
                                        </th>
                                        <th>
                                            اقدام
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($admins as $admin)
                                        <tr class="odd">
                                            <td class="dtr-control " tabindex="0">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">{{ $admin->name }}</a>
                                                </div>
                                            </td>

                                            <td>
                                                {{@$admin->email}}
                                                <br>
                                                {{@$admin->mobile}}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">
                                                        @foreach($admin->roles as $role)
                                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name">
                                                        @foreach($admin->roles as $role)
                                                            @foreach($role->permissions as $permission)
                                                                <div>{{$permission->name}}</div>
                                                            @endforeach
                                                        @endforeach
                                                    </a>
                                                </div>
                                            </td>

                                            <td>
                                                <button
                                                    wire:click="edit({{$admin->id}})"
                                                    class="btn btn-sm btn-soft-danger">
                                                    <i class=" ri-pe-bin-6-line"></i>
                                                    ویرایش
                                                </button>
                                                <button
                                                    wire:confirm="آیا از انتخاب خود برای حدف ارز اطمینان دارید؟"

                                                    wire:click="delete({{$admin->id}})"
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
                                {{$admins->links('layouts.manager.pagination')}}

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
