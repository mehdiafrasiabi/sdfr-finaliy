<div>
    <div class="row">
        <!-- stats cards -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="statbox widget box box-shadow" wire:click="setTab('all')" style="cursor:pointer">
                <div class="widget-content widget-content-area p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">کل تیکت‌ها</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;background:rgba(var(--bs-primary-rgb),0.1)">
                            <i class="fi fi-rr-ticket" style="font-size:20px;color:var(--bs-primary)"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="statbox widget box box-shadow" wire:click="setTab('waiting')" style="cursor:pointer">
                <div class="widget-content widget-content-area p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">در انتظار پاسخ</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $stats['waiting'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;background:rgba(255,193,7,0.1)">
                            <i class="fi fi-rr-clock" style="font-size:20px;color:#ffc107"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="statbox widget box box-shadow" wire:click="setTab('answered')" style="cursor:pointer">
                <div class="widget-content widget-content-area p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">پاسخ داده شده</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $stats['answered'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;background:rgba(25,135,84,0.1)">
                            <i class="fi fi-rr-check-circle" style="font-size:20px;color:#198754"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
            <div class="statbox widget box box-shadow" wire:click="setTab('closed')" style="cursor:pointer">
                <div class="widget-content widget-content-area p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">بسته شده</h6>
                            <h3 class="mb-0 fw-bold text-danger">{{ $stats['closed'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;background:rgba(220,53,69,0.1)">
                            <i class="fi fi-rr-lock" style="font-size:20px;color:#dc3545"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end stats -->

        <!-- main content -->
        <div class="col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row align-items-center">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>مدیریت تیکت‌ها</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <!-- tabs -->
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if($activeTab == 'all') active @endif" href="javascript:void(0)" wire:click="setTab('all')">
                                همه
                                <span class="badge bg-secondary ms-1">{{ $stats['total'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($activeTab == 'waiting') active @endif" href="javascript:void(0)" wire:click="setTab('waiting')">
                                در انتظار
                                <span class="badge bg-warning ms-1">{{ $stats['waiting'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($activeTab == 'answered') active @endif" href="javascript:void(0)" wire:click="setTab('answered')">
                                پاسخ داده شده
                                <span class="badge bg-success ms-1">{{ $stats['answered'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($activeTab == 'closed') active @endif" href="javascript:void(0)" wire:click="setTab('closed')">
                                بسته شده
                                <span class="badge bg-danger ms-1">{{ $stats['closed'] }}</span>
                            </a>
                        </li>
                    </ul>

                    <!-- filters -->
                    <div class="row mb-4 g-2">
                        <div class="col-md-4">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                   placeholder="جستجو (عنوان، شماره تیکت، نام کاربر)...">
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="priority" class="form-select">
                                <option value="">همه اولویت‌ها</option>
                                <option value="low">کم</option>
                                <option value="medium">متوسط</option>
                                <option value="high">زیاد</option>
                                <option value="urgent">فوری</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="department" class="form-select">
                                <option value="">همه دپارتمان‌ها</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col" style="width:60px">#</th>
                                <th scope="col">شماره تیکت</th>
                                <th scope="col">عنوان</th>
                                <th scope="col">کاربر</th>
                                <th scope="col">دپارتمان</th>
                                <th scope="col" class="text-center">اولویت</th>
                                <th scope="col" class="text-center">وضعیت</th>
                                <th scope="col">تاریخ</th>
                                <th scope="col" class="text-center" style="width:80px">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td>{{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}</td>
                                    <td class="fw-bold">#{{ $ticket->ticket_number }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{ Str::limit($ticket->title, 40) }}
                                            @if($ticket->unreadMessagesForAdmin()->count() > 0)
                                                <span class="badge bg-primary rounded-pill">{{ $ticket->unreadMessagesForAdmin()->count() }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $ticket->user->name ?? '-' }}</td>
                                    <td>{{ $ticket->department->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge
                                            @if($ticket->priority == 'low') bg-info
                                            @elseif($ticket->priority == 'medium') bg-warning
                                            @elseif($ticket->priority == 'high') bg-orange text-dark
                                            @elseif($ticket->priority == 'urgent') bg-danger
                                            @endif"
                                              style="@if($ticket->priority == 'high') background-color:#fd7e14 !important @endif">
                                            {{ $ticket->priority_label }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge
                                            @if($ticket->status == 'waiting') bg-warning text-dark
                                            @elseif($ticket->status == 'answered') bg-success
                                            @elseif($ticket->status == 'closed') bg-danger
                                            @endif">
                                            {{ $ticket->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ jalali($ticket->updated_at)->format('%d %B %Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.ticket.show', $ticket->id) }}"
                                           class="action-btn btn-edit bs-tooltip"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="مشاهده">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-eye">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fi fi-rr-ticket mb-2" style="font-size:32px;display:block"></i>
                                            تیکتی وجود ندارد
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- pagination -->
                    @if($tickets->hasPages())
                        <div class="mt-3">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
