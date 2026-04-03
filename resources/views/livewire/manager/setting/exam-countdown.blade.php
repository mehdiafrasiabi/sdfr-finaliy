<div>
    @push('link')
        <link rel="stylesheet" href="/admin/assets/css/jalalidatepicker.min.css">
    @endpush

    {{-- بخش اول: زمان اصلی کنکور --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">زمان اصلی کنکور</h4>
                </div>
                <div class="card-body">
                    <form wire:submit="save">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">تیتر <sup class="text-danger">*</sup></label>
                                <input type="text" wire:model="title" class="form-control"
                                       placeholder="مثلا: روز شمار کنکور ۱۴۰۵">
                                @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">زیر تیتر <sup class="text-danger">*</sup></label>
                                <input type="text" wire:model="subtitle" class="form-control"
                                       placeholder="مثلا: روز شمار کنکور ریاضی، تجربی، انسانی">
                                @error('subtitle') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عنوان کارت <sup class="text-danger">*</sup></label>
                                <input type="text" wire:model="card_title" class="form-control"
                                       placeholder="مثلا: کنکور ۱۴۰۵">
                                @error('card_title') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">شروع شمارش <sup class="text-danger">*</sup></label>
                                <input type="text" wire:model="start_date"
                                       class="form-control jalali-date-picker-start"
                                       placeholder="انتخاب تاریخ" autocomplete="off">
                                @error('start_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">پایان شمارش <sup class="text-danger">*</sup></label>
                                <input type="text" wire:model="end_date"
                                       class="form-control jalali-date-picker-end"
                                       placeholder="انتخاب تاریخ" autocomplete="off">
                                @error('end_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- بخش دوم: رویدادهای مهم --}}
                        <hr class="my-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">رویدادهای مهم</h5>
                            <button type="button" wire:click="addEvent" class="btn btn-sm btn-outline-primary">
                                <i class="ri-add-line"></i> افزودن رویداد
                            </button>
                        </div>

                        @forelse($events as $i => $event)
                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-5">
                                    <input type="text"
                                           wire:model="events.{{ $i }}.event_title"
                                           class="form-control"
                                           placeholder="عنوان رویداد (مثلا: زمان برگزاری کنکور تجربی)">
                                    @error("events.{$i}.event_title")
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-5">
                                    <input type="text"
                                           wire:model="events.{{ $i }}.event_date"
                                           class="form-control jalali-date-picker-event-{{ $i }}"
                                           placeholder="تاریخ رویداد" autocomplete="off">
                                    @error("events.{$i}.event_date")
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <button type="button" wire:click="removeEvent({{ $i }})"
                                            class="btn btn-sm btn-soft-danger w-100">
                                        <i class="ri-delete-bin-6-line"></i> حذف
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-3">رویدادی ثبت نشده است. با دکمه «افزودن رویداد» رویداد اضافه کنید.</p>
                        @endforelse

                        {{-- بخش سوم: باکس توضیحات --}}
                        <hr class="my-4">
                        <h5 class="mb-3">باکس توضیحات</h5>
                        <div wire:ignore>
                            <textarea id="exam-description" name="description" class="form-control"
                                      rows="8">{{ $description }}</textarea>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success">
                                <span wire:loading.remove wire:target="save">
                                    <i class="ri-save-line me-1"></i> ذخیره
                                </span>
                                <span wire:loading wire:target="save">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                         preserveAspectRatio="xMidYMid" width="20" height="20"
                                         style="shape-rendering:auto;display:inline-block;vertical-align:middle">
                                        <circle stroke-linecap="round" fill="none"
                                                stroke-dasharray="50.265 50.265"
                                                stroke="#ffffff" stroke-width="8"
                                                r="32" cy="50" cx="50">
                                            <animateTransform values="0 50 50;360 50 50" keyTimes="0;1"
                                                              dur="0.6s" repeatCount="indefinite"
                                                              type="rotate" attributeName="transform"/>
                                        </circle>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="/admin/assets/js/jalalidatepicker.min.js"></script>
        <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
        <script>
            document.addEventListener('livewire:init', function () {
                // CKEditor
                var ckEditor = CKEDITOR.replace('exam-description', {
                    filebrowserUploadUrl: "{{ route('manager.setting.exam-countdown.ck-upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form',
                    contentsLangDirection: 'rtl',
                    height: 400,
                });
                ckEditor.on('change', function (event) {
                @this.set('description', event.editor.getData());
                });

                // Jalali datepicker - start_date
                jalaliDatepicker.startWatch({
                    minDate: false,
                    maxDate: false,
                    time: false,
                    observer: true,
                    persianDigits: false,
                    selector: '.jalali-date-picker-start',
                    onSelect: function (unix, jalali, gregorian, el) {
                    @this.set('start_date', jalali.year + '/' + jalali.month + '/' + jalali.day);
                    }
                });

                // Jalali datepicker - end_date
                jalaliDatepicker.startWatch({
                    minDate: false,
                    maxDate: false,
                    time: false,
                    observer: true,
                    persianDigits: false,
                    selector: '.jalali-date-picker-end',
                    onSelect: function (unix, jalali, gregorian, el) {
                    @this.set('end_date', jalali.year + '/' + jalali.month + '/' + jalali.day);
                    }
                });
            });

            // Reinitialize event datepickers after Livewire updates
            document.addEventListener('livewire:updated', function () {
                document.querySelectorAll('[class*="jalali-date-picker-event-"]').forEach(function (el) {
                    var idx = el.className.match(/jalali-date-picker-event-(\d+)/);
                    if (!idx) return;
                    var i = idx[1];
                    jalaliDatepicker.startWatch({
                        minDate: false,
                        maxDate: false,
                        time: false,
                        observer: true,
                        persianDigits: false,
                        selector: '.jalali-date-picker-event-' + i,
                        onSelect: function (unix, jalali, gregorian, el) {
                        @this.set('events.' + i + '.event_date', jalali.year + '/' + jalali.month + '/' + jalali.day);
                        }
                    });
                });
            });
        </script>
    @endpush
</div>
