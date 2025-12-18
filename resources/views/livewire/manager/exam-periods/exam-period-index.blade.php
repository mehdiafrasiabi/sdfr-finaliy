<div>

    <div class="container-fluid">

        <!-- Page Header -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">

                            <i class="ti ti-calendar me-2"></i>

                            دوره‌های زمانی آزمون

                        </h4>

                        <button wire:click="openModal" class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            افزودن دوره جدید

                        </button>

                    </div>

                </div>

            </div>

        </div>


        <!-- List Card -->

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        @if($periods->isEmpty())

                            <div class="text-center py-5">

                                <i class="ti ti-calendar-off text-muted" style="font-size: 4rem;"></i>

                                <h5 class="mt-3 text-muted">دوره زمانی‌ای یافت نشد</h5>

                                <p class="text-muted">برای شروع، یک دوره زمانی جدید ایجاد کنید.</p>

                            </div>

                        @else

                            <div class="table-responsive">

                                <table class="table table-hover">

                                    <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>نام دوره</th>

                                        <th>مقدار</th>

                                        <th>ترتیب</th>

                                        <th>وضعیت</th>

                                        <th>عملیات</th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    @foreach($periods as $period)

                                        <tr wire:key="period-{{ $period->id }}">

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $period->name }}</td>

                                            <td><code>{{ $period->value }}</code></td>

                                            <td>{{ $period->order }}</td>

                                            <td>

                                                <button wire:click="toggleActive({{ $period->id }})"

                                                        class="btn btn-sm {{ $period->is_active ? 'btn-success' : 'btn-secondary' }}">

                                                    {{ $period->is_active ? 'فعال' : 'غیرفعال' }}

                                                </button>

                                            </td>

                                            <td>

                                                <div class="d-flex gap-2">

                                                    <button wire:click="edit({{ $period->id }})"

                                                            class="btn btn-sm btn-outline-primary">

                                                        <i class="ti ti-edit"></i>

                                                    </button>

                                                    <button wire:click="delete({{ $period->id }})"

                                                            wire:confirm="آیا از حذف این دوره اطمینان دارید؟"

                                                            class="btn btn-sm btn-outline-danger">

                                                        <i class="ti ti-trash"></i>

                                                    </button>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            </div>



                            <div class="mt-4">

                                {{ $periods->links('layouts.manager.pagination') }}

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Modal -->

    @if($showModal)

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            {{ $editingId ? 'ویرایش دوره زمانی' : 'افزودن دوره زمانی' }}

                        </h5>

                        <button type="button" class="btn-close" wire:click="closeModal"></button>

                    </div>

                    <form wire:submit="save">

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">نام دوره <span class="text-danger">*</span></label>

                                <input type="text"

                                       wire:model="name"

                                       class="form-control"

                                       placeholder="مثال: ۱۴۰۴-۱۴۰۵">

                                @error('name')

                                <div class="text-danger small mt-1">{{ $message }}</div>

                                @enderror

                            </div>


                            <div class="mb-3">

                                <label class="form-label">مقدار <span class="text-danger">*</span></label>

                                <input type="text"

                                       wire:model="value"

                                       class="form-control"

                                       placeholder="مثال: 1404-1405"

                                       dir="ltr">

                                @error('value')

                                <div class="text-danger small mt-1">{{ $message }}</div>

                                @enderror

                            </div>


                            <div class="mb-3">

                                <label class="form-label">ترتیب <span class="text-danger">*</span></label>

                                <input type="number"

                                       wire:model="order"

                                       class="form-control"

                                       min="0">

                                @error('order')

                                <div class="text-danger small mt-1">{{ $message }}</div>

                                @enderror

                            </div>


                            <div class="form-check">

                                <input type="checkbox"

                                       wire:model="is_active"

                                       class="form-check-input"

                                       id="is_active">

                                <label class="form-check-label" for="is_active">

                                    فعال

                                </label>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" wire:click="closeModal">

                                انصراف

                            </button>

                            <button type="submit" class="btn btn-primary">

                                {{ $editingId ? 'ذخیره تغییرات' : 'ایجاد' }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>
