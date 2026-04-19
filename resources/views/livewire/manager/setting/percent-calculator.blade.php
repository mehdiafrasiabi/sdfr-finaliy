<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">تنظیمات درصد گیر</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form autocomplete="off" wire:submit="save">
        <div class="row">
            <div class="col-lg-8">

                {{-- عنوان و زیر عنوان --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">اطلاعات اصلی</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">عنوان <sup style="color:red">*</sup></label>
                            <input type="text" class="form-control" wire:model="title">
                            @error('title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">زیر عنوان</label>
                            <input type="text" class="form-control" wire:model="subtitle">
                            @error('subtitle') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- توضیحات با CKEditor --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">توضیحات</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3" wire:ignore>
                            <label class="form-label">متن توضیحات</label>
                            <textarea class="form-control" id="percent_description" wire:model="description" name="description"></textarea>
                        </div>
                        @error('description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- سئو --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">سئو</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">عنوان متا</label>
                            <input type="text" class="form-control" wire:model="meta_title">
                            @error('meta_title') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات متا</label>
                            <textarea class="form-control" rows="3" wire:model="meta_description"></textarea>
                            @error('meta_description') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">

                {{-- آپلود تصویر --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">تصویر بنر</h5>
                    </div>
                    <div class="card-body">

                        @if($existingImage)
                            <div class="mb-3">
                                <img src="{{ asset('percent-calculator/' . $existingImage) }}"
                                     class="img-fluid rounded mb-2" alt="تصویر فعلی">
                                <button type="button" class="btn btn-danger btn-sm w-100"
                                        wire:click="removeExistingImage"
                                        wire:confirm="آیا از حذف تصویر مطمئن هستید؟">
                                    <i class="ri-delete-bin-line me-1"></i> حذف تصویر
                                </button>
                            </div>
                        @endif

                        <div x-data="{isUploading:false, progress:0}"
                             x-on:livewire-upload-start="isUploading=true"
                             x-on:livewire-upload-finish="isUploading=false"
                             x-on:livewire-upload-error="isUploading=false"
                             x-on:livewire-upload-progress="progress=$event.detail.progress">

                            <label class="form-label">{{ $existingImage ? 'تغییر تصویر' : 'آپلود تصویر' }}</label>
                            <input type="file" class="form-control" wire:model="photo" accept="image/*">

                            <div x-show="isUploading" class="progress mt-2 ltr">
                                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                     role="progressbar"
                                     x-bind:style="`width:${progress}%`"
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        @if($photo)
                            <div class="mt-2">
                                <img src="{{ $photo->temporaryUrl() }}" class="img-fluid rounded" alt="پیش‌نمایش">
                            </div>
                        @endif

                        @error('photo')
                        <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- دکمه ذخیره --}}
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success w-100 _effect--ripple waves-effect waves-light"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="ri-save-line me-1"></i> ذخیره تنظیمات
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                در حال ذخیره...
                            </span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    @push('script')
        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                const editor = CKEDITOR.replace('percent_description', {
                    filebrowserUploadUrl: "{{ route('manager.setting.percent-calculator.ck-upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form',
                    contentsLangDirection: 'rtl',
                    height: 500,
                });

                editor.on('change', function (event) {
                @this.set('description', event.editor.getData());
                });

                Livewire.on('success', (message) => {
                    editor.setData(@this.get('description'));
                });
            });
        </script>
    @endpush
</div>

