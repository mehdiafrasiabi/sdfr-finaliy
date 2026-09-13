<div>
    @php
        $qCount   = $questions->count();
        $minutes  = max(2, (int) ceil($qCount * 15 / 60)); // برآوردِ زمان ~۱۵ ثانیه برای هر سوال
    @endphp


@assets
    <style>
        [x-cloak] { display: none !important; }

        /* ───────── کارت سوال ───────── */
        .q-card { border: 1px solid hsl(var(--border));
            border-radius: 1.25rem; padding: 1.1rem 1.1rem 1.25rem; transition: border-color .2s; }
        .q-card.q-unanswered { border-color: hsl(var(--primary) / .45); }
        .q-num { display: inline-flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem;
            border-radius: .6rem; font-weight: 800; font-size: .8rem; background: hsl(var(--primary) / .12);
            color: hsl(var(--primary)); border: 1px solid hsl(var(--primary) / .25); flex: none; }

        /* ───────── (C1) مقیاسِ لیکرت دایره‌ای ───────── */
        .likert { display: flex; align-items: center; justify-content: center; gap: .5rem; flex-wrap: nowrap; }
        .likert-pole { font-size: clamp(.62rem, 2.6vw, .72rem); font-weight: 800; white-space: nowrap; }
        .likert-pole--agree { color: hsl(var(--primary)); }
        .likert-pole--disagree { color: #ec4899; }
        .likert-circles { display: flex; align-items: center; gap: clamp(.35rem, 2vw, .6rem); }
        .likert-dot { border-radius: 9999px; border: 2px solid; background: transparent; cursor: pointer; flex: none;
            transition: transform .15s ease, background .15s ease, box-shadow .15s ease; }
        .likert-dot:hover { transform: scale(1.12); }
        .likert-dot--agree { border-color: hsl(var(--primary) / .65); }
        .likert-dot--disagree { border-color: #ec4899; opacity: .85; }
        .likert-dot--neutral { border-color: hsl(var(--border)); }
        .likert-dot--agree.likert-dot--on { background: hsl(var(--primary)); border-color: hsl(var(--primary)); box-shadow: 0 0 0 4px hsl(var(--primary) / .2); }
        .likert-dot--disagree.likert-dot--on { background: #ec4899; border-color: #ec4899; opacity: 1; box-shadow: 0 0 0 4px rgba(236, 72, 153, .2); }
        .likert-dot--neutral.likert-dot--on { background: hsl(var(--muted-foreground, var(--foreground)) / .5); box-shadow: 0 0 0 4px hsl(var(--border) / .6); }

        /* ───────── کارت گزینه (تک/چندانتخابی) ───────── */
        .opt-card { display: flex; align-items: center; gap: .75rem; width: 100%; padding: .8rem 1rem; border-radius: 1rem;
            border: 1px solid hsl(var(--border)); background: hsl(var(--secondary) / .4); cursor: pointer; text-align: right;
            transition: border-color .15s ease, background .15s ease, transform .1s ease; }
        .opt-card:hover { border-color: hsl(var(--primary) / .5); }
        .opt-card:active { transform: scale(.99); }
        .opt-card--on { border-color: hsl(var(--primary)); background: hsl(var(--primary) / .10); }
        .opt-mark { width: 22px; height: 22px; flex: none; display: flex; align-items: center; justify-content: center;
            border: 2px solid hsl(var(--border)); color: hsl(var(--primary-foreground)); transition: .15s; }
        .opt-mark--radio { border-radius: 9999px; }
        .opt-mark--check { border-radius: .45rem; }
        .opt-card--on .opt-mark { background: hsl(var(--primary)); border-color: hsl(var(--primary)); }
    </style>
@endassets

    {{--
        توضیح: قبلاً x-data به یک تابع سراسری assessmentTake() اشاره می‌کرد که
        داخل یک بلوک @script جدا تعریف شده بود؛ اجرای @script تضمین‌شده نیست
        که قبل از ارزیابی x-data روی همین المنت تمام شود (مثل باگِ مشابه در
        pre-session-wizard.blade.php)، پس برای حذفِ کامل این ریسک، همان آبجکت
        مستقیماً داخل x-data اینلاین شده است.
    --}}
    <div
        x-data="{
            picks: Object.assign({}, @js($answers) || {}),
            qids: @js($questionIds) || [],
            other: {{ (int) $otherAnswered }},
            total: {{ (int) $globalTotal }},
            intro: @js($showIntro),
            introTimer: null,
            loading: false,
            showError: false,

            init() {
                if (this.intro) this.openIntro(true);
            },

            // باز کردنِ مودالِ معرفی؛ withTimer=true یعنی اگر کاربر خودش نبندد،
            // بعد از ۵ ثانیه خودکار بسته می‌شود (فقط وقتی اولین‌بار و خودکار باز می‌شود).
            // از دکمهٔ «راهنما» با withTimer=false صدا زده می‌شود تا تایمر نداشته باشد.
            openIntro(withTimer = true) {
                clearTimeout(this.introTimer);
                this.intro = true;
                document.body.style.overflow = 'hidden';
                if (withTimer) {
                    this.introTimer = setTimeout(() => this.closeIntro(), 5000);
                }
            },
            closeIntro() {
                clearTimeout(this.introTimer);
                this.intro = false;
                document.body.style.overflow = '';
            },

            isAnswered(qid) {
                const v = this.picks[qid];
                if (Array.isArray(v)) return v.length > 0;
                return v !== undefined && v !== null && v !== '';
            },
            isSel(qid, val) {
                const v = this.picks[qid];
                if (Array.isArray(v)) return v.includes(String(val));
                return String(v ?? '') === String(val);
            },
            choose(qid, val) {
                this.picks[qid] = String(val);
                this.showError = false;
            },
            toggle(qid, val) {
                val = String(val);
                let arr = Array.isArray(this.picks[qid]) ? [...this.picks[qid]] : [];
                const i = arr.indexOf(val);
                if (i >= 0) arr.splice(i, 1); else arr.push(val);
                this.picks[qid] = arr;
                this.showError = false;
            },
            get answered() {
                return this.qids.filter(q => this.isAnswered(q)).length;
            },
            get remaining() {
                return Math.max(0, this.qids.length - this.answered);
            },
            get done() {
                return this.other + this.answered;
            },
            get percent() {
                return this.total ? Math.min(100, Math.round((this.done / this.total) * 100)) : 0;
            },
            async submit() {
                if (this.remaining > 0) {
                    this.showError = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }
                this.loading = true;
                const plain = JSON.parse(JSON.stringify(this.picks));
                this.$wire.submitAll(plain);
            },
        }"
        x-init="init()"
        dir="rtl"
        class="min-h-screen bg-background text-foreground">

        {{-- ───────── (C4) لودینگِ بارگذاریِ دستهٔ بعدی ───────── --}}
        <div x-show="loading" x-cloak class="fixed inset-0 z-[70] flex flex-col items-center justify-center gap-4 bg-background/85 backdrop-blur-sm">
            <x-ui.spinner size="lg" class="text-primary"/>
            <span class="text-sm font-bold text-muted">در حال بارگذاری دسته‌بندی جدید سوالات…</span>
        </div>

        {{-- ───────── (C3) مودالِ معرفیِ آزمون ─────────
             اگر خودکار (اولین‌بار) باز شود، بعد از ۵ ثانیه اگر کاربر خودش نبندد
             خودش بسته می‌شود (openIntro/closeIntro در x-data بالا). با دکمهٔ
             «راهنما» در هدر هم قابل بازکردن است، منتها بدون تایمر. --}}
        <div x-show="intro" x-cloak
             @keydown.escape.window="closeIntro()"
             class="fixed inset-0 z-[65] flex items-center justify-center p-4">
            <div x-show="intro"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-black/55 backdrop-blur-sm" @click="closeIntro()"></div>
            <div x-show="intro"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-lg rounded-3xl border border-border bg-secondary p-6 shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-primary/10 text-primary border border-primary/20">
                        {{-- استثنا: آیکون «چک‌باکس تیک‌خورده» دقیقاً در دیکشنری موجود نیست؛ شکل اصلی حفظ شده --}}
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </span>
                    <div>
                        <h2 class="font-black text-xl">{{ $assessment->name_fa }}</h2>
                        <p class="text-xs text-muted mt-0.5">قبل از شروع، این توضیحات را بخوان.</p>
                    </div>
                </div>

                @if($assessment->description_fa)
                    <p class="text-sm text-muted leading-7 mb-4">{{ $assessment->description_fa }}</p>
                @else
                    <p class="text-sm text-muted leading-7 mb-4">
                        در این آزمون با چند سوال کوتاه، الگوی یادگیری و شخصیت تحصیلی‌ات سنجیده می‌شود
                        تا مشاور بتواند بهترین برنامه را برایت طراحی کند.
                    </p>
                @endif

                <ul class="space-y-2.5 mb-6">
                    <li class="flex items-start gap-2.5 text-sm">
                        <x-ui.icon name="clock" class="w-4 h-4 text-primary mt-0.5"/>
                        <span>این آزمون <span class="font-bold">{{ $qCount }} سوال</span> دارد و حدود <span class="font-bold">{{ $minutes }} دقیقه</span> زمان می‌برد.</span>
                    </li>
                    <li class="flex items-start gap-2.5 text-sm">
                        <x-ui.icon name="check" class="w-4 h-4 text-primary mt-0.5"/>
                        <span>پاسخ درست یا غلط وجود ندارد؛ صادقانه و بر اساس حال‌وهوای واقعی خودت پاسخ بده.</span>
                    </li>
                    @if($isMulti)
                        <li class="flex items-start gap-2.5 text-sm">
                            {{-- استثنا: بدون آیکون دقیقاً معادل ◉ (بولزی) در دیکشنری؛ نزدیک‌ترین معادل معنایی «list-check» (امکان انتخاب چند مورد) --}}
                            <x-ui.icon name="list-check" class="w-4 h-4 text-primary mt-0.5"/>
                            <span>در این آزمون می‌توانی برای هر سوال <span class="font-bold">چند گزینه</span> انتخاب کنی.</span>
                        </li>
                    @endif
                    <li class="flex items-start gap-2.5 text-sm">
                        <x-ui.icon name="check" class="w-4 h-4 text-primary mt-0.5"/>
                        <span>همهٔ سوالات در یک صفحه نمایش داده می‌شوند؛ بعد از پاسخ به همه، دکمهٔ «ثبت و ادامه» را بزن.</span>
                    </li>
                </ul>

                <button type="button" @click="closeIntro()"
                        data-elevated="true"
                        class="btn-press w-full h-12 rounded-xl bg-primary text-primary-foreground font-bold text-sm hover:opacity-90 transition-opacity inline-flex items-center justify-center gap-2">
                    شروع آزمون
                    <x-ui.icon name="arrow-left" class="w-4 h-4"/>
                </button>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 py-6 pb-36">

            {{-- هدر --}}
            <div class="flex items-center justify-between mb-5">
                <a href="{{ route('client.profile.assessment.list') }}" wire:navigate
                   data-elevated="false"
                   class="btn-press inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-muted hover:text-foreground bg-secondary/50 border border-border transition-colors">
                    خروج
                    <x-ui.icon name="x" class="w-3.5 h-3.5"/>
                </a>
                <div class="flex items-center gap-2">
                    {{-- راهنمای این آزمون: همان مودالِ معرفی را بدون تایمرِ ۵ ثانیه‌ای دوباره باز می‌کند --}}
                    <button type="button" @click="openIntro(false)"
                            data-elevated="false"
                            class="btn-press inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-primary hover:bg-primary/10 border border-primary/20 transition-colors">
                        <x-ui.icon name="info" class="w-3.5 h-3.5"/>
                        راهنما
                    </button>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-xs font-bold text-primary">{{ $assessment->name_fa }}</span>
                    </div>
                </div>
            </div>

            {{-- (C5) پیشرفتِ کلی روی همهٔ آزمون‌ها — هنگام اسکرول به زیر هدر می‌چسبد --}}
            <div class="sticky top-[64px] md:top-[88px] z-40 -mx-4 px-4 mb-6">
                <div class="rounded-2xl p-4 bg-secondary/95 backdrop-blur-xl border border-border shadow-md shadow-black/5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-muted">
                            پاسخ‌داده‌شده: <span class="text-foreground font-bold" x-text="done">{{ $otherAnswered }}</span>
                            از <span class="text-foreground font-bold">{{ $globalTotal }}</span> سوال
                        </span>
                        <span class="text-xs font-black text-primary"><span x-text="percent">0</span>٪</span>
                    </div>
                    <div class="h-2.5 rounded-full overflow-hidden bg-border/60">
                        <div class="h-full rounded-full bg-primary transition-all duration-300" :style="`width: ${percent}%`"></div>
                    </div>
                </div>
            </div>

            {{-- (C6) راهنمای چند/تک انتخابی --}}
            @if($isMulti)
                <div class="flex items-center gap-2 mb-4 px-4 py-2.5 rounded-xl bg-warning/15 border border-warning/30 text-[14px] font-semibold text-warning">
                    {{-- استثنا: آیکون «چک‌باکس تیک‌خورده» دقیقاً در دیکشنری موجود نیست؛ شکل اصلی حفظ شده --}}
                    <svg class="w-4 h-4 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    در این آزمون می‌توانید برای هر سوال چند گزینه انتخاب کنید.
                </div>
            @endif

            {{-- خطای اعتبارسنجی --}}
            <div x-show="showError" x-cloak class="flex items-center gap-2 mb-4 px-4 py-3 rounded-xl bg-error/10 border border-error/30 text-sm text-error">
                <x-ui.icon name="info" class="w-4 h-4 flex-none"/>
                <span>به <span class="font-bold" x-text="remaining"></span> سوال هنوز پاسخ نداده‌ای. لطفاً همه را کامل کن.</span>
            </div>
            @error('answers')
                <div class="flex items-center gap-2 mb-4 px-4 py-3 rounded-xl bg-error/10 border border-error/30 text-sm text-error">
                    <x-ui.icon name="info" class="w-4 h-4 flex-none"/>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            {{-- ───────── (C2) همهٔ سوالاتِ این دسته ───────── --}}
            <div class="space-y-4">
                @foreach($questions as $index => $question)
                    <div class="q-card glass" wire:key="q-{{ $question->id }}"
                         :class="!isAnswered({{ $question->id }}) ? 'q-unanswered' : ''">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="q-num">{{ $index + 1 }}</span>
                            <p class="text-sm md:text-base leading-7 font-semibold flex-1">{{ $question->question_text_fa }}</p>
                        </div>

                        @if($question->type === \App\Models\AssessmentQuestion::TYPE_LIKERT5)
                            {{-- ترتیب برعکس: سمت راست (اولِ RTL) = کاملا موافق با رنگِ آبی، سمت چپ = کاملا مخالف. --}}
                            @php $opts = $question->options->reverse()->values(); $n = $opts->count(); @endphp
                            <div class="likert">
                                <span class="likert-pole likert-pole--agree">{{ $opts->first()->label_fa }}</span>
                                <div class="likert-circles">
                                    @foreach($opts as $i => $opt)
                                        @php
                                            $center  = $n > 1 ? ($n - 1) / 2 : 0;
                                            $distRaw = $center > 0 ? abs($i - $center) / $center : 0;
                                            $size    = 26 + (int) round($distRaw * 22); // 26..48px
                                            $side    = $i < $center ? 'agree' : ($i > $center ? 'disagree' : 'neutral');
                                        @endphp
                                        <button type="button"
                                                @click="choose({{ $question->id }}, '{{ $opt->value }}')"
                                                data-elevated="false"
                                                class="btn-press likert-dot likert-dot--{{ $side }}"
                                                :class="isSel({{ $question->id }}, '{{ $opt->value }}') ? 'likert-dot--on' : ''"
                                                style="width: {{ $size }}px; height: {{ $size }}px;"
                                                title="{{ $opt->label_fa }}" aria-label="{{ $opt->label_fa }}"></button>
                                    @endforeach
                                </div>
                                <span class="likert-pole likert-pole--disagree">{{ $opts->last()->label_fa }}</span>
                            </div>

                        @elseif($question->type === \App\Models\AssessmentQuestion::TYPE_VARK_MULTI)
                            <div class="space-y-2">
                                @foreach($question->options as $opt)
                                    <button type="button"
                                            @click="toggle({{ $question->id }}, '{{ $opt->id }}')"
                                            data-elevated="false"
                                            class="btn-press opt-card"
                                            :class="isSel({{ $question->id }}, '{{ $opt->id }}') ? 'opt-card--on' : ''">
                                        <span class="opt-mark opt-mark--check">
                                            <x-ui.icon name="check" class="w-3 h-3" x-show="isSel({{ $question->id }}, '{{ $opt->id }}')" x-cloak/>
                                        </span>
                                        <span class="text-sm font-medium flex-1">{{ $opt->label_fa }}</span>
                                    </button>
                                @endforeach
                            </div>

                        @else
                            <div class="space-y-2">
                                @foreach($question->options as $opt)
                                    <button type="button"
                                            @click="choose({{ $question->id }}, '{{ $opt->id }}')"
                                            data-elevated="false"
                                            class="btn-press opt-card"
                                            :class="isSel({{ $question->id }}, '{{ $opt->id }}') ? 'opt-card--on' : ''">
                                        <span class="opt-mark opt-mark--radio">
                                            <span class="w-2 h-2 rounded-full bg-primary-foreground" x-show="isSel({{ $question->id }}, '{{ $opt->id }}')" x-cloak></span>
                                        </span>
                                        <span class="text-sm font-medium flex-1">{{ $opt->label_fa }}</span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- نوار پایین: ثبت و ادامه --}}
        <div class="fixed bottom-0 inset-x-0 z-50 bg-background/95 backdrop-blur-xl border-t border-border">
            <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
                <span class="text-xs text-muted">
                    <span x-show="remaining > 0">باقی‌مانده: <span class="font-bold text-foreground" x-text="remaining"></span> سوال</span>
                    <span x-show="remaining === 0" x-cloak class="text-primary font-bold">همهٔ سوالات پاسخ داده شد ✓</span>
                </span>
                <button type="button" @click="submit()" :disabled="loading"
                        data-elevated="true"
                        class="btn-press inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-primary-foreground bg-primary transition-all hover:opacity-90 active:scale-[0.98] disabled:opacity-60"
                        :class="remaining === 0 ? '' : 'opacity-70'">
                    <span x-show="!loading" class="flex items-center gap-2">
                        ثبت و ادامه
                        <x-ui.icon name="arrow-left" class="w-4 h-4"/>
                    </span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <x-ui.spinner size="sm" class="text-primary-foreground"/>
                        در حال ذخیره…
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>
