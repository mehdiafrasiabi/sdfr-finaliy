<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">تماس اولیه</li>
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
                    <h4 class="mb-0">تماس اولیه</h4>
                    <p class="small text-muted mb-0">با هر دانش‌آموز جدید تماس بگیر و نتیجه را ثبت کن.</p>
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
                            <th>موبایل دانش‌آموز</th>
                            <th>موبایل پدر</th>
                            <th>موبایل مادر</th>
                            <th>پایه/رشته</th>
                            <th>دفعات عدم پاسخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($trials as $trial)
                        <tr>
                            <td>{{ $trial->id }}</td>
                            <td><strong>{{ $trial->user?->personalInformation?->name ?? $trial->user?->name ?? '—' }}</strong></td>
                            <td>{{ $trial->user?->mobile ?? '—' }}</td>
                            <td>{{ $trial->father_mobile ?? '—' }}</td>
                            <td>{{ $trial->mother_mobile ?? '—' }}</td>
                            <td>{{ $trial->grade_label }} / {{ $trial->field_label }}</td>
                            <td>
                                @if($trial->unanswered_count > 0)
                                    <span class="badge bg-warning-subtle text-warning">{{ $trial->unanswered_count }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <button wire:click="openSuccessForm({{ $trial->id }})"
                                        class="btn btn-sm btn-success">
                                    گرفته شد
                                </button>
                                <button wire:click="markUnanswered({{ $trial->id }})"
                                        wire:confirm="آیا از ثبت عدم پاسخ مطمئن هستید؟"
                                        class="btn btn-sm btn-outline-danger">
                                    پاسخ نداد
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                دانش‌آموز جدیدی برای تماس اولیه وجود ندارد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $trials->links() }}</div>
        </div>
    </div>

    {{-- ─────── Modal ثبت تماس موفق ─────── --}}
    @if ($activeTrialId)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ثبت تماس اولیهٔ موفق</h5>
                        <button type="button" class="btn-close" wire:click="closeForm"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">پیگیر آموزشی <span class="text-danger">*</span></label>
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
                            <label class="form-label">توضیحات</label>
                            <textarea wire:model="contactNotes" rows="3" class="form-control"></textarea>
                            @error('contactNotes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeForm">انصراف</button>
                        <button class="btn btn-success" wire:click="markAnswered">ثبت تماس</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
