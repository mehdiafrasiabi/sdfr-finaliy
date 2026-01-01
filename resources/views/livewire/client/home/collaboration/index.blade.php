<div>
    <div class="bg-gradient-to-l from-secondary to-background rounded-2xl p-5 space-y-8 border border-border/60">
        <!-- section:title -->
        <div class="flex items-center justify-between gap-8">
            <div class="flex items-center gap-5">
                <span class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-smile">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </span>
                <span class="font-black xs:text-2xl text-lg text-primary">
                    حضور SDFR ، در هر مدرسه
                </span>
            </div>
        </div>
        <!-- end section:title -->

        {{-- content --}}
        @if(!empty($schoolSdfr) && count($schoolSdfr))
            <div class="relative">
                <div id="unique-slider" class="swiper overflow-hidden select-none">
                    <div class="swiper-wrapper">
                        @foreach($schoolSdfr as $item)
                            <div class="swiper-slide">
                                <div
                                    class="group relative rounded-2xl overflow-hidden
                                           bg-background/60 dark:bg-background/40 backdrop-blur
                                           border border-border/70
                                           shadow-sm shadow-black/5 dark:shadow-black/20
                                           transition-all duration-300
                                           hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary/10">
                                    <div class="p-2">
                                        <div class="aspect-square w-full rounded-xl overflow-hidden bg-muted/40">
                                            <img
                                                src="{{ asset('client/sdfr/schoolSdfr/'.$item->document) }}"
                                                alt="{{ $item->name }}"
                                                class="w-full h-full object-cover
                                                       transition-transform duration-500
                                                       group-hover:scale-[1.03]"
                                                loading="lazy"
                                                style="pointer-events:none;"
                                            >
                                        </div>
                                    </div>

                                    {{-- subtle shine --}}
                                    <div class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        <div class="absolute -inset-x-20 -top-10 h-24 rotate-12 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- soft edge fade (nice on both modes) --}}
                <div class="pointer-events-none absolute inset-y-0 left-0 w-10 bg-gradient-to-r from-background to-transparent"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l from-background to-transparent"></div>
            </div>
        @else
            {{-- اگر دیتا نداری، همینجا می‌تونی skeleton جداگانه رو include کنی --}}
            <div class="text-center text-muted">وجود ندارد</div>
        @endif
    </div>

    @push('link')
        <style>
            /* ultra-smooth infinite movement */
            #unique-slider .swiper-wrapper { transition-timing-function: linear !important; }
        </style>
    @endpush

    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const el = document.querySelector('#unique-slider');
                if (!el) return;

                // جلوگیری از init تکراری در Livewire re-render
                if (el.dataset.swiperInitialized === "1") return;
                el.dataset.swiperInitialized = "1";

                const swiper = new Swiper('#unique-slider', {
                    loop: true,
                    loopAdditionalSlides: 10,
                    centeredSlides: false,

                    // ریسپانسیو: موبایل 1، تبلت 2، دسکتاپ 3~4
                    slidesPerView: 1,
                    spaceBetween: 14,
                    breakpoints: {
                        480: { slidesPerView: 1.2, spaceBetween: 14 },
                        640: { slidesPerView: 2,   spaceBetween: 16 },
                        768: { slidesPerView: 3,   spaceBetween: 18 },
                        1024:{ slidesPerView: 4,   spaceBetween: 20 },
                    },

                    speed: 9000, // هرچی بیشتر، نرم‌تر و آرام‌تر
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },

                    // حس «حرکت پیوسته» واقعی
                    freeMode: { enabled: true, momentum: false },
                    grabCursor: true,
                    allowTouchMove: true,
                });

                // اگر Livewire صفحه رو دوباره رندر کرد، بهتره اینو داشته باشیم
                document.addEventListener('livewire:navigated', () => {
                    try { swiper.update(); } catch (e) {}
                });
            });
        </script>
    @endpush
</div>
