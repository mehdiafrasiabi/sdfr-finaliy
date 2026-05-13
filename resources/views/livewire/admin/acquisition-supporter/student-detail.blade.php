<div dir="rtl" class="container py-4">
    {{-- اطلاعات دانش‌آموز --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h4 class="mb-1">{{ $trial->user->name }}</h4>
                    <div class="text-muted small">
                        موبایل: {{ $trial->user->mobile }} |
                        پایه: {{ $trial->grade_label }} |
                        رشته: {{ $trial->field_label }} |
                        شماره پدر: {{ $trial->father_mobile }} |
                        شماره مادر: {{ $trial->mother_mobile }}
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-{{ $trial->status_color }} fs-6">{{ $trial->status_label }}</span>
                    @if ($inactive)
                        <div class="mt-2"><span class="badge bg-danger">⚠ ۳ روز فعالیت ندارد — پیگیری کنید</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3">
        @foreach (['info'=>'اطلاعات','calls'=>'تماس‌ها','program'=>'برنامه','study'=>'ساعت مطالعه','reports'=>'گزارش‌ها','exams'=>'آزمون‌ها'] as $key => $label)
            <li class="nav-item">
                <button class="nav-link {{ $activeTab === $key ? 'active' : '' }}" wire:click="$set('activeTab','{{ $key }}')">{{ $label }}</button>
            </li>
        @endforeach
    </ul>

    {{-- INFO --}}
    @if ($activeTab === 'info')
        <div class="card"><div class="card-body">
            <h5 class="mb-3">اطلاعات کامل ثبت‌نامی</h5>
            @if ($trial->user->personalInformation)
                @php $p = $trial->user->personalInformation; @endphp
                <dl class="row">
                    <dt class="col-sm-3">نام</dt><dd class="col-sm-9">{{ $p->name }} {{ $p->last_name ?? '' }}</dd>
                    <dt class="col-sm-3">کد ملی</dt><dd class="col-sm-9">{{ $p->code_mell }}</dd>
                    <dt class="col-sm-3">شماره پدر</dt><dd class="col-sm-9">{{ $p->father_mobile }}</dd>
                    <dt class="col-sm-3">شماره مادر</dt><dd class="col-sm-9">{{ $p->mother_mobile }}</dd>
                    <dt class="col-sm-3">پایه/رشته</dt><dd class="col-sm-9">{{ $p->grade }} / {{ $p->field }}</dd>
                </dl>
            @endif
        </div></div>
    @endif

    {{-- CALLS --}}
    @if ($activeTab === 'calls')
        @if ($secondaryDue)
            <div class="alert alert-warning">
                <strong>توجه:</strong> بیش از ۲ روز از زمان تخصیص گذشته. اکنون باید تماس ثانویه را ثبت کنید و گزارش این دو روز را به والدین بدهید.
            </div>
        @endif

        <div class="card mb-3"><div class="card-body">
            <h6>ثبت تماس جدید (تماس بعدی پیشنهادی:
                <strong>
                    @if ($nextCall === 'initial') اولیه
                    @elseif ($nextCall === 'secondary') ثانویه
                    @else جانبی @endif
                </strong>)
            </h6>

            <form wire:submit.prevent="logCall" class="row g-2 mt-2">
                <div class="col-md-3">
                    <label class="form-label small">نوع تماس</label>
                    <select wire:model="callType" class="form-select">
                        <option value="">انتخاب...</option>
                        <option value="initial">تماس اولیه (خوش‌آمد)</option>
                        <option value="secondary" {{ $secondaryDue ? '' : 'disabled' }}>تماس ثانویه</option>
                        <option value="side">تماس جانبی</option>
                    </select>
                    @error('callType') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label small">نتیجه</label>
                    <select wire:model="callStatus" class="form-select">
                        <option value="">انتخاب...</option>
                        <option value="answered">پاسخ داده شد</option>
                        <option value="no_answer">پاسخ داده نشد</option>
                    </select>
                    @error('callStatus') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small">توضیحات</label>
                    <textarea wire:model="callDescription" class="form-control" rows="1" placeholder="گفت‌وگو، نکات، توافقات..."></textarea>
                    @error('callDescription') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">ثبت</button>
                </div>
            </form>
        </div></div>

        {{-- پیش‌بینی + پلن جذب (بعد از secondary answered) --}}
        @php
            $secondaryAnswered = $trial->acquisitionCalls->firstWhere(fn($c)=>$c->type==='secondary' && $c->status==='answered');
        @endphp
        @if ($secondaryAnswered)
            <div class="card mb-3"><div class="card-body">
                <h6 class="mb-3">پیش‌بینی جذب و پلن جذب</h6>
                <form wire:submit.prevent="submitPrediction" class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label small">درصد پیش‌بینی (0-100)</label>
                        <input type="number" min="0" max="100" wire:model="predictionPercent" class="form-control"
                               value="{{ $secondaryAnswered->prediction_percent }}">
                        @error('predictionPercent') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small">پلن جذب (اختیاری)</label>
                        <textarea wire:model="attractionPlan" class="form-control" rows="2" placeholder="چه کارهایی برای جذب این دانش‌آموز باید انجام شود؟">{{ $secondaryAnswered->attraction_plan }}</textarea>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-success w-100">ذخیره</button>
                    </div>
                </form>
            </div></div>
        @endif

        <div class="card"><div class="card-body">
            <h6 class="mb-3">تاریخچه تماس‌ها</h6>
            <div class="list-group">
                @forelse ($trial->acquisitionCalls as $c)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>
                                @if ($c->type === 'initial') تماس اولیه
                                @elseif ($c->type === 'secondary') تماس ثانویه
                                @else تماس جانبی @endif
                            </strong>
                            <span class="badge bg-{{ $c->status === 'answered' ? 'success' : 'secondary' }}">
                                {{ $c->status === 'answered' ? 'پاسخ داده شد' : 'پاسخ داده نشد' }}
                            </span>
                        </div>
                        <div class="text-muted small">{{ $c->called_at?->format('Y-m-d H:i') }}</div>
                        @if ($c->description)
                            <p class="mb-0 mt-1">{{ $c->description }}</p>
                        @endif
                        @if ($c->prediction_percent !== null)
                            <div class="mt-1 small">پیش‌بینی: <strong>{{ $c->prediction_percent }}%</strong></div>
                        @endif
                    </div>
                @empty
                    <div class="text-muted">هنوز تماسی ثبت نشده است.</div>
                @endforelse
            </div>
        </div></div>
    @endif

    {{-- PROGRAM --}}
    @if ($activeTab === 'program')
        <div class="card"><div class="card-body">
            <h6 class="mb-3">برنامه سیستم</h6>
            @if ($weeklyProgram)
                <div class="row g-2">
                    @foreach ($programParts->groupBy('day_of_week') as $day => $parts)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded p-2">
                                <strong>روز {{ $day }}</strong>
                                <ul class="mt-2 mb-0 small">
                                    @foreach ($parts as $p)
                                        <li>{{ $p->part_type_label ?? $p->part_type }} — {{ $p->duration_minutes }} دق</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted">برنامه‌ای ساخته نشده است.</div>
            @endif
        </div></div>
    @endif

    {{-- STUDY --}}
    @if ($activeTab === 'study')
        <div class="card"><div class="card-body">
            <h6 class="mb-3">ساعت‌های مطالعه</h6>
            <ul class="list-group list-group-flush">
                @forelse ($studySessions as $s)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $s->created_at->format('Y-m-d') }}</span>
                        <span>{{ $s->duration_minutes ?? '—' }} دق</span>
                    </li>
                @empty
                    <li class="text-muted">ثبتی وجود ندارد.</li>
                @endforelse
            </ul>
        </div></div>
    @endif

    {{-- REPORTS --}}
    @if ($activeTab === 'reports')
        <div class="card"><div class="card-body">
            <h6 class="mb-3">گزارش‌های روزانه</h6>
            <ul class="list-group list-group-flush">
                @forelse ($reports as $r)
                    <li class="list-group-item">
                        <strong>{{ $r->created_at->format('Y-m-d') }}</strong>
                        <span class="badge bg-{{ $r->status === 'approved' ? 'success' : 'warning' }} ms-2">{{ $r->status }}</span>
                    </li>
                @empty
                    <li class="text-muted">گزارشی نیست.</li>
                @endforelse
            </ul>
        </div></div>
    @endif

    {{-- EXAMS --}}
    @if ($activeTab === 'exams')
        <div class="card"><div class="card-body">
            <h6 class="mb-3">آزمون‌های فعال‌شده</h6>
            <table class="table table-sm">
                <thead><tr><th>آزمون</th><th>درصد</th><th>وضعیت</th></tr></thead>
                <tbody>
                @forelse ($typedExams as $ex)
                    @php $attempt = $ex->attempts->first(); @endphp
                    <tr>
                        <td>{{ $ex->typedExam->title ?? '#' . $ex->id }}</td>
                        <td>{{ $attempt?->score ?? '—' }}</td>
                        <td>{{ $attempt ? 'انجام‌شده' : 'باز' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted text-center">آزمونی نیست.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div></div>
    @endif
</div>
