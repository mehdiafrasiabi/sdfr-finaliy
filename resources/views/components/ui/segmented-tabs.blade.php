@props([
    'items'   => [],          // ['key' => 'برچسب']
    'active'  => null,        // کلید تب فعال اولیه
    'badges'  => [],          // ['key' => تعداد] - اختیاری، بج قرمز شمارنده
    'icons'   => [],          // ['key' => 'd path'] - اختیاری، آیکون heroicon قبل از برچسب
    'method'  => null,        // نام متد لایوایر که با کلیک روی هر تب صدا زده می‌شود، مثلا setCategory
    'rtl'     => true,

    // ═══ ظاهر - همه‌شون کلاس تیلویند خام می‌گیرن، پس هرجور پس‌زمینه/رنگی که صفحه نیاز داره پاس بده ═══
    'containerClass'    => 'bg-background border border-border',              // کادر بیرونی (پیست تب‌های غیرفعال)
    'indicatorClass'    => 'bg-secondary shadow-sm',                          // پیل لغزنده‌ی زیر تب فعال
    'activeTextClass'   => 'text-primary',                                    // رنگ متنِ تب فعال
    'inactiveTextClass' => 'text-foreground/70 hover:text-foreground',        // رنگ متنِ تب‌های غیرفعال
    'itemClass'         => 'px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm font-semibold', // پدینگ/سایز متن هر دکمه
])
{{--
    @props باید همیشه قبل از هر خروجی HTML/attributes باشه، وگرنه $attributes->class(...)
    قبل از اینکه پراپ‌های آرایه‌ای (items/icons/badges) از attributes bag جدا بشن صدا زده
    می‌شه و ComponentAttributeBag سعی می‌کنه اون‌ها رو هم به رشته تبدیل کنه (خطای
    "trim(): ... array given") - همینه که باعث این خطا شده بود.

    wrapper بیرونی به صورت پیش‌فرض توی موبایل وسط‌چین و توی دسکتاپ سمت چپ (md:justify-end
    روی صفحه‌ی راست‌چین) قرار می‌گیره - دقیقا مثل نمونه‌ی صفحه‌ی نوتیفیکیشن. اگر خواستی جای
    دیگه‌ای غیر از این چیدمان لازم داشتی کافیه با پاس دادن class روی خود کامپوننت override کنی.

    رنگ‌بندی (containerClass/indicatorClass/...) کاملاً آزاده تا هر صفحه رنگ خودش رو نگه داره؛
    مثلا صفحه‌ی نمایش برنامه‌ی هفتگی پس‌زمینه‌ی سفید/تیره‌ی خودش رو با همین پراپ‌ها پاس می‌ده.
--}}
<div {{ $attributes->class(['flex justify-center md:justify-end']) }}>
    <div
        dir="{{ $rtl ? 'rtl' : 'ltr' }}"
        x-data="{
            current: @js($active),
            @if($method)
                // برای صفحاتی که تعویض تب واقعاً یک رفت‌وبرگشت سرور لازم داره (مثلاً چون لیست
                // صفحه‌بندی/فیلترشده‌ست و همه‌ی دیتا از قبل لود نشده - مثل دسته‌بندی نوتیفیکیشن‌ها)
                // این دو برای جلوگیری از ریس‌کاندیشن لازمن: اگه یک درخواست در حال رفتن باشه و
                // کاربر سریع روی تب دیگه‌ای بزنه، به‌جای اینکه دو درخواست هم‌زمان بفرستیم (که
                // جواب‌هاشون ممکنه با ترتیب اشتباه برگردن و باعث بشه ایندیکیتور یک تب رو نشون
                // بده ولی محتوای تبِ دیگه‌ای لود شده باشه)، فقط آخرین تبِ خواسته‌شده صف می‌شه و
                // بعد از تمومِ درخواستِ جاری فرستاده می‌شه - یعنی همیشه دقیقاً یک درخواست در
                // حال رفت‌وبرگشته و سرور همیشه با آخرین کلیکِ کاربر هماهنگ می‌مونه.
                pending: false,
                queuedKey: null,
            @endif

            // پیل پس‌زمینه (ایندیکیتور) رو دقیقا زیر دکمه‌ی داده‌شده می‌بره.
            // چون از offsetLeft/offsetWidth (مختصات فیزیکی/چپ‌مبنا) استفاده می‌کنیم،
            // ایندیکیتور هم باید با left:0 لنگر بشه نه right:0 -- در غیر این صورت
            // در حالت rtl از کادر بیرون می‌زنه.
            moveIndicator(el) {
                if (! el || ! this.$refs.indicator) return;
                this.$refs.indicator.style.width     = el.offsetWidth + 'px';
                this.$refs.indicator.style.transform = 'translateX(' + el.offsetLeft + 'px)';
            },
            activeTab() {
                if (! this.$refs.tabs) return null;
                return [...this.$refs.tabs.querySelectorAll('[data-tab-key]')].find(b => b.dataset.tabKey === this.current) || null;
            },
            // رنگ و data-active هر دکمه رو دستی (نه فقط با بایندینگ ری‌اکتیو :class/:data-active) هماهنگ می‌کنه.
            // چرا لازمه: توی صفحاتی که method پاس داده شده (مثل نوتیفیکیشن) بعد از هر $wire.call
            // لایوایر کل زیردرخت این کامپوننت رو مورف می‌کنه، و تست عملی نشون داد که بعضی وقت‌ها
            // بایندینگ‌های ری‌اکتیو Alpine (:data-active و :class) روی دکمه‌ها بعد از این مورف دیگه
            // به تغییرات current گوش نمی‌دن (current خودش درست آپدیت می‌شه ولی DOM قدیمی می‌مونه) -
            // در نتیجه ایندیکیتور/رنگ تب اشتباه نشون داده می‌شه با اینکه محتوا درسته. برای اینکه به
            // این ری‌اکتیویتی متکی نباشیم، بعد از هر کلیک و بعد از هر جواب سرور این متد صراحتاً
            // data-active و کلاس رنگ هر دکمه رو از روی current بازسازی می‌کنه.
            applyActiveStyles() {
                if (! this.$refs.tabs) return;
                const activeClasses   = @js($activeTextClass).split(' ').filter(Boolean);
                const inactiveClasses = @js($inactiveTextClass).split(' ').filter(Boolean);
                this.$refs.tabs.querySelectorAll('[data-tab-key]').forEach(btn => {
                    const isActive = btn.dataset.tabKey === this.current;
                    btn.setAttribute('data-active', isActive ? 'true' : 'false');
                    btn.classList.remove(...activeClasses, ...inactiveClasses);
                    btn.classList.add(...(isActive ? activeClasses : inactiveClasses));
                });
            },
            select(key, event) {
                this.current = key;
                this.moveIndicator(event.currentTarget);
                this.applyActiveStyles();
                @if($method)
                    this.sendToServer(key);
                @endif
                // برای استفاده‌ی خالص با Alpine (بدون لایوایر) هم پخش می‌شه تا والد بتونه گوش بده
                // (نمونه‌ش رو در edit.blade.php ببین: attribute segmented-change روی خود کامپوننت)
                this.$dispatch('segmented-change', key);
            },
            @if($method)
            sendToServer(key) {
                if (this.pending) {
                    this.queuedKey = key;
                    return;
                }
                this.pending = true;
                $wire.call(@js($method), key).then(() => {
                    this.pending = false;
                    this.$nextTick(() => {
                        this.applyActiveStyles();
                        this.moveIndicator(this.activeTab());
                    });
                    if (this.queuedKey !== null) {
                        const next = this.queuedKey;
                        this.queuedKey = null;
                        if (next !== key) this.sendToServer(next);
                    }
                });
            },
            @endif
            init() {
                this.applyActiveStyles();
                this.$nextTick(() => this.moveIndicator(this.activeTab()));
                const resync = () => { this.applyActiveStyles(); this.moveIndicator(this.activeTab()); };
                window.addEventListener('resize', resync);
                if (document.fonts) document.fonts.ready.then(resync);
            }
        }"
    >
        <div x-ref="tabs" class="relative inline-flex items-center gap-1 p-1 rounded-full {{ $containerClass }}">
            <span
                x-ref="indicator"
                class="absolute inset-y-1 left-0 rounded-full pointer-events-none {{ $indicatorClass }}"
                style="width:0px; transform:translateX(0px); transition: transform .38s cubic-bezier(.34,1.56,.64,1), width .38s cubic-bezier(.34,1.56,.64,1);"
            ></span>

            @foreach($items as $key => $label)
                <button
                    type="button"
                    wire:key="segmented-tab-{{ $key }}"
                    data-tab-key="{{ $key }}"
                    data-active="{{ (string) $key === (string) $active ? 'true' : 'false' }}"
                    @click="select(@js((string) $key), $event)"
                    class="relative z-10 inline-flex items-center gap-2 rounded-full transition-colors {{ (string) $key === (string) $active ? $activeTextClass : $inactiveTextClass }} {{ $itemClass }}">
                    @if(isset($icons[$key]))
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$key] }}"/>
                        </svg>
                    @endif
                    {{ $label }}
                    @if(isset($badges[$key]) && $badges[$key] > 0)
                        <span class="inline-flex items-center justify-center min-w-[18px] h-4 px-1 text-[10px] font-bold rounded-full bg-red-500 text-white">
                            {{ $badges[$key] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
