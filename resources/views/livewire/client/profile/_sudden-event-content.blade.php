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
    <button type="button" @click="$wire.close()"
            class="absolute top-3 left-3 p-1.5 rounded-full text-neutral-400 hover:text-white hover:bg-white/10 transition">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
    </button>

    {{-- عنوان --}}
    <div class="flex items-center gap-2.5 mb-4">
        <span class="w-9 h-9 rounded-xl bg-amber-500/15 ring-1 ring-amber-500/30 flex items-center justify-center text-lg">⚡</span>
        <div>
            <h2 class="text-base font-black text-white">اتفاقات یهویی</h2>
            <p class="text-[11px] text-neutral-400">ثبت اتفاق ناگهانی و اصلاح برنامه</p>
        </div>
    </div>

    {{-- ░░░ بدون دسترسی: برنامه ندارد یا برنامه‌اش منقضی شده ░░░ --}}
    @if(($accessState ?? 'ok') !== 'ok')
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-6 text-center">
            <span class="inline-flex w-14 h-14 rounded-2xl bg-amber-500/15 ring-1 ring-amber-500/30 items-center justify-center text-3xl mb-3">
                @if($accessState === 'expired') ⏳ @else 📭 @endif
            </span>
            @if($accessState === 'expired')
                <h3 class="text-[15px] font-black text-white mb-1.5">دسترسی شما به پایان رسیده است</h3>
                <p class="text-[12px] text-neutral-400 leading-relaxed">
                    مهلت ثبت اتفاق یهویی برای برنامهٔ فعلی‌ات تمام شده است.
                    پس از دریافت برنامهٔ جدید از مشاورت می‌توانی دوباره از این بخش استفاده کنی.
                </p>
            @else
                <h3 class="text-[15px] font-black text-white mb-1.5">شما هنوز برنامه‌ای ندارید</h3>
                <p class="text-[12px] text-neutral-400 leading-relaxed">
                    برای ثبت اتفاق یهویی، ابتدا باید یک برنامهٔ درسی فعال داشته باشی.
                    پس از دریافت برنامه از مشاورت، این بخش در دسترس قرار می‌گیرد.
                </p>
            @endif
        </div>
        <button type="button" @click="$wire.close()"
                class="w-full mt-5 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">
            متوجه شدم
        </button>
    @else

        {{-- نوار پیشرفت مراحل --}}
        @if($step >= 1 && $step <= 5)
            <div class="flex items-center gap-1 mb-5">
                @for($s = 1; $s <= 5; $s++)
                    <div class="h-1 flex-1 rounded-full {{ $step >= $s ? 'bg-sky-400' : 'bg-white/10' }}"></div>
                @endfor
            </div>
        @endif

        {{-- بنرِ خطا/هشدارِ همین مرحله — همیشه داخل خودِ مودال نمایش داده می‌شود، هیچ‌وقت مودال را نمی‌بندد --}}
        @if(!empty($stepError))
            <div class="rounded-xl bg-red-500/10 ring-1 ring-red-500/30 px-3 py-2.5 mb-4 flex items-start gap-2">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
                <span class="text-[12.5px] text-red-200 flex-1 leading-relaxed">{{ $stepError }}</span>
                <button type="button" wire:click="clearStepError" class="text-red-300 hover:text-white shrink-0">✕</button>
            </div>
        @endif

        {{-- ░░░ مرحله ۰: هشدار ░░░ --}}
        @if($step === 0)
            <div class="rounded-2xl bg-red-500/10 ring-1 ring-red-500/30 p-4 mb-5">
                <div class="flex items-start gap-2.5">
                    <span class="text-xl">⚠️</span>
                    <div class="text-[13px] leading-relaxed text-red-200">
                        <p class="font-bold text-red-100 mb-1">توجه!</p>
                        شما در حال <span class="font-bold">تغییر برنامهٔ درسی</span> خودت هستی و عواقب این تغییر
                        بر عهدهٔ خودت است. پارت‌ها به برنامهٔ فعالت اضافه یا جابجا می‌شوند.
                    </div>
                </div>
            </div>
            <button type="button" wire:click="goToStep(1)"
                    class="w-full py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">
                متوجه شدم، ادامه می‌دهم
            </button>
        @endif

        {{-- ░░░ مرحله ۱: انتخاب روز اتفاق ░░░ --}}
        @if($step === 1)
            <p class="text-[13px] text-neutral-300 mb-3 font-semibold">این اتفاق در چه روزی افتاده/می‌افتد؟</p>
            <p class="text-[11px] text-neutral-500 mb-4">امروز و روزهای باقی‌ماندهٔ این هفته قابل انتخاب‌اند (روزهای گذشته غیرفعال است).</p>
            @if(count($availableDays) > 0)
                <div class="grid grid-cols-2 gap-2 mb-5">
                    @foreach($availableDays as $day)
                        <button type="button" wire:click="selectDay({{ $day['index'] }})"
                                class="px-3 py-3 rounded-xl text-sm font-bold transition text-center
                                {{ $eventDayIndex === $day['index'] ? 'bg-sky-500 text-white ring-1 ring-sky-300' : 'bg-white/5 text-neutral-200 ring-1 ring-white/10 hover:bg-white/10' }}">
                            {{ $day['label'] }}@if(!empty($day['is_today'])) <span class="text-[10px] opacity-80">(امروز)</span>@endif
                        </button>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-neutral-500 text-[13px]">روز قابل‌انتخابی در این هفته باقی نمانده است.</div>
            @endif
        @endif

        {{-- ░░░ مرحله ۲: برنامهٔ روز قبل + دستهٔ اتفاق ░░░ --}}
        @if($step === 2)
            <p class="text-[13px] text-neutral-300 mb-2 font-semibold">برنامهٔ روز {{ $targetDayLabel }}</p>
            <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-3 mb-4 max-h-40 overflow-auto space-y-2">
                @forelse($targetDayParts as $p)
                    <div class="flex items-center justify-between text-[12px]">
                        <span class="text-white font-semibold">{{ $p->lesson_name ?? ($p->ccSubject->name ?? 'درس') }}</span>
                        <span class="text-neutral-400">{{ $fmtDuration($p->duration_minutes) }}</span>
                    </div>
                @empty
                    <div class="text-center text-neutral-500 text-[12px] py-3">برنامه‌ای برای روز قبل ثبت نشده است.</div>
                @endforelse
            </div>

            <p class="text-[13px] text-neutral-300 mb-2 font-semibold">چه اتفاقی افتاده است؟</p>
            <x-ui.select
                wire:model.live="category"
                wire:key="se-category"
                :options="[
                ['id' => 'exam', 'name' => 'امتحان'],
                ['id' => 'homework', 'name' => 'تکلیف'],
                ['id' => 'class_qa', 'name' => 'پرسش و پاسخ کلاسی'],
            ]"
                placeholder="انتخاب دستهٔ اتفاق..."
                :drop-up="true"
            />

            <div class="flex items-center gap-2 mt-5">
                <button type="button" wire:click="goToStep(1)"
                        class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
                <button type="button" wire:click="nextFromCategory"
                        class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">ادامه</button>
            </div>
        @endif

        {{-- ░░░ مرحله ۳: انتخاب دروس و فصل‌ها (چندتایی) ░░░ --}}
        @if($step === 3)
            <p class="text-[13px] text-neutral-300 mb-2 font-semibold">درس (کتاب) را انتخاب کن</p>
            <x-ui.select
                wire:model.live="pendingSubjectId"
                wire:key="se-pending-subject"
                :options="$availableSubjects"
                value-key="id" label-key="name"
                placeholder="انتخاب کتاب..."
                :searchable="true" search-placeholder="جستجوی درس..."
                :drop-up="true"
            />

            <p class="text-[13px] text-neutral-300 mb-2 mt-4 font-semibold">فصل(های) این درس را انتخاب کن (چند‌انتخابی)</p>
            @if(empty($availableChapters))
                <div class="rounded-xl bg-white/5 ring-1 ring-white/10 px-3 py-3 text-center text-[12px] text-neutral-500">
                    ابتدا درس را از بالا انتخاب کن.
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($availableChapters as $c)
                        @php $chosen = in_array((int) $c['id'], $pendingChapterIds, true); @endphp
                        <button type="button" wire:click="toggleChapterSelection({{ $c['id'] }})"
                                wire:key="pending-chapter-{{ $c['id'] }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[12.5px] font-semibold transition
                                {{ $chosen ? 'bg-sky-500 text-white ring-1 ring-sky-300' : 'bg-white/5 text-neutral-300 ring-1 ring-white/10 hover:bg-white/10' }}">
                            <span class="w-4 h-4 rounded border flex items-center justify-center shrink-0 {{ $chosen ? 'bg-white/20 border-white/40' : 'border-white/25' }}">
                                @if($chosen)
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </span>
                            {{ $c['name'] }}
                        </button>
                    @endforeach
                </div>
            @endif

            <button type="button" wire:click="addLessonSelection"
                    class="w-full mt-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 ring-1 ring-white/15 text-white text-[13px] font-bold transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                افزودن این درس، و انتخاب درسِ دیگر
            </button>

            {{-- فهرست دروس/فصل‌های اضافه‌شده تا این لحظه --}}
            <div class="mt-4">
                @if(!empty($selections))
                    <p class="text-[11px] text-neutral-500 mb-2">دروس انتخاب‌شده:</p>
                    <div class="space-y-2.5 max-h-52 overflow-auto">
                        @foreach($selections as $si => $sel)
                            <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[13px] font-bold text-white">{{ $sel['subject_name'] }}</span>
                                    <button type="button" wire:click="removeLessonSelection({{ $si }})"
                                            class="text-red-400 hover:text-red-300 text-[11px] font-semibold">حذف درس</button>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($sel['chapters'] as $ci => $ch)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-500/15 ring-1 ring-sky-400/30 text-[11px] text-sky-200">
                                            {{ $ch['chapter_name'] }}
                                            <button type="button" wire:click="removeChapterSelection({{ $si }}, {{ $ci }})" class="text-sky-300 hover:text-white leading-none">✕</button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[11px] text-neutral-500 text-center py-2">هنوز درسی اضافه نکرده‌ای؛ حداقل یک فصل باید انتخاب شود.</p>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-5">
                <button type="button" wire:click="goToStep(2)"
                        class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
                <button type="button" wire:click="nextFromLessons"
                        class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">ادامه</button>
            </div>
        @endif

        {{-- ░░░ مرحله ۴: تعداد پارت و مدت هر فصل ░░░ --}}
        @if($step === 4)
            <p class="text-[13px] text-neutral-300 mb-1 font-semibold">برای هر فصل، تعداد پارت و مدتش را مشخص کن</p>
            <p class="text-[11px] text-neutral-500 mb-3">مدت هر پارت را با چرخاندنِ ستون‌های ساعت/دقیقه (مثل تایمر گوشی) تنظیم کن؛ حداقل {{ $minPartMinutes }} دقیقه.</p>

            <div class="space-y-3 max-h-[380px] overflow-auto -mx-1 px-1">
                @foreach($selections as $si => $sel)
                    <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-3">
                        <p class="text-[12.5px] font-bold text-white mb-2.5">{{ $sel['subject_name'] }}</p>

                        <div class="space-y-3">
                            @foreach($sel['chapters'] as $ci => $ch)
                                @php
                                    $chapterTotal = ((int) $ch['hours']) * 60 + (int) $ch['minutes'];
                                    $chapterValid = $chapterTotal >= $minPartMinutes;
                                @endphp
                                <div
                                    wire:key="se-chapter-timing-{{ $si }}-{{ $ci }}"
                                    class="rounded-xl bg-black/20 ring-1 ring-white/10 p-3"
                                    x-data="{
                                        partCount: @entangle('selections.' . $si . '.chapters.' . $ci . '.part_count').live,
                                        incCount(){ this.partCount = Math.min(20, (parseInt(this.partCount)||1) + 1); },
                                        decCount(){ this.partCount = Math.max(1,  (parseInt(this.partCount)||1) - 1); },
                                    }"
                                >
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span class="text-[12px] text-neutral-300 font-semibold truncate">{{ $ch['chapter_name'] }}</span>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[10px] text-neutral-500 ml-1">تعداد پارت</span>
                                            <button type="button" @click="decCount()"
                                                    class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold flex items-center justify-center transition">−</button>
                                            <span class="w-6 text-center text-white text-sm font-bold" x-text="partCount"></span>
                                            <button type="button" @click="incCount()"
                                                    class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold flex items-center justify-center transition">+</button>
                                        </div>
                                    </div>

                                    <x-ui.duration-wheel-picker
                                        :hours-model="'selections.' . $si . '.chapters.' . $ci . '.hours'"
                                        :minutes-model="'selections.' . $si . '.chapters.' . $ci . '.minutes'"
                                    />

                                    @unless($chapterValid)
                                        <div class="flex items-center justify-center gap-1.5 mt-2">
                                            <svg class="w-3 h-3 text-red-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <path d="M12 8v4M12 16h.01"/>
                                            </svg>
                                            <span class="text-[10px] font-semibold text-red-300">حداقل {{ $minPartMinutes }} دقیقه</span>
                                        </div>
                                    @endunless
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-2 mt-5">
                <button type="button" wire:click="goToStep(3)"
                        class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
                <button type="button" wire:click="nextFromParts"
                        class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">ادامه</button>
            </div>
        @endif

        {{-- ░░░ مرحله ۵: نمایش بار مطالعه و تایید ░░░ --}}
        @if($step === 5)
            <p class="text-[13px] text-neutral-300 mb-3 font-semibold">بار مطالعهٔ روز {{ $targetDayLabel }}</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5 text-[13px]">
                    <span class="text-neutral-300">برنامهٔ تعیین‌شده توسط مشاور</span>
                    <span class="text-sky-400 font-bold">{{ $fmtDuration($targetLoad['advisor_minutes']) }}</span>
                </div>
                <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5 text-[13px]">
                    <span class="text-neutral-300">اضافه‌شده توسط خودت</span>
                    <span class="text-emerald-400 font-bold">{{ $fmtDuration($targetLoad['student_minutes']) }}</span>
                </div>
                <div class="flex items-center justify-between bg-amber-500/10 ring-1 ring-amber-500/25 rounded-xl px-3 py-2.5 text-[13px]">
                    <span class="text-amber-200">اتفاق جدید ({{ $newPartsCount }} پارت)</span>
                    <span class="text-amber-300 font-bold">{{ $fmtDuration($targetLoad['new_minutes']) }}</span>
                </div>
                <div class="flex items-center justify-between bg-sky-500/10 ring-1 ring-sky-500/25 rounded-xl px-3 py-3 text-[13px]">
                    <span class="text-sky-200 font-bold">مجموع کل روز {{ $targetDayLabel }}</span>
                    <span class="text-sky-200 font-black text-base">{{ $fmtDuration($targetLoad['advisor_minutes'] + $targetLoad['student_minutes'] + $targetLoad['new_minutes']) }}</span>
                </div>
            </div>
            <p class="text-[13px] text-neutral-200 mb-4 font-semibold text-center">با این حجم مطالعه در این روز اوکی هستی؟</p>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="goToStep(4)"
                        class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
                <button type="button" wire:click="startRedistribute"
                        class="flex-1 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-200 font-bold text-sm hover:bg-white/10 transition">خیر، سنگین است</button>
                <button type="button" wire:click="confirmOkay"
                        class="flex-1 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-sm transition">بله، اوکی است</button>
            </div>
        @endif

        {{-- ░░░ مرحله ۶: انتخاب پارت‌های کم‌اهمیت برای جابجایی ░░░ --}}
        @if($step === 6)
            <p class="text-[13px] text-neutral-300 mb-2 font-semibold">پارت‌های کم‌اهمیت روز {{ $targetDayLabel }} را انتخاب کن</p>
            <p class="text-[11px] text-neutral-500 mb-4">این پارت‌ها به روزهای باقی‌مانده پخش می‌شوند تا آن روز سبک‌تر شود.</p>
            <div class="space-y-2 mb-5 max-h-56 overflow-auto">
                @forelse($targetDayParts as $p)
                    <button type="button" wire:click="toggleLowImportance({{ $p->id }})"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-[13px] transition
                            {{ in_array($p->id, $lowImportancePartIds) ? 'bg-sky-500/20 ring-1 ring-sky-400' : 'bg-white/5 ring-1 ring-white/10 hover:bg-white/10' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded border flex items-center justify-center {{ in_array($p->id, $lowImportancePartIds) ? 'bg-sky-500 border-sky-400' : 'border-white/30' }}">
                            @if(in_array($p->id, $lowImportancePartIds))
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </span>
                        <span class="text-white font-semibold">{{ $p->lesson_name ?? ($p->ccSubject->name ?? 'درس') }}</span>
                    </span>
                        <span class="text-neutral-400">{{ $fmtDuration($p->duration_minutes) }}</span>
                    </button>
                @empty
                    <div class="text-center text-neutral-500 text-[12px] py-3">پارتی برای جابجایی وجود ندارد.</div>
                @endforelse
            </div>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="goToStep(5)"
                        class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
                <button type="button" wire:click="confirmRedistribute"
                        class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">جابجایی و ثبت اتفاق</button>
            </div>
        @endif

    @endif {{-- پایان گارد دسترسی --}}
</div>
