<script src="/client/assets/js/dependencies/alpinejs.min.js"></script>
<script src="/client/assets/js/dependencies/swiper-bundle.min.js"></script>
<script src="/client/assets/js/dependencies/plyr.min.js"></script>
<script src="/client/assets/js/app.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script type="text/javascript">
    ["keydown","touchmove","touchstart","mouseover"].forEach(function(v){window.addEventListener(v,function(){if(!window.isGoftinoAdded){window.isGoftinoAdded=1;var i="hjF21Y",d=document,g=d.createElement("script"),s="https://www.goftino.com/widget/"+i,l=localStorage.getItem("goftino_"+i);g.type="text/javascript",g.async=!0,g.src=l?s+"?o="+l:s;d.getElementsByTagName("head")[0].appendChild(g);}})});
</script>
<script>
    window.addEventListener('success', function(event) {
        Toastify({
            text:event.detail,
            duration: 3000,
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            }
        }).showToast();
    });
    window.addEventListener('warning', function(event) {
        Toastify({
            text:event.detail,
            duration: 3000,
            style: {
                background: "linear-gradient(to right, #d61212, #ff0000)",
            }
        }).showToast();
    });
    window.addEventListener('add-to-cart', function() {
        Toastify({
            text:'با موفقیت به سبد خرید شما اضافه شد ',
            duration: 4000,
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            }
        }).showToast();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loadingOverlay = document.getElementById('loadingOverlay');
        const mainContentContainer = document.getElementById('mainContentContainer');
        const logoImage = document.getElementById('logoImage');
        const comingSoonText = document.getElementById('comingSoonText');

        // Simulate content loading time
        setTimeout(() => {
            // Hide loading overlay
            loadingOverlay.classList.add('hidden');

            // Show main content container
            mainContentContainer.classList.add('visible');

            // Start the "Coming Soon" animation after the loading screen fades out
            setTimeout(() => {
                logoImage.style.opacity = 1;
                logoImage.style.transform = 'translateY(0)';

                setTimeout(() => {
                    comingSoonText.style.opacity = 1;
                    comingSoonText.style.transform = 'scale(1)';
                }, 1500); // Start "Coming Soon" animation after 1.5 seconds
            }, 1000); // Wait for loading overlay to fully fade out (1 second transition)

        }, 3000); // Simulate 3 seconds of loading time
    });
    function checkInternetSpeed() {
        const imageAddr = "/client/loading.png";
        let startTime, endTime;
        const downloadSize = 56119;
        const download = new Image();

        download.onload = function () {
            endTime = new Date().getTime();
            const duration = (endTime - startTime) / 1000;
            const bitsLoaded = downloadSize * 8;
            const speedBps = (bitsLoaded / duration).toFixed(2);
            const speedKbps = (speedBps / 1024).toFixed(2);

            let animationDuration = 3000; // پیش‌فرض 3 ثانیه
            if (speedKbps > 500) animationDuration = 1000; // اینترنت سریع
            else if (speedKbps > 100) animationDuration = 2000; // اینترنت متوسط

            const rocket = document.querySelector('.rocket');
            rocket.style.animationDuration = `${animationDuration}ms`;
            rocket.style.animationIterationCount = 1; // فقط یک بار حرکت
            rocket.addEventListener('animationend', () => {
                rocket.style.display = 'none'; // مخفی کردن موشک بعد از انیمیشن
            });
        };

        startTime = new Date().getTime();
        download.src = imageAddr + "?t=" + startTime;
    }
    window.onload = checkInternetSpeed;
</script>
@stack('script')
