<div class="em-page">
    @include('livewire.admin.educational-manager._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">هدف‌گذاری ثبت‌نام</li>
            </ol>
        </nav>
    </div>

    <section class="em-hero">
        <div class="em-hero-main"><span class="em-hero-icon"><i class="fi fi-rr-target"></i></span><div><h3>هدف‌گذاری ثبت‌نام</h3><p>برای کل تیم یا هر مشاور جذب تلفنی هدف قابل‌اندازه‌گیری تعیین کنید.</p></div></div>
        <span class="badge bg-primary-subtle text-primary">{{ number_format($goals->count()) }} هدف ثبت‌شده</span>
    </section>

    <div class="row g-3">
        {{-- فرم ثبت هدف --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">ثبت هدف جدید</h5>

                    <div class="mb-3">
                        <label class="form-label">نوع هدف</label>
                        <select wire:model.live="scope" class="form-select">
                            <option value="team">کل تیم</option>
                            <option value="consultant">یک مشاور خاص</option>
                        </select>
                    </div>

                    @if ($scope === 'consultant')
                        <div class="mb-3">
                            <label class="form-label">مشاور <span class="text-danger">*</span></label>
                            <select wire:model="adminId" class="form-select">
                                <option value="">— انتخاب کنید —</option>
                                @foreach ($consultants as $consultant)
                                    <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                                @endforeach
                            </select>
                            @error('adminId')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">تعداد هدف ثبت‌نام <span class="text-danger">*</span></label>
                        <input type="text" wire:model="targetCount" class="form-control" placeholder="مثلاً ۲۰">
                        @error('targetCount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">توضیحات (برای نمایش به مشاوران)</label>
                        <textarea wire:model="description" rows="3" class="form-control"
                                  placeholder="مثلاً: تمرکز این هفته روی پایه دوازدهم تجربی…"></textarea>
                        @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">تاریخ مهلت <span class="text-danger">*</span></label>
                        <div wire:ignore>
                            <input type="text" id="jdp-goal" data-jdp
                                   class="form-control" placeholder="انتخاب تاریخ شمسی" autocomplete="off" readonly>
                            <input type="hidden" id="goal_hidden" wire:model="goalDate">
                        </div>
                        @error('goalDate')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary w-100" wire:click="saveGoal">ثبت هدف</button>
                </div>
            </div>
        </div>

        {{-- فهرست اهداف --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">اهداف تعیین‌شده</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>نوع</th>
                                    <th>هدف</th>
                                    <th>محقق‌شده</th>
                                    <th>پیشرفت</th>
                                    <th>مهلت</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($goals as $goal)
                                @php $pct = $goal->target_count > 0 ? min(100, round($goal->achieved / $goal->target_count * 100)) : 0; @endphp
                                <tr>
                                    <td>
                                        {{ $goal->isTeamGoal() ? 'کل تیم' : ($goal->admin?->name ?? '—') }}
                                        @if ($goal->description)
                                            <div class="text-muted small mt-1">{{ $goal->description }}</div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($goal->target_count) }}</td>
                                    <td>{{ number_format($goal->achieved) }}</td>
                                    <td style="min-width:140px">
                                        <div class="progress" style="height:18px">
                                            <div class="progress-bar {{ $pct >= 100 ? 'bg-success' : '' }}" style="width: {{ $pct }}%">{{ $pct }}٪</div>
                                        </div>
                                    </td>
                                    <td>{{ jalali($goal->goal_date)->format('%d %B %Y') }}</td>
                                    <td>
                                        <button wire:click="deleteGoal({{ $goal->id }})"
                                                wire:confirm="حذف این هدف؟"
                                                class="btn btn-sm btn-outline-danger">حذف</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">هدفی ثبت نشده است.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            (function () {
                'use strict';

                function jalaliToGregorian(jy, jm, jd) {
                    jy = parseInt(jy); jm = parseInt(jm); jd = parseInt(jd);
                    var jy1 = jy - 979, jm1 = jm - 1, jd1 = jd - 1;
                    var jDayNo = 365 * jy1 + Math.floor(jy1 / 33) * 8 + Math.floor((jy1 % 33 + 3) / 4);
                    var months = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                    for (var i = 0; i < jm1; i++) jDayNo += months[i];
                    jDayNo += jd1;
                    var gDayNo = jDayNo + 79;
                    var gy = 1600 + 400 * Math.floor(gDayNo / 146097);
                    gDayNo = gDayNo % 146097;
                    var leap = true;
                    if (gDayNo >= 36525) {
                        gDayNo--;
                        gy += 100 * Math.floor(gDayNo / 36524);
                        gDayNo = gDayNo % 36524;
                        if (gDayNo >= 365) gDayNo++; else leap = false;
                    }
                    gy += 4 * Math.floor(gDayNo / 1461);
                    gDayNo %= 1461;
                    if (gDayNo >= 366) {
                        leap = false; gDayNo--;
                        gy += Math.floor(gDayNo / 365);
                        gDayNo = gDayNo % 365;
                    }
                    var gMonths = [31, leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    var gm = 0;
                    for (var i = 0; gDayNo >= gMonths[i]; i++) gDayNo -= gMonths[i];
                    gm = i + 1;
                    var gd = gDayNo + 1;
                    return [gy, gm, gd];
                }

                function syncGoalToLivewire(pickerValue) {
                    var hidden = document.getElementById('goal_hidden');
                    if (!hidden) return;
                    if (!pickerValue) {
                        hidden.value = '';
                        hidden.dispatchEvent(new Event('input', {bubbles: true}));
                        return;
                    }
                    var dp = pickerValue.trim().split(' ')[0].split('/');
                    if (dp.length !== 3) return;
                    var greg = jalaliToGregorian(dp[0], dp[1], dp[2]);
                    hidden.value = greg[0] + '-' + String(greg[1]).padStart(2, '0') + '-' + String(greg[2]).padStart(2, '0');
                    hidden.dispatchEvent(new Event('input', {bubbles: true}));
                }

                function initGoalPicker() {
                    if (typeof jalaliDatepicker === 'undefined') return;
                    var input = document.getElementById('jdp-goal');
                    if (!input || input.dataset.jdpBound === '1') return;

                    jalaliDatepicker.startWatch({
                        time: false,
                        hideAfterChange: true,
                        showTodayBtn: true,
                        showEmptyBtn: true,
                        selector: '#jdp-goal',
                    });
                    input.addEventListener('jdp:change', function () { syncGoalToLivewire(this.value); });
                    input.dataset.jdpBound = '1';
                }

                document.addEventListener('livewire:navigated', initGoalPicker);
                document.addEventListener('livewire:init', function () {
                    Livewire.on('goal-saved', function () {
                        var input = document.getElementById('jdp-goal');
                        if (input) input.value = '';
                    });
                });
                if (document.readyState !== 'loading') initGoalPicker();
            })();
        </script>
    @endpush
</div>
