<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">لیست پشتیبان ها</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control search" placeholder="جستجو...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <form wire:submit.prevent="export" class="me-2">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="ri-download-2-fill align-middle me-1"></i> خروجی اکسل پشتیبان‌ها
                                </button>
                            </form>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">نام ونام خانوادگی</th>
                                    <th class="sort" data-sort="phone">ایمیل</th>
                                    <th class="sort" data-sort="action">موبایل</th>
                                    <th class="sort" data-sort="status">دانش اموزان</th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($supporters as $supporter)
                                    <tr>
                                        <th scope="row">
                                            {{$loop->iteration + $supporters->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">{{ $supporter->name }}</td>
                                        <td class="phone">{{ $supporter->email }}</td>
                                        <td class="phone">{{ $supporter->mobile }}</td>
                                        <td class="status">
                                            <a href="{{ route('manager.supporter.student', $supporter->id) }}"
                                               class="bg-primary text-white px-3 py-1 rounded-lg hover:bg-primary/90">
                                               تعداد :
                                                ( {{ $supporter->students_count }} )
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
                                {{$supporters->links('layouts.manager.pagination')}}
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->

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

