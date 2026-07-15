(function () {
    window.SDFRApp = window.SDFRApp || {};

    const initSwiper = (selector, options) => {
        if (typeof Swiper === "undefined") {
            return;
        }

        document.querySelectorAll(selector).forEach((element) => {
            if (element.dataset.sdfrSwiperReady === "1") {
                return;
            }

            element.dataset.sdfrSwiperReady = "1";
            new Swiper(element, options);
        });
    };

    const initPlayers = () => {
        if (typeof Plyr === "undefined") {
            return;
        }

        document.querySelectorAll(".js-player").forEach((player) => {
            if (player.dataset.sdfrPlyrReady === "1") {
                return;
            }

            player.dataset.sdfrPlyrReady = "1";
            new Plyr(player, {});
        });
    };

    const initScrollToTop = () => {
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");

        if (!scrollToTopBtn || scrollToTopBtn.dataset.sdfrScrollReady === "1") {
            return;
        }

        scrollToTopBtn.dataset.sdfrScrollReady = "1";
        scrollToTopBtn.addEventListener("click", function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        });
    };

    window.SDFRApp.init = function () {
        initSwiper(".single-swiper-slider", {
            spaceBetween: 20,
            slidesPerView: 1,
            loop: true,
            effect: "creative",
            creativeEffect: {
                prev: {
                    shadow: true,
                    translate: [0, 0, -400],
                },
                next: {
                    translate: ["100%", 0, 0],
                },
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
            },
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
        });

        initSwiper(".col3-swiper-slider", {
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                992: {
                    slidesPerView: 3,
                },
                576: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });

        initSwiper(".col4-swiper-slider", {
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                992: {
                    slidesPerView: 4,
                },
                768: {
                    slidesPerView: 3,
                },
                480: {
                    slidesPerView: 2,
                },
                0: {
                    slidesPerView: 1,
                },
            },
        });

        initSwiper(".auto-swiper-slider", {
            slidesPerView: "auto",
            spaceBetween: 30,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        initSwiper(".card-swiper-slider", {
            effect: "cards",
            grabCursor: true,
            autoplay: {
                delay: 3000,
            },
            cardsEffect: {
                rotate: 50,
                slideShadows: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        initPlayers();
        initScrollToTop();
    };

    window.SDFRApp.init();
    if (!window.SDFRApp.livewireNavigatedListenerAttached) {
        window.SDFRApp.livewireNavigatedListenerAttached = true;
        document.addEventListener("livewire:navigated", window.SDFRApp.init);
    }
})();
