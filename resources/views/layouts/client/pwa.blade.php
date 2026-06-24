
{{--Meta--}}
<!-- Android  -->
<meta name="theme-color" content="#2A69CF">
<meta name="mobile-web-app-capable" content="yes">

<!-- iOS -->
<meta name="apple-mobile-web-app-title" content="SDFR">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<!-- Windows  -->
<meta name="msapplication-navbutton-color" content="#2A69CF">
<meta name="msapplication-TileColor" content="#2A69CF">
<meta name="msapplication-TileImage" content="/client/assets/logoPwa/logo144.png">

<!-- Pinned Sites  -->
<meta name="application-name" content="SDFR">
<meta name="msapplication-tooltip" content="وبسایت اموزشی و تحصیلی SDFR">
<meta name="msapplication-starturl" content="/">

<!-- UC Mobile Browser  -->
<meta name="full-screen" content="yes">
<meta name="browsermode" content="application">

<!-- Disable night mode for this page  -->
<meta name="nightmode" content="enable">


{{--links--}}


<!-- Main Link Tags  -->
<link href="/client/assets/logoPwa/logo16.png" rel="icon" type="image/png" sizes="16x16">
<link href="/client/assets/logoPwa/logo32.png" rel="icon" type="image/png" sizes="32x32">
<link href="/client/assets/logoPwa/logo48.png" rel="icon" type="image/png" sizes="48x48">

<!-- iOS  -->
<link href="/client/assets/logoPwa/logo.png" rel="apple-touch-icon">
<link href="/client/assets/logoPwa/logo76.png" rel="apple-touch-icon" sizes="76x76">
<link href="/client/assets/logoPwa/logo120.png" rel="apple-touch-icon" sizes="120x120">
<link href="/client/assets/logoPwa/logo152.png" rel="apple-touch-icon" sizes="152x152">


<!-- Android  -->
<link href="/client/assets/logoPwa/logo192.png" rel="icon" sizes="192x192">
<link href="/client/assets/logoPwa/logo128.png" rel="icon" sizes="128x128">

<!-- UC Browser  -->
<link href="/client/assets/logoPwa/logo57.png" rel="apple-touch-icon-precomposed" sizes="57x57">
<link href="/client/assets/logoPwa/logo72.png" rel="apple-touch-icon" sizes="72x72">

<!-- Manifest.json  -->
<link href="/manifest.json" rel="manifest">

{{-- توی pwa.blade.php اضافه کن --}}
<script>
    if ('serviceWorker' in navigator) {
        // آیا از قبل یک SW کنترل‌کننده داریم؟ (برای جلوگیری از رفرشِ بیخودِ بازدید اول)
        var hadController = !!navigator.serviceWorker.controller;

        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js', { updateViaCache: 'none' })
                .then(reg => {
                    // هر بار بارگذاری، وجودِ نسخه‌ی جدیدِ SW را چک کن
                    reg.update();
                    // هر ۶۰ دقیقه هم چک کن (برای تب‌هایی که باز می‌مانند)
                    setInterval(() => reg.update(), 60 * 60 * 1000);
                })
                .catch(err => console.log('SW failed:', err));
        });

        // وقتی SW جدید فعال و کنترلر شد، یک‌بار صفحه را تازه کن تا کدِ جدید بیاید
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (!hadController) return;            // نصبِ اولیه: رفرش لازم نیست
            if (window.__swReloaded) return;
            window.__swReloaded = true;
            window.location.reload();
        });
    }
</script>
