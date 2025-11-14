<div class="row mb-4 layout-spacing layout-top-spacing">
    <div class="widget-header">
        <div class="d-flex justify-content-between align-items-center">

            <h4>
                {{ $blogId ? 'ویرایش بلاگ' : 'افزودن بلاگ جدید' }}
            </h4>

            <a href="{{route('admin.blog.index')}}" class="btn btn-danger mb-3 me-4">
                بازگشت به جدول بلاگ
            </a>


        </div>
    </div>
    <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">


        <form class="row" wire:submit="submit(Object.fromEntries(new FormData($event.target)))">

            <div class="widget-content widget-content-area ecommerce-create-section">

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label for="title">عنوان بلاگ</label>
                        <sup style="color: red">*</sup>

                        <input type="text" class="form-control" id="title" name="title"
                               wire:model.live.debounce.350ms="title">
                    </div>
                </div>
                @error('title')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label for="slug">اسلاگ</label>
                        <sup style="color: red">*</sup>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{@$product->seo->slug}}"
                               wire:model="slug">
                    </div>
                </div>
                @error('slug')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label for="study_time">مدت زمان مطالعه</label>
                        <sup style="color: red">*</sup>
                        <input type="text" class="form-control" id="study_time" name="study_time"
                               wire:model="study_time">
                    </div>
                </div>
                @error('study_time')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label for="meta_title">عنوان متا</label>
                        <input type="text" class="form-control" name="meta_title" id="meta_title" wire:model="meta_title">
                    </div>
                </div>
                @error('meta_title')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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

                <div class="row mb-4">
                    <div class="col-sm-12">
                        <label for="meta_description">توضیحات متا</label>
                        <textarea class="form-control" id="meta_description" rows="5" name="meta_description" wire:model="meta_description"></textarea>
                    </div>
                </div>
                @error('meta_description')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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
                <div class="row mb-4" wire:ignore>
                    <div class="col-sm-12">
                        <label for="description" class="form-label">توضیحات تخصصی:</label>
                        <sup style="color: red">*</sup>
                        <textarea class="form-control" id="editor" name="description" wire:model="description"></textarea>
                    </div>
                </div>
                @error('description')
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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

            </div>

            <div class="widget-content widget-content-area mt-3">
                <div class="row">
                    <div class="col-md-12">
                        <label for="blog-images">آپلود تصاویر بلاگ</label>
                        <div class="multiple-file-upload">
                            <div class="field-wrapper"
                                 x-data="{isUploading:false,progress:0 }"
                                 x-on:livewire-upload-start="isUploading=true"
                                 x-on:livewire-upload-finish="isUploading=false"
                                 x-on:livewire-upload-error="isUploading=false"
                                 x-on:livewire-upload-progress="progress=$event.detail.progress">

                                <input class="form-control" type="file" wire:model="photos" multiple>

                                {{-- progress --}}
                                <div x-show="isUploading" class="progress mt-3 ltr">
                                    <div class="progress-md progress-bar-striped bg-success progress-bar-animated"
                                         role="progressbar" x-bind:style="`width:${progress}%`"></div>
                                </div>
                            </div>

                            {{-- ارور آپلود --}}
                            @error('photos.*')
                            <div class="alert alert-light-danger mt-2">{{ $message }}</div>
                            @enderror

                            {{-- پیش نمایش عکس‌های جدید --}}
                            <div class="d-flex flex-wrap">
                                @foreach($photos as $index=>$photo)
                                    <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 m-3">
                                        <div class="card style-6">
                                            <img src="{{ $photo->temporaryUrl() }}" class="card-img-top" alt="...">
                                            <div class="card-footer text-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="removePhoto({{ $index }})">
                                                    حذف
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- عکس‌های قدیمی (ذخیره‌شده در دیتابیس) --}}
                            <div class="d-flex flex-wrap mt-4">
                                @foreach($blog->images ?? [] as $photo)
                                    <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 m-3">
                                        <div class="card style-6">
                                            <img src="{{ asset('uploads/blogs/'.$blog->id.'/'.$photo->path) }}"
                                                 class="card-img-top" alt="...">
                                            <div class="card-footer text-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="removeOldPhoto({{ $photo->id }})">
                                                    حذف
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="col-xxl-12 col-xl-8 col-lg-8 col-md-7 mt-xxl-0 mt-4">
            <div class="widget-content widget-content-area ecommerce-create-section">
                <div class="row">
                    <div class="col-xxl-12 col-md-6 mb-4">
                        <label for="category_id">دسته بندی</label>
                        <select class="form-select" id="category_id" name="category_id">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    @error('category_id')
                    <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
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
                    <div class="col-xxl-12 col-xl-4 col-lg-4 col-md-5 mt-4">
                        <div class="widget-content widget-content-area ecommerce-create-section">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-success w-100">

                                        <span wire:loading.remove>
                                            {{ $blogId ? 'ویرایش بلاگ' : 'ثبت بلاگ' }}
                                        </span>
                                        <div class="spinner-border text-white me-2 align-self-center loader-sm "
                                             wire:loading></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </form>
    @push('script')
        <script src="https://cdn.ckeditor.com/4.20.1/full/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('editor', {
                filebrowserUploadUrl: "{{ route('admin.blog.ckeUpload', ['blog' => $blogId ?? 0]) }}?_token={{ csrf_token() }}",
                filebrowserUploadMethod: 'form'
            });

            // برای sync شدن با wire:model
            CKEDITOR.instances.editor.on('change', function () {
            @this.set('description', CKEDITOR.instances.editor.getData());
            });
        </script>
    @endpush
</div>
