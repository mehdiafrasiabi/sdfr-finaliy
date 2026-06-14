<div>

    @props(['steps' => [], 'storageKey' => 'page_tour_done'])

    <div dir="rtl"
         x-data="{
        steps: {{ \Illuminate\Support\Js::from($steps) }},
        storageKey: @js($storageKey),
        active: false,
        transitioning: false,
        index: 0,
        _booted: false,

        /* موقعیت spotlight */
        spotTop: 0,
        spotLeft: 0,
        spotWidth: 0,
        spotHeight: 0,

        /* موقعیت تولتیپ */
        tipTop: 0,
        tipRight: 0,
        tipSide: 'bottom', /* bottom | top */

        init() {
            if (this._booted) return;
            this._booted = true;
            window.addEventListener('resize', () => { if (this.active) this.calcPosition(false); });
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
            this.$nextTick(() => this.calcPosition(true));
        },

        next() {
            const n = this.firstVisible(this.index + 1);
            if (n === -1) { this.finish(); return; }
            this.transitioning = true;
            setTimeout(() => {
                this.index = n;
                this.$nextTick(() => {
                    this.calcPosition(true);
                    setTimeout(() => { this.transitioning = false; }, 60);
                });
            }, 220);
        },

        finish() {
            this.active = false;
            localStorage.setItem(this.storageKey, '1');
        },

        calcPosition(scroll) {
            const el = document.querySelector(this.steps[this.index].el);
            if (!el) { this.next(); return; }

            if (scroll) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            const doCalc = () => {
                const r = el.getBoundingClientRect();
                const pad = 8;

                /* spotlight */
                this.spotTop    = r.top    - pad;
                this.spotLeft   = r.left   - pad;
                this.spotWidth  = r.width  + pad * 2;
                this.spotHeight = r.height + pad * 2;

                /* تولتیپ */
                const tipW = Math.min(300, window.innerWidth - 32);
                const tipH = 170;
                const spaceBelow = window.innerHeight - r.bottom - pad;
                const spaceAbove = r.top - pad;

                if (spaceBelow >= tipH + 16 || spaceBelow >= spaceAbove) {
                    this.tipSide = 'bottom';
                    this.tipTop  = r.bottom + pad + 12;
                } else {
                    this.tipSide = 'top';
                    this.tipTop  = r.top - pad - tipH - 12;
                }

                /* افست افقی: تولتیپ از راست المان شروع، ولی داخل صفحه بمونه */
                const rightAligned = window.innerWidth - r.right;
                this.tipRight = Math.max(16, Math.min(rightAligned, window.innerWidth - tipW - 16));
            };

            if (scroll) {
                setTimeout(doCalc, 380);
            } else {
                doCalc();
            }
        },
     }">

        {{-- آیکون راهنما — بالا چپ --}}
        <button type="button" @click="start()" title="راهنمای صفحه"
                class="fixed top-20 left-3 sm:left-5 z-[70] w-10 h-10 rounded-full bg-secondary border border-border shadow-lg
                   flex items-center justify-center text-muted hover:text-primary hover:border-primary/40 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </button>

        {{-- ────── تور اصلی ────── --}}
        <template x-if="active">
            <div class="fixed inset-0 z-[90] pointer-events-none" aria-modal="true" role="dialog">

                {{-- ░░ لایه‌ی blur/dark با clip-path برای spotlight ░░ --}}
                <div class="absolute inset-0 pointer-events-auto"
                     @click="finish()"
                     :style="`
                    background: rgba(0,0,0,.72);
                    backdrop-filter: blur(4px);
                    -webkit-backdrop-filter: blur(4px);
                    clip-path: polygon(
                        0% 0%, 100% 0%, 100% 100%, 0% 100%,
                        0% ${spotTop}px,
                        ${spotLeft}px ${spotTop}px,
                        ${spotLeft}px ${spotTop + spotHeight}px,
                        ${spotLeft + spotWidth}px ${spotTop + spotHeight}px,
                        ${spotLeft + spotWidth}px ${spotTop}px,
                        0% ${spotTop}px
                    );
                    transition: clip-path .4s cubic-bezier(.4,0,.2,1);
                 `">
                </div>

                {{-- ░░ حلقه‌ی درخشان دور المان ░░ --}}
                <div class="absolute rounded-xl pointer-events-none"
                     :style="`
                    top:    ${spotTop}px;
                    left:   ${spotLeft}px;
                    width:  ${spotWidth}px;
                    height: ${spotHeight}px;
                    box-shadow:
                        0 0 0 2px rgba(56,189,248,.9),
                        0 0 0 5px rgba(56,189,248,.20),
                        0 0 28px 4px rgba(56,189,248,.30);
                    transition: top .4s cubic-bezier(.4,0,.2,1),
                                left .4s cubic-bezier(.4,0,.2,1),
                                width .4s cubic-bezier(.4,0,.2,1),
                                height .4s cubic-bezier(.4,0,.2,1);
                 `">
                </div>

                {{-- ░░ تولتیپ معمولی (مراحل غیر navbar) ░░ --}}
                <div class="absolute pointer-events-auto"
                     x-show="steps[index].el !== '[data-tour=navbar]'"
                     :style="`top: ${tipTop}px; right: ${tipRight}px; width: 300px; max-width: calc(100vw - 2rem);`"
                     :class="transitioning ? 'opacity-0 scale-95' : 'opacity-100 scale-100'"
                     style="transition: opacity .22s ease, transform .22s ease;">

                    <div class="absolute w-3 h-3 rotate-45 bg-[#131825] border border-white/10"
                         :class="tipSide === 'bottom' ? '-top-1.5 right-5' : '-bottom-1.5 right-5'"
                         style="z-index:-1"></div>

                    <div class="rounded-2xl p-4 shadow-2xl border border-white/10 backdrop-blur-xl"
                         style="background: rgba(13,18,30,.92);">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-bold text-white" x-text="steps[index].title"></p>
                            <button type="button" @click="finish()"
                                    class="w-6 h-6 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition text-neutral-400 hover:text-white">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-neutral-400 leading-6 mb-4" x-text="steps[index].text"></p>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-1.5">
                                <template x-for="(s, i) in steps" :key="i">
                    <span class="rounded-full transition-all duration-300"
                          :class="i === index ? 'w-4 h-1.5 bg-sky-400' : (i < index ? 'w-1.5 h-1.5 bg-sky-700' : 'w-1.5 h-1.5 bg-white/15')">
                    </span>
                                </template>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="finish()" class="text-[11px] text-neutral-500 hover:text-neutral-300 transition-colors px-2">رد کردن</button>
                                <button type="button" @click="next()"
                                        class="px-4 py-2 rounded-lg text-xs font-bold bg-sky-500 hover:bg-sky-400 text-white transition-all hover:scale-105 shadow-lg shadow-sky-500/20">
                                    <span x-text="index >= steps.length - 1 ? 'تمام 🎉' : 'بعدی ←'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </template>
    </div>
</div>
