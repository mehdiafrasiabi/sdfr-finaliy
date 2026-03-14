<div>
    <div class="row">
        @if(session()->has('success'))
            <div class="col-12">
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
                    <strong>موفق!</strong>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- ticket header -->
        <div class="col-12 mb-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('admin.ticket.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fi fi-rr-arrow-right me-1"></i> بازگشت
                            </a>
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $ticket->title }}</h5>
                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                    <span><strong>شماره:</strong> #{{ $ticket->ticket_number }}</span>
                                    <span><strong>کاربر:</strong> {{ $ticket->user->name ?? '-' }}</span>
                                    <span><strong>دپارتمان:</strong> {{ $ticket->department->name ?? '-' }}</span>
                                    <span><strong>تاریخ:</strong> {{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge
                                @if($ticket->priority == 'low') bg-info
                                @elseif($ticket->priority == 'medium') bg-warning text-dark
                                @elseif($ticket->priority == 'high') bg-orange text-white
                                @elseif($ticket->priority == 'urgent') bg-danger
                                @endif"
                                  style="@if($ticket->priority == 'high') background-color:#fd7e14 !important @endif">
                                اولویت: {{ $ticket->priority_label }}
                            </span>
                            <span class="badge
                                @if($ticket->status == 'waiting') bg-warning text-dark
                                @elseif($ticket->status == 'answered') bg-success
                                @elseif($ticket->status == 'closed') bg-danger
                                @endif">
                                {{ $ticket->status_label }}
                            </span>
                            @if($ticket->status != 'closed')
                                <button wire:click="closeTicket" wire:confirm="آیا از بستن تیکت مطمئن هستید؟"
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="fi fi-rr-lock me-1"></i> بستن تیکت
                                </button>
                            @else
                                <button wire:click="reopenTicket" wire:confirm="آیا تیکت باز شود؟"
                                        class="btn btn-outline-success btn-sm">
                                    <i class="fi fi-rr-unlock me-1"></i> باز کردن تیکت
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- messages -->
        <div class="col-12 mb-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-12">
                            <h4>گفتگوها</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area" style="max-height:500px;overflow-y:auto">
                    @foreach($ticket->messages as $msg)
                        @if($msg->user_id)
                            <!-- user message -->
                            <div class="d-flex gap-3 mb-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:40px;height:40px;background:rgba(var(--bs-primary-rgb),0.1)">
                                    <i class="fi fi-rr-user" style="color:var(--bs-primary)"></i>
                                </div>
                                <div class="flex-grow-1" style="max-width:70%">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <strong class="small">{{ $msg->user->name ?? 'کاربر' }}</strong>
                                        <small class="text-muted">{{ \Date::parse($msg->created_at)->diffForHumans() }}</small>
                                        @if(!$msg->is_read)
                                            <span class="badge bg-primary" style="font-size:9px">جدید</span>
                                        @endif
                                    </div>
                                    <div class="p-3 rounded-3 small" style="background:var(--bs-tertiary-bg)">
                                        {{ $msg->message }}
                                    </div>
                                    @if($msg->attachment)
                                        <a href="{{ asset('ticket/' . $ticket->user_id . '/file/' . $msg->attachment) }}"
                                           class="small text-primary mt-1 d-inline-block" target="_blank">
                                            <i class="fi fi-rr-clip me-1"></i> دانلود فایل پیوست
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($msg->admin_id)
                            <!-- admin message -->
                            <div class="d-flex gap-3 mb-4 flex-row-reverse">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:40px;height:40px;background:rgba(13,110,253,0.1)">
                                    <i class="fi fi-rr-shield-check" style="color:#0d6efd"></i>
                                </div>
                                <div class="flex-grow-1" style="max-width:70%">
                                    <div class="d-flex align-items-center gap-2 mb-1 justify-content-end">
                                        <small class="text-muted">{{ \Date::parse($msg->created_at)->diffForHumans() }}</small>
                                        <strong class="small text-primary">{{ $msg->admin->name ?? 'پشتیبان' }}</strong>
                                    </div>
                                    <div class="p-3 rounded-3 small text-white" style="background:#0d6efd">
                                        {{ $msg->message }}
                                    </div>
                                    @if($msg->attachment)
                                        <div class="text-end">
                                            <a href="{{ asset('ticket/' . $ticket->user_id . '/file/' . $msg->attachment) }}"
                                               class="small text-primary mt-1 d-inline-block" target="_blank">
                                                <i class="fi fi-rr-clip me-1"></i> دانلود فایل پیوست
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- reply form -->
        <div class="col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-12">
                            <h4>پاسخ به تیکت</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    @if($ticket->status != 'closed')
                        <form wire:submit.prevent="submit">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">متن پاسخ:
                                        <sup style="color:red">*</sup>
                                    </label>
                                    <textarea class="form-control" rows="5" wire:model="message"
                                              placeholder="پاسخ خود را بنویسید..."></textarea>
                                    @error('message')
                                    <div class="alert alert-light-danger border-0 mt-2 mb-0 py-1 px-3" role="alert">
                                        <small>{{ $message }}</small>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <label class="btn btn-outline-secondary btn-sm" for="adminAttachment">
                                        <i class="fi fi-rr-clip me-1"></i>
                                        <span>فایل پیوست</span>
                                        <input type="file" class="d-none" id="adminAttachment" wire:model="attachment">
                                    </label>
                                    @if($attachment)
                                        <small class="text-success ms-2">{{ $attachment->getClientOriginalName() }}</small>
                                    @endif
                                    @error('attachment')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light">
                                    <span wire:loading.remove>
                                        <i class="fi fi-rr-paper-plane me-1"></i> ارسال پاسخ
                                    </span>
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
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <i class="fi fi-rr-lock d-block mb-2" style="font-size:32px;color:var(--bs-danger)"></i>
                            <p class="text-muted mb-2">این تیکت بسته شده است.</p>
                            <button wire:click="reopenTicket" wire:confirm="آیا تیکت باز شود؟"
                                    class="btn btn-outline-success btn-sm">
                                <i class="fi fi-rr-unlock me-1"></i> باز کردن مجدد تیکت
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
