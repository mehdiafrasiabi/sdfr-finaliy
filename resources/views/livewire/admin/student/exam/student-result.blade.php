<div>

    <div class="card shadow-sm">
        {{-- هدر کارت --}}
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">
                لیست نتایج آزمون
                <span class="fw-bold text-success">"{{ $exam->title }}"</span>
            </h5>

            <div class="d-flex align-items-center">
                <form class="position-relative" style="min-width: 230px;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-2 text-muted">
                        <i class="material-symbols-outlined" style="font-size: 20px">search</i>
                    </span>
                    <input type="text"
                           placeholder="جستجو....."
                           wire:model.live.debounce.350ms="search"
                           class="form-control form-control-sm ps-5">
                </form>
            </div>
        </div>

        {{-- بدنه کارت / جدول --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">دانش‌آموز</th>
                        <th scope="col">عملیات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            {{-- ردیف --}}
                            <td>
                                {{ $loop->iteration + $attempts->firstItem() - 1 }}
                            </td>

                            {{-- نام دانش‌آموز --}}
                            <td>
                                <span class="fw-medium">
                                    {{ $attempt->student->user->name }}
                                </span>
                            </td>

                            {{-- عملیات --}}
                            <td>
                                <a href="{{ route('admin.student.exam.studentResultDetail', ['exam' => $exam->id, 'attempt' => $attempt->id]) }}"
                                   class="btn btn-sm btn-outline-primary d-inline-flex align-items-center custom-tooltip">
                                    <i class="material-symbols-outlined me-1" style="font-size: 18px">visibility</i>
                                    مشاهده نتیجه
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
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
            {{ $attempts->links('layouts.admin.pagination') }}
        </div>
    </div>

</div>
