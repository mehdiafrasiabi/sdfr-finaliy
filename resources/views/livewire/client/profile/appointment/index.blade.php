<div>
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">تعیین وقت برای برگزاری جلسه</h5>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($approvedPref)
                <div class="alert alert-info">
                    <strong>برنامه فعال فعلی شما:</strong>
                    مشاور: <strong>{{ $approvedPref->assignedAdvisor?->name ?? '—' }}</strong>
                    <div class="mt-2">
                        @foreach ($approvedPref->times as $t)
                            <span class="badge bg-light text-dark me-1">
                                {{ $days[$t->day_of_week] ?? '' }}
                                از {{ substr($t->start_time, 0, 5) }} تا {{ substr($t->end_time, 0, 5) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @elseif ($current && $current->status === 'pending')
                <div class="alert alert-warning">
                    درخواست تعیین وقت شما ثبت شده و منتظر تایید مدیر آموزشی و انتخاب مشاور است.
                </div>
            @endif

            @if ($canSubmit)
                <p class="text-muted small">
                    لطفا روزهای دلخواه خود را فعال کرده و بازه‌ی ساعت انتخاب کنید.
                    بازه مجاز: شنبه تا جمعه، از ۸:۰۰ صبح تا ۲۱:۰۰.
                    شما می‌توانید در هر سال حداکثر {{ \App\Livewire\Client\Profile\Appointment\Index::MAX_YEARLY_CHANGES }} بار برنامه خود را تغییر دهید.
                </p>

                <form wire:submit.prevent="save">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                            <tr>
                                <th>روز</th>
                                <th class="text-center" style="width:120px;">فعال</th>
                                <th>ساعت شروع</th>
                                <th>ساعت پایان</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($days as $day => $name)
                                <tr>
                                    <td><strong>{{ $name }}</strong></td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input"
                                               wire:model.live="slots.{{ $day }}.enabled">
                                    </td>
                                    <td>
                                        <input type="time" class="form-control"
                                               wire:model="slots.{{ $day }}.start_time"
                                            @disabled(empty($slots[$day]['enabled']))>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control"
                                               wire:model="slots.{{ $day }}.end_time"
                                            @disabled(empty($slots[$day]['enabled']))>
                                    </td>
                                </tr>
                                @error("slots.$day")
                                <tr><td colspan="4"><div class="text-danger small">{{ $message }}</div></td></tr>
                                @enderror
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">توضیحات (اختیاری)</label>
                        <textarea wire:model="notes" class="form-control" rows="2"></textarea>
                    </div>

                    @error('slots') <div class="alert alert-danger">{{ $message }}</div> @enderror

                    <button type="submit" class="btn btn-success">ارسال درخواست به مدیر آموزشی</button>
                </form>
            @else
                <div class="alert alert-secondary">
                    سقف تغییر برنامه‌ی هفتگی در این سال تکمیل شده است.
                </div>
            @endif
        </div>
    </div>

    @if ($approvedPref)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">جابجایی جلسات</h5>
                <button wire:click="toggleRescheduleForm" class="btn btn-sm btn-outline-primary">
                    {{ $showRescheduleForm ? 'بستن' : 'درخواست جدید' }}
                </button>
            </div>
            <div class="card-body">
                @if ($showRescheduleForm)
                    <form wire:submit.prevent="submitReschedule" class="mb-4 border-bottom pb-3">
                        <div class="mb-2">
                            <label class="form-label">نوع درخواست</label>
                            <select wire:model.live="rescheduleType" class="form-select">
                                <option value="exception">جابجایی استثنا (یک‌بار مصرف)</option>
                                <option value="permanent">جابجایی دائمی در برنامه هفتگی</option>
                            </select>
                        </div>

                        @if ($rescheduleType === 'exception')
                            <div class="mb-2">
                                <label class="form-label">جلسه‌ای که می‌خواهید جابه‌جا شود</label>
                                <select wire:model="rescheduleOriginalSessionId" class="form-select">
                                    <option value="">انتخاب کنید</option>
                                    @foreach ($upcomingSessions as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->title }} —
                                            {{ \Morilog\Jalali\Jalalian::fromDateTime($s->activation_date)->format('Y/m/d') }}
                                            @if ($s->session_time) ساعت {{ substr($s->session_time, 0, 5) }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">روز فعلی</label>
                                    <select wire:model="rescheduleOriginalDay" class="form-select">
                                        <option value="">—</option>
                                        @foreach ($days as $d => $n)
                                            <option value="{{ $d }}">{{ $n }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">ساعت فعلی</label>
                                    <input type="time" wire:model="rescheduleOriginalTime" class="form-control">
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">روز پیشنهادی شما</label>
                                <select wire:model="rescheduleProposedDay" class="form-select">
                                    <option value="">—</option>
                                    @foreach ($days as $d => $n)
                                        <option value="{{ $d }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">ساعت پیشنهادی شما</label>
                                <input type="time" wire:model="rescheduleProposedTime" class="form-control">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">توضیحات</label>
                            <textarea wire:model="rescheduleDescription" rows="2" class="form-control"></textarea>
                        </div>

                        @error('rescheduleType') <div class="text-danger small">{{ $message }}</div> @enderror
                        @error('rescheduleProposedTime') <div class="text-danger small">{{ $message }}</div> @enderror

                        <button type="submit" class="btn btn-primary">ارسال درخواست</button>
                    </form>
                @endif

                <h6 class="mb-3">درخواست‌های اخیر جابجایی</h6>

                @forelse ($rescheduleRequests as $req)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div>
                                <span class="badge bg-secondary">
                                    {{ $req->type === 'exception' ? 'استثنا' : 'دائمی' }}
                                </span>
                                <span class="badge bg-info">{{ $req->status_label }}</span>
                                <span class="text-muted small">
                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-2 small">
                            پیشنهاد شما:
                            {{ $days[$req->student_proposed_day] ?? '—' }}
                            ساعت {{ $req->student_proposed_time ? substr($req->student_proposed_time, 0, 5) : '—' }}
                            @if ($req->student_description)
                                <div class="text-muted">توضیحات: {{ $req->student_description }}</div>
                            @endif
                        </div>

                        @if ($req->status === 'awaiting_student_choice')
                            <div class="mt-3">
                                <strong>اسلات‌های قابل انتخاب:</strong>
                                <div class="mt-2">
                                    @if ($req->consultant_proposed_day !== null)
                                        <div class="mb-2">
                                            <button class="btn btn-sm btn-outline-success"
                                                    wire:click="chooseRescheduleSlot({{ $req->id }}, {{ $req->consultant_proposed_day }}, '{{ substr($req->consultant_proposed_time, 0, 5) }}')">
                                                پیشنهاد مشاور:
                                                {{ $days[$req->consultant_proposed_day] ?? '' }}
                                                ساعت {{ substr($req->consultant_proposed_time, 0, 5) }}
                                            </button>
                                            @if ($req->consultant_notes)
                                                <span class="text-muted small d-block">یادداشت مشاور: {{ $req->consultant_notes }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    @foreach (($req->manager_available_slots ?? []) as $slot)
                                        <button class="btn btn-sm btn-outline-primary me-1 mb-1"
                                                wire:click="chooseRescheduleSlot({{ $req->id }}, {{ $slot['day'] }}, '{{ $slot['start'] }}')">
                                            {{ $days[$slot['day']] ?? '' }}
                                            ساعت {{ $slot['start'] }}
                                            @if(!empty($slot['end'])) تا {{ $slot['end'] }} @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($req->student_selected_day !== null)
                            <div class="mt-2 small text-success">
                                انتخاب شما: {{ $days[$req->student_selected_day] ?? '' }}
                                ساعت {{ $req->student_selected_time ? substr($req->student_selected_time, 0, 5) : '' }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-muted small">هنوز درخواست جابجایی‌ای ثبت نکرده‌اید.</div>
                @endforelse
            </div>
        </div>
    @endif
</div>

