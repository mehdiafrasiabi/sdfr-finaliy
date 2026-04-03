<div>
    @push('link')
        <link rel="stylesheet" href="/admin/assets/css/jalalidatepicker.min.css">
        <style>
            .konkur-card { border: 1px solid #e9ebec; border-radius: 14px; }
        </style>
    @endpush

    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-0">تنظیمات روزشمار کنکور</h4>
            <small class="text-muted">تنظیمات نمایش سمت کلاینت + مدیریت رویدادها و توضیحات</small>
        </div>
    </div>

    <form wire:submit.prevent="save" class="row g-4">
        <div class="col-12">
            <div class="card konkur-card">
                <div class="card-header"><h5 class="mb-0">۱) زمان اصلی کنکور</h5></div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">تیتر</label>
                        <input type="text" wire:model.defer="headline" class="form-control" placeholder="روز شمار کنکور ۱۴۰۵">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">زیرتیتر</label>
                        <input type="text" wire:model.defer="subheadline" class="form-control" placeholder="متن معرفی بخش روزشمار">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">عنوان کارت</label>
                        <input type="text" wire:model.defer="exam_title" class="form-control" placeholder="کنکور ۱۴۰۵">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">شروع شمارش (جلالی + ساعت)</label>
                        <input id="countdown_start_at" type="text" wire:model.defer="countdown_start_at" data-jdp class="form-control" placeholder="1405/01/01 00:00">
                        @error('countdown_start_at') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">پایان شمارش (جلالی + ساعت)</label>
                        <input id="countdown_end_at" type="text" wire:model.defer="countdown_end_at" data-jdp class="form-control" placeholder="1405/04/12 08:00">
                        @error('countdown_end_at') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 text-muted small">
                        نمایش در کلاینت به ترتیب: روز، ساعت، دقیقه، ثانیه و به‌صورت خودکار کم می‌شود.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card konkur-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">۲) رویدادهای مهم</h5>
                    <button type="button" wire:click="addEventRow" class="btn btn-sm btn-primary">افزودن ردیف</button>
                </div>
                <div class="card-body">
                    @foreach($events as $index => $event)
                        <div class="row g-3 align-items-end mb-3" wire:key="konkur-event-{{ $index }}">
                            <div class="col-md-7">
                                <label class="form-label">عنوان رویداد</label>
                                <input type="text" wire:model.defer="events.{{ $index }}.title" class="form-control" placeholder="مثال: زمان برگزاری کنکور تجربی">
                                @error('events.'.$index.'.title') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تاریخ جلالی</label>
                                <input type="text" wire:model.defer="events.{{ $index }}.jalali_date" data-jdp class="form-control" placeholder="1405/04/12">
                                @error('events.'.$index.'.jalali_date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-1">
                                <button type="button" wire:click="removeEventRow({{ $index }})" class="btn btn-outline-danger w-100">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card konkur-card">
                <div class="card-header"><h5 class="mb-0">۳) باکس توضیحات (Editor)</h5></div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        ویرایشگر CKEditor 5 سازگار با Laravel + Livewire فعال شده و آپلود عکس را مستقیماً به WebP تبدیل می‌کند.
                    </p>
                    <div wire:ignore>
                        <textarea id="konkur-editor">{!! $description !!}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success">ذخیره تنظیمات</button>
        </div>
    </form>

    @push('script')
        <script src="/admin/assets/js/jalalidatepicker.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            document.addEventListener('livewire:navigated', initKonkurTools);
            document.addEventListener('DOMContentLoaded', initKonkurTools);

            function initKonkurTools() {
                if (typeof jalaliDatepicker !== 'undefined') {
                    jalaliDatepicker.startWatch({
                        time: true,
                        hasSecond: false,
                        autoHide: true,
                        separatorChar: '/',
                    });
                }

                if (window.konkurEditorInitialized) return;
                const el = document.querySelector('#konkur-editor');
                if (!el || typeof ClassicEditor === 'undefined') return;

                window.konkurEditorInitialized = true;

                ClassicEditor
                    .create(el, {
                        language: 'fa',
                        simpleUpload: {
                            uploadUrl: '{{ route('manager.konkur.ck-upload') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }
                    })
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                        @this.set('description', editor.getData());
                        });
                    })
                    .catch(console.error);
            }
        </script>
    @endpush
</div>
