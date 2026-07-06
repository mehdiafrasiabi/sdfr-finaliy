<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">شماره‌های جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="mb-0">شماره‌های جذب تلفنی</h4>
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="active">در جریان</option>
                        <option value="closed">بسته‌شده</option>
                        <option value="dead">خاکستری</option>
                    </select>
                </div>
                <div class="col-md-2 text-md-start">
                    <div class="d-flex gap-1">
                        <button class="btn btn-primary flex-fill" wire:click="openForm">
                            <i class="fi fi-rr-plus"></i> دستی
                        </button>
                        <button class="btn btn-success flex-fill" wire:click="toggleImport">
                            <i class="fi fi-rr-file-excel"></i> اکسل
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>موبایل</th>
                            <th>پایه/رشته</th>
                            <th>استان/شهر</th>
                            <th>وضعیت</th>
                            <th>تماس‌ها</th>
                            <th>مشاور فعلی</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>{{ $lead->id }}</td>
                            <td>{{ $lead->full_name ?: '—' }}</td>
                            <td dir="ltr">{{ $lead->mobile }}</td>
                            <td>{{ $lead->grade_label }} / {{ $lead->field_label }}</td>
                            <td>{{ $lead->state?->name ?? '—' }}{{ $lead->city ? '، ' . $lead->city->name : '' }}</td>
                            <td><span class="badge bg-{{ $lead->color }}">{{ $lead->status_label }}</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $lead->calls_count }}</span></td>
                            <td>{{ $lead->activeAssignment?->consultant?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">شماره‌ای یافت نشد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>

    {{-- مودال ایمپورت اکسل --}}
    @if ($showImport)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">افزودن گروهی با اکسل</h5>
                        <button type="button" class="btn-close" wire:click="toggleImport"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            ستون‌های فایل به ترتیب: <strong>نام</strong> | <strong>موبایل</strong> | <strong>پایه</strong> | <strong>رشته</strong>.
                            تنها «موبایل» اجباری است. پایه می‌تواند عدد (۹ تا ۱۳) یا متن (نهم…) و رشته می‌تواند math/experimental/human یا متن (ریاضی…) باشد.
                            سطر عنوان اختیاری است.
                        </div>

                        <div class="mb-2">
                            <label class="form-label">فایل اکسل (xlsx / xls / csv) <span class="text-danger">*</span></label>
                            <input type="file" wire:model="excelFile" class="form-control" accept=".xlsx,.xls,.csv">
                            @error('excelFile')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <div wire:loading wire:target="excelFile" class="text-muted small mt-1">در حال بارگذاری فایل…</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="toggleImport">انصراف</button>
                        <button class="btn btn-success" wire:click="importExcel" wire:loading.attr="disabled" wire:target="importExcel,excelFile">
                            <span wire:loading.remove wire:target="importExcel"><i class="fi fi-rr-upload"></i> ایمپورت</span>
                            <span wire:loading wire:target="importExcel">در حال ثبت…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- مودال افزودن شماره --}}
    @if ($showForm)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">افزودن شماره جدید</h5>
                        <button type="button" class="btn-close" wire:click="closeForm"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">نام و نام خانوادگی</label>
                            <input type="text" wire:model="fullName" class="form-control">
                            @error('fullName')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">شمارهٔ موبایل <span class="text-danger">*</span></label>
                            <input type="text" wire:model="mobile" class="form-control" dir="ltr" placeholder="09xxxxxxxxx">
                            @error('mobile')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">پایه</label>
                                <select wire:model="grade" class="form-select">
                                    <option value="">— انتخاب کنید —</option>
                                    @foreach ($gradeOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('grade')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رشته</label>
                                <select wire:model="field" class="form-select">
                                    <option value="">— انتخاب کنید —</option>
                                    @foreach ($fieldOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('field')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">استان</label>
                                <select wire:model.live="stateId" class="form-select">
                                    <option value="">— انتخاب کنید —</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('stateId')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">شهر</label>
                                <select wire:model="cityId" class="form-select" @disabled(count($cities) === 0)>
                                    <option value="">— انتخاب کنید —</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('cityId')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeForm">انصراف</button>
                        <button class="btn btn-primary" wire:click="addLead">ثبت شماره</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
