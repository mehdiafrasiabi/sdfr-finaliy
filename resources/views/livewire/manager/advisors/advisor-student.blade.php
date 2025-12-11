<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        لیست دانش آموز های مشاور :

                        {{$advisor->name}}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control search"
                                               placeholder="جستجو...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                            </div>
                            <button wire:click="exportExcel" class="btn btn-success">
                                دانلود اکسل دانش‌آموزان
                            </button>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">دانش آموز</th>
                                    <th class="sort" data-sort="phone">نام پدر</th>
                                    <th class="sort" data-sort="action">کدملی</th>
                                    <th class="sort" data-sort="action">محل تولد</th>
                                    <th class="sort" data-sort="action">استان</th>
                                    <th class="sort" data-sort="action">شهر</th>
                                    <th class="sort" data-sort="action">محل زندگی</th>
                                    <th class="sort" data-sort="action">پدر</th>
                                    <th class="sort" data-sort="action">مادر</th>
                                    <th class="sort" data-sort="status">جزییات</th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($students  as $student)
                                    <tr>
                                        <th scope="row">
                                            {{$loop->iteration + $students ->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">{{ $student->personalInformation->name ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->father_name ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->code_mell ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->place_of_birth ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->state->name ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->city->name ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->address ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->father_mobile ?? '-' }}</td>
                                        <td class="phone">{{ $student->personalInformation->mother_mobile ?? '-' }}</td>
                                        <td class="status">
                                            <a href="{{ route('manager.advisors.students.detail', $student->id) }}"
                                               class="text-sm text-white bg-primary hover:bg-primary/80 px-4 py-1 rounded">
                                                مشاهده
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                                            <p class="text-muted mb-0">ما دانش اموز ها را جستجو کرده ایم، هیچ دانش آموز برای جستجوی شما پیدا نکردیم.</p>
                                        </div>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>

                        <div class="d-flex justify-content-end">
                            <div class="pagination-wrap hstack gap-2">
                                {{$students ->links('layouts.manager.pagination')}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/admin/assets/libs/prismjs/prism.js"></script>
        <script src="/admin/assets/libs/list.js/list.min.js"></script>
        <script src="/admin/assets/libs/list.pagination.js/list.pagination.min.js"></script>
        <script src="/admin/assets/js/pages/listjs.init.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Livewire.on('open-selfie-modal', () => {
                    new bootstrap.Modal(document.getElementById('selfieModal')).show();
                });

                Livewire.on('open-national-modal', () => {
                    new bootstrap.Modal(document.getElementById('nationalCardModal')).show();
                });
            });
        </script>

    @endpush
</div>
