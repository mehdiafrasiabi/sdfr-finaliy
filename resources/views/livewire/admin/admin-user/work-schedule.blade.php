<div>
    <div class="row">
        <div class="col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                برنامه کاری ادمین: {{ $admin->name }}
                            </h4>
                            <a href="{{ route('admin.admin-user.index') }}" class="btn btn-outline-secondary">
                                بازگشت به لیست ادمین‌ها
                            </a>
                        </div>
                    </div>
                </div>

                <div class="widget-content widget-content-area">

                    @if (session()->has('message'))
                        <div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>انجام شد!</strong> {{ session('message') }}
                        </div>
                    @endif

                    {{-- اطلاعات پروفایلِ مشاور (نمایش به دانش‌آموز هنگام انتخاب مشاور) --}}
                    <div class="border rounded p-3 mb-4">
                        <h5 class="mb-1">اطلاعات مشاور</h5>
                        <p class="text-muted small mb-3">
                            تحصیلات، رشته و توضیحاتِ مشاور هنگام «انتخاب مشاور» به دانش‌آموز نمایش داده می‌شود.
                            «ظرفیت اختصاصی» را فقط در صورتی وارد کنید که می‌خواهید این مشاور ظرفیتی متفاوت از مقدار سراسری داشته باشد.
                        </p>
                        <form wire:submit.prevent="saveProfile">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تحصیلات</label>
                                    <input type="text" class="form-control" wire:model="education" placeholder="مثال: کارشناسی ارشد روان‌شناسی">
                                    @error('education') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">رشته</label>
                                    <input type="text" class="form-control" wire:model="field_of_study" placeholder="مثال: مشاوره‌ی تحصیلی">
                                    @error('field_of_study') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">توضیحات</label>
                                    <textarea class="form-control" rows="3" wire:model="bio" placeholder="توضیحات کوتاه درباره‌ی مشاور..."></textarea>
                                    @error('bio') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">ظرفیت اختصاصی (اختیاری)</label>
                                    <input type="number" min="1" max="1000" class="form-control" wire:model="student_capacity" placeholder="خالی = مقدار سراسری">
                                    @error('student_capacity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light">
                                    <span wire:loading.remove wire:target="saveProfile">ذخیره اطلاعات مشاور</span>
                                    <span wire:loading wire:target="saveProfile">در حال ذخیره...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <p class="text-muted mb-4">
                        روزهای کاری ادمین را فعال کرده و ساعت شروع و پایان هر روز را مشخص کنید.
                        برای روزهایی که ادمین کار نمی‌کند، تیک فعال‌سازی را بردارید.
                    </p>

                    <form wire:submit.prevent="save">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th style="width: 120px;">روز</th>
                                    <th style="width: 120px;" class="text-center">فعال</th>
                                    <th class="text-center">ساعت شروع</th>
                                    <th class="text-center">ساعت پایان</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($days as $day => $dayName)
                                    <tr>
                                        <td><strong>{{ $dayName }}</strong></td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="enabled_{{ $day }}"
                                                    wire:model.live="schedules.{{ $day }}.enabled"
                                                >
                                            </div>
                                        </td>
                                        <td>
                                            <input
                                                type="time"
                                                class="form-control"
                                                wire:model="schedules.{{ $day }}.start_time"
                                                @disabled(empty($schedules[$day]['enabled']))
                                            >
                                            @error("schedules.$day.start_time")
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input
                                                type="time"
                                                class="form-control"
                                                wire:model="schedules.{{ $day }}.end_time"
                                                @disabled(empty($schedules[$day]['enabled']))
                                            >
                                            @error("schedules.$day.end_time")
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success _effect--ripple waves-effect waves-light">
                                <span wire:loading.remove wire:target="save">ذخیره برنامه کاری</span>
                                <span wire:loading wire:target="save">در حال ذخیره...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

