<!DOCTYPE html>
<html lang="fa" dir="rtl"  class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="/client/assets/images/favicon.svg" />
    <link rel="stylesheet" href="/client/assets/css/dependencies/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/client/assets/css/dependencies/plyr.min.css" />
    <link rel="stylesheet" href="/client/assets/css/fonts.css" />
    <link rel="stylesheet" href="/client/assets/css/app.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('link')
    <title>ورود و ثبت نام</title>
    <style>
        /* پس‌زمینه کلی */
        .bg-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 20%, rgba(92, 225, 230, 0.15), transparent 45%),
            radial-gradient(circle at 80% 80%, rgba(255, 160, 122, 0.14), transparent 45%),
            linear-gradient(135deg, #0b0f1a, #0e1726 45%, #090f1a);
            overflow: hidden;
        }

        /* لایه محتوای فرم روی پس‌زمینه قرار می‌گیرد */
        .bg-content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
        }

        /* دایره‌های نورانی */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(110px);
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
        }

        .bg-circle-1 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, #6ce5e8, #1a9bd7);
            top: -140px;
            left: -140px;
            animation: float 6s ease-in-out infinite;
        }

        .bg-circle-2 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, #ff8f70, #ff5f6d);
            bottom: -120px;
            right: -120px;
            animation: float 8s ease-in-out infinite reverse;
        }

        /* تنظیمات ریسپانسیو برای لپ‌تاپ و تبلت */
        @media (max-width: 1200px) {
            .bg-circle-1 {
                width: 360px;
                height: 360px;
                top: -110px;
                left: -130px;
            }

            .bg-circle-2 {
                width: 340px;
                height: 340px;
                bottom: -110px;
                right: -110px;
            }
        }

        @media (max-width: 992px) {
            .bg-circle {
                filter: blur(100px);
                opacity: 0.5;
            }

            .bg-circle-1 {
                width: 320px;
                height: 320px;
                top: -90px;
                left: -110px;
            }

            .bg-circle-2 {
                width: 300px;
                height: 300px;
                bottom: -100px;
                right: -90px;
            }
        }

        /* برای موبایل: رنگ‌ها پشت فرم و زیر آن قرار می‌گیرند */
        @media (max-width: 640px) {
            .bg-wrapper {
                padding: 24px 16px 64px;
            }

            .bg-circle {
                filter: blur(90px);
                opacity: 0.45;
            }

            .bg-circle-1 {
                width: 240px;
                height: 240px;
                top: auto;
                left: 50%;
                bottom: 60%;
                transform: translateX(-50%);
            }

            .bg-circle-2 {
                width: 240px;
                height: 240px;
                bottom: -60px;
                right: auto;
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-25px) rotate(180deg);
            }
        }

    </style>
</head>

<body class="dark">
<div class="">
    <div class="bg-content">
        {{$slot}}
    </div>
</div>


<script src="/client/assets/js/dependencies/alpinejs.min.js"></script>
<script src="/client/assets/js/dependencies/swiper-bundle.min.js"></script>
<script src="/client/assets/js/dependencies/plyr.min.js"></script>
<script src="/client/assets/js/app.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
    window.addEventListener('error', function(event) {
        Toastify({
            text:event.detail,
            duration: 3000,
            style: {
                background: "linear-gradient(to right, #d61212, #ff0000)",
            }
        }).showToast();
    });

</script>
</body>
@stack('script')
</html>
