<div>

    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">ویرایش استوری</h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">پنل مدیریت</a></li>

                        <li class="breadcrumb-item"><a href="{{ route('manager.story') }}">استوری ها</a></li>

                        <li class="breadcrumb-item active">ویرایش استوری</li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <!-- Form Column -->

        <div class="col-lg-7">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="card-title mb-0">ویرایش اطلاعات استوری</h5>

                    <div class="form-check form-switch">

                        <input class="form-check-input" type="checkbox" role="switch"

                               wire:model.live="status" id="statusSwitch">

                        <label class="form-check-label" for="statusSwitch">

                            {{ $status ? 'فعال' : 'غیرفعال' }}

                        </label>

                    </div>

                </div>

                <div class="card-body">

                    <form wire:submit="submit">

                        <!-- Title -->

                        <div class="mb-3">

                            <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>

                            <input type="text" class="form-control @error('title') is-invalid @enderror"

                                   id="title" wire:model.live="title" placeholder="عنوان استوری را وارد کنید">

                            @error('title')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- Cover Image -->

                        <div class="mb-3">

                            <label class="form-label">

                                تصویر کاور

                                <small class="text-muted">(بصورت مربع و سایز 100 × 100 باشد)</small>

                            </label>


                            <!-- Current Thumbnail -->

                            @if($existingThumbnail && !$previewThumbnail)

                                <div class="mb-2">

                                    <img src="{{ $existingThumbnail }}" alt="Current Thumbnail"

                                         class="rounded-circle"
                                         style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #198754;">

                                    <span class="text-muted small ms-2">تصویر فعلی</span>

                                </div>

                            @endif


                            <div x-data="{ isUploading: false, progress: 0 }"

                                 x-on:livewire-upload-start="isUploading = true"

                                 x-on:livewire-upload-finish="isUploading = false"

                                 x-on:livewire-upload-error="isUploading = false"

                                 x-on:livewire-upload-progress="progress = $event.detail.progress">


                                <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="document.getElementById('thumbnailInput').click()">

                                    <i class="ri-upload-2-line me-1"></i> تغییر تصویر

                                </button>

                                <input type="file" id="thumbnailInput" wire:model="thumbnail" class="d-none"
                                       accept="image/*">


                                <div x-show="isUploading" class="progress mt-2" style="height: 5px;">

                                    <div class="progress-bar bg-primary" role="progressbar"
                                         x-bind:style="'width: ' + progress + '%'"></div>

                                </div>

                            </div>

                            @error('thumbnail')

                            <div class="text-danger mt-1 small">{{ $message }}</div>

                            @enderror


                            <div class="alert alert-light mt-2 mb-0 small">

                                <i class="ri-information-line me-1"></i>

                                فرمت های مجاز: JPG, PNG, WEBP, GIF, SVG (حداکثر 50 مگابایت)

                            </div>

                        </div>


                        <!-- Story Type -->

                        <div class="mb-3">

                            <label class="form-label">نوع استوری <span class="text-danger">*</span></label>

                            <select class="form-select @error('type') is-invalid @enderror" wire:model.live="type">

                                <option value="video">ویدیویی</option>

                                <option value="image">تصویری</option>

                            </select>

                            @error('type')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- Story Content based on type -->

                        @if($type === 'video')

                            <div class="mb-3">

                                <label for="storyVideoUrl" class="form-label">

                                    آدرس مستقیم ویدیو <span class="text-danger">*</span>

                                </label>

                                <input type="url" class="form-control @error('storyVideoUrl') is-invalid @enderror"

                                       id="storyVideoUrl" wire:model.live="storyVideoUrl"

                                       placeholder="http://127.0.0.1:8000/stories/story/example.mp4">

                                @error('storyVideoUrl')

                                <div class="invalid-feedback">{{ $message }}</div>

                                @enderror

                                <div class="form-text">

                                    از طریق کتابخانه مدیا و یا آپلود سنتر اختصاصی خود ویدیو را بارگذاری کرده و لینک
                                    مستقیم آن را در فیلد بالا قرار دهید.

                                </div>

                            </div>

                        @else

                            <div class="mb-3">

                                <label class="form-label">تصویر استوری</label>


                                <!-- Current Story Image -->

                                @if($story->type === 'image' && $existingStory && !$previewStoryImage)

                                    <div class="mb-2">

                                        <img src="{{ $existingStory }}" alt="Current Story"

                                             style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px;">

                                        <span class="text-muted small ms-2">تصویر فعلی</span>

                                    </div>

                                @endif


                                <div x-data="{ isUploading: false, progress: 0 }"

                                     x-on:livewire-upload-start="isUploading = true"

                                     x-on:livewire-upload-finish="isUploading = false"

                                     x-on:livewire-upload-error="isUploading = false"

                                     x-on:livewire-upload-progress="progress = $event.detail.progress">


                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="document.getElementById('storyImageInput').click()">

                                        <i class="ri-upload-2-line me-1"></i>

                                        {{ $story->type === 'image' ? 'تغییر تصویر' : 'انتخاب تصویر' }}

                                    </button>

                                    <input type="file" id="storyImageInput" wire:model="storyImage" class="d-none"
                                           accept="image/*">


                                    <div x-show="isUploading" class="progress mt-2" style="height: 5px;">

                                        <div class="progress-bar bg-primary" role="progressbar"
                                             x-bind:style="'width: ' + progress + '%'"></div>

                                    </div>

                                </div>

                                @error('storyImage')

                                <div class="text-danger mt-1 small">{{ $message }}</div>

                                @enderror

                            </div>

                        @endif



                        <!-- Expiry Date -->

                        <div class="mb-3">

                            <label for="expiresAt" class="form-label">تاریخ انقضا <span
                                    class="text-danger">*</span></label>

                            <input type="date" class="form-control @error('expiresAt') is-invalid @enderror"

                                   id="expiresAt" wire:model.live="expiresAt">

                            @error('expiresAt')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- Widgets (Optional) -->

                        <div class="card bg-light border-0 mb-3">

                            <div class="card-body">

                                <h6 class="card-title mb-3">ویجت ها <span class="text-muted">(اختیاری)</span></h6>

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label for="widgetTitle" class="form-label">عنوان</label>

                                        <input type="text" class="form-control" id="widgetTitle"

                                               wire:model.live="widgetTitle" placeholder="مثال: ماشین های لوکس خارجی">

                                    </div>

                                    <div class="col-md-6">

                                        <label for="widgetLink" class="form-label">لینک</label>

                                        <input type="url" class="form-control" id="widgetLink"

                                               wire:model.live="widgetLink" placeholder="http://example.com/...">

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="d-flex justify-content-between">

                            <a href="{{ route('manager.story') }}" class="btn btn-light">

                                <i class="ri-arrow-right-line me-1"></i> بازگشت

                            </a>

                            <button type="submit" class="btn btn-success">

                                <span wire:loading.remove wire:target="submit">

                                    <i class="ri-check-line me-1"></i> ذخیره تغییرات

                                </span>

                                <span wire:loading wire:target="submit">

                                    <span class="spinner-border spinner-border-sm me-1"></span> در حال ذخیره...

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        <!-- Preview Column -->

        <div class="col-lg-5">

            <div class="card sticky-top" style="top: 20px;">

                <div class="card-header">

                    <h5 class="card-title mb-0">پیش نمایش زنده</h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-center">

                        <div class="story-preview"
                             style="width: 280px; height: 500px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 20px; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">

                            <!-- Progress Bar -->

                            <div
                                style="position: absolute; top: 10px; left: 10px; right: 10px; height: 3px; background: rgba(255,255,255,0.3); border-radius: 2px;">

                                <div style="width: 30%; height: 100%; background: #4CAF50; border-radius: 2px;"></div>

                            </div>


                            <!-- Header -->

                            <div
                                style="position: absolute; top: 20px; right: 15px; left: 15px; display: flex; align-items: center; gap: 10px;">

                                <!-- Close & Pause buttons -->

                                <div style="display: flex; gap: 8px;">

                                    <div
                                        style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">

                                        <i class="ri-close-line text-white" style="font-size: 14px;"></i>

                                    </div>

                                    <div
                                        style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">

                                        <i class="ri-pause-line text-white" style="font-size: 14px;"></i>

                                    </div>

                                </div>


                                <div style="flex: 1;"></div>


                                <!-- User Info -->

                                <div style="display: flex; align-items: center; gap: 8px;">

                                    <div>

                                        <div class="text-white text-end"
                                             style="font-size: 12px; font-weight: 600;">{{ $title ?: 'عنوان استوری' }}</div>

                                        <div class="text-white-50 text-end" style="font-size: 10px;">۱۰ دقیقه قبل</div>

                                    </div>

                                    @if($previewThumbnail)

                                        <img src="{{ $previewThumbnail }}"
                                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4CAF50;">

                                    @elseif($existingThumbnail)

                                        <img src="{{ $existingThumbnail }}"
                                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4CAF50;">

                                    @else

                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background: #4CAF50; display: flex; align-items: center; justify-content: center;">

                                            <i class="ri-user-line text-white"></i>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            <!-- Main Content Area -->

                            <div
                                style="position: absolute; top: 70px; bottom: 80px; left: 0; right: 0; display: flex; align-items: center; justify-content: center;">

                                @if($type === 'video' && $storyVideoUrl)

                                    <video style="max-width: 100%; max-height: 100%; object-fit: contain;" muted>

                                        <source src="{{ $storyVideoUrl }}" type="video/mp4">

                                    </video>

                                @elseif($type === 'image' && $previewStoryImage)

                                    <img src="{{ $previewStoryImage }}"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">

                                @elseif($type === 'image' && $story->type === 'image' && $existingStory)

                                    <img src="{{ $existingStory }}"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">

                                @else

                                    <div class="text-white-50 text-center">

                                        <i class="ri-image-line" style="font-size: 48px;"></i>

                                        <p style="font-size: 12px; margin-top: 10px;">محتوای استوری اینجا نمایش داده
                                            می‌شود</p>

                                    </div>

                                @endif

                            </div>


                            <!-- Like Button -->

                            <div style="position: absolute; bottom: 80px; left: 15px;">

                                <div style="display: flex; flex-direction: column; align-items: center;">

                                    <i class="ri-heart-line text-white" style="font-size: 24px;"></i>

                                    <span class="text-white" style="font-size: 10px;">{{ $story->likes_count }}</span>

                                </div>

                            </div>


                            <!-- Sound Button -->

                            <div style="position: absolute; bottom: 80px; left: 50px;">

                                <i class="ri-volume-up-line text-white" style="font-size: 24px;"></i>

                            </div>


                            <!-- Widget Link -->

                            @if($widgetTitle)

                                <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);">

                                    <div
                                        style="background: rgba(255,255,255,0.9); padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">

                                        <i class="ri-link" style="font-size: 14px;"></i>

                                        <span style="font-size: 12px; font-weight: 500;">{{ $widgetTitle }}</span>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>


                    <div class="text-center mt-3">

                        <small class="text-muted">

                            <i class="ri-information-line me-1"></i>

                            اندازه مدیا بهتر است نسبت 420 × 740 را رعایت نمایید

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
