<div class="student-ui student-ui-auto-collapse">
    @include('livewire.admin.student._styles')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">اختصاص آزمون تشریحی: {{ $exam->title }}</h4>
                <a href="{{ route('admin.essay-exams.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">انتخاب دانش‌آموزان</h5>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="جستجو..." style="width:250px;">
                    </div>
                    <div class="card-body">
                        @if($students->isEmpty())
                            <p class="text-muted mb-0">دانش‌آموزی یافت نشد.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width:40px;">
                                            <input type="checkbox"
                                                   @checked(count($selected) === $students->count())
                                                   onclick="document.querySelectorAll('.student-check').forEach(c => { c.checked = this.checked; c.dispatchEvent(new Event('change')); });">
                                        </th>
                                        <th>نام</th>
                                        <th>موبایل</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td><input type="checkbox" class="student-check" value="{{ $student->id }}" wire:model.live="selected"></td>
                                            <td>{{ $student->user?->name ?? '—' }}</td>
                                            <td>{{ $student->user?->mobile ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">زمان‌بندی آزمون</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">تاریخ و ساعت شروع *</label>
                            <input type="datetime-local" wire:model.defer="start_at" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تاریخ و ساعت پایان *</label>
                            <input type="datetime-local" wire:model.defer="end_at" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">مدت زمان آزمون (دقیقه) *</label>
                            <input type="number" min="5" max="600" wire:model.defer="duration_minutes" class="form-control">
                        </div>
                        <button type="button" wire:click="assign" class="btn btn-primary w-100">
                            <span wire:loading.remove wire:target="assign">اختصاص به {{ count($selected) }} دانش‌آموز</span>
                            <span wire:loading wire:target="assign">در حال ذخیره...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
