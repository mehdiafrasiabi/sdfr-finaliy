
<div
    x-data="{
        totalMinutes: $wire.entangle('{{ $wireModel }}'),
        hours: 0,
        minutes: 0,
        init() {
            let val = parseInt(this.totalMinutes) || 0;
            this.hours   = Math.floor(val / 60);
            this.minutes = val % 60;
        },
        setHours(h) {
            this.hours = Math.min(Math.max(parseInt(h) || 0, 0), 24);
            this.sync();
        },
        setMinutes(m) {
            this.minutes = Math.min(Math.max(parseInt(m) || 0, 0), 59);
            this.sync();
        },
        incrementHours()   { if(this.hours < 24)  { this.hours++;   this.sync(); } },
        decrementHours()   { if(this.hours > 0)   { this.hours--;   this.sync(); } },
        incrementMinutes() { if(this.minutes < 59) { this.minutes++; this.sync(); } },
        decrementMinutes() { if(this.minutes > 0)  { this.minutes--; this.sync(); } },
        sync() {
            this.totalMinutes = (this.hours * 60) + this.minutes;
        }
    }"
    x-init="init()"
    class="md:col-span-1"
>
    {{--
    Time Picker Partial - spinner style (مثل iOS)
    Params:
        $wireModel : string  - مثل 'examForm.time_per_part'
        $xDataKey  : string  - کلید یکتا برای x-data مثل 'examTimePicker'
--}}
    <label class="mb-2 block text-xs font-medium text-foreground">زمان هر پارت</label>

    <div class="flex items-center gap-3 justify-start" dir="ltr">

        {{-- ساعت --}}
        <div class="flex flex-col items-center gap-1">
            <button
                type="button"
                @click="incrementHours"
                class="flex items-center justify-center w-9 h-9 rounded-xl border border-border bg-background text-foreground
                       hover:bg-blue-500/10 hover:border-blue-400 hover:text-blue-600
                       dark:hover:bg-blue-500/15 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>

            <div class="relative">
                <input
                    type="number"
                    min="0" max="24"
                    x-model.number="hours"
                    @input="setHours($event.target.value)"
                    class="w-16 h-12 rounded-xl border border-border bg-background text-foreground font-bold text-lg text-center
                           shadow-sm outline-none transition
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                           dark:bg-background dark:text-foreground
                           [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                    placeholder="0"
                >
            </div>

            <button
                type="button"
                @click="decrementHours"
                class="flex items-center justify-center w-9 h-9 rounded-xl border border-border bg-background text-foreground
                       hover:bg-blue-500/10 hover:border-blue-400 hover:text-blue-600
                       dark:hover:bg-blue-500/15 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <span class="text-[10px] text-muted-foreground font-medium">ساعت</span>
        </div>

        {{-- جداکننده --}}
        <div class="text-2xl font-black text-muted-foreground pb-5">:</div>

        {{-- دقیقه --}}
        <div class="flex flex-col items-center gap-1">
            <button
                type="button"
                @click="incrementMinutes"
                class="flex items-center justify-center w-9 h-9 rounded-xl border border-border bg-background text-foreground
                       hover:bg-blue-500/10 hover:border-blue-400 hover:text-blue-600
                       dark:hover:bg-blue-500/15 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>

            <div class="relative">
                <input
                    type="number"
                    min="0" max="59"
                    x-model.number="minutes"
                    @input="setMinutes($event.target.value)"
                    class="w-16 h-12 rounded-xl border border-border bg-background text-foreground font-bold text-lg text-center
                           shadow-sm outline-none transition
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                           dark:bg-background dark:text-foreground
                           [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                    placeholder="30"
                >
            </div>

            <button
                type="button"
                @click="decrementMinutes"
                class="flex items-center justify-center w-9 h-9 rounded-xl border border-border bg-background text-foreground
                       hover:bg-blue-500/10 hover:border-blue-400 hover:text-blue-600
                       dark:hover:bg-blue-500/15 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <span class="text-[10px] text-muted-foreground font-medium">دقیقه</span>
        </div>

        {{-- نمایش مجموع --}}
        <div class="pb-5 mr-1" x-show="totalMinutes > 0">
            <div class="flex flex-col items-center justify-center bg-blue-500/10 dark:bg-blue-500/15 rounded-xl px-3 py-2 border border-blue-200 dark:border-blue-500/30">
                <span class="text-blue-700 dark:text-blue-400 font-bold text-sm" x-text="totalMinutes"></span>
                <span class="text-blue-600/70 dark:text-blue-400/70 text-[10px]">دقیقه</span>
            </div>
        </div>
    </div>
</div>
