<div class="container-fluid">

    {{-- ردیف اول: نام دانش‌آموز --}}
    <div class="mb-3 d-flex align-items-center">
        <h4  wire:ignore class="py-3 mb-4">
            <span class="text-muted fw-light">کارنامه وضعیت /</span>
            <span class="text-success"> {{ $studentName }}</span>
        </h4>
    </div>

    {{-- ردیف دوم: فرم افزودن کارنامه جدید --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">افزودن کارنامه جدید</h5>
                </div>

                <div class="card-body">
                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">

                        {{-- عنوان --}}
                        <div class="mb-3">
                            <label class="form-label">
                                عنوان:
                            </label>
                            <input type="text"
                                   name="title"
                                   wire:model="title"
                                   class="form-control"
                                   placeholder="کارنامه-آبان-1404">
                            @error('title')
                            <div class="form-text text-danger mt-1">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- فایل کارنامه --}}
                        <div class="mb-3">
                            <label class="form-label">
                                کارنامه (PDF):
                            </label>
                            <input
                                type="file"
                                name="reportMonthly"
                                wire:model="reportMonthly"
                                class="form-control">
                            @error('reportMonthly')
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
    </div>

    {{-- ردیف سوم: لیست کارنامه‌های دانش‌آموز --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">لیست کارنامه های دانش آموز</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">عنوان</th>
                                <th scope="col">کارنامه</th>
                                <th scope="col">تاریخ بارگذاری</th>
                                <th scope="col">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + $reports->firstItem() - 1 }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $report->title }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ \App\Helpers\FileHelper::publicUrl($report->report) }}"
                                           target="_blank"
                                           class="link-secondary text-decoration-underline">
                                            مشاهده
                                        </a>
                                    </td>
                                    <td>
                                        {{ jalali($report->created_at)->format('%d %B %Y | H:i:s') }}
                                    </td>
                                    <td>
                                            <button
                                                type="button"
                                                wire:confirm="آیا مطمئن هستید؟"
                                                wire:click="delete({{ $report->id }})"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                                                <i class="material-symbols-outlined me-1">delete</i>
                                                حذف
                                            </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
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

                <div class="card-footer d-flex justify-content-center justify-content-md-between align-items-center">
                    {{ $reports->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>
    </div>

</div>
