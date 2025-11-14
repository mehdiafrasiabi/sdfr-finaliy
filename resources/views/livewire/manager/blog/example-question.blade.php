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
                    <h4 class="card-title mb-0 flex-grow-1">مدیرت کشور ها</h4>

                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted">
                        در این قسمت میتوانید کشور هایی که با مجموعه قرار دارند اضافه یا حذف نمود
                    </p>
                    <div class="live-preview">
                        <form wire:submit="save">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">نام :</label>
                                        <sup style="color: red">*</sup>

                                        <input type="text" wire:model="name" name="name" class="form-control"
                                               placeholder="مثلا:ازمون نهایی دی ماه" id="name">
                                        @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">انتخاب پایه:</label>
                                        <sup style="color: red">*</sup>

                                        <select wire:model="category_id" class="form-select">
                                            <option value="">انتخاب دسته‌بندی</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                    </div>
                                  <div class="mb-3">
                                      <div class="field-wrapper  mb-4" x-data="{isUploading:false,progress:0 }"
                                           x-on:livewire-upload-start="isUploading=true"
                                           x-on:livewire-upload-finish="isUploading=false"
                                           x-on:livewire-upload-error="isUploading=false"
                                           x-on:livewire-upload-progress="progress=$event.detail.progress">

                                          <label for="photo" class="form-label">بک گراند :</label>
                                          <input type="file" class="form-control" id="photo" wire:model="photo" name="photo" accept="image/*"
                                                 placeholder="">

                                          <div x-show="isUploading" class="progress mt-3 ltr">
                                              <div class="progress-bar progress-bar-striped  bg-danger progress-bar-animated"
                                                   role="progressbar" x-bind:style="`width:${progress}%`" aria-valuenow="10"
                                                   aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>

                                      </div>
                                      @error('photo') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                  </div>
                                  <div class="mb-3">
                                      <div class="field-wrapper  mb-4" x-data="{isUploading:false,progress:0 }"
                                           x-on:livewire-upload-start="isUploading=true"
                                           x-on:livewire-upload-finish="isUploading=false"
                                           x-on:livewire-upload-error="isUploading=false"
                                           x-on:livewire-upload-progress="progress=$event.detail.progress">

                                          <label for="file" class="form-label">فایل سوالات :</label>
                                          <input type="file" class="form-control" id="file" wire:model="file" name="file" accept=".pdf,.zip"
                                                 placeholder="">

                                          <div x-show="isUploading" class="progress mt-3 ltr">
                                              <div class="progress-bar progress-bar-striped  bg-danger progress-bar-animated"
                                                   role="progressbar" x-bind:style="`width:${progress}%`" aria-valuenow="10"
                                                   aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>

                                      </div>
                                      @error('file') <span class="text-danger text-sm">{{ $message }}</span> @enderror
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
                        <h5 class="card-title mb-0">لیست نمونه سوالات  </h5>
                    </div>
                    <div class="card-body">
                        <div id="alternative-pagination_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row g-4 mb-3">
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input wire:model.live.debounce.500ms="search" type="text"
                                                   class="form-control search" placeholder="جستجو بر اساس نام کشور">
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
                                                aria-label="نام درگاه دریافتی: activate to sort column ascending">نام
                                                کشور
                                            </th>

                                            <th tabindex="0" aria-controls="alternative-pagination"
                                                rowspan="1" colspan="1" style="width: 148.661px;"
                                                aria-label="اقدام: activate to sort column ascending">پایه
                                            </th>
                                            <th tabindex="0" aria-controls="alternative-pagination"
                                                rowspan="1" colspan="1" style="width: 148.661px;"
                                                aria-label="اقدام: activate to sort column ascending">فایل دانلودی
                                            </th>
                                            <th tabindex="0" aria-controls="alternative-pagination"
                                                rowspan="1" colspan="1" style="width: 148.661px;"
                                                aria-label="اقدام: activate to sort column ascending">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($examQuestions as $examQuestion)
                                            <tr class="odd">
                                                <td class="dtr-control " tabindex="0">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="dtr-control " tabindex="0">
                                                    {{ $examQuestion->name }}
                                                </td>
                                                <td class="dtr-control " tabindex="0">
                                                    {{ $examQuestion->examCategory->name }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center fw-medium">
                                                        <a href="{{asset( $examQuestion->file_path)}}"
                                                           download="{{asset( $examQuestion->file_path)}}"
                                                           target="_blank"
                                                           class="currency_name">دانلود</a>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button
                                                        wire:confirm="آیا از انتخاب خود برای حدف ارز اطمینان دارید؟"

                                                        wire:click="delete({{$examQuestion->id}})"
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
                                    {{$examQuestions->links('layouts.manager.pagination')}}

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


                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div>

    </div>

</div>
