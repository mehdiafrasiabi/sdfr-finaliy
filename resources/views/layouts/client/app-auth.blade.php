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
    <link rel="stylesheet" type="text/css" href="/client/assets/css/toast/toastify.min.css">
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
    {{-- استایل‌های سفارشی Toast --}}
    <style>
        /* Base Toast Styles */
        .toastify {
            font-family: inherit !important;
            border-radius: 12px !important;
            padding: 16px 24px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
            direction: rtl !important;
            text-align: right !important;
            backdrop-filter: blur(10px);
            max-width: 400px;
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .toastify {
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5) !important;
            }
        }

        /* Toast Success */
        .toast-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            animation: slideInRight 0.3s ease-out;
        }

        /* Toast Error */
        .toast-error {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            animation: slideInRight 0.3s ease-out;
        }

        /* Toast Warning */
        .toast-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            animation: slideInRight 0.3s ease-out;
        }

        /* Toast Info */
        .toast-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            animation: slideInRight 0.3s ease-out;
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Hover Effect */
        .toastify:hover {
            opacity: 1 !important;
            cursor: pointer;
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }

        /* Close Button */
        .toastify-close {
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .toastify-close:hover {
            opacity: 1;
        }

        /* RTL Icon Alignment */
        .toastify svg {
            vertical-align: middle;
            margin-left: 8px;
            display: inline-block;
        }

        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .toastify {
                margin: 10px !important;
                max-width: calc(100% - 20px) !important;
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
<script type="text/javascript" src="/client/assets/css/toast/toastify-js.js"></script>
<script src="/client/assets/tailwind-3.4.17.js"></script>
<script src="/client/assets/js/chart/chart.js"></script>



<script>
    /**
     * Toast System - سیستم مرکزی نمایش پیام‌های Toast
     * سازگار با dispatch های قدیمی و جدید
     */

// تنظیمات پیش‌فرض Toast
    const TOAST_CONFIG = {
        duration: 3000,
        gravity: "top",
        position: "center",
        stopOnFocus: true,
        offset: {
            x: 0,
            y: 20
        },
        style: {
            borderRadius: "12px",
            padding: "16px 24px",
            fontSize: "14px",
            fontWeight: "500",
            boxShadow: "0 10px 40px rgba(0, 0, 0, 0.2)",
            direction: "rtl",
            textAlign: "right"
        }
    };

    // تم‌های مختلف Toast
    const TOAST_THEMES = {
        success: {
            background: "linear-gradient(135deg, #10b981, #059669)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>`
        },
        error: {
            background: "linear-gradient(135deg, #ef4444, #dc2626)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>`
        },
        warning: {
            background: "linear-gradient(135deg, #f59e0b, #d97706)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>`
        },
        info: {
            background: "linear-gradient(135deg, #3b82f6, #2563eb)",
            color: "#ffffff",
            icon: `<svg style="width: 20px; height: 20px; margin-left: 8px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>`
        }
    };

    /**
     * نمایش Toast با تنظیمات سفارشی
     */
    function showToast(message, type = 'success', duration = 3000) {
        const theme = TOAST_THEMES[type] || TOAST_THEMES.success;

        const config = {
            ...TOAST_CONFIG,
            text: theme.icon + message,
            duration: duration,
            className: `toast-${type}`,
            style: {
                ...TOAST_CONFIG.style,
                background: theme.background,
                color: theme.color
            },
            escapeMarkup: false
        };

        Toastify(config).showToast();
    }

    /**
     * Event Listeners برای سازگاری با dispatch های قدیمی
     */

    // Success Toast - سبز
    window.addEventListener('success', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'success', 3000);
    });

    // Warning/Error Toast - قرمز
    window.addEventListener('warning', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'error', 3000);
    });

    // Error Toast (برای dispatch('error', 'message'))
    window.addEventListener('error', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'error', 3000);
    });

    // Info Toast
    window.addEventListener('info', function (event) {
        const message = typeof event.detail === 'string' ? event.detail : event.detail[0];
        showToast(message, 'info', 3000);
    });

    // Add to Cart Toast
    window.addEventListener('add-to-cart', function (event) {
        const message = event.detail
            ? (typeof event.detail === 'string' ? event.detail : event.detail[0])
            : 'با موفقیت به سبد خرید شما اضافه شد';
        showToast(message, 'success', 4000);
    });

    /**
     * Event Listener برای Toast های جدید با فرمت Object
     * مثال: dispatch('show-toast', { type: 'success', message: 'پیام' })
     */
    window.addEventListener('show-toast', function (event) {
        const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        showToast(data.message, data.type || 'success', data.duration || 3000);
    });

    /**
     * Livewire Hook برای نمایش Toast بعد از بارگذاری صفحه
     */
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.on('toast', (data) => {
                const params = Array.isArray(data) ? data[0] : data;
                showToast(params.message, params.type || 'success', params.duration || 3000);
            });
        }
    });

    /**
     * تابع سراسری برای استفاده مستقیم در JavaScript
     * مثال: window.toast('پیام موفقیت', 'success')
     */
    window.toast = showToast;
</script>
</body>
@stack('script')
</html>
