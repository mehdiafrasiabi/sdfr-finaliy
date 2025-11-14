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
                    <h4 class="card-title mb-0">لیست درخواست های ارتباط با ما وبسایت</h4>
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
                                    <th class="sort" data-sort="customer_name">نام ونام خانوادگی</th>
                                    <th class="sort" data-sort="phone">تلفن تماس</th>
                                    <th class="sort" data-sort="phone">متن پیغام</th>
                                    <th class="sort" data-sort="action">وضعیت تماس</th>
                                    <th class="sort" data-sort="status">تاریخ اخرین وضعیت</th>
                                    <th class="sort" data-sort="date">تاریخ ثبت درخواست </th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($contactUs as $item)
                                    <tr wire:key="student-{{ $item->id }}">
                                        <th scope="row">
                                            {{$loop->iteration + $contactUs->firstItem() - 1}}
                                        </th>
                                        <td class="customer_name"> {{$item->name}}</td>
                                        <td class="phone"> {{$item->mobile}}</td>
                                        <td class="phone"> <p class="wrap-text">{{$item->text}}</p></td>
                                        <td class="status">
                                            <select
                                                wire:confirm="آیا از انتخاب خود برای تغییر وضعیت اطمینان دارید ؟"
                                                wire:change="changeStatus({{$item->id}},$event.target.value)"
                                                    class="form-select rounded-pill mb-3  text-{{$item->statusColor}}">
                                                <option value="pending" {{$item->status=='pending' ? 'selected' :''}}>درانتظار تماس
                                                    تماس
                                                </option>
                                                <option value="completed" {{$item->status=='completed' ? 'selected' :''}}>تماس گرفته
                                                    شد
                                                </option>
                                                <option value="canceled" {{$item->status=='canceled' ? 'selected' :''}}>پاسخ داده شده
                                                    نشده
                                                </option>
                                            </select>
                                        </td>
                                        <td class="time">{{jalali($item->updated_at)->format('%d %B %Y | H:i')}}</td>


                                        <td class="time">{{jalali($item->created_at)->format('%d %B %Y | H:i')}}</td>
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
                                {{$contactUs->links('layouts.manager.pagination')}}
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

