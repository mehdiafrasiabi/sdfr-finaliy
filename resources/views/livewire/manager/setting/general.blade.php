<div>

    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">تنظیمات کلی</h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item"><a href="javascript: void(0);">تنظیمات</a></li>

                        <li class="breadcrumb-item active">تنظیمات کلی</li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">

                        <li class="nav-item">

                            <a class="nav-link {{ $activeTab === 'main' ? 'active' : '' }}"

                               wire:click.prevent="setTab('main')" href="#">

                                <i class="ri-store-2-line me-1"></i> اصلی

                            </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link {{ $activeTab === 'panel' ? 'active' : '' }}"

                               wire:click.prevent="setTab('panel')" href="#">

                                <i class="ri-lock-2-line me-1"></i> بستن پنل

                            </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link {{ $activeTab === 'advisor' ? 'active' : '' }}"

                               wire:click.prevent="setTab('advisor')" href="#">

                                <i class="ri-user-star-line me-1"></i> مشاوران

                            </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link text-muted disabled" href="#">

                                <i class="ri-image-line me-1"></i> مدیا

                            </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link text-muted disabled" href="#">

                                <i class="ri-bank-card-line me-1"></i> درگاه پرداخت

                            </a>

                        </li>

                    </ul>

                </div>


                <div class="card-body">

                    @if($activeTab === 'main')

                        <div class="row">

                            <!-- بخش اطلاعات اصلی فروشگاه -->

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-primary">

                                    <div class="card-header bg-primary-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-information-line fs-18 text-primary me-2"></i>

                                            <h5 class="card-title mb-0 text-primary">اطلاعات اصلی فروشگاه</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>فیلدهای 1، 2 و 3 برای SEO وبسایت استفاده می‌شوند و بقیه در فوتر نمایش
                                                داده می‌شوند.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="saveMainInfo">

                                            <div class="row g-3">

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        عنوان کامل فروشگاه

                                                        <span class="badge bg-info-subtle text-info ms-1">SEO</span>

                                                    </label>

                                                    <input type="text" class="form-control" wire:model="site_title"

                                                           placeholder="مثال: فروشگاه اینترنتی SDFR">

                                                    @error('site_title')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        عنوان کوتاه فروشگاه

                                                        <span class="badge bg-info-subtle text-info ms-1">SEO</span>

                                                    </label>

                                                    <input type="text" class="form-control"
                                                           wire:model="site_short_title"

                                                           placeholder="مثال: SDFR">

                                                    @error('site_short_title')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        عنوان تگ H1 صفحه نخست

                                                        <span class="badge bg-info-subtle text-info ms-1">SEO</span>

                                                    </label>

                                                    <input type="text" class="form-control" wire:model="h1_tag"

                                                           placeholder="مثال: بهترین سامانه مشاوره تحصیلی">

                                                    @error('h1_tag')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">ساعت پاسخگویی</label>

                                                    <input type="text" class="form-control" wire:model="support_hours"

                                                           placeholder="مثال: ۰۹:۰۰ - ۱۷:۰۰">

                                                    @error('support_hours')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">تلفن پشتیبانی</label>

                                                    <input type="text" class="form-control" wire:model="support_phone"

                                                           placeholder="مثال: 051-35092160">

                                                    @error('support_phone')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-12">

                                                    <label class="form-label">توضیحات فروشگاه</label>

                                                    <textarea class="form-control" wire:model="site_description"
                                                              rows="3"

                                                              placeholder="توضیحات مختصر درباره فروشگاه..."></textarea>

                                                    @error('site_description')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-primary">

                                                            <span wire:loading.remove wire:target="saveMainInfo">

                                                                <i class="ri-save-line me-1"></i> ذخیره اطلاعات اصلی

                                                            </span>

                                                            <span wire:loading wire:target="saveMainInfo">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            <!-- بخش تگ‌ها و اسکریپت‌های هدر و فوتر -->

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-warning">

                                    <div class="card-header bg-warning-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-code-s-slash-line fs-18 text-warning me-2"></i>

                                            <h5 class="card-title mb-0 text-warning">تگ‌ها و اسکریپت‌های هدر و فوتر</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>کدهای head در بخش &lt;head&gt; و کدهای فوتر بعد از تگ &lt;body&gt;
                                                قرار می‌گیرند.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="saveScripts">

                                            <div class="row g-3">

                                                <div class="col-md-6">

                                                    <label class="form-label">کدهای Head</label>

                                                    <textarea class="form-control font-monospace"
                                                              wire:model="head_scripts" rows="6"

                                                              placeholder="<script>...</script>" dir="ltr"></textarea>

                                                    @error('head_scripts')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">کدهای فوتر</label>

                                                    <textarea class="form-control font-monospace"
                                                              wire:model="footer_scripts" rows="6"

                                                              placeholder="<script>...</script>" dir="ltr"></textarea>

                                                    @error('footer_scripts')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-warning">

                                                            <span wire:loading.remove wire:target="saveScripts">

                                                                <i class="ri-save-line me-1"></i> ذخیره اسکریپت‌ها

                                                            </span>

                                                            <span wire:loading wire:target="saveScripts">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            <!-- بخش کد اسکریپت مجوزها -->

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-success">

                                    <div class="card-header bg-success-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-shield-check-line fs-18 text-success me-2"></i>

                                            <h5 class="card-title mb-0 text-success">کد اسکریپت مجوزها</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>مجوزهای وبسایت در بخش فوتر نمایش داده می‌شوند.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="saveLicenses">

                                            <div class="row g-3">

                                                <div class="col-md-4">

                                                    <label class="form-label">

                                                        <i class="ri-verified-badge-line text-primary me-1"></i>

                                                        اسکریپت اینماد

                                                    </label>

                                                    <textarea class="form-control font-monospace"
                                                              wire:model="enamad_script" rows="5"

                                                              placeholder="کد اینماد..." dir="ltr"></textarea>

                                                    @error('enamad_script')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-4">

                                                    <label class="form-label">

                                                        <i class="ri-government-line text-success me-1"></i>

                                                        اسکریپت ساماندهی

                                                    </label>

                                                    <textarea class="form-control font-monospace"
                                                              wire:model="samandehi_script" rows="5"

                                                              placeholder="کد ساماندهی..." dir="ltr"></textarea>

                                                    @error('samandehi_script')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-4">

                                                    <label class="form-label">

                                                        <i class="ri-building-line text-warning me-1"></i>

                                                        اسکریپت اتحادیه

                                                    </label>

                                                    <textarea class="form-control font-monospace"
                                                              wire:model="etehaddiye_script" rows="5"

                                                              placeholder="کد اتحادیه..." dir="ltr"></textarea>

                                                    @error('etehaddiye_script')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-success">

                                                            <span wire:loading.remove wire:target="saveLicenses">

                                                                <i class="ri-save-line me-1"></i> ذخیره مجوزها

                                                            </span>

                                                            <span wire:loading wire:target="saveLicenses">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            <!-- بخش لینک شبکه‌های اجتماعی -->

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-info">

                                    <div class="card-header bg-info-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-share-line fs-18 text-info me-2"></i>

                                            <h5 class="card-title mb-0 text-info">لینک شبکه‌های اجتماعی</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>این لینک‌ها در بخش شبکه‌های اجتماعی فوتر نمایش داده می‌شوند.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="saveSocialLinks">

                                            <div class="row g-3">

                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-instagram-line text-danger me-1"></i>

                                                        اینستاگرام

                                                    </label>

                                                    <input type="url" class="form-control" wire:model="instagram"

                                                           placeholder="https://instagram.com/username" dir="ltr">

                                                    @error('instagram')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-telegram-line text-primary me-1"></i>

                                                        تلگرام

                                                    </label>

                                                    <input type="url" class="form-control" wire:model="telegram"

                                                           placeholder="https://t.me/username" dir="ltr">

                                                    @error('telegram')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-youtube-line text-danger me-1"></i>

                                                        یوتیوب

                                                    </label>

                                                    <input type="url" class="form-control" wire:model="youtube"

                                                           placeholder="https://youtube.com/channel/..." dir="ltr">

                                                    @error('youtube')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-play-circle-line text-warning me-1"></i>

                                                        آپارات

                                                    </label>

                                                    <input type="url" class="form-control" wire:model="aparat"

                                                           placeholder="https://aparat.com/username" dir="ltr">

                                                    @error('aparat')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-info">

                                                            <span wire:loading.remove wire:target="saveSocialLinks">

                                                                <i class="ri-save-line me-1"></i> ذخیره شبکه‌های اجتماعی

                                                            </span>

                                                            <span wire:loading wire:target="saveSocialLinks">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            <!-- بخش دکمه شناور پشتیبانی -->

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-secondary">

                                    <div class="card-header bg-secondary-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-customer-service-2-line fs-18 text-secondary me-2"></i>

                                            <h5 class="card-title mb-0 text-secondary">دکمه شناور پشتیبانی</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>این دکمه در گوشه سمت راست پایین صفحه نمایش داده می‌شود.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="saveFloatingSupport">

                                            <div class="row g-3">

                                                <div class="col-12">

                                                    <div class="form-check form-switch form-switch-lg">

                                                        <input class="form-check-input" type="checkbox" role="switch"

                                                               wire:model="floating_support_enabled"
                                                               id="floatingSupportEnabled">

                                                        <label class="form-check-label" for="floatingSupportEnabled">

                                                            دکمه شناور پشتیبانی فعال باشد؟

                                                        </label>

                                                    </div>

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-smartphone-line text-primary me-1"></i>

                                                        تلفن همراه

                                                    </label>

                                                    <input type="text" class="form-control" wire:model="floating_mobile"

                                                           placeholder="09123456789" dir="ltr">

                                                    @error('floating_mobile')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-phone-line text-success me-1"></i>

                                                        تلفن ثابت

                                                    </label>

                                                    <input type="text" class="form-control" wire:model="floating_phone"

                                                           placeholder="051-35092160" dir="ltr">

                                                    @error('floating_phone')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-whatsapp-line text-success me-1"></i>

                                                        شماره واتساپ

                                                    </label>

                                                    <input type="text" class="form-control"
                                                           wire:model="floating_whatsapp"

                                                           placeholder="09123456789" dir="ltr">

                                                    @error('floating_whatsapp')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">

                                                        <i class="ri-telegram-line text-primary me-1"></i>

                                                        آیدی تلگرام

                                                    </label>

                                                    <input type="text" class="form-control"
                                                           wire:model="floating_telegram"

                                                           placeholder="username یا https://t.me/username" dir="ltr">

                                                    @error('floating_telegram')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>


                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-secondary">

                                                            <span wire:loading.remove wire:target="saveFloatingSupport">

                                                                <i class="ri-save-line me-1"></i> ذخیره تنظیمات دکمه شناور

                                                            </span>

                                                            <span wire:loading wire:target="saveFloatingSupport">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                        </div>

                    @endif

                    @if($activeTab === 'panel')

                        <div class="row">

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-warning">

                                    <div class="card-header bg-warning-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-lock-2-line fs-18 text-warning me-2"></i>

                                            <h5 class="card-title mb-0 text-warning">بستن موقت پنل دانش‌آموز</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>با فعال‌کردن این گزینه، دانش‌آموزان هنگام ورود به پنل پیام زیر را می‌بینند و دسترسی موقتاً بسته می‌شود. سایت عمومی و پنل مدیریت باز می‌مانند.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="savePanelStatus">

                                            <div class="row g-3">

                                                <div class="col-12">

                                                    <div class="form-check form-switch fs-18">

                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                               id="studentPanelClosed" wire:model="student_panel_closed">

                                                        <label class="form-check-label ms-2" for="studentPanelClosed">

                                                            پنل دانش‌آموز بسته باشد

                                                        </label>

                                                    </div>

                                                </div>

                                                <div class="col-12">

                                                    <label class="form-label">پیام نمایشی هنگام بسته بودن پنل</label>

                                                    <textarea class="form-control" rows="3"
                                                              wire:model="student_panel_closed_message"
                                                              placeholder="مثلاً: پنل به‌دلیل به‌روزرسانی موقتاً بسته است. لطفاً ساعاتی دیگر مراجعه کنید."></textarea>

                                                    @error('student_panel_closed_message')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>

                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-warning">

                                                            <span wire:loading.remove wire:target="savePanelStatus">

                                                                <i class="ri-save-line me-1"></i> ذخیره وضعیت پنل

                                                            </span>

                                                            <span wire:loading wire:target="savePanelStatus">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                    @if($activeTab === 'advisor')

                        <div class="row">

                            <div class="col-lg-12 mb-4">

                                <div class="card border card-border-primary">

                                    <div class="card-header bg-primary-subtle">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-user-star-line fs-18 text-primary me-2"></i>

                                            <h5 class="card-title mb-0 text-primary">ظرفیت پیش‌فرض مشاوران</h5>

                                        </div>

                                        <p class="text-muted mb-0 mt-2">

                                            <small>حداکثر تعداد دانش‌آموزی که هر مشاور تحصیلی می‌تواند بپذیرد. وقتی تعداد دانش‌آموزانِ یک مشاور به این عدد برسد، ظرفیتش تکمیل شده و در فهرستِ انتخابِ مشاور (دستی و تصادفی) نمایش داده نمی‌شود. برای هر مشاور می‌توان ظرفیتِ اختصاصی هم تعریف کرد که بر این مقدار اولویت دارد.</small>

                                        </p>

                                    </div>

                                    <div class="card-body">

                                        <form wire:submit.prevent="saveAdvisorCapacity">

                                            <div class="row g-3">

                                                <div class="col-md-4">

                                                    <label class="form-label">ظرفیت پیش‌فرض هر مشاور</label>

                                                    <input type="number" min="1" max="1000" class="form-control"

                                                           wire:model="advisor_default_capacity" placeholder="مثال: 50">

                                                    @error('advisor_default_capacity')

                                                    <span class="text-danger small">{{ $message }}</span>

                                                    @enderror

                                                </div>

                                                <div class="col-12">

                                                    <div class="text-end">

                                                        <button type="submit" class="btn btn-primary">

                                                            <span wire:loading.remove wire:target="saveAdvisorCapacity">

                                                                <i class="ri-save-line me-1"></i> ذخیره ظرفیت

                                                            </span>

                                                            <span wire:loading wire:target="saveAdvisorCapacity">

                                                                <i class="ri-loader-4-line ri-spin me-1"></i> در حال ذخیره...

                                                            </span>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
