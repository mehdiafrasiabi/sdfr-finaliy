<div>
    @assets
    <style>
        .sdfr-help-pagination {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
            gap: 6px;
        }

        .sdfr-help-pagination .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            background-color: #94a3b8;
            opacity: 0.4;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sdfr-help-pagination .swiper-pagination-bullet-active {
            width: 32px;
            opacity: 1;
            background-color: var(--primary, #2563eb);
        }

        .sdfr-help-swiper {
            width: 100%;
            overflow: hidden;
        }

        .sdfr-help-swiper .swiper-wrapper {
            display: flex;
        }

        .sdfr-help-swiper .swiper-slide {
            flex-shrink: 0;
            width: 100% !important;
            box-sizing: border-box;
        }

        @media (min-width: 768px) {
            .sdfr-help-swiper .swiper-slide {
                width: calc(50% - 10px) !important;
            }
        }

        @media (min-width: 1024px) {
            .sdfr-help-swiper .swiper-slide {
                width: calc(33.3333% - 16px) !important;
            }
        }

        .sdfr-help-swiper img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 24px;
        }
    </style>
    @endassets

    <div class="space-y-8">
        <div class="flex items-center justify-between gap-8 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
            <div class="flex items-center gap-5">
                <span class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z" clip-rule="evenodd"/>
                    </svg>
                </span>
                <div>
                    <span class="font-black xs:text-2xl text-lg text-primary">SDFR چه کمکی به شما میکند؟</span>
                </div>
            </div>
        </div>

        <div class="relative px-1 w-full min-w-0">
            <div class="swiper sdfr-help-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/client/1.webp" alt="پیشرفت تحصیلی"/>
                    </div>
                    <div class="swiper-slide">
                        <img src="/client/2.webp" alt="موفقیت در آزمون کنکور"/>
                    </div>
                    <div class="swiper-slide">
                        <img src="/client/3.webp" alt="موفقیت در امتحان نهایی"/>
                    </div>
                    <div class="swiper-slide">
                        <img src="/client/4.webp" alt="موفقیت در آزمون تیزهوشان"/>
                    </div>
                </div>
            </div>

            <div class="sdfr-help-pagination"></div>
        </div>
    </div>

    @script
    <script>
        (function () {
            function initSlider() {
                if (typeof Swiper === 'undefined') {
                    setTimeout(initSlider, 300);
                    return;
                }

                const el = document.querySelector('.sdfr-help-swiper');
                if (!el) return;

                if (el.swiper) {
                    el.swiper.destroy(true, true);
                }

                new Swiper(el, {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    loop: true,
                    speed: 600,
                    grabCursor: true,
                    observer: true,
                    observeParents: true,
                    watchOverflow: true,
                    pagination: {
                        el: '.sdfr-help-pagination',
                        clickable: true,
                        renderBullet: function (index, className) {
                            return '<span class="' + className + '"></span>';
                        },
                    },
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSlider);
            } else {
                initSlider();
            }
        })();
    </script>
    @endscript
</div>
