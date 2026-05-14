<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">تماس اکسترا</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">تماس اکسترا</h4>
                    <p class="small text-muted mb-0">تماس‌های متفرقه: اطلاع‌رسانی، پیگیری، تماس با والدین.</p>
                </div>
                <div class="col-md-6">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>دانش‌آموز</th>
                            <th>موبایل</th>
                            <th>پایه/رشته</th>
                            <th>تعداد تماس اکسترا</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($trials as $trial)
                        <tr>
                            <td>{{ $trial->id }}</td>
                            <td><strong>{{ $trial->user?->personalInformation?->name ?? $trial->user?->name ?? '—' }}</strong></td>
                            <td>{{ $trial->user?->mobile ?? '—' }}</td>
                            <td>{{ $trial->grade_label }} / {{ $trial->field_label }}</td>
                            <td>{{ $trial->extra_count ?: '—' }}</td>
                            <td>
                                <button wire:click="openForm({{ $trial->id }})"
                                        class="btn btn-sm btn-primary">
                                    ثبت تماس اکسترا
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                دانش‌آموزی برای شما تخصیص داده نشده است.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $trials->links() }}</div>
        </div>
    </div>

    @if ($activeTrialId)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ثبت تماس اکسترا</h5>
                        <button type="button" class="btn-close" wire:click="closeForm"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">پیگیر آموزشی (اختیاری)</label>
                            <select wire:model="educationalFollowUp" class="form-select">
                                <option value="">— انتخاب کنید —</option>
                                <option value="father">پدر</option>
                                <option value="mother">مادر</option>
                                <option value="student">خود دانش‌آموز</option>
                                <option value="other">سایر</option>
                            </select>
                            @error('educationalFollowUp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">توضیحات <span class="text-danger">*</span></label>
                            <textarea wire:model="contactNotes" rows="4" class="form-control"
                                      placeholder="اطلاع‌رسانی / پیگیری / تماس با والدین / …"></textarea>
                            @error('contactNotes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeForm">انصراف</button>
                        <button class="btn btn-primary" wire:click="save">ثبت تماس</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
