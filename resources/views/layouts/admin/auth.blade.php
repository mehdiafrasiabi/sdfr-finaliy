<!DOCTYPE html>
<html class="light-style layout-wide customizer-hide" data-assets-path="/admin/assets/" data-template="vertical-menu-template" data-theme="theme-default" dir="rtl" lang="fa">

<head>
    <base href="../"/>
    <!-- begin::NexLink Meta Basic -->
    <meta charset="utf-8"/>
    <meta content="#5955D1" name="theme-color"/>
    <title>
      ورود مشاوران
    </title>
    <!-- end::NexLink Website Page Title -->
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
    <!-- end::NexLink Required Stylesheet -->
    <!-- begin::NexLink CSS Stylesheet -->
    <link href="/admin/assets/css/styles.css" rel="stylesheet"/>
    <!-- end::NexLink CSS Stylesheet -->
    <!-- begin::NexLink Googletagmanager -->
    <script async=""  src="/admin/assets/js/js.js">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-XWVQM68HHQ', {
            'cookie_flags': 'SameSite=None;Secure',
            'send_page_view': true
        });
    </script>
    <!-- end::NexLink Googletagmanager -->
</head>

<body>
<div class="page-layout">
    <div class="auth-wrapper min-vh-100 px-2">
        {{$slot}}
    </div>
</div>
<!-- begin::NexLink Page Scripts -->
<script src="/admin/assets/libs/global/global.min.js">
</script>
<script src="/admin/assets/js/appSettings.js">
</script>
<script src="/admin/assets/js/main.js">
</script>
<!-- end::NexLink Page Scripts -->
@include('layouts.admin.theme-toggle-script')
</body>
</html>
