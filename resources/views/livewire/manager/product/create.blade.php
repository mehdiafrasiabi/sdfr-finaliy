<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">ایجاد محصول</h4>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <form  autocomplete="off" class="needs-validation"  wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">نام  محصول</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{@$product->name}}" wire:model.live.debounce.350ms="name">
                            @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">اسلاگ</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{@$product->seo->slug}}" wire:model="slug">
                            @error('slug') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">سئو</h4>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">عنوان متا</label>
                            <input type="text" class="form-control" name="meta_title"  value="{{@$product->seo->meta_title}}" id="meta_title">
                            @error('meta_title') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="product-title-input">توضیحات متا</label>
                            <textarea type="text" class="form-control" id="meta_description" name="meta_description" wire:model="meta_description">
                               {{@$product->seo->meta_description}}
                            </textarea>
                            @error('meta_description') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                        </div>
                    </div>
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">گالری محصولات</h5>
                    </div>
                    <div class="card-body">
                        <div>

                            <div class="widget-content widget-content-area ecommerce-create-section mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="product-images">آپلود تصاویر محصول </label>
                                        <sup style="color: red">*</sup>
                                        <div class="multiple-file-upload">
                                            <div class="field-wrapper" x-data="{isUploading:false,progress:0 }"
                                                 x-on:livewire-upload-start="isUploading=true"
                                                 x-on:livewire-upload-finish="isUploading=false"
                                                 x-on:livewire-upload-error="isUploading=false"
                                                 x-on:livewire-upload-progress="progress=$event.detail.progress">

                                                <input class="form-control" type="file" wire:model="photos" multiple>

                                                <div x-show="isUploading" class="progress mt-3 ltr">
                                                    <div class="progress-md progress-bar-striped  bg-success progress-bar-animated"
                                                         role="progressbar" x-bind:style="`width:${progress}%`" aria-valuenow="10"
                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>

                                            </div>
                                            @error('coverIndex')
                                            <div class="alert alert-light-danger alert-dismissible fade show border-0 mt-2" role="alert"
                                                 wire:loading.remove
                                                 wire:loading.remove>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round" class="feather feather-x">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                                <strong>خطا !</strong> <br>
                                                {{$message}}
                                            </div>
                                            @enderror
                                            @error('photos.*')
                                            <div class="alert alert-light-danger alert-dismissible fade show border-0 mt-2" role="alert"
                                                 wire:loading.remove
                                                 wire:loading.remove>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round" class="feather feather-x">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                                <strong>خطا !</strong> <br>
                                                {{$message}}
                                            </div>
                                            @enderror
                                            <div class="d-flex flex-wrap">
                                                @foreach($photos as $index=>$photo)
                                                    @if(in_array($photo->getMimeType(),['image/jpeg',  'image/png', 'image/jpg', 'image/gif', 'image/webp']))
                                                        <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 m-3">
                                                            <div class="card style-6">
                                                                <span class="badge badge-danger"></span>
                                                                <img src="{{$photo->temporaryUrl()}}" class="card-img-top" alt="...">
                                                                <div class="card-footer">
                                                                    <div class="row">
                                                                        <div class="col-6 text-start">
                                                                            <input type="radio" id="cover_image" class="form-check-input"
                                                                                   {{$index==$coverIndex ? 'checked' : ''}}
                                                                                   wire:click="setCoverImage({{$index}})"
                                                                                   style="cursor: pointer"
                                                                                   name="cover_image">
                                                                            <label for="cover_image" class="text-white"> کاور</label>

                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <div class="pricing d-flex justify-content-end">
                                                                                <a href="javascript:void(0);" class="text-danger  mb-0"
                                                                                   wire:click="removePhoto({{$index}})">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                                         stroke-width="2" stroke-linecap="round"
                                                                                         stroke-linejoin="round" class="feather feather-trash-2">
                                                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                                                        <path
                                                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                                    </svg>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                @endforeach


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content widget-content-area ecommerce-create-section mt-3">
                                @if(@$product->image)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="product-images">گالری تصاویر محصول </label>
                                            <sup style="color: red">*</sup>
                                            <div class="multiple-file-upload">
                                                <div class="field-wrapper" x-data="{isUploading:false,progress:0 }"
                                                     x-on:livewire-upload-start="isUploading=true"
                                                     x-on:livewire-upload-finish="isUploading=false"
                                                     x-on:livewire-upload-error="isUploading=false"
                                                     x-on:livewire-upload-progress="progress=$event.detail.progress">

                                                    <div x-show="isUploading" class="progress mt-3 ltr">
                                                        <div class="progress-md progress-bar-striped  bg-success progress-bar-animated"
                                                             role="progressbar" x-bind:style="`width:${progress}%`" aria-valuenow="10"
                                                             aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>

                                                </div>
                                                @error('photos.*')
                                                <div class="alert alert-light-danger alert-dismissible fade show border-0 mt-2" role="alert"
                                                     wire:loading.remove
                                                     wire:loading.remove>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                             stroke-linejoin="round" class="feather feather-x">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                    <strong>خطا !</strong> <br>
                                                    {{$message}}
                                                </div>
                                                @enderror
                                                <div class="d-flex flex-wrap">
                                                    @foreach($product->image as $photo)
                                                        <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 m-3">
                                                            <div class="card style-6">
                                                                <span class="badge badge-danger"></span>
                                                                <img src="/products/{{$product->id}}/photo/{{@$photo->path}}" class="card-img-top"
                                                                     alt="...">
                                                                <div class="card-footer">
                                                                    <div class="row">
                                                                        <div class="col-6 text-start">
                                                                            <input type="radio" id="cover_image" class="form-check-input"
                                                                                   {{@$photo->id==@$product->coverImage->id ? 'checked' : ''}}
                                                                                   wire:confirm="ایا از تغییر خود اطمنیان دارید؟"
                                                                                   wire:click="setOldCoverImage({{$photo}})"
                                                                                   style="cursor: pointer"
                                                                                   name="cover_image">
                                                                            <label for="cover_image" class="text-white"> کاور</label>

                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <div class="pricing d-flex justify-content-end">
                                                                                <a href="javascript:void(0);" class="text-danger  mb-0"
                                                                                   wire:confirm="آیا از انتخاب خود اطمنیان دارید؟"
                                                                                   wire:click="removeOldPhoto({{$photo->id}},{{$product->id}})">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                                         stroke-width="2" stroke-linecap="round"
                                                                                         stroke-linejoin="round" class="feather feather-trash-2">
                                                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                                                        <path
                                                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                                    </svg>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            </div>



                            <!-- end dropzon-preview -->
                        </div>
                    </div>
                </div>
                <!-- end card -->


                <!-- end card -->
                <div class="text-end mb-3">
                    <div class="text-end">
                        <a class="btn btn-danger" href="{{route('manager.product.index')}}">بازگشت</a>
                        <button type="submit" class="btn btn-success">
                            <span wire:loading.remove>ذخیره</span>
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
            </div>
            <!-- end col -->

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">اطلاعات دوره</h5>
                    </div>
                    <div class="card-body">


                        <div class="mb-3">
                            <div>
                                <label for="title" class="form-label">عنوان محصول</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{@$product->title}}">
                                @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="tag" class="form-label">مدت زمان دوره</label>
                                <input type="text" class="form-control" id="course_time" name="course_time" value="{{@$product->course_time}}">
                                @error('course_time') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="tag" class="form-label">تعداد جلسات دوره</label>
                                <input type="text" class="form-control" id="meeting_time" name="meeting_time" value="{{@$product->meeting_time}}">
                                @error('meeting_time') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="tag" class="form-label">قیمت</label>
                                <input type="text" class="form-control" id="price" name="price" value="{{@$product->price}}">
                                @error('price') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع دوره</label>
                            <select class="form-select" id="type" name="type" wire:model="type">
                                <option value="">انتخاب کنید</option>
                                <option value="weekly">هفتگی</option>
                                <option value="monthly">ماهانه</option>
                                <option value="yearly_online">سالانه آنلاین</option>
                                <option value="yearly_offline">سالانه حضوری</option>
                            </select>
                            @error('type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="duration_days" class="form-label">مدت دوره (روز)</label>
                            <input type="number" class="form-control" id="duration_days" name="duration_days" wire:model="duration_days">
                            @error('duration_days') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="checkbox" wire:model="has_supporter" name="has_supporter" class="form-check-input" id="hasSupporter">
                            <label for="hasSupporter" class="form-check-label">پشتیبان دارد</label>
                            @error('has_supporter') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="checkbox" wire:model="has_advisor" name="has_advisor" class="form-check-input" id="hasAdvisor">
                            <label for="hasAdvisor" class="form-check-label">مشاور دارد</label>
                            @error('has_advisor') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">دسته بندی محصولات</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2"> <a href="{{route('manager.category.index')}}" class="float-end text-decoration-underline">اضافه کنید
                                جدید</a>دسته بندی محصول را انتخاب کنید</p>
                        <select class="form-select" id="choices-publish-status-input"  id="categoryId" name="categoryId">
                            @foreach($categories as $category)
                                <option  value="{{@$category->id}}"{{@$category->id==@$product->category_id?'selected':''}}>{{@$category->name}}</option>
                            @endforeach
                        </select>
                        @error('categoryId') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">برچسب های محصول</h5>
                    </div>
                    <div class="card-body">
                        <div class="hstack gap-3 align-items-start">
                            <div class="flex-grow-1">
                                <input class="form-control"  placeholder="برچسب ها را وارد کنید" type="text"  id="tag"  name="tag" value="{{@$product->tag}}">
                                @error('tag') <span class="text-danger text-sm">{{ $message }}</span> @enderror

                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
                <!-- end card -->

            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

    </form>

</div>
