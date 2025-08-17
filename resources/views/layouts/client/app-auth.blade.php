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
    @stack('link')
    <title>ورود و ثبت نام</title>
    <style>
        /* پس‌زمینه کلی */
        .bg-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0a, #1a1a1a, #000000);
            overflow: hidden;
        }

        /* دایره‌های نورانی */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.6;
        }

        .bg-circle-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #00d4ff, #0099cc);
            top: -120px;
            left: -120px;
            animation: float 6s ease-in-out infinite;
        }

        .bg-circle-2 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #ff6b35, #ff8c42);
            bottom: -100px;
            right: -100px;
            animation: float 8s ease-in-out infinite reverse;
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
<div class="bg-wrapper">
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    {{$slot}}
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
