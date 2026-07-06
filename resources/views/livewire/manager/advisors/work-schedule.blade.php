<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">ساعت کاری و پروفایل: {{ $admin->name }}</h4>
                <a href="{{ route('manager.advisors') }}" class="btn btn-soft-secondary btn-sm">بازگشت به لیست مشاوران</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- پروفایل مشاور --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">اطلاعات مشاور</h5></div>
                <div class="card-body">
                    <p class="text-muted small">تحصیلات، رشته و توضیحات هنگام «انتخاب مشاور» به دانش‌آموز نمایش داده می‌شود. ظرفیتِ اختصاصی خالی = استفاده از مقدار سراسری.</p>
                    <form wire:submit.prevent="saveProfile">
                        <div class="mb-3">
                            <label class="form-label">تحصیلات</label>
                            <input type="text" class="form-control" wire:model="education" placeholder="مثال: کارشناسی ارشد روان‌شناسی">
                            @error('education') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رشته</label>
                            <input type="text" class="form-control" wire:model="field_of_study">
                            @error('field_of_study') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات</label>
                            <textarea class="form-control" rows="3" wire:model="bio"></textarea>
                            @error('bio') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ظرفیت اختصاصی (اختیاری)</label>
                            <input type="number" min="1" max="1000" class="form-control" wire:model="student_capacity" placeholder="خالی = مقدار سراسری">
                            @error('student_capacity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="saveProfile">ذخیره اطلاعات</span>
                            <span wire:loading wire:target="saveProfile">در حال ذخیره…</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ساعت کاری --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">ساعت کاری هفتگی</h5></div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:120px">روز</th>
                                        <th class="text-center" style="width:90px">فعال</th>
                                        <th class="text-center">شروع</th>
                                        <th class="text-center">پایان</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($days as $day => $dayName)
                                    <tr>
                                        <td><strong>{{ $dayName }}</strong></td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input type="checkbox" class="form-check-input"
                                                       wire:model.live="schedules.{{ $day }}.enabled">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="time" class="form-control"
                                                   wire:model="schedules.{{ $day }}.start_time"
                                                   @disabled(empty($schedules[$day]['enabled']))>
                                            @error("schedules.$day.start_time") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>
                                        <td>
                                            <input type="time" class="form-control"
                                                   wire:model="schedules.{{ $day }}.end_time"
                                                   @disabled(empty($schedules[$day]['enabled']))>
                                            @error("schedules.$day.end_time") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <span wire:loading.remove wire:target="save">ذخیره ساعت کاری</span>
                                <span wire:loading wire:target="save">در حال ذخیره…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
