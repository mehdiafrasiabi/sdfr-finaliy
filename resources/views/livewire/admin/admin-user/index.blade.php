<div>
    <div class="row">
        @if(session()->has('message'))

            <div class="alert alert-icon-left alert-light-success alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <svg data-bs-dismiss="alert"> ...</svg>
                </button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-check-square">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <strong>پیغام !</strong>
                {{ session()->get('message') }}
            </div>

        @endif
        {{--form--}}
        <div class="col-md-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>مدیریت ادمین ها</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">

                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label for="name" class="form-label">نام ادمین:
                                    <sup style="color: red">*</sup>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" wire:model="name"
                                       placeholder="">
                            </div>
                        </div>
                        @error('name')
                        <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
                             wire:loading.remove
                             wire:loading.remove>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <strong>خطا !</strong> <br>
                            {{$message}}
                        </div>
                        @enderror
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label for="email" class="form-label"> ایمیل:
                                    <sup style="color: red">*</sup>
                                </label>
                                <input type="text" class="form-control" id="email"  name="email" wire:model="email"
                                       placeholder="">
                            </div>
                        </div>
                        @error('email')
                        <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
                             wire:loading.remove
                             wire:loading.remove>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <strong>خطا !</strong> <br>
                            {{$message}}
                        </div>
                        @enderror
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label for="mobile" class="form-label"> موبایل:
                                    <sup style="color: red">*</sup>
                                </label>
                                <input type="text" class="form-control" id="mobile" name="mobile" wire:model="mobile"
                                       placeholder="">
                            </div>
                        </div>
                        @error('mobile')
                        <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
                             wire:loading.remove
                             wire:loading.remove>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <strong>خطا !</strong> <br>
                            {{$message}}
                        </div>
                        @enderror

                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label class="form-label" for="roles">انتخاب نقش :</label>
                                <sup style="color: red">*</sup>
                                <select class="form-select" id="roles" wire:model="selectedRoles" multiple>
                                    @foreach($roles as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @error('selectedRoles')
                        <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
                             wire:loading.remove
                             wire:loading.remove>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <strong>خطا !</strong> <br>
                            {{$message}}
                        </div>
                        @enderror
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <label class="form-label" for="country">انتخاب دسترسی :</label>
                                <sup style="color: red">*</sup>
                                <select class="form-select" id="country" wire:model="selectedPermissions" multiple>
                                    @foreach($permissions as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @error('selectedPermissions')
                        <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert"
                             wire:loading.remove
                             wire:loading.remove>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                            <strong>خطا !</strong> <br>
                            {{$message}}
                        </div>
                        @enderror

                        <button type="submit" class="btn btn-success btn-lg _effect--ripple waves-effect waves-light">
                            <span wire:loading.remove>ثبت</span>
                            <div wire:loading>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-loader spin me-2">
                                    <line x1="12" y1="2" x2="12" y2="6"></line>
                                    <line x1="12" y1="18" x2="12" y2="22"></line>
                                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                    <line x1="2" y1="12" x2="6" y2="12"></line>
                                    <line x1="18" y1="12" x2="22" y2="12"></line>
                                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                </svg>
                            </div>
                        </button>
                    </form>

                </div>
            </div>


        </div>
        {{--list--}}
        <div class="col-md-8">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>لیست ادمین ها</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">نام ادمین</th>
                                <th scope="col">اطلاعات تماس</th>
                                <th class="text-center" scope="col">نقش</th>
                                <th class="text-center" scope="col">دسترسی</th>
                                <th class="text-center" scope="col">برنامه کاری</th>


                                <th class="text-center" scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($admins as $admin)

                                <tr>
                                    <td>
                                        {{$loop->index+1}}
                                    </td>
                                    <td>
                                        <div class="media">

                                            <div class="media-body align-self-center">
                                                <h6 class="mb-0">{{$admin->name}}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>

                                        {{@$admin->email}}
                                        <br>
                                        {{@$admin->mobile}}
                                    </td>
                                    <td class="text-center">
                                        @foreach($admin->roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @foreach($admin->roles as $role)
                                            @foreach($role->permissions as $permission)
                                                <div>{{$permission->name}}</div>
                                            @endforeach
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $activeSchedules = $admin->workSchedules->where('is_active', true)->sortBy('day_of_week');
                                            $dayMap = \App\Models\AdminWorkSchedule::DAYS;
                                        @endphp
                                        @forelse($activeSchedules as $schedule)
                                            <div class="small">
                                                <strong>{{ $dayMap[$schedule->day_of_week] ?? '' }}</strong>
                                                از {{ substr($schedule->start_time, 0, 5) }}
                                                تا {{ substr($schedule->end_time, 0, 5) }}
                                            </div>
                                        @empty
                                            <span class="text-muted small">تعریف نشده</span>
                                        @endforelse
                                        <a href="{{ route('admin.admin-user.work-schedule', $admin->id) }}"
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            مدیریت برنامه کاری
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btns">

                                            <a href="javascript:void(0);" wire:click="edit({{$admin->id}})"
                                               class="action-btn btn-edit bs-tooltip me-2"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-bs-original-title="ویرایش">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-edit-2">
                                                    <path
                                                        d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0);"
                                               wire:confirm="آیا از انتخاب خود اطمینان دارید؟"
                                               wire:click="delete({{$admin->id}})"
                                               class="action-btn btn-delete bs-tooltip"
                                               data-toggle="tooltip" data-placement="top" title=""
                                               data-bs-original-title="حدف">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                            @endforeach


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
