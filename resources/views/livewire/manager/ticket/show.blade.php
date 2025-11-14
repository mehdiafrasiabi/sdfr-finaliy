<div>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-n4 mx-n4 mb-n5">
                    <div class="bg-info-subtle">
                        <div class="card-body pb-4 mb-5">
                            <div class="row">
                                <div class="col-md">
                                    <div class="row align-items-center">
                                        <div class="col-md-auto">
                                            <div class="avatar-md mb-md-0 mb-4">
                                                <div class="avatar-title bg-white rounded-circle">
                                                    <img src="/manager/assets/images/companies/img-4.png" alt=""
                                                         class="avatar-sm">
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md">
                                            <h4 class="fw-semibold" id="ticket-title">#تیکت - {{$ticket->title}}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div class="text-muted">تاریخ ایجاد:<span class="fw-medium "
                                                                                          id="create-date">{{jalali($ticket->created_at)->format('%d %B %Y | H:i')}}</span>
                                                </div>
                                                <div class="vr"></div>
                                                <div class="text-muted">تاریخ انجام:<span class="fw-medium"
                                                                                          id="due-date">{{jalali($ticket->updated_at)->format('%d %B %Y | H:i')}}</span>
                                                </div>
                                                <div class="vr"></div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end col-->
                                <div class="col-md-auto mt-md-0 mt-4">
                                    <div class="hstack gap-1 flex-wrap">
                                        <button type="button" class="btn avatar-xs mt-n1 p-0 favourite-btn active">
                                                        <span class="avatar-title bg-transparent fs-15">
                                                            <i class="ri-star-fill"></i>
                                                        </span>
                                        </button>
                                        <button type="button" class="btn py-0 fs-16 text-body" id="settingDropdown"
                                                data-bs-toggle="dropdown">
                                            <i class="ri-share-line"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="settingDropdown">
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="ri-eye-fill align-bottom me-2 text-muted"></i>مشاهده کنید</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="ri-share-forward-fill align-bottom me-2 text-muted"></i>اشتراک
                                                    گذاری با</a></li>
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>حذف
                                                    کنید</a></li>
                                        </ul>
                                        <button type="button" class="btn py-0 fs-16 text-body">
                                            <i class="ri-flag-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div><!-- end card body -->
                    </div>
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row">
            <div class="col-xxl-9">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="mt-4">
                            <h6 class="fw-semibold text-uppercase mb-3">توجه کنید!!!</h6>
                            <div class="language-markup rounded-2">
                                  <pre>
                                                برای حفظ احترام متقابل، افزایش رضایت کاربران، و ارتقاء سطح خدمات، لطفاً در هنگام پاسخ‌گویی به تیکت‌ها به نکات زیر توجه فرمایید:

            ✅ ۱. شروع با احترام
            همواره پاسخ خود را با یک سلام یا جمله‌ای محترمانه آغاز کنید:

            «سلام وقت شما بخیر،»
            «کاربر گرامی، سلام و سپاس از پیام شما.»

            ✅ ۲. لحن پاسخ
            از لحن محترمانه، صبور و دوستانه استفاده کنید.

            حتی اگر کاربر لحن مناسبی نداشت، شما حرفه‌ای برخورد کنید.

            از کلمات عامیانه، طعنه‌آمیز یا دستوری خودداری کنید.

            ✅ ۳. پاسخ شفاف و دقیق
            به‌صورت واضح و ساده توضیح دهید.

            اگر مشکل کاربر فنی است، راه‌حل مرحله به مرحله ارائه دهید.

            از دادن پاسخ کلی یا گمراه‌کننده پرهیز کنید.

            ✅ ۴. ارجاع مناسب
            اگر تیکت مربوط به بخش شما نیست:

            «موضوع تیکت شما به دپارتمان [نام دپارتمان] مربوط می‌شود. لطفاً کمی صبور باشید تا توسط همکاران آن بخش بررسی شود.»

            ✅ ۵. بستن تیکت
            فقط در صورت اطمینان از حل کامل مشکل، وضعیت تیکت را «بسته شده» (Closed) قرار دهید. پیش از آن، از کاربر تأیید بگیرید:

            «در صورت رفع مشکل، تیکت شما بسته خواهد شد. چنانچه مورد دیگری هست لطفاً اطلاع دهید.»

            ✅ ۶. امتیازدهی و بازخورد
            در صورت فعال بودن سیستم امتیازدهی، کاربر را تشویق به ثبت نظر کنید:

            «اگر از پاسخ ما رضایت داشتید، خوشحال می‌شویم امتیاز خود را ثبت کنید.»
                                            </pre>
                            </div>
                        </div>
                    </div>
                    <!--end card-body-->
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">گفت‌و‌گو</h5>

                        <div data-simplebar style="height: 300px;" class="px-3 mx-n3">
                            @foreach($ticket->messages as $msg)
                                @if($msg->user_id )
                                    <div class="d-flex mb-4 justify-content ">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('/manager/assets/images/users/avatar-1.jpg') }}" alt="آواتار"
                                                 class="avatar-xs rounded-circle">
                                        </div>
                                        <div
                                            class="flex-grow-1 ms-3  me-3 ">
                                            <h5 class="fs-14">
                                                {{ $msg->user->name ?? 'سیستم' }}
                                                <small class="text-muted ms-2">
                                                    {{ jalali($msg->created_at)->format('Y/m/d H:i') }}
                                                </small>
                                            </h5>
                                            <p class="text-muted mb-2">{{ $msg->message }}</p>

                                            @if($msg->attachment)
                                                @php
                                                    $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                                                    $path = asset("storage/ticket/{$msg->user_id}/file/{$msg->attachment}");
                                                @endphp
                                                <div class="mb-2">
                                                    @if(in_array($ext, ['jpg','jpeg','png','webp','gif']))
                                                        <img src="{{ $path }}" alt="پیوست" class="w-25 rounded shadow">
                                                    @elseif(in_array($ext, ['mp4','mov','avi','mkv','webm']))
                                                        <video src="{{ $path }}" controls
                                                               class="w-50 rounded shadow"></video>
                                                    @else
                                                        <a href="{{ $path }}" class="text-primary" target="_blank">دانلود
                                                            فایل</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                @elseif($msg->admin_id === auth()->id())
                                    <div class="d-flex mb-4 justify-content-end ">
                                        <div class="flex-shrink-0 text-end justify-content-end">
                                            <img src="{{ asset('/manager/assets/images/users/avatar-2.jpg') }}" alt="آواتار"
                                                 class="avatar-xs rounded-circle">
                                        </div>
                                        <div
                                            class="flex-grow-1 ms-3  me-3  ">
                                            <h5 class="fs-14 text-success">
                                                {{ $msg->admin?->name ?? 'پشتیبان' }}
                                                <small class="text-muted ms-2">
                                                    {{ jalali($msg->created_at)->format('Y/m/d H:i') }}
                                                </small>
                                            </h5>
                                            <p class="text-muted mb-2">{{ $msg->message }}</p>

                                            @if($msg->attachment)
                                                @php
                                                    $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                                                    $path = asset("storage/ticket/{$msg->user_id}/file/{$msg->attachment}");
                                                @endphp
                                                <div class="mb-2">
                                                    @if(in_array($ext, ['jpg','jpeg','png','webp','gif']))
                                                        <img src="{{ $path }}" alt="پیوست" class="w-25 rounded shadow">
                                                    @elseif(in_array($ext, ['mp4','mov','avi','mkv','webm']))
                                                        <video src="{{ $path }}" controls
                                                               class="w-50 rounded shadow"></video>
                                                    @else
                                                        <a href="{{ $path }}" class="text-primary" target="_blank">دانلود
                                                            فایل</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                @endif
                            @endforeach
                        </div>

                        <form wire:submit.prevent="submit" class="mt-4">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <label for="message" class="form-label">پاسخ شما</label>
                                    <textarea wire:model.defer="message" id="message"
                                              class="form-control @error('message') is-invalid @enderror"
                                              rows="4" placeholder="پاسخ خود را بنویسید..."></textarea>
                                    @error('message')
                                    <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label">پیوست (اختیاری)</label>
                                    <input type="file" wire:model="attachment"
                                           class="form-control @error('attachment') is-invalid @enderror">
                                    @error('attachment')
                                    <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                    @if($attachment)
                                        <div class="mt-2">
                                            <span
                                                class="text-muted">فایل انتخاب شده: {{ $attachment->getClientOriginalName() }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-12 text-end">
                                    <button class="btn btn-success" type="submit">ارسال پاسخ</button>
                                    <button type="button" wire:click="closeTicket" class="btn btn-danger ms-2">بستن
                                        تیکت
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end card body -->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">جزئیات تیکت</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless align-middle mb-0">
                                <tbody>
                                <tr>
                                    <td class="fw-semibold">بلیط</td>
                                    <td>#<span id="t-no">{{$ticket->ticket_number}}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">نام کاربر</td>
                                    <td id="t-client">{{$ticket->user->name}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">دپارتمان</td>
                                    <td id="t-client">{{$ticket->department->name}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">سطح اولویت</td>
                                    <td id="t-client">
                                        @if($ticket->priority=='low')
                                            <span class="badge bg-primary-subtle text-primary text-uppercase">
                                                کم
                                            </span>
                                        @elseif($ticket->priority=='medium')
                                            <span class="badge bg-warning-subtle text-warning text-uppercase">
                                            متوسط
                                            </span>
                                        @elseif($ticket->priority=='high')
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">
                                            فوری
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">وضعیت ظاهری تیکت</td>
                                    <td id="t-client">
                                        @if($ticket->status=='waiting')
                                            <span class="badge bg-warning-subtle text-warning text-uppercase">
                                                در انتظار پاسخ ادمین
                                            </span>
                                        @elseif($ticket->status=='answered')
                                            <span class="badge bg-success-subtle text-success text-uppercase">
                                             پاسخ داده شده
                                            </span>
                                        @elseif($ticket->status=='closed')
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">
                                             بسته شده
                                            </span>
                                        @elseif($ticket->status=='referred')
                                            <span class="badge bg-primary-subtle text-primary text-uppercase">
                                             ارجاع داده شد
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">ایجاد تاریخ</td>
                                    <td id="c-date">{{jalali($ticket->created_at)->format('%d %B %Y | H:i')}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">تاریخ پاسخ</td>
                                    <td id="d-date">{{jalali($ticket->updated_at)->format('%d %B %Y | H:i')}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">آخرین فعالیت</td>
                                    <td>{{ Date::parse($ticket->updated_at)->diffForHumans() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </div>
</div>
