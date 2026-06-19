<div class="container-fluid"
     x-data="{ ok:'' }"
     x-on:success.window="ok = ($event.detail && ($event.detail[0] ?? $event.detail)) || ''; setTimeout(() => ok='', 3500)">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h4 class="mb-0">داشبورد مدرسه: {{ $school?->name ?? '—' }}</h4>
        <div class="d-flex gap-2">
            <select wire:model.live="jalaliYear" class="form-select form-select-sm" style="width:auto">
                @foreach($yearOptions as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
            </select>
            <select wire:model.live="jalaliMonth" class="form-select form-select-sm" style="width:auto">
                @foreach($monthNames as $num => $name)<option value="{{ $num }}">{{ $name }}</option>@endforeach
            </select>
        </div>
    </div>

    <div x-show="ok" x-cloak class="alert alert-success py-2" x-text="ok"></div>

    {{-- عکس مدرسه --}}
    @if($school)
        <div class="card mb-4">
            <div class="card-header"><strong>عکس مدرسه</strong>
                <span class="text-muted small">در داشبورد دانش‌آموزان مدرسه نمایش داده می‌شود (webp).</span>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        @if($school->image)
                            <img src="{{ asset('schools/' . $school->id . '/' . $school->image) }}" alt="عکس مدرسه"
                                 style="width:96px;height:96px;object-fit:cover;border-radius:.5rem;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                 style="width:96px;height:96px;border-radius:.5rem;">بدون عکس</div>
                        @endif
                    </div>
                    <div class="col">
                        <input type="file" wire:model="schoolImage" accept="image/*" class="form-control">
                        @error('schoolImage') <span class="text-danger small">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="schoolImage" class="text-muted small mt-1">در حال بارگذاری…</div>
                        @if($schoolImage)
                            <div class="mt-2">
                                <img src="{{ $schoolImage->temporaryUrl() }}" alt="پیش‌نمایش"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:.5rem;">
                            </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <button wire:click="saveSchoolImage" wire:loading.attr="disabled" wire:target="saveSchoolImage,schoolImage"
                                class="btn btn-primary" @if(!$schoolImage) disabled @endif>
                            <span wire:loading.remove wire:target="saveSchoolImage">ذخیرهٔ عکس</span>
                            <span wire:loading wire:target="saveSchoolImage">در حال ذخیره…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- کارت‌های خلاصه --}}
    <div class="row g-3 mb-4">
        {{-- ۵) تعداد کل دانش‌آموزان --}}
        <div class="col-md-3 col-6">
            <div class="card text-center h-100" role="button" wire:click="openModal('students')"><div class="card-body py-3">
                <div class="text-muted small">تعداد کل دانش‌آموزان</div>
                <h3 class="mb-0 mt-1">{{ $studentCount }}</h3>
                <div class="text-primary small mt-1">مشاهدهٔ اسامی</div>
            </div></div>
        </div>
        {{-- ۱) جلسات برگزارشده --}}
        <div class="col-md-3 col-6">
            <div class="card text-center h-100" role="button" wire:click="openModal('held')"><div class="card-body py-3">
                <div class="text-muted small">جلسات برگزارشده (این ماه)</div>
                <h3 class="mb-0 mt-1 text-success">{{ $heldCount }}</h3>
                <div class="text-primary small mt-1">اسامی</div>
            </div></div>
        </div>
        {{-- ۱) جلسات برگزارنشده --}}
        <div class="col-md-3 col-6">
            <div class="card text-center h-100" role="button" wire:click="openModal('notheld')"><div class="card-body py-3">
                <div class="text-muted small">جلسات برگزارنشده (این ماه)</div>
                <h3 class="mb-0 mt-1 text-danger">{{ $notHeldCount }}</h3>
                <div class="text-primary small mt-1">اسامی + علت</div>
            </div></div>
        </div>
        {{-- ۷) تماس اورژانسی در انتظار --}}
        <div class="col-md-3 col-6">
            <div class="card text-center h-100 {{ $emergencyPending ? 'border-danger' : '' }}"><div class="card-body py-3">
                <div class="text-muted small">تماس اورژانسی در انتظار</div>
                <h3 class="mb-0 mt-1 {{ $emergencyPending ? 'text-danger' : '' }}">{{ $emergencyPending }}</h3>
                <div class="text-muted small mt-1">پایین صفحه</div>
            </div></div>
        </div>
    </div>

    {{-- ۴) رشد و پسرفت + ۶) تماس‌ها --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card text-center h-100" role="button" wire:click="openModal('grew')"><div class="card-body py-3">
                <div class="text-muted small">رشد نسبت به ماه قبل</div>
                <h3 class="mb-0 mt-1 text-success">{{ $grew->count() }} ▲</h3>
                <div class="text-primary small mt-1">اسامی</div>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center h-100" role="button" wire:click="openModal('regressed')"><div class="card-body py-3">
                <div class="text-muted small">پسرفت نسبت به ماه قبل</div>
                <h3 class="mb-0 mt-1 text-danger">{{ $regressed->count() }} ▼</h3>
                <div class="text-primary small mt-1">اسامی</div>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center h-100"><div class="card-body py-3">
                <div class="text-muted small">تماس‌های کافی (≥{{ $callTarget }})</div>
                <h3 class="mb-0 mt-1 text-success">{{ $callsMetCount }} / {{ $studentCount }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center h-100" role="button" wire:click="openModal('calls')"><div class="card-body py-3">
                <div class="text-muted small">کمتر از {{ $callTarget }} تماس</div>
                <h3 class="mb-0 mt-1 text-warning">{{ $callsNotMet->count() }}</h3>
                <div class="text-primary small mt-1">اسامی</div>
            </div></div>
        </div>
    </div>

    {{-- ۳) نفرات برتر --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>🏆 نفرات برتر کارنامهٔ ماهانه (معدل کل)</strong></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">معدل کل</th></tr></thead>
                        <tbody>
                        @forelse($topGrades as $i => $row)
                            <tr><td class="text-center">{{ $i + 1 }}</td><td>{{ $row['name'] }}</td><td class="text-center"><strong>{{ $row['avg'] }}</strong></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">نمره‌ای برای این ماه ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>⏱ برترین‌های ساعت مطالعه (این ماه)</strong></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">ساعت</th></tr></thead>
                        <tbody>
                        @forelse($topStudy as $i => $row)
                            <tr><td class="text-center">{{ $i + 1 }}</td><td>{{ $row['name'] }}</td><td class="text-center"><strong>{{ $row['hours'] }}</strong></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">ساعت مطالعه‌ای برای این ماه ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ۲) میزان تسلط دانش‌آموزان --}}
    <div class="card mb-4">
        <div class="card-header"><strong>میزان تسلط دانش‌آموزان (بر اساس طبقه‌بندی)</strong>
            <span class="text-muted small">A خیلی‌خوب · B خوب · C متوسط · D ضعیف</span>
        </div>
        <div class="card-body">
            @forelse($masteryStudents as $ms)
                <button wire:click="showMastery({{ $ms['id'] }})" class="btn btn-sm btn-outline-secondary mb-1">{{ $ms['name'] }}</button>
            @empty
                <div class="text-muted small">داده‌ی طبقه‌بندی‌ای ثبت نشده است.</div>
            @endforelse
        </div>
    </div>

    {{-- ۷) تماس‌های اورژانسی --}}
    <div class="card mb-4">
        <div class="card-header"><strong>🚨 تماس‌های اورژانسی (ثبت‌شده توسط مشاور)</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr><th>دانش‌آموز</th><th>علت</th><th>مشاور</th><th class="text-center">تاریخ</th><th class="text-center">وضعیت</th></tr>
                </thead>
                <tbody>
                @forelse($emergencyCalls as $call)
                    <tr class="{{ $call->status === 'pending' ? 'table-warning' : '' }}">
                        <td>{{ $call->student?->user?->name ?? '—' }}</td>
                        <td class="small">{{ $call->reason }}</td>
                        <td class="small">{{ $call->admin?->name ?? '—' }}</td>
                        <td class="text-center small">{{ jdate($call->called_at)->format('Y/m/d H:i') }}</td>
                        <td class="text-center">
                            @if($call->status === 'resolved')
                                <span class="badge bg-success">پیگیری شد</span>
                            @else
                                <button wire:click="resolveEmergency({{ $call->id }})" class="btn btn-sm btn-outline-success">علامت پیگیری‌شده</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">تماس اورژانسی‌ای ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============ مودال‌ها ============ --}}
    @if($modal)
        <div wire:click.self="closeModal"
             style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1080;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div class="card" style="max-width:640px;width:100%;max-height:85vh;overflow:auto;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>
                        @switch($modal)
                            @case('students') اسامی دانش‌آموزان ({{ $studentCount }}) @break
                            @case('held') جلسات برگزارشده ({{ $heldCount }}) @break
                            @case('notheld') جلسات برگزارنشده ({{ $notHeldCount }}) @break
                            @case('grew') دانش‌آموزانِ دارای رشد ({{ $grew->count() }}) @break
                            @case('regressed') دانش‌آموزانِ دارای پسرفت ({{ $regressed->count() }}) @break
                            @case('calls') کمتر از {{ $callTarget }} تماس ({{ $callsNotMet->count() }}) @break
                        @endswitch
                    </strong>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="card-body">
                    @switch($modal)
                        @case('students')
                            <ul class="mb-0">@foreach($studentNames as $n)<li>{{ $n }}</li>@endforeach</ul>
                            @break
                        @case('held')
                            <ul class="mb-0">@forelse($heldNames as $n)<li>{{ $n }}</li>@empty<li class="text-muted">موردی نیست.</li>@endforelse</ul>
                            @break
                        @case('notheld')
                            <table class="table table-sm mb-0"><thead><tr><th>دانش‌آموز</th><th>علت</th><th>تاریخ</th></tr></thead><tbody>
                            @forelse($notHeldSessions as $s)
                                <tr><td>{{ $s['name'] }}</td><td><span class="badge bg-danger-subtle text-danger">{{ $s['reason'] }}</span></td><td class="small">{{ $s['date'] }}</td></tr>
                            @empty<tr><td colspan="3" class="text-muted">موردی نیست.</td></tr>@endforelse
                            </tbody></table>
                            @break
                        @case('grew')
                            <table class="table table-sm mb-0"><thead><tr><th>دانش‌آموز</th><th class="text-center">قبل</th><th class="text-center">فعلی</th><th class="text-center">تغییر</th></tr></thead><tbody>
                            @forelse($grew as $r)
                                <tr><td>{{ $r['name'] }}</td><td class="text-center">{{ $r['prev'] }}</td><td class="text-center">{{ $r['cur'] }}</td><td class="text-center text-success">▲ {{ $r['delta'] }}</td></tr>
                            @empty<tr><td colspan="4" class="text-muted">موردی نیست.</td></tr>@endforelse
                            </tbody></table>
                            @break
                        @case('regressed')
                            <table class="table table-sm mb-0"><thead><tr><th>دانش‌آموز</th><th class="text-center">قبل</th><th class="text-center">فعلی</th><th class="text-center">تغییر</th></tr></thead><tbody>
                            @forelse($regressed as $r)
                                <tr><td>{{ $r['name'] }}</td><td class="text-center">{{ $r['prev'] }}</td><td class="text-center">{{ $r['cur'] }}</td><td class="text-center text-danger">▼ {{ abs($r['delta']) }}</td></tr>
                            @empty<tr><td colspan="4" class="text-muted">موردی نیست.</td></tr>@endforelse
                            </tbody></table>
                            @break
                        @case('calls')
                            <table class="table table-sm mb-0"><thead><tr><th>دانش‌آموز</th><th class="text-center">تعداد تماس</th></tr></thead><tbody>
                            @forelse($callsNotMet as $r)
                                <tr><td>{{ $r['name'] }}</td><td class="text-center">{{ $r['count'] }} / {{ $callTarget }}</td></tr>
                            @empty<tr><td colspan="2" class="text-muted">همه به هدف رسیده‌اند.</td></tr>@endforelse
                            </tbody></table>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    @endif

    {{-- مودال میزان تسلط هر دانش‌آموز --}}
    @if($masteryStudentId && isset($masteryByStudent[$masteryStudentId]))
        <div wire:click.self="closeMastery"
             style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1080;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div class="card" style="max-width:560px;width:100%;max-height:85vh;overflow:auto;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>میزان تسلط در دروس</strong>
                    <button wire:click="closeMastery" class="btn-close"></button>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>درس</th><th class="text-center">طبقه</th><th class="text-center">سطح تسلط</th></tr></thead>
                        <tbody>
                        @foreach($masteryByStudent[$masteryStudentId] as $m)
                            @php $color = ['4'=>'success','3'=>'info','2'=>'warning','1'=>'danger'][(string)$m['rating']] ?? 'secondary'; @endphp
                            <tr>
                                <td>{{ $m['subject'] }}</td>
                                <td class="text-center"><span class="badge bg-{{ $color }}">{{ \App\Models\StudentClassification::RATINGS[$m['rating']] ?? '—' }}</span></td>
                                <td class="text-center">{{ $m['label'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
