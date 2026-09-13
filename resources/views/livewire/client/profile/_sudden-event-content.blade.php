{{-- محتوای مشترک مودال «اتفاقات یهویی» (مراحل سرور-محور بر اساس $step) --}}
@php
    /** فرمت دقیقه به "X ساعت و Y دقیقه" */
    $fmtDuration = function ($minutes) {
        $minutes = max(0, (int) $minutes);
        if ($minutes === 0) return '۰ دقیقه';
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        if ($h > 0 && $m > 0) return $h . ' ساعت و ' . $m . ' دقیقه';
        if ($h > 0) return $h . ' ساعت';
        return $m . ' دقیقه';
    };
    $minPartMinutes = \App\Livewire\Client\Profile\SuddenEventModal::MIN_PART_MINUTES;
@endphp

<div class="p-5 sm:p-6 text-right" dir="rtl">

    {{-- دکمه بستن --}}
    <button type="button" @click="$wire.close()" data-elevated="false"
            class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:bg-secondary hover:text-foreground transition-colors">
        <x-ui.icon name="x" class="w-4 h-4"/>
    </button>

    {{-- عنوان --}}
    <div class="flex items-center gap-2.5 mb-4">
        <span class="w-9 h-9 rounded-xl bg-warning/15 border border-warning/30 flex items-center justify-center text-lg">⚡</span>
        <div>
            <h2 class="text-base font-black text-foreground">اتفاقات یهویی</h2>
            <p class="text-[11px] text-muted">ثبت اتفاق ناگهانی و اصلاح برنامه</p>
        </div>
    </div>

    {{-- ░░░ بدون دسترسی: برنامه ندارد یا برنامه‌اش منقضی شده ░░░ --}}
    @if(($accessState ?? 'ok') !== 'ok')
        <div class="rounded-2xl bg-secondary border border-border p-6 text-center">
            <span class="inline-flex w-14 h-14 rounded-2xl bg-warning/15 border border-warning/30 items-center justify-center text-3xl mb-3">
                @if($accessState === 'expired') ⏳ @else 📭 @endif
            </span>
            @if($accessState === 'expired')
                <h3 class="text-[15px] font-black text-foreground mb-1.5">دسترسی شما به پایان رسیده است</h3>
                <p class="text-[12px] text-muted leading-relaxed">
                    مهلت ثبت اتفاق یهویی برای برنامهٔ فعلی‌ات تمام شده است.
                    پس از دریافت برنامهٔ جدید از مشاورت می‌توانی دوباره از این بخش استفاده کنی.
                </p>
            @else
                <h3 class="text-[15px] font-black text-foreground mb-1.5">شما هنوز برنامه‌ای ندارید</h3>
                <p class="text-[12px] text-muted leading-relaxed">
                    برای ثبت اتفاق یهویی، ابتدا باید یک برنامهٔ درسی فعال داشته باشی.
                    پس از دریافت برنامه از مشاورت، این بخش در دسترس قرار می‌گیرد.
                </p>
            @endif
        </div>
        <x-ui.button type="button" wire:click="close" variant="primary" block icon="check" class="mt-5">
            متوجه شدم
        </x-ui.button>
    @else

        {{-- نوار پیشرفت مراحل --}}
        @if($step >= 1 && $step <= 5)
            <div class="flex items-center gap-1 mb-5">
                @for($s = 1; $s <= 5; $s++)
                    <div class="h-1 flex-1 rounded-full {{ $step >= $s ? 'bg-primary' : 'bg-secondary' }}"></div>
                @endfor
            </div>
        @endif

        {{-- بنرِ خطا/هشدارِ همین مرحله — همیشه داخل خودِ مودال نمایش داده می‌شود، هیچ‌وقت مودال را نمی‌بندد --}}
        @if(!empty($stepError))
            <div class="rounded-xl bg-error/10 border border-error/30 px-3 py-2.5 mb-4 flex items-start gap-2">
                <x-ui.icon name="triangle-alert" class="w-4 h-4 text-error flex-shrink-0 mt-0.5"/>
                <span class="text-[12.5px] text-error flex-1 leading-relaxed">{{ $stepError }}</span>
                <button type="button" wire:click="clearStepError" data-elevated="false" class="btn-press text-error hover:text-foreground shrink-0">
                    <x-ui.icon name="x" class="w-3.5 h-3.5"/>
                </button>
            </div>
        @endif

        {{-- ░░░ مرحله ۰: هشدار ░░░ --}}
        @if($step === 0)
            <div class="rounded-2xl bg-error/10 border border-error/30 p-4 mb-5">
                <div class="flex items-start gap-2.5">
                    <x-ui.icon name="triangle-alert" class="w-5 h-5 text-error flex-shrink-0"/>
                    <div class="text-[13px] leading-relaxed text-error">
                        <p class="font-bold mb-1">توجه!</p>
                        شما در حال <span class="font-bold">تغییر برنامهٔ درسی</span> خودت هستی و عواقب این تغییر
                        بر عهدهٔ خودت است. پارت‌ها به برنامهٔ فعالت اضافه یا جابجا می‌شوند.
                    </div>
                </div>
            </div>
            <x-ui.button type="button" wire:click="goToStep(1)" variant="primary" block icon="chevron-left">
                متوجه شدم، ادامه می‌دهم
            </x-ui.button>
        @endif

        {{-- ░░░ مرحله ۱: انتخاب روز اتفاق ░░░ --}}
        @if($step === 1)
            <p class="text-[13px] text-foreground mb-3 font-semibold">این اتفاق در چه روزی افتاده/می‌افتد؟</p>
            <p class="text-[11px] text-muted mb-4">امروز و روزهای باقی‌ماندهٔ این هفته قابل انتخاب‌اند (روزهای گذشته غیرفعال است).</p>
            @if(count($availableDays) > 0)
                <div class="grid grid-cols-2 gap-2 mb-5">
                    @foreach($availableDays as $day)
                        @php $isSelectedDay = $eventDayIndex === $day['index']; @endphp
                        <button type="button" wire:click="selectDay({{ $day['index'] }})" data-elevated="false"
                                class="btn-press px-3 py-3 rounded-xl text-sm font-bold transition-colors text-center flex items-center justify-center gap-1.5
                                {{ $isSelectedDay ? 'bg-primary text-primary-foreground' : 'bg-secondary text-foreground border border-border hover:bg-secondary/70' }}">
                            <span>{{ $day['label'] }}@if(!empty($day['is_today'])) <span class="text-[10px] opacity-80">(امروز)</span>@endif</span>
                            @if($isSelectedDay)
                                <x-ui.icon name="check" class="w-3.5 h-3.5"/>
                            @endif
                        </button>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-muted text-[13px]">روز قابل‌انتخابی در این هفته باقی نمانده است.</div>
            @endif
        @endif

        {{-- ░░░ مرحله ۲: برنامهٔ روز قبل + دستهٔ اتفاق ░░░ --}}
        @if($step === 2)
            <p class="text-[13px] text-foreground mb-2 font-semibold">برنامهٔ روز {{ $targetDayLabel }}</p>
            <div class="rounded-2xl bg-secondary border border-border p-3 mb-4 max-h-40 overflow-auto space-y-2">
                @forelse($targetDayParts as $p)
                    <div class="flex items-center justify-between text-[12px]">
                        <span class="text-foreground font-semibold">{{ $p->lesson_name ?? ($p->ccSubject->name ?? 'درس') }}</span>
                        <span class="text-muted">{{ $fmtDuration($p->duration_minutes) }}</span>
                    </div>
                @empty
                    <div class="text-center text-muted text-[12px] py-3">برنامه‌ای برای روز قبل ثبت نشده است.</div>
                @endforelse
            </div>

            <p class="text-[13px] text-foreground mb-2 font-semibold">چه اتفاقی افتاده است؟</p>
            <x-ui.select
                wire:model.live="category"
                wire:key="se-category"
                :options="[
                ['id' => 'exam', 'name' => 'امتحان'],
                ['id' => 'homework', 'name' => 'تکلیف'],
                ['id' => 'class_qa', 'name' => 'پرسش و پاسخ کلاسی'],
            ]"
                placeholder="انتخاب دستهٔ اتفاق..."
                dropUp
            />

            <div class="flex items-center gap-2 mt-5">
                <x-ui.button type="button" wire:click="goToStep(1)" variant="secondary-outline" icon="chevron-right">بازگشت</x-ui.button>
                <x-ui.button type="button" wire:click="nextFromCategory" variant="primary" class="flex-1" icon="chevron-left">ادامه</x-ui.button>
            </div>
        @endif

        {{-- ░░░ مرحله ۳: انتخاب دروس و فصل‌ها (چندتایی) ░░░ --}}
        @if($step === 3)
            <p class="text-[13px] text-foreground mb-2 font-semibold">درس (کتاب) را انتخاب کن</p>
            <x-ui.select
                wire:model.live="pendingSubjectId"
                wire:key="se-pending-subject"
                :options="$availableSubjects"
                value-key="id" label-key="name"
                placeholder="انتخاب کتاب..."
                :searchable="true" search-placeholder="جستجوی درس..."
                dropUp
            />

            <p class="text-[13px] text-foreground mb-2 mt-4 font-semibold">فصل(های) این درس را انتخاب کن (چند‌انتخابی)</p>
            @if(empty($availableChapters))
                <div class="rounded-xl bg-secondary border border-border px-3 py-3 text-center text-[12px] text-muted">
                    ابتدا درس را از بالا انتخاب کن.
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($availableChapters as $c)
                        @php $chosen = in_array((int) $c['id'], $pendingChapterIds, true); @endphp
                        <button type="button" wire:click="toggleChapterSelection({{ $c['id'] }})"
                                wire:key="pending-chapter-{{ $c['id'] }}" data-elevated="false"
                                class="btn-press inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[12.5px] font-semibold transition-colors
                                {{ $chosen ? 'bg-primary text-primary-foreground' : 'bg-secondary text-foreground border border-border hover:bg-secondary/70' }}">
                            <span>{{ $c['name'] }}</span>
                            @if($chosen)
                                <x-ui.icon name="check" class="w-3.5 h-3.5"/>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif

            <x-ui.button type="button" wire:click="addLessonSelection" variant="secondary" block icon="plus" class="mt-3">
                افزودن این درس، و انتخاب درسِ دیگر
            </x-ui.button>

            {{-- فهرست دروس/فصل‌های اضافه‌شده تا این لحظه --}}
            <div class="mt-4">
                @if(!empty($selections))
                    <p class="text-[11px] text-muted mb-2">دروس انتخاب‌شده:</p>
                    <div class="space-y-2.5 max-h-52 overflow-auto">
                        @foreach($selections as $si => $sel)
                            <div class="rounded-xl bg-secondary border border-border p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[13px] font-bold text-foreground">{{ $sel['subject_name'] }}</span>
                                    <button type="button" wire:click="removeLessonSelection({{ $si }})" data-elevated="false"
                                            class="btn-press inline-flex items-center gap-1 text-error hover:text-error/70 text-[11px] font-semibold">
                                        <span>حذف درس</span>
                                        <x-ui.icon name="trash" class="w-3 h-3"/>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($sel['chapters'] as $ci => $ch)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary/15 border border-primary/30 text-[11px] text-primary">
                                            {{ $ch['chapter_name'] }}
                                            <button type="button" wire:click="removeChapterSelection({{ $si }}, {{ $ci }})" data-elevated="false" class="btn-press text-primary hover:text-foreground leading-none">
                                                <x-ui.icon name="x" class="w-3 h-3"/>
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[11px] text-muted text-center py-2">هنوز درسی اضافه نکرده‌ای؛ حداقل یک فصل باید انتخاب شود.</p>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-5">
                <x-ui.button type="button" wire:click="goToStep(2)" variant="secondary-outline" icon="chevron-right">بازگشت</x-ui.button>
                <x-ui.button type="button" wire:click="nextFromLessons" variant="primary" class="flex-1" icon="chevron-left">ادامه</x-ui.button>
            </div>
        @endif

        {{-- ░░░ مرحله ۴: تعداد پارت و مدت هر فصل ░░░ --}}
        @if($step === 4)
            <p class="text-[13px] text-foreground mb-1 font-semibold">برای هر فصل، تعداد پارت و مدتش را مشخص کن</p>
            <p class="text-[11px] text-muted mb-3">مدت هر پارت را با چرخاندنِ ستون‌های ساعت/دقیقه (مثل تایمر گوشی) تنظیم کن؛ حداقل {{ $minPartMinutes }} دقیقه.</p>

            <div class="space-y-3 max-h-[380px] overflow-auto -mx-1 px-1">
                @foreach($selections as $si => $sel)
                    <div class="rounded-2xl bg-secondary border border-border p-3">
                        <p class="text-[12.5px] font-bold text-foreground mb-2.5">{{ $sel['subject_name'] }}</p>

                        <div class="space-y-3">
                            @foreach($sel['chapters'] as $ci => $ch)
                                @php
                                    $chapterTotal = ((int) $ch['hours']) * 60 + (int) $ch['minutes'];
                                    $chapterValid = $chapterTotal >= $minPartMinutes;
                                @endphp
                                <div
                                    wire:key="se-chapter-timing-{{ $si }}-{{ $ci }}"
                                    class="rounded-xl bg-background/60 border border-border p-3"
                                    x-data="{
                                        partCount: @entangle('selections.' . $si . '.chapters.' . $ci . '.part_count').live,
                                        incCount(){ this.partCount = Math.min(20, (parseInt(this.partCount)||1) + 1); },
                                        decCount(){ this.partCount = Math.max(1,  (parseInt(this.partCount)||1) - 1); },
                                    }"
                                >
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="text-[12px] text-foreground font-semibold truncate">{{ $ch['chapter_name'] }}</span>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[10px] text-muted ml-1">تعداد پارت</span>
                                            <button type="button" @click="decCount()" data-elevated="false"
                                                    class="btn-press w-7 h-7 rounded-lg bg-secondary hover:bg-secondary/70 border border-border text-foreground flex items-center justify-center transition-colors">
                                                <x-ui.icon name="minus" class="w-3.5 h-3.5"/>
                                            </button>
                                            <span class="w-6 text-center text-foreground text-sm font-bold" x-text="partCount"></span>
                                            <button type="button" @click="incCount()" data-elevated="false"
                                                    class="btn-press w-7 h-7 rounded-lg bg-secondary hover:bg-secondary/70 border border-border text-foreground flex items-center justify-center transition-colors">
                                                <x-ui.icon name="plus" class="w-3.5 h-3.5"/>
                                            </button>
                                        </div>
                                    </div>

                                    <x-ui.duration-wheel-picker
                                        :hours-model="'selections.' . $si . '.chapters.' . $ci . '.hours'"
                                        :minutes-model="'selections.' . $si . '.chapters.' . $ci . '.minutes'"
                                    />

                                    @unless($chapterValid)
                                        <div class="flex items-center justify-center gap-1.5 mt-2">
                                            <x-ui.icon name="triangle-alert" class="w-3 h-3 text-error flex-shrink-0"/>
                                            <span class="text-[10px] font-semibold text-error">حداقل {{ $minPartMinutes }} دقیقه</span>
                                        </div>
                                    @endunless
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-2 mt-5">
                <x-ui.button type="button" wire:click="goToStep(3)" variant="secondary-outline" icon="chevron-right">بازگشت</x-ui.button>
                <x-ui.button type="button" wire:click="nextFromParts" variant="primary" class="flex-1" icon="chevron-left">ادامه</x-ui.button>
            </div>
        @endif

        {{-- ░░░ مرحله ۵: نمایش بار مطالعه و تایید ░░░ --}}
        @if($step === 5)
            <p class="text-[13px] text-foreground mb-3 font-semibold">بار مطالعهٔ روز {{ $targetDayLabel }}</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center justify-between bg-secondary border border-border rounded-xl px-3 py-2.5 text-[13px]">
                    <span class="text-foreground">برنامهٔ تعیین‌شده توسط مشاور</span>
                    <span class="text-primary font-bold">{{ $fmtDuration($targetLoad['advisor_minutes']) }}</span>
                </div>
                <div class="flex items-center justify-between bg-secondary border border-border rounded-xl px-3 py-2.5 text-[13px]">
                    <span class="text-foreground">اضافه‌شده توسط خودت</span>
                    <span class="text-success font-bold">{{ $fmtDuration($targetLoad['student_minutes']) }}</span>
                </div>
                <div class="flex items-center justify-between bg-warning/10 border border-warning/25 rounded-xl px-3 py-2.5 text-[13px]">
                    <span class="text-warning">اتفاق جدید ({{ $newPartsCount }} پارت)</span>
                    <span class="text-warning font-bold">{{ $fmtDuration($targetLoad['new_minutes']) }}</span>
                </div>
                <div class="flex items-center justify-between bg-primary/10 border border-primary/25 rounded-xl px-3 py-3 text-[13px]">
                    <span class="text-primary font-bold">مجموع کل روز {{ $targetDayLabel }}</span>
                    <span class="text-primary font-black text-base">{{ $fmtDuration($targetLoad['advisor_minutes'] + $targetLoad['student_minutes'] + $targetLoad['new_minutes']) }}</span>
                </div>
            </div>
            <p class="text-[13px] text-foreground mb-4 font-semibold text-center">با این حجم مطالعه در این روز اوکی هستی؟</p>
            <div class="flex items-center gap-2">
                <x-ui.button type="button" wire:click="goToStep(4)" variant="secondary-outline" icon="chevron-right">بازگشت</x-ui.button>
                <x-ui.button type="button" wire:click="startRedistribute" variant="secondary-outline" class="flex-1">خیر، سنگین است</x-ui.button>
                <x-ui.button type="button" wire:click="confirmOkay" variant="success" class="flex-1" icon="check">بله، اوکی است</x-ui.button>
            </div>
        @endif

        {{-- ░░░ مرحله ۶: انتخاب پارت‌های کم‌اهمیت برای جابجایی ░░░ --}}
        @if($step === 6)
            <p class="text-[13px] text-foreground mb-2 font-semibold">پارت‌های کم‌اهمیت روز {{ $targetDayLabel }} را انتخاب کن</p>
            <p class="text-[11px] text-muted mb-4">این پارت‌ها به روزهای باقی‌مانده پخش می‌شوند تا آن روز سبک‌تر شود.</p>
            <div class="space-y-2 mb-5 max-h-56 overflow-auto">
                @forelse($targetDayParts as $p)
                    @php $isLowImportance = in_array($p->id, $lowImportancePartIds); @endphp
                    <button type="button" wire:click="toggleLowImportance({{ $p->id }})" data-elevated="false"
                            class="btn-press w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-[13px] transition-colors
                            {{ $isLowImportance ? 'bg-primary/20 border border-primary' : 'bg-secondary border border-border hover:bg-secondary/70' }}">
                        <span class="flex items-center gap-2">
                            <span class="text-foreground font-semibold">{{ $p->lesson_name ?? ($p->ccSubject->name ?? 'درس') }}</span>
                            @if($isLowImportance)
                                <x-ui.icon name="check" class="w-3.5 h-3.5 text-primary"/>
                            @endif
                        </span>
                        <span class="text-muted">{{ $fmtDuration($p->duration_minutes) }}</span>
                    </button>
                @empty
                    <div class="text-center text-muted text-[12px] py-3">پارتی برای جابجایی وجود ندارد.</div>
                @endforelse
            </div>
            <div class="flex items-center gap-2">
                <x-ui.button type="button" wire:click="goToStep(5)" variant="secondary-outline" icon="chevron-right">بازگشت</x-ui.button>
                <x-ui.button type="button" wire:click="confirmRedistribute" variant="primary" class="flex-1" icon="check">جابجایی و ثبت اتفاق</x-ui.button>
            </div>
        @endif

    @endif {{-- پایان گارد دسترسی --}}
</div>
