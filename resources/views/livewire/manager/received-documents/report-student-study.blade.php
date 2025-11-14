<div>
    @push('link')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 300px; /* یا هر عرضی که می‌خوای */
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">لیست مستندات گزارش درسی دانش اموزان ارسالی از ادمین ها</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="listjs-table" id="customerList">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">

                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-card mt-3 mb-1">
                            <table class="table align-middle table-nowrap" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th class="sort" data-sort="customer_name">ارسال کننده</th>
                                    <th class="sort" data-sort="phone">متن پیغام</th>
                                    <th class="sort" data-sort="action">فایل</th>
                                    <th class="sort" data-sort="date">تاریخ ارسال </th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($receivedFiles as $file)
                                    <tr wire:key="student-{{ $file->id }}">
                                        <th scope="row">
                                            {{$loop->iteration + $receivedFiles->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name">{{ $file->sender?->name ?? '---' }}</td>
                                        <td class="phone"> <p class="wrap-text">{{ $file->message ?? 'ندارد' }}</p></td>
                                        <td class="phone"> <a href="{{ asset($file->file_path) }}" class="wrap-text">مشاهده</a></td>


                                        <td class="time">{{jalali($file->created_at)->format('%d %B %Y | H:i')}}</td>
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
                                {{$receivedFiles->links('layouts.manager.pagination')}}
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

