<div>

    @props(['steps' => [], 'storageKey' => 'page_tour_done', 'auto' => false])

    <div dir="rtl"
         x-data="{
        steps: {{ \Illuminate\Support\Js::from($steps) }},
        storageKey: @js($storageKey),
        auto: @js((bool) $auto),
        active: false,
        transitioning: false,
        tipVisible: false,
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

        /* آیا این step اجباری هست؟ (بدون دکمه بستن) */
        isForced(idx) {
            return !!(this.steps[idx] && this.steps[idx].forced);
        },

        /* دکمه بستن فقط در steps غیر-اجباری نشون داده میشه */
        canClose() {
            return !this.isForced(this.index);
        },

init() {
            if (this._booted) return;
            this._booted = true;

            window.addEventListener('resize', () => { if (this.active) this.calcPosition(false); });

            // ✨ اضافه شدن Listener اسکرول با requestAnimationFrame برای پرفورمنس بالا
            window.addEventListener('scroll', () => {
                if (this.active && !this.transitioning) {
                    window.requestAnimationFrame(() => this.calcPosition(false));
                }
            }, { passive: true });

            // حالت auto: در موبایل و دسکتاپ حتماً خودکار شروع می‌شود (مثلاً بعد از ساخت برنامه).
            if (this.auto) {
                setTimeout(() => this.start(), 700);
            } else if (!localStorage.getItem(this.storageKey) && window.innerWidth < 768) {
                // پیش‌فرض: فقط در موبایل auto-start؛ در دسکتاپ کاربر باید دکمه راهنما رو بزنه.
                setTimeout(() => this.start(), 800);
            }
        },

        /* بررسی واقعی نمایش بودن المنت (md:hidden رو هم تشخیص میده) */
        isElVisible(el) {
            if (!el) return false;
            const r = el.getBoundingClientRect();
            if (r.width === 0 && r.height === 0) return false;
            const style = window.getComputedStyle(el);
            if (style.display === 'none' || style.visibility === 'hidden' || parseFloat(style.opacity) === 0) return false;
            if (!el.offsetParent && style.position !== 'fixed') return false;
            return true;
        },

        /* پیدا کردن اولین step قابل نمایش (direction: 1 جلو، -1 عقب) */
        firstVisible(from, direction) {
            direction = direction || 1;
            if (direction > 0) {
                for (let i = from; i < this.steps.length; i++) {
                    if (this.isElVisible(document.querySelector(this.steps[i].el))) return i;
                }
            } else {
                for (let i = from; i >= 0; i--) {
                    if (this.isElVisible(document.querySelector(this.steps[i].el))) return i;
                }
            }
            return -1;
        },

        hasPrev() {
            return this.firstVisible(this.index - 1, -1) !== -1;
        },

        start() {
            const first = this.firstVisible(0, 1);
            if (first === -1) return;
            this.index = first;
            this.active = true;
            this.tipVisible = false;
            this.$nextTick(() => {
                this.calcPosition(true);
                setTimeout(() => { this.tipVisible = true; }, 500);
            });
        },

        next() {
            const n = this.firstVisible(this.index + 1, 1);
            if (n === -1) { this.forceFinish(); return; }
            this.transitionTo(n);
        },

        prev() {
            const p = this.firstVisible(this.index - 1, -1);
            if (p === -1) return;
            this.transitionTo(p);
        },

        /* انیمیشن جمع شدن spotlight روی نقطه، رفتن به بعدی، باز شدن دوباره */
        transitionTo(newIndex) {
            this.transitioning = true;
            this.tipVisible = false;

            // فاز ۱: spotlight به نقطه مرکزی جمع میشه
            const cx = this.spotLeft + this.spotWidth / 2;
            const cy = this.spotTop + this.spotHeight / 2;
            this.spotTop = cy;
            this.spotLeft = cx;
            this.spotWidth = 0;
            this.spotHeight = 0;

            // فاز ۲: بعد از جمع شدن، index تغییر و spotlight روی المنت جدید باز میشه
            setTimeout(() => {
                this.index = newIndex;
                this.$nextTick(() => {
                    this.calcPosition(true);
                    // فاز ۳: نمایش تولتیپ بعد از باز شدن spotlight
                    setTimeout(() => {
                        this.transitioning = false;
                        this.tipVisible = true;
                    }, 280);
                });
            }, 190);
        },

        /* بستن اجباری - فقط در steps غیر-اجباری از طریق دکمه X صدا زده میشه */
        forceFinish() {
            this.active = false;
            this.tipVisible = false;
            localStorage.setItem(this.storageKey, '1');
        },

      calcPosition(scroll) {
            const el = document.querySelector(this.steps[this.index].el);
            if (!el || !this.isElVisible(el)) { this.next(); return; }

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
                const tipH = 200;
                const spaceBelow = window.innerHeight - r.bottom - pad;
                const spaceAbove = r.top - pad;

                if (spaceBelow >= tipH + 16 || spaceBelow >= spaceAbove) {
                    this.tipSide = 'bottom';
                    this.tipTop  = r.bottom + pad + 12;
                } else {
                    this.tipSide = 'top';
                    this.tipTop  = r.top - pad - tipH - 12;
                }

                const rightAligned = window.innerWidth - r.right;
                this.tipRight = Math.max(16, Math.min(rightAligned, window.innerWidth - tipW - 16));
            };

            if (scroll) {
                // ✨ افزایش تایم‌اوت از 380 به 600 برای جبران کندی اسکرول گوشی‌ها
                // بعلاوه با وجود scroll event (که بالاتر اضافه کردیم) کادر در حین حرکت هم آپدیت می‌شود
                setTimeout(doCalc, 600);
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
            <div class="fixed inset-0 z-[90]" aria-modal="true" role="dialog">

                {{-- ░░ لایه‌ی blur/dark با clip-path برای spotlight ░░
                     ❌ کلیک روی این لایه باعث بسته شدن نمیشه (per request) --}}
                <div class="absolute inset-0 pointer-events-auto"
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
                    transition: clip-path .26s cubic-bezier(.4,0,.2,1);
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
                    transition: top .26s cubic-bezier(.4,0,.2,1),
                                left .26s cubic-bezier(.4,0,.2,1),
                                width .26s cubic-bezier(.4,0,.2,1),
                                height .26s cubic-bezier(.4,0,.2,1);
                 `">
                </div>

                {{-- ░░ تولتیپ ░░ --}}
                <div class="absolute pointer-events-auto"
                     :style="`top: ${tipTop}px; right: ${tipRight}px; width: 300px; max-width: calc(100vw - 2rem);`"
                     :class="tipVisible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-2 scale-95'"
                     style="transition: opacity .3s ease, transform .3s cubic-bezier(.2,.8,.2,1);">

                    <div class="absolute w-3 h-3 rotate-45 bg-[#131825] border border-white/10"
                         :class="tipSide === 'bottom' ? '-top-1.5 right-5' : '-bottom-1.5 right-5'"
                         style="z-index:-1"></div>

                    <div class="rounded-2xl p-4 shadow-2xl border border-white/10 backdrop-blur-xl"
                         style="background: rgba(13,18,30,.92);">

                        {{-- ─── Header: عنوان + دکمه بستن (یا badge اجباری) ─── --}}
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <p class="text-sm font-bold text-white flex-1 min-w-0 truncate" x-text="steps[index].title"></p>

                            {{-- دکمه بستن - فقط در steps غیر-اجباری --}}
                            <template x-if="canClose()">
                                <button type="button" @click="forceFinish()"
                                        class="w-6 h-6 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition text-neutral-400 hover:text-white flex-shrink-0">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6 6 18M6 6l12 12"/>
                                    </svg>
                                </button>
                            </template>

                        </div>

                        <p class="text-xs text-neutral-400 leading-6 mb-4" x-text="steps[index].text"></p>

                        {{-- ─── Footer: نقاط پیشرفت + دکمه‌های قبلی/بعدی ─── --}}
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-1.5">
                            </div>

                            <div class="flex items-center gap-1.5">
                                {{-- دکمه قبلی --}}
                                <button type="button" @click="prev()"
                                        :disabled="!hasPrev() || transitioning"
                                        :class="hasPrev() && !transitioning ? 'bg-white/5 hover:bg-white/10 text-neutral-300 cursor-pointer' : 'bg-white/5 text-neutral-600 cursor-not-allowed opacity-40'"
                                        class="w-9 h-9 rounded-lg flex items-center justify-center transition-all">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>
                                </button>

                                {{-- دکمه بعدی --}}
                                <button type="button" @click="next()"
                                        :disabled="transitioning"
                                        class="px-4 h-9 rounded-lg text-xs font-bold bg-sky-500 hover:bg-sky-400 text-white transition-all hover:scale-105 shadow-lg shadow-sky-500/20 flex items-center gap-1.5 disabled:opacity-60 disabled:cursor-wait">
                                    <span x-text="index >= steps.length - 1 ? 'تمام 🎉' : 'بعدی'"></span>
                                    <template x-if="index < steps.length - 1">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m15 18-6-6 6-6"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </template>
    </div>
</div>
