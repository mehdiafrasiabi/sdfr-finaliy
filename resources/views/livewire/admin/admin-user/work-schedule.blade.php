<div>
    <div class="row">
        <div class="col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                برنامه کاری ادمین: {{ $admin->name }}
                            </h4>
                            <a href="{{ route('admin.admin-user.index') }}" class="btn btn-outline-secondary">
                                بازگشت به لیست ادمین‌ها
                            </a>
                        </div>
                    </div>
                </div>

                <div class="widget-content widget-content-area">

                    @if (session()->has('message'))
                        <div class="alert alert-light-success alert-dismissible fade show border-0 mb-4" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>انجام شد!</strong> {{ session('message') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        روزهای کاری ادمین را فعال کرده و ساعت شروع و پایان هر روز را مشخص کنید.
                        برای روزهایی که ادمین کار نمی‌کند، تیک فعال‌سازی را بردارید.
                    </p>

                    <form wire:submit.prevent="save">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th style="width: 120px;">روز</th>
                                    <th style="width: 120px;" class="text-center">فعال</th>
                                    <th class="text-center">ساعت شروع</th>
                                    <th class="text-center">ساعت پایان</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($days as $day => $dayName)
                                    <tr>
                                        <td><strong>{{ $dayName }}</strong></td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="enabled_{{ $day }}"
                                                    wire:model.live="schedules.{{ $day }}.enabled"
                                                >
                                            </div>
                                        </td>
                                        <td>
                                            <input
                                                type="time"
                                                class="form-control"
                                                wire:model="schedules.{{ $day }}.start_time"
                                                @disabled(empty($schedules[$day]['enabled']))
                                            >
                                            @error("schedules.$day.start_time")
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input
                                                type="time"
                                                class="form-control"
                                                wire:model="schedules.{{ $day }}.end_time"
                                                @disabled(empty($schedules[$day]['enabled']))
                                            >
                                            @error("schedules.$day.end_time")
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success _effect--ripple waves-effect waves-light">
                                <span wire:loading.remove wire:target="save">ذخیره برنامه کاری</span>
                                <span wire:loading wire:target="save">در حال ذخیره...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

