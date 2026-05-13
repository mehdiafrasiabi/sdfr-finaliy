<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">ثبت‌نام‌های نیازمند تخصیص پشتیبان</h4>
                </div>
                <div class="card-body">
                    <div class="search-box mb-3">
                        <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="جستجو بر اساس نام یا موبایل...">
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>دانش‌آموز</th>
                                <th>موبایل</th>
                                <th>پایه</th>
                                <th>مبلغ نهایی</th>
                                <th>تاریخ پرداخت</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($enrollments as $e)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $e->user?->name ?? '-' }}</td>
                                    <td>{{ $e->user?->mobile ?? '-' }}</td>
                                    <td>{{ $e->ccGrade?->name ?? '-' }}</td>
                                    <td>{{ number_format($e->final_amount) }} تومان</td>
                                    <td>{{ $e->paid_at ? jalali($e->paid_at)->format('%d %B %Y') : '-' }}</td>
                                    <td>
                                        <button wire:click="openAssign({{ $e->id }})" class="btn btn-sm btn-primary">تخصیص پشتیبان</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">موردی برای نمایش وجود ندارد.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $enrollments->links() }}
                </div>
            </div>
        </div>
    </div>

    @if($assigningEnrollmentId)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="assign">
                        <div class="modal-header">
                            <h5 class="modal-title">تخصیص پشتیبان</h5>
                            <button type="button" wire:click="closeAssign" class="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">انتخاب پشتیبان</label>
                            <select wire:model="supporterId" class="form-select">
                                <option value="">انتخاب کنید...</option>
                                @foreach($supporters as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('supporterId') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeAssign" class="btn btn-light">انصراف</button>
                            <button type="submit" class="btn btn-primary">تخصیص</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
