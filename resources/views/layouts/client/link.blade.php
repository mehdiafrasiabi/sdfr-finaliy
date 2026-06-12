<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="/client/assets/css/dependencies/swiper-bundle.min.css"/>
<link rel="stylesheet" href="/client/assets/css/dependencies/plyr.min.css"/>
<link rel="stylesheet" href="/client/assets/css/fonts.css"/>
<link rel="stylesheet" href="/client/assets/css/app.css"/>
<link rel="stylesheet" type="text/css" href="/client/assets/css/toast/toastify.min.css">
<link href="/client/assets/css/custom-pagination.css" rel="stylesheet" type="text/css"/>
<link href="/client/assets/css/custom-pagination2.css" rel="stylesheet" type="text/css"/>
<link href="/client/assets/js/story-player/styles.css" rel="stylesheet" type="text/css"/>

<script src="/client/assets/tailwind-3.4.17.js"></script>
<script src="/client/assets/js/chart/chart.js"></script>

<style>
    #loadingOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000; /* Black background for loading */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Ensure it's on top */
        opacity: 1;
        transition: opacity 1s ease-out; /* Smooth fade out */
    }

    #loadingOverlay.hidden {
        opacity: 0;
        pointer-events: none; /* Disable interactions when hidden */
    }

    .loading-logo {
        max-width: 200px; /* Size for loading logo */
        height: auto;
        border-radius: 12px;
        animation: pulse 2s infinite ease-in-out; /* Pulsing animation for logo */
    }

    .bg-gray-600 {
        background-color: #718096;
    }

</style>
<style>
    .glass {
        position: relative;
        /*border-radius: 1rem;*/
        z-index: 1;
        background: linear-gradient(209deg, rgb(146 146 146 / 16%), rgb(0 0 0));
        -webkit-backdrop-filter: blur(16px) saturate(140%);
        backdrop-filter: blur(16px) saturate(140%);
        /*border: 1px solid rgba(255,255,255,.09);*/
        /*box-shadow: 0 10px 34px rgba(0,0,0,.40), inset 0 1px 0 rgba(255,255,255,.07);*/
        transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s, border-color .35s;
    }
    /*.glass:hover {*/
    /*    transform: translateY(-4px);*/
    /*    box-shadow: 0 18px 46px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.10);*/
    /*}*/
</style>
<style>
    [x-cloak] { display: none !important; }

    /* اطمینان از نمایش درست */
    #pwaBanner.hidden,
    #pwaLoading.hidden {
        display: none !important;
        background-color: #2866c8;
    }

    .blur-container {
        position: relative;
    }

    .blur-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px) brightness(0.7);
        border-radius: 16px;
        z-index: 20;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .blur-container *:not(.blur-overlay, .blur-overlay *) {
        pointer-events: none;
        user-select: none;
    }


</style>
<style>
    /* احترام به کاربرانی که Reduce Motion فعال دارند */
    @media (prefers-reduced-motion: reduce) {
        .services-circle.is-bouncing,
        .services-circle.is-bouncing .services-icon {
            animation: none !important;
        }
    }

    /* انیمیشن پرش: تند بالا، نرم پایین */
    @keyframes servicesBounceUpDown {
        0%   { transform: translateY(0) scale(1); }
        18%  { transform: translateY(-40px) scale(1.06); } /* سریع میره بالا */
        55%  { transform: translateY(0) scale(1.00); }     /* آروم برمیگرده */
        70%  { transform: translateY(-8px) scale(1.02); }  /* یه بونس کوچیک */
        100% { transform: translateY(0) scale(1); }
    }

    /* چرخش فرفره‌ای عکس */
    @keyframes servicesSpin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* وقتی کلاس فعال شد */
    .services-circle.is-bouncing {
        animation: servicesBounceUpDown 1.2s cubic-bezier(.15,.95,.25,1) both;
        will-change: transform;
    }

    /* همزمان با پرش، عکس بچرخه */
    .services-circle.is-bouncing .services-icon {
        animation: servicesSpin 1.2s linear both;
        will-change: transform;
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
@stack('link')
@php

    $generalSettings = \App\Models\GeneralSetting::first();

@endphp

@if($generalSettings && $generalSettings->head_scripts)

    {!! $generalSettings->head_scripts !!}

@endif
