<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard.index')}}">
                        <i class="fi fi-rr-home">
                        </i>
                        صفحه اصلی
                    </a>
                </li>
                <li aria-current="page" class="breadcrumb-item active">

                </li>
            </ol>
        </nav>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">
                        اتاق مشاوره
                    </h6>
                    <button wire:click="openStudentSelectModal"
                            class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="fi fi-rr-calendar-plus"></i>
                        تعریف جلسه خودکار
                    </button>
                </div>

                <div class="card-body p-0 pb-2">
                    <div id="dt_basic_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">

                        <!-- Top Controls -->
                        <div class="row mt-2 justify-content-between mx-2 py-2">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                                <div class="dt-length">
                                    <label for="dt-length-0"> در هرصفحه</label>:
                                    <select
                                        aria-controls="dt_basic"
                                        class="form-select form-select-sm"
                                        id="dt-length-0"
                                    >
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>

                                </div>
                            </div>

                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
                                <div >
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="search"
                                        wire:model.live.debounce.350ms="search"
                                        name="search"
                                        placeholder="جستجو"
                                    />
                                    <label for="dt-search-0"></label>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="row mt-2 justify-content-between ">
                            <div
                                class="d-md-flex justify-content-between align-items-center col-12  col-md">
                                <table class="table display"  style="width: 100%;">


                                    <thead class="table-light">
                                    <tr>
                                        <th data-dt-column="0">#</th>
                                        <th data-dt-column="1">دانش آموز</th>
                                        <th data-dt-column="2">پدر</th>
                                        <th data-dt-column="3">مادر</th>
                                        <th data-dt-column="4">پایه + رشته</th>
                                        <th data-dt-column="5"></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                {{$loop->iteration + $students->firstItem() - 1}}
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            @php
                                                                $profile = $student->user->profile ?? null;
                                                                $personalInfo = $student->user->personalInformation ?? null;
                                                            @endphp
                                                            @if($profile && $profile->picture)
                                                                <img
                                                                    src="{{ asset('user/img/' . $student->user->id . '/' . $profile->picture) }}"
                                                                    alt="{{ $personalInfo->name ?? '' }}"
                                                                    class="rounded-circle"
                                                                    style="width:40px;height:40px;object-fit:cover;"
                                                                />
                                                            @elseif($profile && $profile->gender === 'male')
                                                                <span
                                                                    class="avatar-title rounded-circle bg-primary-subtle text-primary fs-4">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 -7.5 154 154" fill="none" >
                                                                                    <g clip-path="url(#clip0)">
                                                                                    <path d="M76.6855 71.5615C65.6857 70.9757 55.8861 69.1054 47.7116 62.3051C41.7686 57.3606 38.2463 50.9588 36.7987 43.4632C33.6317 27.0857 42.6463 12.8772 56.1894 5.89224C65.6228 1.0266 75.5581 -0.855128 86.1177 0.77602C95.1291 2.16731 101.72 7.2487 107.058 14.2679C108.865 16.6451 110.987 18.9033 112.244 21.5559C114.841 27.0416 117.049 32.6689 115.551 39.0629C114.148 45.0425 112.412 50.8119 108.506 55.6989C101.648 64.2794 92.9826 69.7286 81.8758 70.9402C79.8933 71.1555 77.9212 71.4128 76.6855 71.5615ZM46.4202 39.9973C47.6111 48.8674 52.006 55.2204 60.2239 58.5021C63.5488 59.7191 67.0069 60.538 70.5253 60.941C81.8888 62.547 91.6425 59.6001 98.8685 50.236C102.12 46.2124 103.704 41.0997 103.295 35.9481C94.099 34.2445 85.7793 31.1639 79.0382 24.457C70.3509 34.3228 58.8442 37.9304 46.4181 39.9973H46.4202Z" fill="#000000"/>
                                                                                    <path d="M95.7339 137.907C81.8738 137.907 68.013 137.836 54.1544 137.928C42.1912 138.007 30.2319 138.35 18.2669 138.403C14.9469 138.425 11.6336 138.098 8.38211 137.429C2.30359 136.153 0.343106 132.725 1.87245 126.697C3.08673 121.913 5.56776 117.71 8.18172 113.601C18.7984 96.8872 33.9953 85.9476 52.2918 78.9375C62.2227 75.0506 72.7768 72.9883 83.444 72.8493C98.8963 72.7342 113.246 76.4697 125.57 86.4402C134.336 93.4348 141.524 102.193 146.667 112.145C148.186 115.095 149.811 118.012 151.039 121.082C152.127 123.729 152.905 126.492 153.359 129.317C154.013 133.713 151.627 136.648 147.246 137.176C144.173 137.575 141.079 137.797 137.979 137.84C123.898 137.91 109.816 137.871 95.7339 137.871V137.907ZM142.397 127.433C142.448 126.851 142.543 126.609 142.475 126.426C137.953 114.457 131.542 103.723 121.671 95.3336C115.882 90.4143 109.494 86.4861 102.035 84.5505C92.1086 82.062 81.7668 81.6935 71.6876 83.4695C46.6982 87.5845 26.735 99.7092 13.1076 121.389C11.658 123.695 10.5034 126.188 9.13486 128.735C13.6821 130.051 17.8222 129.526 22.0271 129.272C30.2015 128.779 38.4038 128.6 46.5978 128.555C72.3204 128.416 98.0445 128.432 123.767 128.31C129.522 128.283 135.275 127.94 141.028 127.725C141.492 127.664 141.95 127.566 142.397 127.431V127.433Z" fill="#000000"/>
                                                                                    </g>
                                                                                    <defs>
                                                                                    <clipPath id="clip0">
                                                                                    <rect width="153" height="139" fill="white" transform="translate(0.777344)"/>
                                                                                    </clipPath>
                                                                                    </defs>
                                                                                    </svg>
                                                                </span>
                                                            @elseif($profile && $profile->gender === 'female')
                                                                <span
                                                                    class="avatar-title rounded-circle bg-danger-subtle text-danger fs-4">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="24px" height="24px" viewBox="0 0 32 32" version="1.1" class="w-5 h-5">
                                                                                <path
                                                                                    d="M30.001 25.084l-8.703-4.127c1.161-0.582 5.695-0.767 6.070-1.79 0 0-1.792-2.75-2.229-6.323-0.17-1.386-0.461-3.206-0.75-5.769-0.469-4.157-3.965-7.075-8.381-7.075h-0.016c-4.416 0-7.912 2.919-8.38 7.075-0.289 2.563-0.58 4.382-0.75 5.769-0.438 3.573-2.229 6.323-2.229 6.323 0.375 1.023 4.909 1.208 6.071 1.79l-8.704 4.128s-1.999 0.702-1.999 2.358v2.642c0 1.105 0.894 1.916 1.999 1.916h28.002c1.105 0 1.999-0.811 1.999-1.916v-2.642c0-1.657-1.999-2.358-1.999-2.358zM2 30v-2.558c0-0.107 0.378-0.363 0.685-0.48 0.067-0.023 0.107-0.042 0.17-0.072l8.703-4.127c0.691-0.327 1.135-1.021 1.144-1.786s-0.42-1.468-1.104-1.81c-0.678-0.34-1.573-0.508-2.976-0.751-0.333-0.058-0.788-0.14-1.229-0.229 0.572-1.285 1.205-3.081 1.454-5.114 0.062-0.506 0.14-1.075 0.229-1.706 0.152-1.073 0.339-2.434 0.524-4.069 0.349-3.090 2.977-5.299 6.393-5.299h0.016c3.416 0 6.045 2.209 6.393 5.299 0.184 1.635 0.372 2.997 0.523 4.069 0.088 0.63 0.167 1.2 0.229 1.706 0.249 2.032 0.882 3.829 1.454 5.114-0.442 0.088-0.896 0.17-1.23 0.229-1.404 0.243-2.299 0.411-2.977 0.751-0.683 0.343-1.111 1.046-1.104 1.811 0.009 0.764 0.452 1.459 1.143 1.786l8.703 4.127c0.063 0.030 0.104 0.049 0.17 0.072 0.308 0.117 0.64 0.373 0.686 0.48l0.001 2.557h-28.001z"/>
                                                                            </svg>
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="avatar-title rounded-circle bg-secondary-subtle text-secondary fs-4">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                                         fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg"
                                                                         class="w-5 h-5 text-blue-600 dark:text-blue-400">

                                                                          <!-- سر -->
                                                                          <path
                                                                              d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                                                              stroke="currentColor" stroke-width="1.5"
                                                                              stroke-linecap="round"
                                                                              stroke-linejoin="round"/>

                                                                        <!-- بدن -->
                                                                          <path
                                                                              d="M4 20C4 16.6863 7.58172 14 12 14C16.4183 14 20 16.6863 20 20"
                                                                              stroke="currentColor" stroke-width="1.5"
                                                                              stroke-linecap="round"
                                                                              stroke-linejoin="round"/>

                                                                        </svg>

                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column">
                                                          <span class="text-truncate fw-medium">
                                                            {{ $personalInfo->name ?? '-' }}
                                                              {{ $personalInfo->name_full ?? '' }}
                                                        </span>
                                                        <small class="text-truncate text-muted">
                                                            {{ $student->user->mobile ?? '' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                {{$student->user->personalInformation->father_mobile}}
                                            </td>

                                            <td>
                                                {{$student->user->personalInformation->mother_mobile}}
                                            </td>

                                            <td>
                                                @if($student->user->personalInformation->grade == 12)
                                                    دوازدهم
                                                @elseif($student->user->personalInformation->grade == 11)
                                                    یازدهم
                                                @elseif($student->user->personalInformation->grade == 10)
                                                    دهم
                                                @endif

                                                @if($student->user->personalInformation->field == 'math')
                                                    ریاضی
                                                @elseif($student->user->personalInformation->field == 'experimental')
                                                    تجربی
                                                @elseif($student->user->personalInformation->field == 'human')
                                                    انسانی
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group float-end">
                                                    <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                                        <i class="fi fi-rr-menu-dots">
                                                        </i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('admin.student.advising-sessions.create',$student->payment->order->user->id)}}">
                                                               ایجاد جلسه مشاوره
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-danger text-center">
                                                وجود ندارد
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>

                                </table>


                            </div>
                        </div>

                        <!-- Bottom Pagination -->
                        <div class="row mt-2 justify-content-between">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                            </div>

                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
                                <div class="dt-paging">
                                    {{ $students->links('layouts.admin.pagination') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ==================== Modal 1: انتخاب دانش‌آموزان ==================== --}}
    @if($showStudentSelectModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width:720px;">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fi fi-rr-users me-2"></i>
                            انتخاب دانش‌آموزان برای تعریف جلسه خودکار
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeStudentSelectModal"></button>
                    </div>

                    <div class="modal-body p-0">
                        <!-- Search -->
                        <div class="p-3 border-bottom bg-light">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fi fi-rr-search"></i></span>
                                <input type="text"
                                       class="form-control"
                                       wire:model.live.debounce.300ms="studentSearch"
                                       placeholder="جستجو بر اساس نام، نام خانوادگی یا موبایل..."
                                       autofocus />
                            </div>
                        </div>

                        <!-- Selected count badge -->
                        @if(count($selectedStudents) > 0)
                            <div class="p-2 px-3 bg-primary-subtle border-bottom">
                                <span class="badge bg-primary rounded-pill me-1">{{ count($selectedStudents) }}</span>
                                دانش‌آموز انتخاب شده
                            </div>
                        @endif

                        <!-- Student List -->
                        <div style="max-height: 420px; overflow-y: auto;">
                            @forelse($modalStudents as $mStudent)
                                @php
                                    $mProfile = $mStudent->user->profile ?? null;
                                    $mInfo    = $mStudent->user->personalInformation ?? null;
                                    $isSelected = in_array($mStudent->id, $selectedStudents);
                                @endphp
                                <div wire:click="toggleStudentSelection({{ $mStudent->id }})"
                                     class="d-flex align-items-center p-3 border-bottom cursor-pointer
                                        {{ $isSelected ? 'bg-primary-subtle' : 'hover-bg-light' }}"
                                     style="cursor:pointer; transition: background 0.15s;">

                                    <!-- Checkbox -->
                                    <div class="me-3">
                                        <div class="form-check mb-0">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   style="width:18px;height:18px;"
                                                   @checked($isSelected)
                                                   onclick="return false;" />
                                        </div>
                                    </div>

                                    <!-- Avatar -->
                                    <div class="me-3">
                                        @if($mProfile && $mProfile->picture)
                                            <img src="{{ asset('user/img/' . $mStudent->user->id . '/' . $mProfile->picture) }}"
                                                 class="rounded-circle"
                                                 style="width:42px;height:42px;object-fit:cover;" />
                                        @elseif($mProfile && $mProfile->gender === 'female')
                                            <div class="avatar-title rounded-circle bg-danger-subtle text-danger"
                                                 style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                                                <i class="fi fi-rr-user"></i>
                                            </div>
                                        @else
                                            <div class="avatar-title rounded-circle bg-primary-subtle text-primary"
                                                 style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                                                <i class="fi fi-rr-user"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">
                                            {{ $mInfo->name ?? '-' }} {{ $mInfo->name_full ?? '' }}
                                        </div>
                                        <div class="text-muted small d-flex gap-3 mt-1 flex-wrap">
                                        <span>
                                            <i class="fi fi-rr-smartphone me-1"></i>
                                            {{ $mStudent->user->mobile ?? '-' }}
                                        </span>
                                            @if($mInfo)
                                                <span>
                                            پایه:
                                            @if($mInfo->grade == 12) دوازدهم
                                                    @elseif($mInfo->grade == 11) یازدهم
                                                    @elseif($mInfo->grade == 10) دهم
                                                    @else -
                                                    @endif
                                        </span>
                                                <span>
                                            رشته:
                                            @if($mInfo->field == 'math') ریاضی
                                                    @elseif($mInfo->field == 'experimental') تجربی
                                                    @elseif($mInfo->field == 'human') انسانی
                                                    @else -
                                                    @endif
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Selected checkmark -->
                                    @if($isSelected)
                                        <div class="text-primary ms-2">
                                            <i class="fi fi-rr-check-circle" style="font-size:1.3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <i class="fi fi-rr-user-slash" style="font-size:2rem;"></i>
                                    <p class="mt-2">دانش‌آموزی یافت نشد.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="modal-footer border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" wire:click="closeStudentSelectModal">
                            انصراف
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="proceedToSchedule">
                            <i class="fi fi-rr-arrow-left me-1"></i>
                            ادامه ({{ count($selectedStudents) }} نفر انتخاب شده)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== Modal 2: تنظیم روز و ساعت ==================== --}}
    @if($showScheduleModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fi fi-rr-calendar me-2"></i>
                            تنظیم روز و ساعت جلسات
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeScheduleModal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- اطلاعیه -->
                        <div class="alert alert-info d-flex align-items-start gap-2 mb-4" role="alert">
                            <i class="fi fi-rr-info mt-1"></i>
                            <div class="small">
                                برای هر دانش‌آموز انتخاب‌شده، <strong>۴ جلسه</strong> به صورت خودکار در روز و ساعت مشخص‌شده ثبت می‌شود.
                                هر جلسه یک هفته پس از جلسه قبلی برگزار می‌شود.
                                عنوان هر جلسه تاریخ شمسی آن جلسه و توضیحات «جلسه مشاوره فردی» خواهد بود.
                            </div>
                        </div>

                        <!-- دانش‌آموزان انتخابی -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">دانش‌آموزان انتخاب‌شده</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($modalStudents->whereIn('id', $selectedStudents) as $selStudent)
                                    @php $selInfo = $selStudent->user->personalInformation ?? null; @endphp
                                    <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3 py-2">
                                    <i class="fi fi-rr-user me-1"></i>
                                    {{ $selInfo->name ?? '-' }} {{ $selInfo->name_full ?? '' }}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- روز هفته -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                روز هفته
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('selectedDay') is-invalid @enderror"
                                    wire:model="selectedDay">
                                <option value="">-- روز را انتخاب کنید --</option>
                                <option value="6">شنبه</option>
                                <option value="0">یکشنبه</option>
                                <option value="1">دوشنبه</option>
                                <option value="2">سه‌شنبه</option>
                                <option value="3">چهارشنبه</option>
                                <option value="4">پنج‌شنبه</option>
                                <option value="5">جمعه</option>
                            </select>
                            @error('selectedDay')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ساعت و دقیقه -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                ساعت برگزاری
                                <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="flex-fill">
                                    <label class="form-label small text-muted">ساعت</label>
                                    <select class="form-select @error('selectedHour') is-invalid @enderror"
                                            wire:model="selectedHour">
                                        @for($h = 0; $h <= 23; $h++)
                                            <option value="{{ $h }}">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    @error('selectedHour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="pt-3 fw-bold text-muted">:</div>
                                <div class="flex-fill">
                                    <label class="form-label small text-muted">دقیقه</label>
                                    <select class="form-select @error('selectedMinute') is-invalid @enderror"
                                            wire:model="selectedMinute">
                                        @foreach([0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55] as $m)
                                            <option value="{{ $m }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedMinute')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- محل برگزاری -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                محل برگزاری
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('autoLocationType') is-invalid @enderror"
                                    wire:model.live="autoLocationType">
                                <option value="online">مجازی (آنلاین)</option>
                                <option value="in_person">حضوری</option>
                            </select>
                            @error('autoLocationType')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- لینک آنلاین -->
                        @if($autoLocationType === 'online')
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    لینک جلسه آنلاین
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="url"
                                       class="form-control @error('autoSkyroomLink') is-invalid @enderror"
                                       wire:model="autoSkyroomLink"
                                       placeholder="https://..." />
                                @error('autoSkyroomLink')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                    </div>

                    <div class="modal-footer border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" wire:click="backToStudentSelect">
                            <i class="fi fi-rr-arrow-right me-1"></i>
                            بازگشت
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" wire:click="closeScheduleModal">
                                انصراف
                            </button>
                            <button type="button" class="btn btn-success" wire:click="createAutoSessions"
                                    wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="createAutoSessions">
                                <i class="fi fi-rr-check me-1"></i>
                                ثبت جلسات
                            </span>
                                <span wire:loading wire:target="createAutoSessions">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                در حال ثبت...
                            </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
