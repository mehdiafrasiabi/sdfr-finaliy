{{-- محتوای مشترک مودال «اتفاقات یهویی» (مراحل سرور-محور بر اساس $step) --}}
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

    {{-- نوار پیشرفت مراحل --}}
    @if($step >= 1 && $step <= 5)
        <div class="flex items-center gap-1 mb-5">
            @for($s = 1; $s <= 5; $s++)
                <div class="h-1 flex-1 rounded-full {{ $step >= $s ? 'bg-sky-400' : 'bg-white/10' }}"></div>
            @endfor
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
        <p class="text-[11px] text-neutral-500 mb-4">فقط روزهای باقی‌ماندهٔ این هفته قابل انتخاب‌اند (روزهای گذشته و امروز غیرفعال است).</p>
        @if(count($availableDays) > 0)
            <div class="grid grid-cols-2 gap-2 mb-5">
                @foreach($availableDays as $day)
                    <button type="button" wire:click="selectDay({{ $day['index'] }})"
                            class="px-3 py-3 rounded-xl text-sm font-bold transition text-center
                                {{ $eventDayIndex === $day['index'] ? 'bg-sky-500 text-white ring-1 ring-sky-300' : 'bg-white/5 text-neutral-200 ring-1 ring-white/10 hover:bg-white/10' }}">
                        {{ $day['label'] }}
                    </button>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-neutral-500 text-[13px]">روز قابل‌انتخابی در این هفته باقی نمانده است.</div>
        @endif
    @endif

    {{-- ░░░ مرحله ۲: برنامهٔ روز قبل + دستهٔ اتفاق ░░░ --}}
    @if($step === 2)
        <p class="text-[13px] text-neutral-300 mb-2 font-semibold">برنامهٔ روز قبل از اتفاق</p>
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-3 mb-4 max-h-40 overflow-auto space-y-2">
            @forelse($targetDayParts as $p)
                <div class="flex items-center justify-between text-[12px]">
                    <span class="text-white font-semibold">{{ $p->lesson_name ?? ($p->ccSubject->name ?? 'درس') }}</span>
                    <span class="text-neutral-400" style="direction:ltr;">{{ $p->duration_minutes }} دقیقه</span>
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
        />

        <div class="flex items-center gap-2 mt-5">
            <button type="button" wire:click="goToStep(1)"
                    class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
            <button type="button" wire:click="nextFromCategory"
                    class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">ادامه</button>
        </div>
    @endif

    {{-- ░░░ مرحله ۳: کتاب و فصل ░░░ --}}
    @if($step === 3)
        <p class="text-[13px] text-neutral-300 mb-2 font-semibold">کتاب (درس)</p>
        <x-ui.select
            wire:model.live="ccSubjectId"
            wire:key="se-subject"
            :options="$availableSubjects"
            value-key="id" label-key="name"
            placeholder="انتخاب کتاب..."
            :searchable="true" search-placeholder="جستجوی درس..."
        />

        <p class="text-[13px] text-neutral-300 mb-2 mt-4 font-semibold">فصل</p>
        <x-ui.select
            wire:model.live="ccChapterId"
            wire:key="se-chapter-{{ $ccSubjectId }}"
            :options="$availableChapters"
            value-key="id" label-key="name"
            placeholder="ابتدا کتاب را انتخاب کن..."
            :searchable="true" search-placeholder="جستجوی فصل..."
            :disabled="empty($availableChapters)"
        />

        <div class="flex items-center gap-2 mt-5">
            <button type="button" wire:click="goToStep(2)"
                    class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
            <button type="button" wire:click="nextFromSubject"
                    class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">ادامه</button>
        </div>
    @endif

    {{-- ░░░ مرحله ۴: تعداد پارت و ساعت/دقیقه ░░░ --}}
    @if($step === 4)
        <p class="text-[13px] text-neutral-300 mb-3 font-semibold">چند پارت و با چه مدتی؟</p>
        <div class="space-y-3">
            <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5">
                <span class="text-[13px] text-neutral-300">تعداد پارت</span>
                <input type="number" min="1" wire:model="partCount"
                       class="w-20 bg-background/40 border border-white/10 rounded-lg px-2 py-1.5 text-center text-white text-sm" style="direction:ltr;">
            </div>
            <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5">
                <span class="text-[13px] text-neutral-300">مدت هر پارت (ساعت)</span>
                <input type="number" min="0" wire:model="hours"
                       class="w-20 bg-background/40 border border-white/10 rounded-lg px-2 py-1.5 text-center text-white text-sm" style="direction:ltr;">
            </div>
            <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5">
                <span class="text-[13px] text-neutral-300">مدت هر پارت (دقیقه)</span>
                <input type="number" min="0" max="59" wire:model="minutes"
                       class="w-20 bg-background/40 border border-white/10 rounded-lg px-2 py-1.5 text-center text-white text-sm" style="direction:ltr;">
            </div>
        </div>
        <p class="text-[11px] text-neutral-500 mt-3">مثلاً ۳ پارت با مدت ۱ ساعت = ۳ پارت یک‌ساعته اضافه می‌شود.</p>

        <div class="flex items-center gap-2 mt-5">
            <button type="button" wire:click="goToStep(3)"
                    class="px-4 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-300 text-sm font-semibold hover:bg-white/10 transition">بازگشت</button>
            <button type="button" wire:click="nextFromParts"
                    class="flex-1 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition">ادامه</button>
        </div>
    @endif

    {{-- ░░░ مرحله ۵: نمایش بار مطالعه و تایید ░░░ --}}
    @if($step === 5)
        @php
            $advH = round($targetLoad['advisor_minutes'] / 60, 1);
            $stuH = round($targetLoad['student_minutes'] / 60, 1);
            $newH = round($targetLoad['new_minutes'] / 60, 1);
        @endphp
        <p class="text-[13px] text-neutral-300 mb-3 font-semibold">بار مطالعهٔ روز قبل از اتفاق</p>
        <div class="space-y-2 mb-4">
            <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5 text-[13px]">
                <span class="text-neutral-300">برنامهٔ تعیین‌شده توسط مشاور</span>
                <span class="text-sky-400 font-bold" style="direction:ltr;">{{ $advH }} ساعت</span>
            </div>
            <div class="flex items-center justify-between bg-white/5 ring-1 ring-white/10 rounded-xl px-3 py-2.5 text-[13px]">
                <span class="text-neutral-300">اضافه‌شده توسط خودت</span>
                <span class="text-emerald-400 font-bold" style="direction:ltr;">{{ $stuH }} ساعت</span>
            </div>
            <div class="flex items-center justify-between bg-amber-500/10 ring-1 ring-amber-500/25 rounded-xl px-3 py-2.5 text-[13px]">
                <span class="text-amber-200">اتفاق جدید ({{ $partCount }} پارت)</span>
                <span class="text-amber-300 font-bold" style="direction:ltr;">{{ $newH }} ساعت</span>
            </div>
        </div>
        <p class="text-[13px] text-neutral-200 mb-4 font-semibold text-center">با این حجم مطالعه در روز قبل اوکی هستی؟</p>
        <div class="flex items-center gap-2">
            <button type="button" wire:click="startRedistribute"
                    class="flex-1 py-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 text-neutral-200 font-bold text-sm hover:bg-white/10 transition">خیر، سنگین است</button>
            <button type="button" wire:click="confirmOkay"
                    class="flex-1 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-sm transition">بله، اوکی است</button>
        </div>
    @endif

    {{-- ░░░ مرحله ۶: انتخاب پارت‌های کم‌اهمیت برای جابجایی ░░░ --}}
    @if($step === 6)
        <p class="text-[13px] text-neutral-300 mb-2 font-semibold">پارت‌های کم‌اهمیت روز قبل را انتخاب کن</p>
        <p class="text-[11px] text-neutral-500 mb-4">این پارت‌ها به روزهای باقی‌مانده پخش می‌شوند تا روز قبل سبک‌تر شود.</p>
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
                    <span class="text-neutral-400" style="direction:ltr;">{{ $p->duration_minutes }} دقیقه</span>
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
</div>
