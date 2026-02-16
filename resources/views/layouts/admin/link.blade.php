<meta charset="utf-8"/>

<!-- begin::NexLink Mobile Specific -->
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<!-- end::NexLink Mobile Specific -->
<!-- begin::NexLink Favicon Tags -->
<link href="/admin/assets/images/favicon.png" rel="icon" type="image/png"/>

<link href="/admin/assets/images/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180"/>
<!-- end::NexLink Favicon Tags -->

<!-- begin::NexLink Google Fonts -->
<link href="/admin/assets/css/css2.css" rel="stylesheet"/>
<!-- end::NexLink Google Fonts -->
<!-- begin::NexLink Required Stylesheet -->
<link href="/admin/assets/libs/flaticon/css/all/all.css" rel="stylesheet"/>
<link href="/admin/assets/libs/lucide/lucide.css" rel="stylesheet"/>
<link href="/admin/assets/libs/fontawesome/css/all.min.css" rel="stylesheet"/>
<link href="/admin/assets/libs/simplebar/simplebar.css" rel="stylesheet"/>
<link href="/admin/assets/libs/node-waves/waves.css" rel="stylesheet"/>
<link href="/admin/assets/libs/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/admin/assets/js/persian-datepicker.min.js"/>
<link rel="stylesheet" href="/admin/assets/css/persian-datepicker.min.css">
<!-- end::NexLink Required Stylesheet -->
<!-- begin::NexLink CSS Stylesheet -->
<link href="/admin/assets/libs/datatables/datatables.min.css" rel="stylesheet"/>
<link href="/admin/assets/css/styles.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="/admin/assets/css/toast/toastify.min.css">
<!-- end::NexLink CSS Stylesheet -->
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
<!-- begin::NexLink Googletagmanager -->
<script async=""  src="/admin/assets/js/js.js">
</script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'G-XWVQM68HHQ', {
        'cookie_flags': 'SameSite=None;Secure',
        'send_page_view': true
    });
</script>
<!-- end::NexLink Googletagmanager -->
@stack('link')





