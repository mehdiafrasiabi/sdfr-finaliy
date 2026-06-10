{{--
    تور راهنمای صفحه — نمایش خودکار در اولین ورود + آیکون راهنما (بالا چپ) برای نمایش دوباره.
    props:
      - steps: آرایه‌ای از ['el' => سلکتور, 'title' => عنوان, 'text' => توضیح]
      - storageKey: کلید localStorage برای «یک‌بار دیده شد»
--}}
@props(['steps' => [], 'storageKey' => 'page_tour_done'])

<div dir="rtl"
     x-data="{
        steps: {{ \Illuminate\Support\Js::from($steps) }},
        storageKey: @js($storageKey),
        active: false,
        index: 0,
        top: 0,
        right: 0,
        _booted: false,
        init() {
            if (this._booted) return;
            this._booted = true;
            if (!localStorage.getItem(this.storageKey)) {
                setTimeout(() => this.start(), 800);
            }
        },
        firstVisible(from) {
            for (let i = from; i < this.steps.length; i++) {
                if (document.querySelector(this.steps[i].el)) return i;
            }
            return -1;
        },
        start() {
            const first = this.firstVisible(0);
            if (first === -1) return;
            this.index = first;
            this.active = true;
            this.position();
        },
        next() {
            const n = this.firstVisible(this.index + 1);
            if (n === -1) { this.finish(); return; }
            this.index = n;
            this.position();
        },
        finish() {
            this.active = false;
            localStorage.setItem(this.storageKey, '1');
        },
        position() {
            const t = document.querySelector(this.steps[this.index].el);
            if (!t) { this.next(); return; }
            t.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                const r = t.getBoundingClientRect();
                const w = Math.min(320, window.innerWidth - 32);
                const h = 180;
                let top = r.bottom + 12;
                if (top + h > window.innerHeight) {
                    top = Math.max(12, r.top - h - 12);
                }
                this.top = top;
                // هم‌ترازی با لبه‌ی راست باکس (RTL) + ماندن داخل صفحه در همه‌ی سایزها
                this.right = Math.max(16, Math.min(window.innerWidth - r.right, window.innerWidth - w - 16));
            }, 350);
        },
     }">

    {{-- آیکون راهنما — بالا چپ، همیشه در دسترس --}}
    <button type="button" @click="start()" title="راهنمای صفحه"
            class="fixed top-20 left-3 sm:left-5 z-[70] w-10 h-10 rounded-full bg-secondary border border-border shadow-lg
                   flex items-center justify-center text-muted hover:text-primary hover:border-primary/40 transition-colors">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </button>

    {{-- اورلی + تول‌تیپ --}}
    <template x-if="active">
        <div class="fixed inset-0 z-[80]">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]" @click="finish()"></div>

            <div class="absolute w-[320px] max-w-[calc(100vw-2rem)] rounded-2xl p-4 shadow-2xl bg-secondary border border-border"
                 dir="rtl"
                 :style="`top:${top}px; right:${right}px;`">
                <p class="text-sm text-foreground leading-7 mb-1 font-bold" x-text="steps[index].title"></p>
                <p class="text-xs text-muted leading-6 mb-4" x-text="steps[index].text"></p>

                <div class="flex items-center justify-between gap-3">
                    {{-- نقطه‌های پیشرفت --}}
                    <div class="flex items-center gap-1.5">
                        <template x-for="(s, i) in steps" :key="i">
                            <span class="rounded-full transition-all"
                                  :class="i === index ? 'w-4 h-1.5 bg-primary' : 'w-1.5 h-1.5 bg-muted-foreground/30'"></span>
                        </template>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="finish()"
                                class="text-[11px] text-muted hover:text-foreground transition-colors">رد کردن</button>
                        <button type="button" @click="next()"
                                class="px-4 py-2 rounded-lg text-xs font-bold bg-primary text-primary-foreground transition-transform hover:scale-105"
                                x-text="index === steps.length - 1 ? 'تمام' : 'بعدی'"></button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
