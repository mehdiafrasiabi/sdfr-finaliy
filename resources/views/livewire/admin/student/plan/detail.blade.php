<div class="container-fluid">

    {{-- ردیف اول: نام دانش‌آموز --}}
    <div class="mb-4">
        <h4  wire:ignore class="py-3 mb-4">
            <span class="text-muted fw-light">برنامه ها /</span>
           <span class="text-success"> {{ $studentName }}</span>
        </h4>
    </div>

    {{-- ردیف دوم: فرم آپلود + خروجی اکسل --}}
    <div class="row g-4 mb-4">

        {{-- ستون 1: فرم افزودن برنامه --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">افزودن برنامه</h5>
                </div>

                <div class="card-body">
                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">

                        {{-- عنوان برنامه --}}
                        <div class="mb-3">
                            <label class="form-label">
                                عنوان برنامه:
                            </label>
                            <input type="text"
                                   name="title"
                                   wire:model="title"
                                   class="form-control"
                                   placeholder="برنامه-مهر-1404">
                            @error('title')
                            <div class="form-text text-danger mt-1">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- فایل برنامه --}}
                        <div class="mb-3">
                            <label class="form-label">
                                فایل برنامه (PDF):
                            </label>
                            <input
                                type="file"
                                name="barnameh"
                                wire:model="barnameh"
                                class="form-control">
                            @error('barnameh')
                            <div class="form-text text-danger mt-1">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('admin.student.index') }}"
                               type="button"
                               class="btn btn-outline-danger">
                                خروج
                            </a>

                            <button type="submit" class="btn btn-success d-flex align-items-center">
                                <div  class="d-flex align-items-center">
                                    <span  wire:loading.remove class="ms-1">ثبت و ارسال</span>

                                    <span wire:loading class="spinner-border spinner-border-sm ms-2" role="status"
                                          aria-hidden="true">

                                    </span>
                                </div>

                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ستون 2: خروجی اکسل --}}
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">خروجی اکسل</h5>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- از تاریخ --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                از تاریخ (شمسی)
                            </label>
                            <input type="text"
                                   name="from"
                                   wire:model="from"
                                   class="form-control"
                                   placeholder="1404/05/24">
                            @error('from')
                            <div class="form-text text-danger mt-1">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- تا تاریخ --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                تا تاریخ (شمسی)
                            </label>
                            <input type="text"
                                   name="to"
                                   wire:model="to"
                                   class="form-control"
                                   placeholder="1404/06/24">
                            @error('to')
                            <div class="form-text text-danger mt-1">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 text-start text-md-end">
                        <button wire:click="exportExcel"
                                type="button"
                                wire:loading.attr="disabled"
                                class="btn btn-outline-primary d-inline-flex align-items-center">
                            <span class="position-relative">
                                <i class="material-symbols-outlined me-1 align-middle">
                                    download
                                </i>
                                <span wire:loading.remove>خروجی اکسل</span>
                                <span wire:loading>در حال آماده‌سازی...</span>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- ردیف سوم: جدول لیست برنامه‌های دانش‌آموز --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">لیست برنامه های دانش آموز</h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">عنوان</th>
                                <th scope="col">برنامه</th>
                                <th scope="col">تاریخ بارگذاری</th>
                                <th scope="col">وضعیت مشاهده</th>
                                <th scope="col">تاریخ آخرین مشاهده</th>
                                <th scope="col">عملیات</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($plans as $plan)
                                <tr>
                                    {{-- ردیف --}}
                                    <td>
                                        {{ $loop->iteration + $plans->firstItem() - 1 }}
                                    </td>

                                    {{-- عنوان --}}
                                    <td>
                                        <span class="fw-medium">{{ $plan->title }}</span>
                                    </td>

                                    {{-- لینک برنامه --}}
                                    <td>
                                        <a href="{{ \App\Helpers\FileHelper::publicUrl($plan->barnameh) }}"
                                           target="_blank"
                                           class="link-primary text-decoration-underline">
                                            مشاهده
                                        </a>
                                    </td>

                                    {{-- تاریخ بارگذاری --}}
                                    <td>
                                        {{ jalali($plan->created_at)->format('%d %B %Y | H:i:s') }}
                                    </td>

                                    {{-- وضعیت مشاهده --}}
                                    <td>
                                        @if ($plan->views->isNotEmpty())
                                            <span class="badge bg-success">
                                                مشاهده شده است
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                مشاهده نشده است
                                            </span>
                                        @endif
                                    </td>

                                    {{-- تاریخ آخرین مشاهده --}}
                                    <td>
                                        @if ($plan->views->isNotEmpty())
                                            <span class="text-success fw-bold">
                                                {{ jalali($plan->created_at)->format('%d %B %Y | H:i') }}
                                            </span>
                                        @else
                                            <span class="text-danger fw-bold">
                                                ---
                                            </span>
                                        @endif
                                    </td>

                                    {{-- عملیات --}}
                                    <td>
                                        <button type="button"
                                                wire:confirm="آیا مطمئن هستید؟"
                                                wire:click="delete({{ $plan->id }})"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                                            <i class="material-symbols-outlined me-1">
                                                delete
                                            </i>
                                            حذف
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div>
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                       trigger="loop"
                                                       colors="primary:#121331,secondary:#08a88a"
                                                       style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2 mb-0">
                                                متاسفیم! هیچ نتیجه‌ای یافت نشد
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- صفحه‌بندی --}}
                <div class="card-footer d-flex justify-content-center justify-content-md-between align-items-center">
                    {{ $plans->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>
    </div>

</div>
