<div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0 flex-grow-1">{{ $school->name }} <small class="text-muted">(کد: {{ $school->code }})</small></h4>
            <a href="{{ route('manager.schools.index') }}" class="btn btn-sm btn-soft-secondary">بازگشت به لیست</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>تلفن:</strong> {{ $school->public_phone }}</p>
                    <p><strong>آدرس:</strong> {{ $school->address }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>مدیر:</strong> {{ $school->manager?->name ?? '---' }} ({{ $school->manager?->phone ?? '---' }})</p>
                    <p><strong>معاون آموزشی:</strong> {{ $school->deputy?->name ?? '---' }} ({{ $school->deputy?->phone ?? '---' }})</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">پشتیبان‌های مدرسه</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">
                از میان ادمین‌هایی که نقش «پشتیبان مدرسه» دارند، یک یا چند نفر را برای این مدرسه انتخاب کنید.
                اگر هیچ ادمینی با این نقش وجود ندارد، ابتدا در صفحه‌ی «مدیریت ادمین‌ها» نقش را به یک ادمین اختصاص دهید.
            </p>

            @if ($availableSupporters->isEmpty())
                <div class="alert alert-warning">
                    هیچ ادمینی با نقش «پشتیبان مدرسه» وجود ندارد.
                </div>
            @else
                <div class="row">
                    @foreach($availableSupporters as $supporter)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       wire:model="selectedSupporters"
                                       value="{{ $supporter->id }}"
                                       id="sup-{{ $supporter->id }}">
                                <label class="form-check-label" for="sup-{{ $supporter->id }}">
                                    {{ $supporter->name }}
                                    <small class="text-muted d-block">{{ $supporter->mobile }}</small>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-end mt-3">
                    <button wire:click="syncSupporters" class="btn btn-success">
                        <span wire:loading.remove>ذخیره پشتیبان‌ها</span>
                        <span wire:loading>در حال ذخیره...</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">دانش‌آموزان مدرسه (۵۰ مورد اخیر)</h5>
            <a href="{{ route('manager.school-students.index', ['school' => $school->id]) }}"
               class="btn btn-sm btn-primary">
                مدیریت دانش‌آموزان این مدرسه
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام و نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>تلفن دانش‌آموز</th>
                        <th>پایه</th>
                        <th>رشته</th>
                        <th>پشتیبان تحصیلی</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $st)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $st->user?->name ?? '---' }}</td>
                            <td>{{ $st->national_code ?? '---' }}</td>
                            <td>{{ $st->user?->mobile ?? '---' }}</td>
                            <td>{{ $st->grade ?? '---' }}</td>
                            <td>{{ $st->field ?? '---' }}</td>
                            <td>{{ $st->schoolSupporter?->name ?? '---' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">هنوز دانش‌آموزی برای این مدرسه ثبت نشده است.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
