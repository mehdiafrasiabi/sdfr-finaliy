@props([
    'partCountModel',
    'hoursModel',
    'minutesModel',
    'maxHours'   => 12,
    'minuteStep' => 5,
    'maxParts'   => 20,
    'partLabel'  => 'تعداد پارت',
])

{{--
    x-ui.duration-part-picker — بخش مشترکِ «تعداد پارت + مدت هر پارت» به سبک
    آیفون، عیناً از الگوی resources/views/livewire/client/profile/_sudden-event-content.blade.php
    (مرحله‌ی ۴) استخراج شده تا در همه‌ی فرم‌های مشابه (مثل pre-session-wizard) یکسان
    و بدون تکرار کد استفاده شود. موتور چرخشِ ساعت/دقیقه همان کامپوننت
    x-ui.duration-wheel-picker است؛ این کامپوننت فقط شمارنده‌ی تعداد پارت را
    به آن اضافه می‌کند.

    استفاده:
        <x-ui.duration-part-picker
            part-count-model="examForm.part_count"
            hours-model="examForm.hours"
            minutes-model="examForm.minutes"
        />
--}}
<div
    class="rounded-2xl bg-secondary border border-border p-3"
    x-data="{
        partCount: @entangle($partCountModel).live,
        incPart(){ this.partCount = Math.min({{ (int) $maxParts }}, (parseInt(this.partCount)||1) + 1); },
        decPart(){ this.partCount = Math.max(1, (parseInt(this.partCount)||1) - 1); },
    }"
>
    <div class="flex items-center justify-between gap-3 mb-3">
        <span class="text-[11px] text-muted-foreground">{{ $partLabel }}</span>
        <div class="flex items-center gap-1.5 shrink-0">
            <button type="button" @click="decPart()" data-elevated="false"
                    class="btn-press w-7 h-7 rounded-lg bg-background hover:bg-background/70 border border-border text-foreground flex items-center justify-center transition-colors">
                <x-ui.icon name="minus" class="w-3.5 h-3.5"/>
            </button>
            <span class="w-6 text-center text-foreground text-sm font-bold" x-text="partCount"></span>
            <button type="button" @click="incPart()" data-elevated="false"
                    class="btn-press w-7 h-7 rounded-lg bg-background hover:bg-background/70 border border-border text-foreground flex items-center justify-center transition-colors">
                <x-ui.icon name="plus" class="w-3.5 h-3.5"/>
            </button>
        </div>
    </div>

    <x-ui.duration-wheel-picker
        :hours-model="$hoursModel"
        :minutes-model="$minutesModel"
        :max-hours="$maxHours"
        :minute-step="$minuteStep"
    />
</div>
