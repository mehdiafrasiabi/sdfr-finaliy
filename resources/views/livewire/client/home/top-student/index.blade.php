<div class="bg-gradient-to-l from-secondary to-background rounded-2xl p-5 space-y-8">
    <!-- section:title -->
    <div class="flex items-center justify-between gap-8">
        <div class="flex items-center gap-5">
            <span class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
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
                گوشه ای از لبخند ستارگان SDFR
            </span>
        </div>
    </div>
    <!-- end section:title -->

    <!-- Swiper -->
    <div id="unique-slider2" class="swiper col3-swiper-slider">
        <div class="swiper-wrapper" id="students-container2">
            @forelse($topStudent as $item)
                <div class="swiper-slide">
                    <div style="box-shadow: 0 7px 16px rgb(30 59 239 / 30%); display: inline-flex;">
                        <div class="relative mb-3 z-20">
                            <div class="block">
                                <img src="{{ asset('client/sdfr/topStudent/'.$item->document) }}"
                                     class="max-w-full rounded-xl bg-cover"
                                     alt="{{ $item->name }}"
                                     style="pointer-events: none;">
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide text-center text-gray-500">
                    وجود ندارد
                </div>
            @endforelse
        </div>
    </div>

    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new Swiper('#unique-slider2', {
                    slidesPerView: 4, // پیش‌فرض دسکتاپ
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 0, // بدون توقف
                        disableOnInteraction: false,
                    },
                    speed: 4000, // سرعت حرکت پیوسته
                    breakpoints: {
                        320: { slidesPerView: 1.2 },
                        640: { slidesPerView: 2 },
                        1024: { slidesPerView: 4 }
                    }
                });
            });
        </script>
    @endpush
</div>
