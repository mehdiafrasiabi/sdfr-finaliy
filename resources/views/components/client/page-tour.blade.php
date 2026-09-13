<div>

    @props(['steps' => [], 'storageKey' => 'page_tour_done', 'auto' => false])

    {{--
        نسخه‌ی سبک page-tour:
        اسپات‌لایت (تیره شدن بقیه‌ی صفحه + هایلایت دور المنت هدف) برگشته چون برای فهموندن
        «این بخش داره توضیح داده می‌شه» لازمه، ولی این‌بار بدون backdrop-filter/blur —
        همون چیزی که باعث لگ بین step ها می‌شد. لایه‌ی تیره فقط یک پس‌زمینه‌ی نیمه‌شفاف با
        clip-path هست (بدون هیچ فیلتری روی کل صفحه)، که خیلی ارزون‌تر از blur روی مرورگره.
    --}}
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

        /* موقعیت اسپات‌لایت (هایلایت دور المنت هدف) */
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

            // فقط موقعیت اسپات‌لایت/تولتیپ رو دنبال اسکرول آپدیت می‌کنیم (بدون blur) — سبک و ارزون.
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
            if (first === -1) {
                this.forceFinish();
                return;
            }
            this.index = first;
            this.active = true;
            this.tipVisible = false;
            this.$nextTick(() => {
                this.calcPosition(true);
                setTimeout(() => { this.tipVisible = true; }, 350);
            });
        },

        next() {
            const n = this.firstVisible(this.index + 1, 1);
            if (n === -1) { this.forceFinish(); return; }
            this.goTo(n);
        },

        prev() {
            const p = this.firstVisible(this.index - 1, -1);
            if (p === -1) return;
            this.goTo(p);
        },

        /* جابجایی بین step‌ها: اسپات‌لایت با یک transition ساده‌ی clip-path (بدون blur) سُر می‌خوره،
           تولتیپ هم فقط فید می‌کنه. هیچ انیمیشن چندفازی سنگینی وجود نداره. */
        goTo(newIndex) {
            if (this.transitioning) return;
            this.transitioning = true;
            this.tipVisible = false;

            setTimeout(() => {
                this.index = newIndex;
                this.$nextTick(() => {
                    this.calcPosition(true);
                    setTimeout(() => {
                        this.transitioning = false;
                        this.tipVisible = true;
                    }, 260);
                });
            }, 100);
        },

        /* بستن کامل تور - از طریق دکمه X صدا زده میشه */
        forceFinish() {
            this.active = false;
            this.tipVisible = false;
            localStorage.setItem(this.storageKey, '1');
            window.dispatchEvent(new CustomEvent('sdfr-page-tour-finished', {
                detail: { storageKey: this.storageKey }
            }));
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

                /* اسپات‌لایت */
                this.spotTop    = r.top    - pad;
                this.spotLeft   = r.left   - pad;
                this.spotWidth  = r.width  + pad * 2;
                this.spotHeight = r.height + pad * 2;

                /* تولتیپ */
                const tipW = Math.min(300, window.innerWidth - 32);
                const tipH = 190;
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
                setTimeout(doCalc, 450);
            } else {
                doCalc();
            }
        },
     }">

        {{-- آیکون راهنما — پایین چپ (بالای دکمه‌ی برگشت به بالا در صورت وجود) --}}
        <button type="button" id="sdfr-page-tour-trigger" @click="start()" title="راهنمای صفحه"
                aria-label="راهنمای صفحه"
                class="fixed bottom-[calc(6rem+env(safe-area-inset-bottom))] md:bottom-6 left-3 sm:left-5 z-[70] w-11 h-11 rounded-full bg-secondary border border-border shadow-lg
                   flex items-center justify-center text-muted hover:text-primary hover:border-primary/40 transition-colors">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                 strokeWidth="2" class="w-6 h-6">
                <path
                    d="M11.75 15.1658V14.6915C11.75 13.1553 10.8011 12.342 9.85232 11.6869C8.9261 11.0544 8 10.2411 8 8.75014C8 6.67179 9.67165 5 11.75 5C13.8283 5 15.5 6.67179 15.5 8.75014"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M11.7586 18.5456H11.7382" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                      stroke-linejoin="round"></path>
            </svg>
        </button>

        {{-- ────── تور اصلی: اسپات‌لایت + تولتیپ ────── --}}
        <template x-if="active">
            <div class="fixed inset-0 z-[90]" aria-modal="true" role="dialog">

                {{-- ░░ لایه‌ی تیره با clip-path برای اسپات‌لایت — بدون backdrop-filter/blur ░░
                     کلیک روی این لایه باعث بسته شدن نمی‌شه؛ فقط جلوی تعامل با بقیه‌ی صفحه رو می‌گیره. --}}
                <div class="absolute inset-0 pointer-events-auto"
                     :style="`
                    background: rgba(0,0,0,.62);
                    clip-path: polygon(
                        0% 0%, 100% 0%, 100% 100%, 0% 100%,
                        0% ${spotTop}px,
                        ${spotLeft}px ${spotTop}px,
                        ${spotLeft}px ${spotTop + spotHeight}px,
                        ${spotLeft + spotWidth}px ${spotTop + spotHeight}px,
                        ${spotLeft + spotWidth}px ${spotTop}px,
                        0% ${spotTop}px
                    );
                    transition: clip-path .24s cubic-bezier(.4,0,.2,1);
                 `">
                </div>

                {{-- ░░ حلقه‌ی درخشان دور المان هدف ░░ --}}
                <div class="absolute rounded-xl pointer-events-none"
                     :style="`
                    top:    ${spotTop}px;
                    left:   ${spotLeft}px;
                    width:  ${spotWidth}px;
                    height: ${spotHeight}px;
                    box-shadow:
                        0 0 0 2px rgba(56,189,248,.9),
                        0 0 0 5px rgba(56,189,248,.20),
                        0 0 24px 3px rgba(56,189,248,.28);
                    transition: top .24s cubic-bezier(.4,0,.2,1),
                                left .24s cubic-bezier(.4,0,.2,1),
                                width .24s cubic-bezier(.4,0,.2,1),
                                height .24s cubic-bezier(.4,0,.2,1);
                 `">
                </div>

                {{-- ░░ کارت تولتیپ ░░ --}}
                <div class="absolute pointer-events-auto"
                     :style="`top: ${tipTop}px; right: ${tipRight}px; width: 300px; max-width: calc(100vw - 2rem); will-change: opacity, transform;`"
                     :class="tipVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-1'"
                     style="transition: opacity .18s ease, transform .18s ease;">

                    {{-- دم کوچک اشاره‌گر به سمت المنت هدف --}}
                    <div class="absolute w-3 h-3 rotate-45 bg-white  border border-white/10"
                         :class="tipSide === 'bottom' ? '-top-1.5 right-5' : '-bottom-1.5 right-5'"
                         style="z-index:-1"></div>

                    <div class="rounded-2xl p-4 shadow-2xl border border-white/10 bg-secondary  ">

                        {{-- ─── Header: عنوان + دکمه بستن ─── --}}
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <p class="text-sm font-bold text-white flex-1 min-w-0 truncate"
                               x-text="steps[index].title"></p>

                            {{-- دکمه بستن - فقط در steps غیر-اجباری --}}
                            <template x-if="canClose()">
                                <button type="button" @click="forceFinish()"
                                        class="w-7 h-7 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition text-neutral-400 hover:text-white flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6 6 18M6 6l12 12"/>
                                    </svg>
                                </button>
                            </template>
                        </div>

                        <p class="text-xs text-neutral-400 leading-6 mb-4" x-text="steps[index].text"></p>

                        {{-- ─── Footer: شمارنده صفحه + دکمه‌های قبلی/بعدی ─── --}}
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[11px] text-neutral-500 font-medium"
                                  x-text="`${index + 1} از ${steps.length}`"></span>

                            <div class="flex items-center gap-2">
                                {{-- دکمه قبلی --}}
                                <button type="button" @click="prev()"
                                        :disabled="!hasPrev() || transitioning"
                                        :class="hasPrev() && !transitioning ? 'bg-white/5 hover:bg-white/10 text-neutral-300 border-white/10 cursor-pointer' : 'bg-white/5 text-neutral-600 border-white/5 cursor-not-allowed opacity-40'"
                                        class="px-4 h-9 rounded-lg text-xs font-bold border transition-colors">
                                    قبلی
                                </button>

                                {{-- دکمه بعدی --}}
                                <button type="button" @click="next()"
                                        :disabled="transitioning"
                                        class="px-4 h-9 rounded-lg text-xs font-bold bg-primary hover:bg-white hover:text-black text-white transition-colors disabled:opacity-60 disabled:cursor-wait">
                                    <span x-text="index >= steps.length - 1 ? 'تمام 🎉' : 'بعدی'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </template>
    </div>
</div>
